<?php
require_once __DIR__ . '/src/Helpers/ContentFilter.php';

$t = '<p>No se pierdan la<strong style="color: rgb(230, 0, 0);"><em> </em></strong>mejor fiesta<strong style="color: rgb(230, 0, 0);"> </strong><strong style="color: rgb(0, 102, 204);">ELEC</strong><strong style="color: rgb(107, 36, 178);">TRONI</strong><a href="https://es.ra.co/events/2443117" target="_blank" rel="noopener noreferrer">. Saca tus entradas aca</a>.</p>';

echo "IN : " . htmlspecialchars($t) . "\n";
echo "OUT: " . htmlspecialchars(\App\Helpers\ContentFilter::filter($t)) . "\n\n";
