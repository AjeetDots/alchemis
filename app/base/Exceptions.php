<?php

/**
 * Ensures the Exception PHP files in the app/base directory are automatically included.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

$dir = 'app' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR;
$dh = opendir($dir);

while ($file = readdir($dh))
{
	if (substr($file, -13) == 'Exception.php')
	{
		require_once($dir . $file);
	}
}

?>