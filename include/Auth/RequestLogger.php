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


class RequestLogger implements Observer
{
	/**
	 * @var string
	 */
	private $cmd = '';

	function __construct($cmd)
	{
//		$this->cmd = $cmd;
	}

	function update(Observable $observable)
	{
//		$conf = array('mode' => 0755, 'timeFormat' => '%X %x');
//		$logger = &Log::singleton('file', '/var/www/html/alchemis/out.log', 'cmd', $conf);
//		$logger->log('Command ' . get_class($this->cmd), PEAR_LOG_INFO);
	}

}

?>