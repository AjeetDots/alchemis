<?php

/**
 * <description>
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Observer.php');
require_once('app/controller/ApplicationHelper.php');
require_once('Log.php');

/**
 * Handles the logging of Exceptions.
 * @package Framework
 */
class ExceptionLogger implements app_base_Observer
{
	/**
	 * @var app_base_Observable
	 */
	protected $observable;

	public function update(app_base_Observable $observable)
	{
		$this->observable = $observable;
		$this->fileHandler();
//		$this->mailHandler();
	}

	/**
	 * The File handler writes log events to a text file using configurable 
	 * string formats.
	 */	
	protected function fileHandler()
	{
		require_once('app/base/Registry.php');
		
		// File handler
		$conf = array('mode' => 0755, 'timeFormat' => '%Y-%m-%d %a %H:%M:%S');
		$logger = &Log::singleton('file', app_base_ApplicationRegistry::getLogDirectory(). 'ExceptionLogger.log', get_class($this->observable), $conf);
		
		$message = preg_replace('/\n/', ' ', $this->observable->getMessage());
		$logger->log($message, PEAR_LOG_ERR);
		
		$logger->log($this->observable->getFile() . ' (line ' . $this->observable->getLine() . ')', PEAR_LOG_ERR);
		$traces = preg_split('/\n/', $this->observable->getTraceAsString());
		foreach ($traces as $trace)
		{
			$logger->log($trace, PEAR_LOG_ERR);
		}
		
//		$admin_email = app_base_ApplicationRegistry::getEmail('admin');
//		$system_email = app_base_ApplicationRegistry::getEmail('system');
//		$logger->log('admin-email: ' . $admin_email, PEAR_LOG_DEBUG);
//		$logger->log('system-email: ' . $system_email, PEAR_LOG_DEBUG);
	}

	/**
	 * The Mail handler aggregates a session's log events and sends them in the 
	 * body of an email message using PHP's mail() function.
	 */
	protected function mailHandler()
	{
		$admin_email = app_base_ApplicationRegistry::getEmail('admin');
		$system_email = app_base_ApplicationRegistry::getEmail('system');
		$conf = array('from' => $system_email, 'subject' => 'Important Log Events');
		$logger = &Log::singleton('mail', $admin_email, get_class($this->observable), $conf);

		$message = preg_replace('/\n/', ' ', $this->observable->getMessage());
		$logger->log($message, PEAR_LOG_ERR);

		$logger->log($this->observable->getFile() . ' (line ' . $this->observable->getLine() . ')', PEAR_LOG_ERR);
		$traces = preg_split('/\n/', $this->observable->getTraceAsString());
		foreach ($traces as $trace)
		{
			$logger->log($trace, PEAR_LOG_ERR);
		}
	}

}

?>