<?php

/**
 * Ensures the PHP files in the app/domain directory are automatically included. 
 */

$dir = 'app' . DIRECTORY_SEPARATOR . 'domain' . DIRECTORY_SEPARATOR;
$dh = opendir($dir);

while ($file = readdir($dh))
{
	if (substr($file, -4) == '.php' && !preg_match('/\.+.+\./i', $file) && !preg_match('/^_.+/i', $file))
	{
		require_once($dir . $file);
	}
}

?>