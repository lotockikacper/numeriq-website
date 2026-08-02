<?php

declare(strict_types=1);

// Endpoint formularza Numeriq. Zwraca wyłącznie JSON.
header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

function respond(int $status, array $payload): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function newCsrfToken(): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function stringField(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function safeHeaderValue(string $value): bool
{
    return !preg_match('/[\r\n]/', $value);
}

function textLength(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

function textSubstr(string $value, int $start, int $length): string
{
    return function_exists('mb_substr') ? mb_substr($value, $start, $length, 'UTF-8') : substr($value, $start, $length);
}

function encodeMailSubject(string $value): string
{
    if (function_exists('mb_encode_mimeheader')) {
        return mb_encode_mimeheader($value, 'UTF-8', 'B', "\r\n");
    }
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'token') {
    respond(200, [
        'success' => true,
        'csrf_token' => newCsrfToken(),
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: GET, POST');
    respond(405, ['success' => false, 'message' => 'Niedozwolona metoda żądania.']);
}

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    error_log('Numeriq contact form: missing config.php');
    respond(503, ['success' => false, 'message' => 'Formularz nie został jeszcze skonfigurowany.']);
}

$config = require $configPath;
if (!is_array($config)) {
    error_log('Numeriq contact form: invalid config.php');
    respond(503, ['success' => false, 'message' => 'Nieprawidłowa konfiguracja formularza.']);
}

$recipient = trim((string)($config['recipient_email'] ?? ''));
$fromEmail = trim((string)($config['from_email'] ?? ''));
$siteName = trim((string)($config['site_name'] ?? 'Numeriq'));
$deliveryMode = trim((string)($config['delivery_mode'] ?? 'mail'));
$rateLimitSeconds = max(20, (int)($config['rate_limit_seconds'] ?? 60));

if (!filter_var($recipient, FILTER_VALIDATE_EMAIL) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
    error_log('Numeriq contact form: recipient_email or from_email is invalid');
    respond(503, ['success' => false, 'message' => 'Formularz wymaga uzupełnienia konfiguracji e-mail.']);
}

$contentLength = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
if ($contentLength > 50_000) {
    respond(413, ['success' => false, 'message' => 'Wiadomość jest zbyt duża.']);
}

$sessionToken = (string)($_SESSION['csrf_token'] ?? '');
$postedToken = stringField('csrf_token');
if ($sessionToken === '' || $postedToken === '' || !hash_equals($sessionToken, $postedToken)) {
    respond(419, [
        'success' => false,
        'message' => 'Sesja formularza wygasła. Odśwież stronę i spróbuj ponownie.',
        'csrf_token' => newCsrfToken(),
    ]);
}

// Honeypot: prawdziwy użytkownik nie widzi tego pola.
if (stringField('company') !== '') {
    // Zwracamy pozorny sukces, aby bot nie dostał informacji o blokadzie.
    respond(200, ['success' => true, 'message' => 'Wiadomość została wysłana.']);
}

$startedAt = (int)stringField('started_at');
$elapsedMs = (int)round(microtime(true) * 1000) - $startedAt;
if ($startedAt <= 0 || $elapsedMs < 2500) {
    respond(429, [
        'success' => false,
        'message' => 'Formularz został wysłany zbyt szybko. Odczekaj chwilę i spróbuj ponownie.',
        'csrf_token' => newCsrfToken(),
    ]);
}

$lastSubmission = (int)($_SESSION['last_submission'] ?? 0);
if ($lastSubmission > 0 && (time() - $lastSubmission) < $rateLimitSeconds) {
    $wait = $rateLimitSeconds - (time() - $lastSubmission);
    respond(429, [
        'success' => false,
        'message' => "Odczekaj {$wait} s przed wysłaniem kolejnej wiadomości.",
        'csrf_token' => newCsrfToken(),
    ]);
}

$name = stringField('name');
$email = stringField('email');
$phone = stringField('phone');
$level = stringField('level');
$topic = stringField('topic');
$message = stringField('message');
$privacyAck = stringField('privacy_ack');

$allowedLevels = ['Szkoła podstawowa', 'Liceum / technikum', 'Matura podstawowa', 'Inny'];
$errors = [];

$nameLength = textLength($name);
if ($nameLength < 2 || $nameLength > 80 || !safeHeaderValue($name)) {
    $errors[] = 'Podaj poprawne imię.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || textLength($email) > 160 || !safeHeaderValue($email)) {
    $errors[] = 'Podaj poprawny adres e-mail.';
}
if ($phone !== '' && (textLength($phone) > 30 || !preg_match('/^[0-9+() .\-]{6,30}$/u', $phone))) {
    $errors[] = 'Podaj poprawny numer telefonu albo pozostaw pole puste.';
}
if (!in_array($level, $allowedLevels, true)) {
    $errors[] = 'Wybierz poziom nauki.';
}
if (textLength($topic) < 3 || textLength($topic) > 160 || !safeHeaderValue($topic)) {
    $errors[] = 'Podaj temat lub cel zajęć.';
}
if (textLength($message) < 10 || textLength($message) > 3000) {
    $errors[] = 'Wiadomość powinna mieć od 10 do 3000 znaków.';
}
if ($privacyAck !== '1') {
    $errors[] = 'Potwierdź zapoznanie się z polityką prywatności.';
}

if ($errors !== []) {
    respond(422, [
        'success' => false,
        'message' => implode(' ', $errors),
        'csrf_token' => newCsrfToken(),
    ]);
}

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'brak danych';
$userAgent = textSubstr((string)($_SERVER['HTTP_USER_AGENT'] ?? 'brak danych'), 0, 300);
$submittedAt = (new DateTimeImmutable('now', new DateTimeZone('Europe/Warsaw')))->format('Y-m-d H:i:s T');

$subjectText = "Nowe zapytanie Numeriq: {$level} — {$topic}";
$encodedSubject = encodeMailSubject($subjectText);

$body = implode("\n", [
    "Nowe zapytanie ze strony {$siteName}",
    str_repeat('=', 48),
    "Imię: {$name}",
    "E-mail: {$email}",
    'Telefon: ' . ($phone !== '' ? $phone : 'nie podano'),
    "Poziom: {$level}",
    "Temat: {$topic}",
    '',
    'Wiadomość:',
    $message,
    '',
    str_repeat('-', 48),
    "Wysłano: {$submittedAt}",
    "Adres IP: {$ipAddress}",
    "Przeglądarka: {$userAgent}",
]);

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    "From: {$siteName} <{$fromEmail}>",
    "Reply-To: {$name} <{$email}>",
    'X-Mailer: PHP/' . PHP_VERSION,
];

$sent = false;
if ($deliveryMode === 'log') {
    $logLine = "\n--- {$submittedAt} ---\nTO: {$recipient}\nSUBJECT: {$subjectText}\n{$body}\n";
    $sent = file_put_contents(__DIR__ . '/contact-test.log', $logLine, FILE_APPEND | LOCK_EX) !== false;
} elseif ($deliveryMode === 'mail') {
    $sent = mail($recipient, $encodedSubject, $body, implode("\r\n", $headers), '-f' . $fromEmail);
} else {
    error_log('Numeriq contact form: unsupported delivery mode');
}

if (!$sent) {
    error_log('Numeriq contact form: message delivery failed');
    respond(500, [
        'success' => false,
        'message' => 'Nie udało się wysłać wiadomości. Spróbuj ponownie później.',
        'csrf_token' => newCsrfToken(),
    ]);
}

$_SESSION['last_submission'] = time();
respond(200, [
    'success' => true,
    'message' => 'Wiadomość została wysłana. Dziękuję — odezwę się po zapoznaniu ze zgłoszeniem.',
    'csrf_token' => newCsrfToken(),
]);
