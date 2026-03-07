<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2006 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('Auth/Observer.php');
require_once('Log.php');


/**
 * @package framework
 */
class SqlLogger implements Observer
{
	function update(Observable $observable)
	{
		$conf = array('mode' => 0755, 'timeFormat' => '%Y-%m-%d %a %H:%M:%S');
		$logger = &Log::singleton('file', '/var/www/html/alchemis/out.log', 'sql', $conf);
		$logger->log($observable->getStatus(), PEAR_LOG_DEBUG);
	}
}

?>