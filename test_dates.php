<?php
$dbTime = '2026-05-23 20:45:00';
$date = new DateTime($dbTime);
$utcDate = clone $date;
$utcDate->setTimezone(new DateTimeZone('UTC'));
$utcIso = $utcDate->format('c');
echo "Original: $dbTime\n";
echo "Server TZ: " . date_default_timezone_get() . "\n";
echo "UTC ISO: $utcIso\n";
