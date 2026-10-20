<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/generator.php';
require_once __DIR__ . '/historia.php';

$database = new Database(__DIR__ . '/pesel.sqlite');

$blad  = null;
$wynik = null;
$plec  = 'k';
$dzien = '';
$miesiac = '';
$rok   = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $plec    = $_POST['plec'] ?? '';
    $dzien   = trim((string) ($_POST['dzien'] ?? ''));
    $miesiac = trim((string) ($_POST['miesiac'] ?? ''));
    $rok     = trim((string) ($_POST['rok'] ?? ''));

    try {
        $d = (int) $dzien;
        $m = (int) $miesiac;
        $r = (int) $rok;
        $data = sprintf('%04d-%02d-%02d', $r, $m, $d);

        $pesel = generatePesel($data, $plec);
        $database->savePesel($pesel, $plec, $data);
        $wynik = $pesel;

        // odpowiedź AJAX — sam numer, historia odświeżana osobnym zapytaniem
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => true, 'pesel' => $pesel], JSON_UNESCAPED_UNICODE);
            exit;
        }
    } catch (Throwable $e) {
        $blad = $e->getMessage();

        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => false, 'blad' => $blad], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}

$historia = $database->getHistory(20);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Generator numeru PESEL</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <header class="naglowek">
        <div class="ikona">🔢</div>
        <h1>Generator numeru PESEL</h1>
        <p>Podaj datę urodzenia i płeć — numer policzy się sam.</p>
    </header>

    <form id="form-generuj" method="post" action="index.php">
        <fieldset class="data">
            <legend>Data urodzenia</legend>
            <div class="data-pola">
                <label class="pole">
                    <span class="etykieta">Dzień</span>
                    <input type="number" name="dzien" inputmode="numeric" min="1" max="31" step="1"
                           placeholder="DD" value="<?= htmlspecialchars($dzien, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>
                <label class="pole">
                    <span class="etykieta">Miesiąc</span>
                    <input type="number" name="miesiac" inputmode="numeric" min="1" max="12" step="1"
                           placeholder="MM" value="<?= htmlspecialchars($miesiac, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>
                <label class="pole">
                    <span class="etykieta">Rok</span>
                    <input type="number" name="rok" inputmode="numeric" min="1800" max="2299" step="1"
                           placeholder="RRRR" value="<?= htmlspecialchars($rok, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>
            </div>
        </fieldset>

        <fieldset class="plec">
            <legend>Płeć</legend>
            <label><input type="radio" name="plec" value="k" <?= $plec === 'k' ? 'checked' : '' ?>> 👩 Kobieta</label>
            <label><input type="radio" name="plec" value="m" <?= $plec === 'm' ? 'checked' : '' ?>> 👨 Mężczyzna</label>
        </fieldset>

        <button type="submit">🎲 Generuj PESEL</button>
    </form>

    <p id="blad" class="blad" role="alert" <?= $blad ? '' : 'hidden' ?>><?= htmlspecialchars((string) $blad, ENT_QUOTES, 'UTF-8') ?></p>

    <div id="wynik" class="wynik" <?= $wynik ? '' : 'hidden' ?>>
        <span class="etykieta">Wygenerowany numer</span>
        <code class="pesel"><?= htmlspecialchars((string) $wynik, ENT_QUOTES, 'UTF-8') ?></code>
        <button type="button" class="kopiuj" data-kopiuj>📋 Kopiuj</button>
    </div>

    <section id="historia">
        <h2>📜 Historia wygenerowanych numerów</h2>
        <?= historiaHtml($historia) ?>
    </section>
</main>
<script src="script.js"></script>
</body>
</html>
