<?php

/**
 * Generuje poprawny numer PESEL na podstawie daty urodzenia i płci.
 *
 * @param string $date  Data w formacie RRRR-MM-DD
 * @param string $gender 'k' (kobieta) lub 'm' (mężczyzna)
 * @return string 11-cyfrowy numer PESEL
 * @throws InvalidArgumentException gdy dane są nieprawidłowe
 */
function generatePesel(string $date, string $gender): string
{
    if ($gender !== 'k' && $gender !== 'm') {
        throw new InvalidArgumentException('Nieprawidłowa płeć.');
    }

    $parts = explode('-', $date);
    if (count($parts) !== 3) {
        throw new InvalidArgumentException('Nieprawidłowa data urodzenia.');
    }
    [$year, $month, $day] = array_map('intval', $parts);

    if (!checkdate($month, $day, $year)) {
        throw new InvalidArgumentException('Nieprawidłowa data urodzenia.');
    }

    // maska wieku: dopisek do numeru miesiąca zależnie od stulecia
    $maski = [18 => 80, 19 => 0, 20 => 20, 21 => 60, 22 => 40];
    $stulecie = intdiv($year, 100);
    if (!isset($maski[$stulecie])) {
        throw new InvalidArgumentException('Rok musi mieścić się w zakresie 1800–2299.');
    }

    $rr = str_pad($year % 100, 2, '0', STR_PAD_LEFT);
    $mm = str_pad($month + $maski[$stulecie], 2, '0', STR_PAD_LEFT);
    $dd = str_pad($day, 2, '0', STR_PAD_LEFT);

    // 3 losowe cyfry
    $trzyLosowe = (string) random_int(100, 999);

    // 4. cyfra porządkowa: parzysta = kobieta, nieparzysta = mężczyzna
    $czwarta = (string) (random_int(0, 4) * 2 + ($gender === 'm' ? 1 : 0));

    $podstawa = $rr . $mm . $dd . $trzyLosowe . $czwarta;

    return $podstawa . cyfraKontrolna($podstawa);
}

/**
 * Oblicza 11. cyfrę (kontrolną) numeru PESEL z pierwszych 10 cyfr.
 *
 * Wagi: 1, 3, 7, 9, 1, 3, 7, 9, 1, 3.
 * Z sumy mnożeń bierzemy drugą cyfrę, odejmujemy ją od 10;
 * jeśli wynik jest dwucyfrowy, zostaje tylko druga cyfra (0).
 */
function cyfraKontrolna(string $pesel10): int
{
    $wagi = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
    $suma = 0;
    for ($i = 0; $i < 10; $i++) {
        $suma += (int) $pesel10[$i] * $wagi[$i];
    }
    $cyfra = $suma % 10;                    // druga cyfra dwucyfrowej sumy
    return $cyfra === 0 ? 0 : 10 - $cyfra;  // 10 minus cyfra (10 → 0)
}
