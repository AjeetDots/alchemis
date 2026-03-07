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
class UserLogger implements Observer
{
	function update(Observable $observable)
	{
		$conf = array('mode' => 0755, 'timeFormat' => '%X %x');
		$logger = &Log::singleton('file', '/var/www/html/alchemis/out.log', 'user', $conf);
		$logger->log($observable->getStatus(), PEAR_LOG_INFO);
	}
}

?>