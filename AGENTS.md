# Generator numeru PESEL — wymagania

## Wymagania techniczne

- Baza danych: **SQLite** (np. `pesel.sqlite`, przez PDO)
- Technologie: **PHP + HTML + CSS + JS**

## Funkcjonalność

Generator numeru PESEL:

1. **Rok, miesiąc i dzień urodzenia** — generowany numer zaczyna się od daty urodzenia (np. RRMMDD z uwzględnieniem maski wieku, jeśli wymagana).
2. **3 losowe cyfry** — kolejne trzy cyfry losowane losowo.
3. **4. cyfra porządkowa** — musi być **parzysta dla kobiety** i **nieparzysta dla mężczyzny** ( określa płeć w numerze PESEL).
4. **Cyfra kontrolna (11. cyfra)** — liczona z poprzednich 10 cyfr:
   - mnoży się je kolejno przez wagi: **1, 3, 7, 9, 1, 3, 7, 9, 1, 3**,
   - wyniki mnożenia **dodaje do siebie**,
   - jeśli suma jest **dwucyfrowa**, bierze się **tylko drugą cyfrę**,
   - następnie **odejmuje się ją od 10**,
   - jeśli wynik jest dwucyfrowy, bierze się **tylko drugą cyfrę**,
   - otrzymana cyfra jest **11. cyfrą numeru PESEL**,
   - gotowy numer PESEL zostaje wypisany (i zapisany w bazie).

## Stan projektu

- `db.php` — klasa `Database` (PDO + SQLite): tworzy tabelę `generated_pesel`
  (pesel, gender, birth_date, created_at), zapis generowanego numeru
  (`savePesel`) oraz pobieranie historii (`getHistory`).
