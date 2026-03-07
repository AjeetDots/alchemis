<?php

/**
 * Defines the app_base_MDB2Exception class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/ExceptionLogger.php');
require_once('app/base/Observable.php');

/**
 * @package Framework
 */
class app_base_MDB2Exception extends Exception implements app_base_Observable
{
	/**
	 * Array of observers attached to this object.
	 * @var array 
	 */
	private $observers = array();

	/**
	 * @param string $message exception message
	 * @param integer $code user defined exception code
	 */
	function __construct(MDB2_Error $error)
	{
		$this->attach(new ExceptionLogger());

		$message = $error->getMessage() . '. ' . $error->userinfo;
		parent::__construct($message, $error->getCode());

		echo '<div style="background-color: blue; color: white; padding: 3px">' . get_class($this) . '</div>';
		echo '<div style="border: 1px solid blue; background-color: #eee; color: blue; padding: 3px"">';
		echo '<p><strong>' . $this->getMessage() . ' (' . $this->getCode() . ')</strong></p>';
//		echo '<p>' . $error->userinfo . '</p>';
		echo '<p>' . $this->getFile() . ' (line ' . $this->getLine() . ')</p>';
		echo '<pre>' . $this->getTraceAsString() . '</pre>';
//		echo '<p><pre>';
		// print_r($this->getTrace());
//		echo '</pre></p>';
		echo "</div>";
		echo '<br />';

		$this->handleCode();
		
		// Notify observers
        $this->notify();
		
		exit;
	}
	
	/**
	 * Handles the exception code by showing a view file if found. 
	 */
	protected function handleCode()
	{
            $app_controller = app_controller_ApplicationHelper::instance()->appController();
            $view = $app_controller->resolveView($this->getCode());
            $view->execute();
        }

	/**
	 * @param Observer $observer
	 */
	public function attach(app_base_Observer $observer)
	{
		$this->observers[] = $observer;
	}

	public function detach(app_base_Observer $observer)
	{
		$this->observers = array_diff($this->observers, array($observer));
	}

	public function notify()
	{
		foreach ($this->observers as $obs)
		{
			$obs->update($this);
		}
	}

}

?>