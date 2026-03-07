<?php

/**
 * Ensures the PHP files in the app/mapper directory are automatically included. 
 */

$dir = 'app' . DIRECTORY_SEPARATOR . 'mapper' . DIRECTORY_SEPARATOR;
$dh = opendir($dir);

while ($file = readdir($dh))
{
	if (substr($file, -4) == '.php')
	{
		require_once($dir . $file);
	}
}

?>