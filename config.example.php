<?php

// Skopiuj ten plik jako config.php i uzupełnij wartości.
// Plik config.php jest ignorowany przez Git i nie powinien trafić do publicznego repozytorium.

return [
    'site_name' => 'Numeriq',

    // Tutaj mają przychodzić wiadomości z formularza.
    'recipient_email' => 'TWOJ_ADRES_EMAIL@example.com',

    // Adres nadawcy powinien być adresem w domenie numeriqmath.pl.
    // Najbezpieczniej utworzyć np. formularz@numeriqmath.pl w panelu pocztowym.
    'from_email' => 'formularz@numeriqmath.pl',

    // Dane wyświetlane w polityce prywatności.
    'administrator_name' => 'UZUPEŁNIJ IMIĘ I NAZWISKO',
    'privacy_email' => 'TWOJ_ADRES_EMAIL@example.com',

    // Na OVH pozostaw „mail”. Do testów lokalnych można wpisać „log”.
    'delivery_mode' => 'mail',

    // Minimalny odstęp między dwiema wiadomościami w tej samej sesji.
    'rate_limit_seconds' => 60,
];
