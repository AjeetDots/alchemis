<?php

/**
 * Defines the app_base_AppException class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Observable.php');

/**
 * @package Framework
 */
class app_base_AppException extends Exception implements app_base_Observable
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
	function __construct($message = null, $code = 0)
	{
		require_once('app/base/ExceptionLogger.php');
//		$this->attach(new ExceptionLogger());
		
		parent::__construct($message, $code);

		$html = '<html>';
		$html .= '<head>';
		$html .= '	<title>' . get_class($this) . '</title>';
		$html .= '	<link rel="stylesheet" type="text/css" href="app/view/styles/main.css" />';
		$html .= '</head>';
		$html .= '<body style="background-color: #EDEDED">';
		$html .= '	<div id="content">';
		$html .= '			<div class="msgError">';
		$html .= '			<span class="title">' . get_class($this) . ':</span>';
		$html .= '<p><strong>' . $this->getMessage() . ' (' . $this->getCode() . ')</strong></p>';
		$html .= '<p>' . $this->getFile() . ' (line ' . $this->getLine() . ')</p>';
		$html .= '<pre>' . $this->getTraceAsString() . '</pre>';
		$html .= '		</div>';
		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';
		echo $html;
//		$this->handleCode();
		
		// Notify observers
//		$this->notify();
		
		exit;
	}
	
	/**
	 * Handles the exception code by showing a view file if found. 
	 */
	protected function handleCode()
	{
		try
		{
			$app_controller = app_controller_ApplicationHelper::instance()->appController();
			$view = $app_controller->resolveView($this->getCode());
			$view->execute();
		}
		catch (Exception $e)
		{
		// do nothing
		}
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
//		echo "<h1>" . get_class($this) . "::notify()</h1>";
//		echo "<pre>";
//		print_r($this->observers);
//		echo "</pre>";
		foreach ($this->observers as $obs)
		{
//			echo "<h1>in 1</h1>";
			$obs->update($this);
		}
	}

}

?>