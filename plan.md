# Plan pracy — Generator numeru PESEL

> Zaznaczam `[x]` wykonane zadania, na bieżąco aktualizując ten plik.

## Etap 1 — Baza danych

- [x] Klasa `Database` (PDO + SQLite), tabela `generated_pesel` (pesel, gender, birth_date, created_at)
- [x] Zapisywanie wygenerowanego numeru (`savePesel`) i pobieranie historii (`getHistory`)

## Etap 2 — Logika generowania PESEL

- [x] Funkcja generująca numer PESEL (`generator.php`):
  - [x] rok / miesiąc / dzień urodzenia (maski wieku: 1800→80, 1900→0, 2000→20, 2100→60, 2200→40)
  - [x] 3 losowe cyfry
  - [x] cyfra porządkowa: parzysta = kobieta, nieparzysta = mężczyzna
  - [x] cyfra kontrolna: wagi 1, 3, 7, 9, 1, 3, 7, 9, 1, 3 → suma → druga cyfra → 10 minus cyfra → gotowe
- [x] Walidacja poprawności danych (nieprawidłowa data, rok poza zakresiem 1800–2299, zła płeć)

## Etap 3 — Interfejs (PHP + HTML + CSS + JS)

- [x] `index.php` — formularz: data urodzenia, wybór płci, przycisk „Generuj"
- [x] Wyświetlenie wygenerowanego numeru PESEL
- [x] `style.css` — prosty, czytelny styl (karta formularza, duży numer, lista historii)
- [x] `script.js` — interakcja bez przeładowania strony (fetch do `index.php` z `ajax=1`)

## Etap 4 — Historia wygenerowanych numerów

- [x] Lista ostatnich wygenerowanych numerów (`historia.php`: numer, płeć, data urodzenia, data dodania)
- [x] Odświeżanie historii po każdym wygenerowaniu (fetch `historia.php?ajax=1`)

## Etap 5 — Dopracowanie

- [x] Komunikaty błędów (walidacja daty i płci, komunikat AJAX)
- [x] Testy: `test.php` — logika (maski wieku, parzystość, walidacja) + porównanie z realnym PESEL-em 44051401458
- [x] Test na wbudowanym serwerze PHP (POST AJAX, historia, walidacja — OK)
- [ ] Sprawdzenie w Laragonie pod adresem `localhost/pesel`

## Etap 6 — Wygląd i przejrzystość

- [x] Nowy `style.css`: gradientowe tło, karty z cieniem, spójna paleta (zmienne CSS), zaokrąglenia
- [x] Nagłówek z ikoną i krótkim opisem
- [x] Wyraźne etykiety formularza, ładniejsze pola (stan `:focus`), pełnowymiarowy przycisk „Generuj"
- [x] Wynik na wyróżnionej karcie (zielone tło) z przyciskiem **„📋 Kopiuj"**
- [x] Historia: odznaki płci (kolor różowy/niebieski), wyrównana data, efekt `hover` na wierszach
- [x] Komunikat błędu w ramce zamiast zwykłego tekstu
- [x] Wersja mobilna (media query dla małych ekranów)
- [x] Stany przycisku w JS („⏳ Generowanie…" → „🎲 Generuj PESEL") + kopiowanie do schowka

