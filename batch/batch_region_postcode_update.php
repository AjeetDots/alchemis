<?php

// Ensure the maximum execution time is at least 300 seconds
if (ini_get('max_execution_time') < 300)
{
	set_time_limit(300);
}

require_once('/var/www/html/include/EasySql/EasySql.class.php');

define('DB_HOST',     'alchemis-mysql.cswhqpuhwywg.eu-west-1.rds.amazonaws.com');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis_app');
define('DB_PASSWORD', 'rYT4maP7');

$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$db->debug_all = false;

$sql = "update tbl_sites set region_postcode = substring(postcode, 1,3) where substring(postcode,3,1) = ' '";
$db->query($sql);

$sql = "update tbl_sites set region_postcode = substring(postcode, 1,4) where substring(postcode,4,1) = ' '";
$db->query($sql);

$sql = "update tbl_sites set region_postcode = substring(postcode, 1,5) where substring(postcode,5,1) = ' '";
$db->query($sql);

?>