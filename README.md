# Numeriq — wersja gotowa do wdrożenia

Responsywna strona korepetycji z działającym formularzem PHP przygotowanym pod zwykły hosting WWW.

## Zawartość

- `index.html` — strona główna,
- `style.css` — pełny wygląd i wersja mobilna,
- `script.js` — menu, animacje, walidacja i wysyłanie AJAX,
- `contact.php` — bezpieczny endpoint formularza,
- `config.example.php` — wzór prywatnej konfiguracji,
- `polityka-prywatnosci.php` — polityka z danymi pobieranymi z konfiguracji,
- `.htaccess` — HTTPS, nagłówki bezpieczeństwa i cache,
- `robots.txt`, `sitemap.xml`, favicony i grafika Open Graph.

## Konfiguracja formularza

1. Skopiuj `config.example.php` jako `config.php`.
2. W `config.php` uzupełnij:
   - adres, na który mają przychodzić wiadomości,
   - adres nadawcy w domenie `numeriqmath.pl`,
   - imię i nazwisko administratora danych,
   - kontaktowy adres e-mail do polityki prywatności.
3. Nie dodawaj `config.php` do GitHuba. Jest już wpisany w `.gitignore`.

## Test lokalny z PHP

W folderze strony:

```bash
cp config.example.php config.php
```

W `config.php` ustaw:

```php
'delivery_mode' => 'log',
```

Następnie:

```bash
php -S localhost:8000
```

Otwórz `http://localhost:8000`. Testowe wiadomości trafią do pliku `contact-test.log`.
Nie otwieraj strony bezpośrednio przez `file://`, bo formularz potrzebuje PHP i sesji.

## Wdrożenie na OVHcloud

1. Utwórz kopię obecnego katalogu `www`.
2. Wgraj wszystkie pliki z tego projektu do katalogu domeny.
3. Utwórz na serwerze `config.php` na podstawie wzoru.
4. Upewnij się, że domena ma aktywny certyfikat HTTPS.
5. Otwórz stronę w trybie incognito i wyślij test.
6. Sprawdź skrzynkę odbiorczą i spam.

## GitHub

Przykładowy workflow:

```bash
git checkout -b feature/contact-form
git add .
git commit -m "Add production-ready contact form"
git push -u origin feature/contact-form
```

`config.php` tworzysz wyłącznie na serwerze OVH — nie publikuj go w repozytorium.

## Ważne przed publikacją

- wpisz prawdziwy adres odbiorcy w `config.php`,
- utwórz lub wybierz adres nadawcy w domenie `numeriqmath.pl`,
- uzupełnij administratora danych,
- upewnij się, że ceny i zakres oferty są aktualne,
- publikuj wyłącznie prawdziwe opinie; w tej wersji nie ma fikcyjnych recenzji,
- wykonaj test formularza na komputerze i telefonie.

## Gdy wiadomości nie dochodzą

- sprawdź spam,
- sprawdź poprawność `from_email`,
- użyj adresu nadawcy należącego do domeny,
- sprawdź logi hostingu,
- jeśli funkcja `mail()` okaże się niewystarczająca, kolejnym krokiem jest SMTP przez PHPMailer.
