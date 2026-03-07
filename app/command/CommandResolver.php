<?php

/**
 * Defines the app_command_CommandResolver class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/command/Command.php');
require_once('app/command/DefaultCommand.php');

/**
 * A controller needs a way of deciding to interpret an HTTP request so that it can invoke the 
 * right code in order to fulfill the request. By using a specialist class it makes it easier to 
 * refactor for polymorphism if necessary.
 * 
 * This class looks for a request parameter called cmd. Assuming this is found, and that it maps 
 * to a real class file in the command directory, and that the class file contains the right kind 
 * of class, the getCommand() method creates and returns an instance of the relevant class.
 * 
 * @package Framework
 */
class app_command_CommandResolver
{
	private static $base_cmd;
	private static $default_cmd;
	
	/**
	 * 
	 */
	public function __construct()
	{
		if (!self::$base_cmd)
		{
			self::$base_cmd = new ReflectionClass( "app_command_Command" );
			self::$default_cmd = new app_command_DefaultCommand();
		}
	}
	
	/**
	 * This class looks for a request parameter called cmd. Assuming this is found, and that it maps
	 * to a real class file in the command directory, and that the class file contains the right kind
	 * of class, the getCommand() method creates and returns an instance of the relevant class.
	 * @param app_controller_Request $request
	 * @return Command a new instance of the relevant class
	 */
	public function getCommand(app_controller_Request $request)
	{
		$cmd = $request->getProperty('cmd');
		if (!$cmd)
		{
			return self::$default_cmd;
		}
		
		$filepath = 'app/command/' . $cmd . '.php';
		$classname = 'app_command_' . $cmd;
		
		if (file_exists($filepath))
		{
			// File exists
			require_once($filepath);
			if (class_exists($classname))
			{
				// Class exists
				$cmd_class = new ReflectionClass($classname);
				if ($cmd_class->isSubClassOf(self::$base_cmd))
				{
					/* Class is a subclass of the base one (specified in the constructor) */
					/* By declaring final, make impossible for a child class to override. No 
					 * Command class therefore will ever require arguments to its constructor. */
					return $cmd_class->newInstance();
				}
				else
				{
					$request->addFeedback("command '$cmd' is not a Command");
				}
			}
		}
		$request->addFeedback("command '$cmd' not found");
		return clone self::$default_cmd;
    }

}

?>