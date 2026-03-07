#!/usr/bin/php
<?php
define('PROCESS_HOST', 'http://db.alchemis.co.uk');
define('PROCESS_CAMPAIGNS_PATH', '/index.php?cmd=CampaignImport');
ob_start();
$ch = curl_init();
ini_set('memory_limit', '256M');
curl_setopt($ch, CURLOPT_URL, PROCESS_HOST . PROCESS_CAMPAIGNS_PATH);
$result = curl_exec($ch);
curl_close($ch);
ob_end_clean();
die;
