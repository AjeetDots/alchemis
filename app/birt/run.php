<?php

$path = '/Users/ian/Sites/ContinuityOnline-svn/'; 
$dir = 'app/birt/reports_tmp/';

if (!isset($_GET['rpt']))
{
	exit('No report specified');
}

require_once($path . 'include/Smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->force_compile = true;
$smarty->compile_check = true;
$smarty->template_dir  = $path . 'app/birt/reports';
$smarty->compile_dir   = $path . 'app/birt/reports_c';

if (!$smarty->template_exists($_GET['rpt'] . '.rptdesign'))
{
	exit('Report not found');
}


// Create temp file
$filestring = tempnam ($path . $dir, '');

$rpt = $smarty->fetch($_GET['rpt'] . '.rptdesign');

$handle = fopen($filestring, "w");
fwrite($handle, $rpt);
fclose($handle);


//chown($filestring, 'ian');
//chgrp($filestring, 'www');
chmod($filestring, '0100');

$fname = $filestring;


//echo "<p>fname: $fname</p>";

//// Redirect browser
//$dest = "http://localhost:8080/birt-viewer/frameset?__report=";
//$dest .= urlencode( realpath( $fname ) );
//header('Location: ' . $dest);




$paramValue = 'pdf';
$dest = 'http://localhost:8180/birt/run?__report=';
$dest .= urlencode($fname);
$dest .= '&__format=' . urlencode($paramValue);

$ch = curl_init($dest);
if (! $ch) {
die('Cannot allocate a new PHP-CURL handle');
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

$data = curl_exec($ch);
curl_close($ch);
header('Content-type: application/pdf');
print($data); 

?>