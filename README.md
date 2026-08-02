# Numeriq — wersja Formspree / GitHub Pages

Ta wersja działa jako zwykła strona statyczna na GitHub Pages.

Formularz jest podłączony do:

```text
https://formspree.io/f/xaqrreqn
```

Nie są potrzebne:

- hosting PHP,
- `contact.php`,
- `config.php`,
- FTP do OVH,
- własny backend.

## Publikacja

Podmień pliki w repozytorium plikami z tej paczki, a następnie:

```powershell
git add .
git commit -m "Connect contact form to Formspree"
git push
```

Po wdrożeniu otwórz stronę w trybie incognito albo użyj `Ctrl + F5`.

## Test formularza

1. Wejdź na `https://numeriqmath.pl/`.
2. Przejdź do sekcji kontaktowej.
3. Wpisz swój drugi adres e-mail albo dodaj w wiadomości słowo „TEST”.
4. Wyślij formularz.
5. Sprawdź adres odbiorczy ustawiony w Formspree oraz folder spam.
6. Sprawdź również zgłoszenie w panelu Formspree.

## Ważne ustawienie Formspree

W panelu Formspree ustaw ograniczenie domeny na:

```text
numeriqmath.pl
```

Nie wpisuj `https://` ani końcowego ukośnika. Pozwoli to ograniczyć zgłoszenia pochodzące z innych stron.

## Zawartość

- `index.html` — strona i formularz,
- `script.js` — wysyłanie AJAX i komunikaty,
- `style.css` — wygląd,
- `polityka-prywatnosci.html` — statyczna polityka prywatności,
- `CNAME` — domena dla GitHub Pages,
- `.nojekyll` — wyłączenie przetwarzania Jekyll,
- grafiki, favicony, `robots.txt` i `sitemap.xml`.

## Pierwszy test

Formspree może wymagać potwierdzenia lub aktywacji adresu odbiorczego w panelu.
Jeżeli wiadomość nie przyjdzie, sprawdź:

- czy formularz ma status aktywny,
- czy adres odbiorczy jest zweryfikowany,
- zakładkę Submissions,
- folder spam,
- ustawienie Restrict to Domain.
