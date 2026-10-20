<?php

require_once __DIR__ . '/db.php';

/**
 * Zwraca HTML z listą ostatnio wygenerowanych numerów PESEL.
 */
function historiaHtml(array $rows): string
{
    if (!$rows) {
        return '<p class="pusty">Brak wygenerowanych numerów.</p>';
    }

    $html = '<ul class="historia">';
    foreach ($rows as $row) {
        $kobieta = $row['gender'] === 'k';
        $odznaka = $kobieta
            ? '<span class="odznaka k">👩 Kobieta</span>'
            : '<span class="odznaka m">👨 Mężczyzna</span>';

        $html .= '<li>'
            . '<code class="pesel">' . htmlspecialchars($row['pesel'], ENT_QUOTES, 'UTF-8') . '</code>'
            . $odznaka
            . '<span class="szczegoly">'
            . '<span class="data-ur">ur. ' . htmlspecialchars($row['birth_date'], ENT_QUOTES, 'UTF-8') . '</span>'
            . '<span>' . htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') . '</span>'
            . '</span>'
            . '</li>';
    }
    return $html . '</ul>';
}

// Endpoint AJAX zwracający samą listę historii
if (isset($_GET['ajax'])) {
    header('Content-Type: text/html; charset=utf-8');
    $db = new Database(__DIR__ . '/pesel.sqlite');
    echo historiaHtml($db->getHistory(20));
    exit;
}
