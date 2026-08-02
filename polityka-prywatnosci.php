<?php

declare(strict_types=1);

$configPath = __DIR__ . '/config.php';
$config = is_file($configPath) ? require $configPath : [];
$administrator = htmlspecialchars((string)($config['administrator_name'] ?? '[uzupełnij administratora danych]'), ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars((string)($config['privacy_email'] ?? '[uzupełnij adres e-mail]'), ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Polityka prywatności strony Numeriq.">
    <title>Polityka prywatności — Numeriq</title>
    <link rel="canonical" href="https://numeriqmath.pl/polityka-prywatnosci.php">
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .privacy-page { min-height: 100vh; padding: 120px 0 90px; background: radial-gradient(circle at 20% 0%, rgba(37,99,235,.18), transparent 33%), var(--bg); }
        .privacy-card { max-width: 860px; margin-inline: auto; padding: clamp(25px,5vw,55px); border: 1px solid var(--line); border-radius: 26px; background: var(--card); }
        .privacy-card h1 { margin: 14px 0 22px; font-size: clamp(2.3rem,6vw,4rem); line-height: 1; letter-spacing: -.05em; }
        .privacy-card h2 { margin: 38px 0 12px; font-size: 1.25rem; }
        .privacy-card p, .privacy-card li { color: var(--muted); }
        .privacy-card ul { padding-left: 21px; }
        .privacy-card a { color: var(--blue-light); text-decoration: underline; text-underline-offset: 3px; }
        .privacy-warning { margin: 24px 0; padding: 16px; border: 1px solid rgba(249,112,102,.35); border-radius: 14px; background: rgba(249,112,102,.08); color: #fecaca !important; }
        .privacy-back { display: inline-flex; margin-bottom: 25px; color: var(--blue-light); font-weight: 700; }
    </style>
</head>
<body>
<main class="privacy-page">
    <div class="container">
        <article class="privacy-card">
            <a class="privacy-back" href="index.html#kontakt">← Wróć do strony</a>
            <p class="eyebrow">Numeriq</p>
            <h1>Polityka prywatności</h1>
            <?php if (str_starts_with($administrator, '[') || str_starts_with($email, '[')): ?>
                <p class="privacy-warning"><strong>Uwaga dla właściciela strony:</strong> przed publikacją uzupełnij plik <code>config.php</code>, aby ta strona zawierała prawidłowe dane administratora.</p>
            <?php endif; ?>

            <p>Ostatnia aktualizacja: 2 sierpnia 2026 r.</p>

            <h2>1. Administrator danych</h2>
            <p>Administratorem danych osobowych przekazywanych przez formularz jest <?= $administrator ?>. Kontakt w sprawach prywatności: <a href="mailto:<?= $email ?>"><?= $email ?></a>.</p>

            <h2>2. Jakie dane są zbierane?</h2>
            <p>Formularz może zbierać imię, adres e-mail, opcjonalny numer telefonu, poziom nauki, temat zajęć oraz treść wiadomości. Serwer może dodatkowo zapisać podstawowe dane techniczne, takie jak adres IP, czas wysłania i identyfikator przeglądarki, w celu zabezpieczenia formularza i diagnozowania nadużyć.</p>

            <h2>3. Cel i podstawa przetwarzania</h2>
            <p>Dane są przetwarzane w celu odpowiedzi na zapytanie, ustalenia dostępności oraz — gdy jest to potrzebne — podjęcia działań przed zawarciem umowy dotyczącej korepetycji. Dane techniczne mogą być przetwarzane w celu ochrony formularza przed spamem i nadużyciami.</p>

            <h2>4. Jak długo dane są przechowywane?</h2>
            <p>Wiadomości są przechowywane przez czas potrzebny do obsługi zapytania i dalszego kontaktu. Gdy współpraca nie zostanie rozpoczęta, dane powinny zostać usunięte po ustaniu celu ich przetwarzania, chyba że dalsze przechowywanie jest potrzebne do obrony lub dochodzenia roszczeń albo wynika z obowiązku prawnego.</p>

            <h2>5. Odbiorcy danych</h2>
            <p>Dane mogą być przetwarzane przez dostawców usług technicznych niezbędnych do działania strony, hostingu i poczty elektronicznej. Dane nie są sprzedawane ani wykorzystywane do automatycznego profilowania.</p>

            <h2>6. Twoje prawa</h2>
            <p>W zakresie przewidzianym przepisami możesz zażądać dostępu do danych, ich sprostowania, usunięcia, ograniczenia przetwarzania lub wnieść sprzeciw. Możesz również złożyć skargę do Prezesa Urzędu Ochrony Danych Osobowych.</p>

            <h2>7. Pliki cookies</h2>
            <p>Strona nie korzysta z cookies marketingowych ani analitycznych. Formularz może używać krótkotrwałego, niezbędnego pliku sesyjnego do zabezpieczenia wysyłania wiadomości. Taki plik jest potrzebny do działania formularza i wygasa po zakończeniu sesji.</p>

            <h2>8. Dobrowolność podania danych</h2>
            <p>Podanie danych jest dobrowolne, ale bez wymaganych pól nie będzie możliwe wysłanie zapytania i udzielenie odpowiedzi.</p>

            <h2>9. Bezpieczeństwo</h2>
            <p>Strona wykorzystuje zabezpieczenia formularza obejmujące token sesyjny, walidację danych, pole antyspamowe i ograniczenie częstotliwości wysyłania. Nie należy przesyłać przez formularz danych wrażliwych ani informacji, które nie są potrzebne do ustalenia korepetycji.</p>

            <p><small>Ten dokument jest praktycznym szablonem dla prostego formularza kontaktowego. Właściciel strony powinien dostosować go do faktycznego sposobu działania i skonsultować w razie potrzeby z osobą zajmującą się ochroną danych.</small></p>
        </article>
    </div>
</main>
</body>
</html>
