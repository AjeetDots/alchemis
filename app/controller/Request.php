<?php

/**
 * Defines the app_controller_Request class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2006 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once( 'app/base/Registry.php' );
require_once( 'app/base/Exceptions.php' );

define('REQUEST_FEEDBACK_ERR',     0);     /** Error conditions */
define('REQUEST_FEEDBACK_WARNING', 1);     /** Warning conditions */
define('REQUEST_FEEDBACK_NOTICE',  2);     /** Normal but significant */

/**
 * Most of the class is taken up with mechanisms for setting and acquiring properites. Once you 
 * have a Request object, you should be able to access an HTTP prarameter via the getProperty() 
 * method.
 * 
 * 
 * Background:
 * 
 * Requests are magically handled for us by PHP and neatly packaged up in superglobal arrays. We 
 * still use a class to represent a request. A Request object is passed to CommandResolver, and 
 * later on to Command.
 * 
 * Why do we not let these classes simply query the $_REQUEST, $_POST or $_GET arrays for 
 * themselves? We could of course, but by centralising request operations in one place we open up 
 * new options. E.g. apply filters to the incoming request, or gather request parameters from 
 * somewhere other than an HTTP request, allowing the application to run from the command line or 
 * from a test script.
 * 
 * The Request object is also a useful repository for data that needs to be communicated to the View 
 * layer. In that respect, Request can also provide response capabilities.
 * 
 * @author  Ian Munday <ian.munday@illumen.co.uk>
 * @package Framework
 */
class app_controller_Request
{
	private $appreg;
	
	/**
	 * @var array
	 */
	private $properties;
	private $objects = array();
	
	/**
	 * A simple conduit through which controller classes can pass messages to the user.
	 * @var array
	 */
	private $error = array();

	/**
	 * A simple conduit through which controller classes can pass messages to the user.
	 * @var array
	 */
	private $feedback = array();
	
	/**
	 * @var app_command_Command
	 */
	private $lastCommand;
	
	/**
	 * @var boolean
	 */
	private $validation_error = false;

	public $method;
	public $input;
	public $referrer;
	public $ip;

	/**
	 * Constructor calls the initialisation routine and registers itself with the Request Registry.
	 */
	public function __construct()
	{
		$this->init();
		app_base_RequestRegistry::setRequest($this);
	}
	
	/**
	 * Responsible for populating the private $properties array. Notice that it works with command 
	 * line arguments as well as the HTTP requests. This is extremely useful when it comes to 
	 * testing and debugging.
	 */
	public function init()
	{
		$this->ip = $_SERVER['REMOTE_ADDR'];
		
		if ($_SERVER['REQUEST_METHOD'])
		{
			$this->properties = $_REQUEST;
			$this->method = $_SERVER['REQUEST_METHOD'];
			$body = file_get_contents('php://input');
			$input = $_REQUEST;
			if($body){
				$body = json_decode($body, true);
				if($body !== null){
					$input = array_merge($input, $body);
				}
			}
			$this->input = new app_controller_Input($input);
			$this->action = $this->input->get('action');
			// $this->referrer = $_SERVER['HTTP_REFERER'];
			return;
		}
		
		foreach ($_SERVER['argv'] as $arg)
		{
			if (strpos($arg, '='))
			{
				list($key, $val) = explode('-', $arg);
				$this->setProperty($key, $val);
			}
		}
	}
	
	/**
	 * Set a parameter (stored in the private $properties array).
	 * @param string $key
	 * @param mixed the data
	 */
	public function setProperty($key, $val)
	{
		$this->properties[$key] = $val;
	}

	/**
	 * Access a parameter (stored in the private $properties array).
	 * @param string $key
	 * @return mixed the corresponding value
	 */
	public function getProperty($key)
	{
		if (isset($this->properties[$key]))
		{
			return $this->properties[$key];
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * TODO
	 *  - do we want to allow access to /all/ properties in this manner?
	 * Get the array of properties.
	 * @return array the array of properties
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * Determines whether a given property exists.
	 * @param string $key
	 * @return boolean
	 */
	public function propertyExists($key)
	{
		return isset($this->properties[$key]);
	}

	/**
	 * When an object is cloned, PHP 5 will perform a shallow copy of all of the object's 
	 * properties. Any properties that are references to other variables, will remain references. 
	 * If a __clone() method is defined, then the newly created object's __clone() method will be 
	 * called, to allow any necessary properties that need to be changed.
	 */
	function __clone()
	{
		$this->properties = array();
	}

	/**
	 * Adds a message to the list of feedback.
	 * @param mixed $msg string or array of messages to add
	 */
	public function addFeedback($msg)
	{
		array_push($this->feedback, $msg);
	}

	/**
	 * Gets the feedback messages.
	 * @return array
	 */
	public function getFeedback()
	{
		return $this->feedback;
	}

	/**
	 * Returns a string containing a string representation of all feedback array elements in the 
	 * same order, with the seperator string between each element.
	 * @param string $separator
	 * @return string 
	 */
	public function getFeedbackString($separator = "\n")
	{
		return implode($separator, $this->feedback);
	}

	/**
	 * @param string $msg the message
	 */
	public function addError($msg)
	{
		array_push($this->error, $msg);
	}

	/**
	 * Returns a string containing a string representation of all feedback array elements in the 
	 * same order, with the seperator string between each element.
	 * @param string $separator
	 * @return string 
	 */
	public function getErrorString($separator = "\n")
	{
		return implode($separator, $this->error);
	}

	/**
	 * Set an object (stored in the private $objects array).
	 * @param string $name name given to the object
	 * @param mixed the object
	 */
	public function setObject($name, $object)
	{
		$this->objects[$name] = $object;
	}

	/**
	 * Access an object (stored in the private $objects array).
	 * @param string $name name given to object
	 * @return mixed the corresponding objet
	 */
	public function getObject($name)
	{
		if (isset($this->objects[$name]))
		{
			return $this->objects[$name];
		}
		else
		{
			return null;
		}
	}
	
	
	/**
	 * Set the last command.
	 * @param app_command_Command $command
	 */
	public function setCommand(app_command_Command $command)
	{
		$this->lastCommand = $command;
	}
	
	/**
	 * Return the last command.
	 * @return app_command_Command
	 */
	public function getLastCommand()
	{
		return $this->lastCommand;
	}

	/**
	 * Set a parameter (stored in the private $properties array).
	 * @param string $key
	 * @param mixed the data
	 */
	public function setValidationError($error = true)
	{
		$this->validation_error = $error;
	}

	/**
	 * Return whether or not there is a valdiation error.
	 * @return boolean
	 */
	public function isValidationError()
	{
		return $this->validation_error;
	}
	
	public function intIP()
	{
		return ip2long($this->ip);
	}

}

?>