<?php
require_once __DIR__ . '/src/Helpers/ContentFilter.php';

$tests = [
    '<p><strong style="color: red;">Red Bold</strong></p>',
    '<p><span style="color: rgb(230, 0, 0);"><strong>Red Bold</strong></span></p>',
    '<p><strong><span style="color: rgb(230, 0, 0);">Red Bold</span></strong></p>',
    '<p><span style="color: rgb(230, 0, 0);"><em><strong>Red Bold Italic</strong></em></span></p>'
];

foreach ($tests as $t) {
    echo "IN : " . htmlspecialchars($t) . "\n";
    echo "OUT: " . htmlspecialchars(\App\Helpers\ContentFilter::sanitize($t)) . "\n\n";
}
