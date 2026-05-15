<?php

/**
 * Application-wide timezone for UK local time (GMT/BST via Europe/London).
 * Override with ALCHEMIS_TIMEZONE in .env or server environment if needed.
 */
$alchemisTimezone = getenv('ALCHEMIS_TIMEZONE');
if ($alchemisTimezone === false || $alchemisTimezone === '') {
    $alchemisTimezone = $_SERVER['ALCHEMIS_TIMEZONE'] ?? 'Europe/London';
}
date_default_timezone_set($alchemisTimezone);
