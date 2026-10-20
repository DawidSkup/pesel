# Plan pracy — Generator numeru PESEL

> Zaznaczam `[x]` wykonane zadania, na bieżąco aktualizując ten plik.

## Etap 1 — ~~Baza danych~~ (usunięta w Etapie 12)

- [x] ~~Klasa `Database` (PDO + SQLite), tabela `generated_pesel`~~
- [x] ~~Zapisywanie wygenerowanego numeru i pobieranie historii~~

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

## Etap 4 — ~~Historia wygenerowanych numerów~~ (usunięta w Etapie 12)

- [x] ~~Lista ostatnich wygenerowanych numerów (`historia.php`)~~
- [x] ~~Odświeżanie historii po każdym wygenerowaniu~~

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

## Etap 7 — Repozytorium Git

- [x] `git init` (branch `main`), `.gitignore` (bez `pesel.sqlite` i `test.php`)
- [x] Dodany `README.md` z opisem projektu
- [x] Zdalne repozytorium: https://github.com/DawidSkup/pesel — commit `cddd5f2` i push na `main`

## Etap 8 — Osobne pola daty

- [x] Zamiast jednego `type="date"` — trzy pola: **Dzień / Miesiąc / Rok** (`index.php`)
- [x] Serwer składa z nich datę `RRRR-MM-DD` i przekazuje do `generatePesel()`
- [x] Style trzech pól w jednym rzędzie (grid), ukryte strzałki spinnerów
- [x] Walidacja HTML5 (`checkValidity` / `reportValidity`) przed wysłaniem AJAX
- [x] Commit `282cb13` — „Data urodzenia: osobne pola na dzien, miesiac i rok"

## Etap 9 — Poprawki wyglądu (segmenty, spójność)

- [x] Wybór płci jako **segmenty** (dwa kafle, podświetlenie wybranego, obsługa `:has()`)
- [x] Jednolity wygląd obu `fieldset` (jasne tło, legenda w kapitalikach)
- [x] Ujednolicone pola `input`, subtelny cień, wyrównane cyfry (`tabular-nums`)
- [x] Commit `68b6ad6` — „Wyglad: segmentowy wybor plci…"

## Etap 10 — Podpowiedzi w polach daty

- [x] Listy podpowiedzi (`<datalist>`) dla dnia (1–31), miesiąca (1–12) i roku — commit `d35f21d`
- [x] Okazało się, że natywny `datalist` sortuje wartości i pokazuje tylko dopasowania
- [x] Zastąpiony **własną przewijalną listą** (`div.lista-panel` + logika w `script.js`):
  - [x] wartości pokazywane po kolei: dzień 1…31, miesiąc „1 — styczeń"…, rok **od 1900** w górę
  - [x] auto-przewinięcie do wybranej wartości, podświetlenie zaznaczonej
  - [x] zamykanie: klik w tło / `Esc` / zmiana rozmiaru okna
- [x] Pole roku: zakres formularza **1900–2299** (serwer nadal przyjmuje 1800–2299)
- [x] Commit `6cd615d` — „Data urodzenia: przewijalna lista wyboru (rok od 1900) zamiast datalist"

## Etap 11 — Nowoczesny motyw (commit `6b16a4b`)

- [x] Wielowarstwowe tło (rozbłysk + gradient), głębsze cienie kart
- [x] Gradientowy tytuł, ikona z efektem obrotu, animacje pojawienia się (wynik, błąd, lista)
- [x] Daty w historii rozdzielone na datę urodzenia i datę dodania (dwa odcienie)
- [x] Obsługa `prefers-reduced-motion`, dopracowana responsywność
- [x] Commit `6b16a4b` — „Wyglad: nowoczesny motyw…"

## Historia commitów (od najstarszego)

| Commit | Opis |
|--------|------|
| `cddd5f2` | Generator PESEL: logika, formularz AJAX, historia i wygląd |
| `282cb13` | Data urodzenia: osobne pola na dzień, miesiąc i rok |
| `68b6ad6` | Wygląd: segmentowy wybór płci, spójne pola formularza |
| `d35f21d` | Listy podpowiedzi (datalist) dla dnia, miesiąca i roku |
| `6b16a4b` | Wygląd: nowoczesny motyw, gradientowy nagłówek, animacje |
| `6cd615d` | Przewijalna lista wyboru (rok od 1900) zamiast datalist |
| `04da449` | Dokumentacja: pełny stan prac w `AGENTS.md` i `plan.md` |

## Etap 12 — Usunięcie historii i bazy danych

- [x] Usunięta sekcja historii z `index.php` (sekcja `<section id="historia">`)
- [x] Usunięte odświeżanie historii z `script.js`
- [x] Usunięte style historii z `style.css` (`.historia`, `.odznaka`, `.pusty` itd.)
- [x] Usunięty plik `historia.php`
- [x] Usunięty zapis numerów do bazy, plik `db.php` oraz `pesel.sqlite`
- [x] Zaktualizowany `.gitignore` (bez wpisów o bazie) i dokumentacja
- [x] Sprawdzone: strona i generowanie działają bez bazy (`85060519925`)

## Etap 13 — Favicona

- [x] Podpięta ikona `favicon.png` w `<head>` (`<link rel="icon" type="image/png" href="favicon.png">`)
- [x] Sprawdzone: plik serwowany jako `image/png` (HTTP 200, 535 B)

## Etap 14 — Drobne poprawki

- [x] Usunięta ikonka ostrzeżenia (`⚠️` z reguły `.blad::before`) pod przyciskiem „Generuj PESEL"

## Etap 15 — Walidacja daty i znikający komunikat

- [x] Walidacja z liczbą dni w miesiącu i latami przestępnymi (`dniWMiesiacu()` w `generator.php`)
- [x] Konkretne komunikaty, np. „Rok 2023 nie jest przestępny — luty ma wtedy 28 dni."
- [x] Komunikat błędu znika od razu po zmianie pola (`input` + wybór z listy w `script.js`)
- [x] Sprawdzone: 29.02.2024 → OK, 29.02.2023 → jasny błąd, 28.02.2023 → OK

## Do zrobienia / możliwe dalej

- [ ] Sprawdzenie w Laragonie pod adresem `localhost/pesel`
- [ ] Ewentualnie: ciemny motyw, walidacja daty po stronie klienta
