<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/generator.php';
require_once __DIR__ . '/historia.php';

$database = new Database(__DIR__ . '/pesel.sqlite');

$blad  = null;
$wynik = null;
$plec  = 'k';
$data  = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $plec = $_POST['plec'] ?? '';
    $data = $_POST['data'] ?? '';

    try {
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
        <label class="pole">
            <span class="etykieta">Data urodzenia</span>
            <input type="date" name="data" value="<?= htmlspecialchars($data, ENT_QUOTES, 'UTF-8') ?>"
                   min="1800-01-01" max="2299-12-31" required>
        </label>

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
