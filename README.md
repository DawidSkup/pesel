# Generator numeru PESEL

Prosty generator poprawnych numerów PESEL (PHP + JS + CSS), bez bazy danych.

## Wymagania techniczne

- **PHP**
- **HTML + CSS + JS**

## Jak to działa

Numer PESEL (11 cyfr) powstaje tak:

1. **data urodzenia** — rok i miesiąc z maską wieku (1800→+80, 1900→+0, 2000→+20, 2100→+60, 2200→+40), dalej dzień
2. **3 losowe cyfry**
3. **cyfra porządkowa** — parzysta dla kobiety, nieparzysta dla mężczyzny
4. **cyfra kontrolna** — cyfry 1–10 mnożone kolejno przez wagi `1, 3, 7, 9, 1, 3, 7, 9, 1, 3`,
   wyniki sumowane; z sumy bierze się **drugą cyfrę**, odejmuje od 10,
   a jeśli wynik jest dwucyfrowy — znowu **druga cyfra** (czyli 0)

## Pliki

| Plik | Opis |
|------|------|
| `index.php` | formularz + wynik (generowanie przez AJAX) |
| `generator.php` | logika generowania numeru PESEL |
| `style.css` | wygląd |
| `script.js` | obsługa formularza bez przeładowania, przewijalne listy, kopiowanie |

## Uruchomienie

1. Umieść katalog w docroot (np. Laragon) i otwórz `http://localhost/pesel`.
2. Alternatywnie wbudowany serwer PHP:

   ```bash
   php -S 127.0.0.1:8000 -t .
   ```

## Uwagi

- `test.php` nie trafia do repozytorium (`.gitignore`).
- Zakres obsługiwanych lat: **1800–2299** (w formularzu rok od 1900).
- Historia wygenerowanych numerów oraz baza SQLite zostały usunięte — numer liczony jest na żywo.
