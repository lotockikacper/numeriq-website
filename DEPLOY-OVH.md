# Checklista publikacji Numeriq na OVHcloud

## 1. Kopia zapasowa

Pobierz aktualną zawartość katalogu strony z OVH albo zmień nazwę dotychczasowego katalogu.

## 2. Repozytorium

W swoim repozytorium zastąp aktualne pliki plikami z tej paczki, ale nie dodawaj `config.php`.

## 3. Prywatna konfiguracja

Na komputerze możesz przygotować `config.php`, ale nie wykonuj na nim `git add`.
Skopiuj treść `config.example.php` i wstaw prawdziwe wartości.

## 4. Wgranie na serwer

Wgraj pliki do katalogu przypisanego do `numeriqmath.pl`, zwykle `www` albo podkatalogu multisite.

## 5. Test techniczny

Otwórz w przeglądarce:

- `https://numeriqmath.pl/`
- `https://numeriqmath.pl/contact.php?action=token`
- `https://numeriqmath.pl/polityka-prywatnosci.php`

Drugi adres powinien pokazać JSON z `success: true` i tokenem.

## 6. Test formularza

Wyślij wiadomość, używając własnego drugiego adresu e-mail. Sprawdź:

- komunikat sukcesu na stronie,
- wiadomość w skrzynce odbiorczej,
- folder spam,
- działanie przycisku „Odpowiedz” — powinien odpowiadać osobie z formularza.

## 7. Po teście

Usuń testową wiadomość, sprawdź stronę na telefonie i wyczyść cache przeglądarki, jeśli widzisz starą wersję.
