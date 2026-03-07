<?php

/**
 * Defines the app_command_AjaxCommand class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/ajax/domain/Ajax_JSON.class.php');
require_once('app/ajax/domain/ajaxResponse.class.php');
require_once('app/ajax/domain/ajaxWarning.class.php');
require_once('app/ajax/domain/ajaxNotice.class.php');

/**
 * @package Framework
 */
abstract class app_command_AjaxCommand
{
	/**
	 * Holds the response object.
	 * @var ajaxResponse
	 */
	protected $response = null;

	/**
	 * Holds the request object.
	 * @var ajaxResponse
	 */
	protected $request = null;

	/**
	 * Constructor.
	 * @param $request
	 */
	function __construct($request = null)
	{
		// Create the response object to hold the data structure types to be 
		// passed back to the calling client.
		$this->response = new ajaxResponse();
		if (is_null($request))
		{
			array_push($this->response->notices, 'Request is null');
		}
		$this->request = $request;
		
	}

	abstract public function execute();

	/**
	 * Sets the response object.
	 * @param array
	 */
	public function setResponse($response)
	{
		$this->response = $response;
	}

	/**
	 * Gets the response object.
	 * @return array
	 */
	public function getResponse()
	{
		return $this->response;
	}


	/**
	 * Automatically process standard getters and setters.
	 * @param app_domain_DomainObject $object
	 * @param $request
	 * @param string $function_type e.g. 'get', 'set'
	 */
	protected static function processAccessorsMutators(app_domain_DomainObject $object, $request, $function_type)
	{
		$debug = false;
		
		foreach ($request as $key => $item)
		{
			if ($debug) echo '$key = ' . $key . "\n";
			if ($debug) echo '$item = ' . $item . "\n------------\n";
			
			$function_name = self::makeAccessorMutatorFunctionName($function_type, $key);
			if (method_exists($object, $function_name))
			{
				if ($debug) echo 'function: ' . $function_name . '(' . $item . ")\n"; 
				$object->{$function_name}($item);
			}
			else
			{
				// Do nothing. Do not exit at this point as we need to process
				// any other accessors or mutators in the request string 
			}
		}
		return true;
	}

	
	/**
	 * Returns the function name that needs to be called by concatenating the 
	 * field name and the type of function (e.g. 'get', 'set')
	 * @param string $function_type
	 * @param string $field
	 * @return string the function name
	 */
	protected static function makeAccessorMutatorFunctionName($function_type, $field)
	{
		return $function_type . ucfirst($field);
	}

}

?>