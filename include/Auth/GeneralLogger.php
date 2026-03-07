<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2006 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('Auth/Observer.php');



//require_once('Log.php');

class GeneralLogger implements Observer
{
	function update(Observable $observable)
	{
//		$conf = array('mode' => 0644, 'timeFormat' => '%X %x');
//		
//		$logger = &Log::singleton('file', '/var/www/html/alchemis/out.log', 'ident', $conf);
//		
//		for ($i = 0; $i < 10; $i++)
//		{
//			echo "<p>Log entry $i</p>";
//			$logger->log("Log entry $i", PEAR_LOG_INFO);
//		}
//		
//		print "<h1>" . __CLASS__ . ": add login data to log</h1>";
//		
//		$a = &Log::singleton('console', '', 'TEST');
	}
}

?>