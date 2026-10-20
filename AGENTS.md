# Generator numeru PESEL — wymagania

## Wymagania techniczne

- Baza danych: **SQLite** (`pesel.sqlite`, przez PDO)
- Technologie: **PHP + HTML + CSS + JS**

## Funkcjonalność

Generator numeru PESEL:

1. **Rok, miesiąc i dzień urodzenia** — generowany numer zaczyna się od daty urodzenia
   (RRMMDD z uwzględnieniem maski wieku).
2. **3 losowe cyfry** — kolejne trzy cyfry losowane losowo.
3. **4. cyfra porządkowa** — musi być **parzysta dla kobiety** i **nieparzysta dla mężczyzny**
   (określa płeć w numerze PESEL).
4. **Cyfra kontrolna (11. cyfra)** — liczona z poprzednich 10 cyfr:
   - mnoży się je kolejno przez wagi: **1, 3, 7, 9, 1, 3, 7, 9, 1, 3**,
   - wyniki mnożenia **dodaje do siebie**,
   - jeśli suma jest **dwucyfrowa**, bierze się **tylko drugą cyfrę**,
   - następnie **odejmuje się ją od 10**,
   - jeśli wynik jest dwucyfrowy, bierze się **tylko drugą cyfrę**,
   - otrzymana cyfra jest **11. cyfrą numeru PESEL**,
   - gotowy numer PESEL zostaje wypisany i zapisany w bazie.

## Format daty urodzenia w formularzu

- Data urodzenia wpisywana jest w **trzech osobnych polach**: **Dzień / Miesiąc / Rok**
  (zamiast jednego pola `type="date"`).
- Po kliknięciu pola rozwija się **przewijalna lista wyboru** z wartościami po kolei:
  - **Dzień** — `1 … 31`
  - **Miesiąc** — `1 … 12` (z nazwami: „1 — styczeń", „2 — luty", …)
  - **Rok** — od **1900** w górę, do 2299 (lista przewijana w obie strony)
- Lista sama przewija się do aktualnie wybranej wartości, a wybrana pozycja jest podświetlona.
- Kliknięcie wartości wpisuje ją w pole; zamknięcie listy: kliknięcie w tło, `Esc` lub zmiana rozmiaru okna.

## Pliki projektu

| Plik | Opis |
|------|------|
| `index.php` | formularz + wynik + historia, zapis do bazy |
| `generator.php` | logika generowania numeru PESEL (maski wieku, cyfra kontrolna) |
| `historia.php` | lista ostatnich numerów (także endpoint AJAX `?ajax=1`) |
| `db.php` | klasa `Database` (PDO + SQLite) |
| `style.css` | wygląd (zmienne CSS, karty, animacje, responsywność) |
| `script.js` | obsługa formularza bez przeładowania, przewijalne listy, kopiowanie |
| `test.php` | testy logiki (nie trafia do repo — `.gitignore`) |
| `README.md` | krótki opis dla repozytorium |
| `plan.md` | plan pracy z odhaczanymi zadaniami |

## Baza danych

- Klasa `Database` (PDO + SQLite) tworzy tabelę `generated_pesel`
  (`pesel`, `gender`, `birth_date`, `created_at`), zapisuje numer (`savePesel`)
  i pobiera historię (`getHistory`, domyślnie 20 ostatnich).
- Plik bazy `pesel.sqlite` tworzy się automatycznie przy pierwszym żądaniu
  i **nie trafia do repozytorium**.

## Walidacja

- Data sprawdzana przez `checkdate()` (np. 30 lutego odrzucone).
- Rok musi mieścić się w zakresie **1800–2299** (nowsze stulecia obsługiwane maskami wieku).
  W formularzu pole roku ma zakres **1900–2299**, serwer przyjmuje szerszy zakres 1800–2299.
- Płeć musi być `k` lub `m`.

## Repozytorium

- Zdalne: **https://github.com/DawidSkup/pesel** (branch `main`).
- `.gitignore` pomija `pesel.sqlite`, `*.sqlite*`, `test.php` oraz pliki systemowe/edytora.
- Po każdej zmianie robię commit i `git push` na `main`.
