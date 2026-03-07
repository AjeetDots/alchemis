<?php

/**
 * Defines the app_base_CommandTimer class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Observer.php');
require_once('Log.php');

/**
 * Handles the logging of Exceptions.
 * @package Framework
 */
class app_base_CommandTimer implements app_base_Observer
{

	/**
	 * The object being observed.
	 * @var app_base_Observable
	 */
	protected $observable;

	/**
	 * Variable to store start time.
	 * @var integer
	 */
	private $tstart = null;

	/**
	 * Variable to store end time.
	 * @var integer
	 */
	private $tend = null;

	/**
	 * The timer starts on construction.
	 */
	public function __construct()
	{
		$this->start();
	}
	
	/**
	 * Returns the current time to an accuracy of microseconds.
	 * @return float current Unix timestamp with microseconds
	 * @static
	 */
	public static function now()
	{
		// Get current time
		$mtime = microtime();
		
		// Split seconds and microseconds
		$mtime = explode(' ', $mtime);
		
		// Create one value for end time
		return $mtime = $mtime[1] + $mtime[0];
	}

	/**
	 * Assigns current time to $tstart.
	 */
	public function start()
	{
		$this->tstart = $this->now(); 
	}

	/**
	 * Assigns current time to $tend.
	 */
	public function end()
	{
		$this->tend = $this->now(); 
	}

	/**
	 * Calculates the time difference between $tstart and $tend.
	 * @param integer specified precision (number of digits after the decimal point)
	 * @return float the time value to the specified precision
	 * @access public
	 */
	public function getTime($precision = 3)
	{
		if (is_null($this->tend))
		{
			$this->end();
		}
		$time = round(($this->tend - $this->tstart), $precision);
		return $time;
	}
	
	/**
	 * Handle the notification given by the Observable object.
	 * @param app_base_Observable $observable
	 */
	public function update(app_base_Observable $observable)
	{
		$this->observable = $observable;
		$this->fileHandler();
	}

	/**
	 * The File handler writes log events to a text file using configurable 
	 * string formats.
	 */	
	protected function fileHandler()
	{
		require_once('app/base/Registry.php');
		$conf = array('mode' => 0755, 'timeFormat' => '%Y-%m-%d %a %H:%M:%S');
		$logger = &Log::singleton('file', app_base_ApplicationRegistry::getLogDirectory() . 'CommandTimer.log', get_class($this->observable), $conf);
		$message = $this->getTime() . ' ' . $this->observable->getCurrentCommandName();
		$logger->log($message, PEAR_LOG_INFO);
	}

}

?>