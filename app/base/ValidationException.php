<?php

/**
 * Defines the app_base_ValidationException class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Observable.php');

/**
 * @package Framework
 */
class app_base_ValidationException extends Exception
{
	protected $errors = array();
	
	/**
	 * @param string $message exception message
	 * @param integer $code user defined exception code
	 */
	function __construct($message = null, $code = 0, $errors = array())
	{
		$this->errors = $errors;
		parent::__construct($message, $code);
	}
	
	public function getErrors()
	{
		return $this->errors;
	}

}

?>