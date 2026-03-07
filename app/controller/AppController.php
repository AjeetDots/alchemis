<?php

/**
 * Defines the app_controller_AppController class.
 * @author    $Author$
 * @copyright 2006 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/controller/Request.php');

/**
 * Finds and instantiates the correct Command class using the Request object.
 * 
 * Relies upon previous commands having been stored in the Request object. This is done by the 
 * Command base class.
 * @package Framework
 */
class app_controller_AppController
{
	private static $base_cmd;
	private static $default_cmd;
	private static $base_view;
	private $controllerMap;
	private $invoked = array();
	
	/**
	 * @param app_controller_ControllerMap $map
	 */
	function __construct(app_controller_ControllerMap $map)
	{
		$this->controllerMap = $map;
		if (!self::$base_cmd)
		{
			self::$base_cmd = new ReflectionClass('app_command_Command');
			self::$default_cmd = new app_command_DefaultCommand();
		}
		if (!self::$base_view)
		{
			require_once('app/view/View.php');
			self::$base_view = new ReflectionClass('app_view_View');
		}
	}

	/**
	 * @param app_controller_Request $req
	 * @return string
	 * @see getResource()
	 */
    function getView(app_controller_Request $req)
    {
    	return $this->getResource($req, 'View');
    }
    
    /**
	 * @param app_controller_Request $req
	 * @see getResource()
	 */
	function getForward(app_controller_Request $req)
	{
		$forward = $this->getResource($req, 'Forward');
		if ($forward)
		{
			$req->setProperty('cmd', $forward);
		}
		return $forward;
	}
	
	/**
	 * Implements the search for both forwarding and view selection. It is called by getView() and 
	 * getForward().
	 * @param app_controller_Request $req
	 * @param $res
	 */
	private function getResource(app_controller_Request $req, $res)
	{
		$cmd_str = $req->getProperty('cmd');
		
		$previous = $req->getLastCommand();
		$status = $previous->getStatus();
		
		if (!$status)
		{
			$status = 0;
		}
		
		$acquire = "get$res";
		$resource = $this->controllerMap->$acquire($cmd_str, $status);
		
		if (!$resource)
		{
			$resource = $this->controllerMap->$acquire($cmd_str, 0);
		}
		
		if (!$resource)
		{
			$resource = $this->controllerMap->$acquire('default', $status);
		}
		
		if (!$resource)
		{
			$resource = $this->controllerMap->$acquire('default', 0);
		}
		
		return $resource;
	}


	/**
	 * Responsible for returning as many commands as have been configured into a forwarding chain. 
	 * 
	 * When the intial request is received, there should be a 'cmd' property available, and no 
	 * record of a previous Command having been run in this request. The 'Request' object stores 
	 * this information. If the 'cmd' request property has not yet been set, then the method uses 
	 * 'default' and returns the default Command class. The $cmd variable string variable is passed 
	 * to resolveCommand(), which uses it to acquire a Command object.
	 * 
	 * When getCommand() is called for the second time in the request, the Request object will be 
	 * holding a reference to to the Command previously run. getCommand() then checks to see if any 
	 * forwarding is set for the combination of that Command and its status flag (by calling 
	 * getForward()). If getForward() finds a match, it returns a string that can be resolved to a 
	 * Command and returned to the Controller.
	 * 
	 * @param app_controller_Request $req
	 * @return mixed
	 */
	function getCommand(app_controller_Request $req)
	{
		$previous = $req->getLastCommand();
		
		if (!$previous)
		{
			$cmd = $req->getProperty('cmd');
			if (!$cmd)
			{
				$req->setProperty('cmd', 'default');
				return self::$default_cmd;
			}
		}
		else
		{
			$cmd = $this->getForward($req);
			if (!$cmd)
			{
				return null;
			}
		}
		
		$cmd_obj = $this->resolveCommand($cmd);
		if (!$cmd_obj)
		{
			throw new app_base_AppException("Couldn't resolve '$cmd'");
		}
		
		$cmd_class = get_class($cmd_obj);
		
		// Test variable index is set to avoid errant notice on first invocation
		if (!isset($this->invoked[$cmd_class]))
		{
			$this->invoked[$cmd_class] = 0;
		}
		$this->invoked[$cmd_class]++;
		
		if ($this->invoked[$cmd_class] > 1)
		{
			throw new app_base_AppException('Circular forwarding');
		}
		
		return $cmd_obj;
	}
	
	/**
	 * 
	 * @param $cmd
	 * @return mixed
	 */
	function resolveCommand($cmd)
	{
		$classroot = $this->controllerMap->getClassroot($cmd);
		$filepath = "app/command/$classroot.php";
		$classname = "app_command_$classroot";
		
		if (file_exists($filepath))
		{
			require_once("$filepath");
			if (class_exists($classname))
			{
				$cmd_class = new ReflectionClass($classname);
				if ($cmd_class->isSubClassOf(self::$base_cmd))
				{
					return $cmd_class->newInstance();
				}
				elseif ($cmd_class->isSubClassOf('app_command_AjaxCommand'))
				{
					return $cmd_class->newInstance();
				}
			}
		}
		return null;
	}

	/**
	 * 
	 * @param $cmd
	 * @return mixed
	 */
	function resolveView($view)
	{
		
//		$classroot = $this->controllerMap->getClassroot($cmd);
		$classroot = $view;
		$filepath = "app/view/$classroot.php";
		$classname = "app_view_$classroot";
		if (file_exists($filepath))
		{
			require_once($filepath);
			if (class_exists($classname))
			{
				$view_class = new ReflectionClass($classname);
				if ($view_class->isSubClassOf(self::$base_view))
				{
					return $view_class->newInstance();
				}
			}
			else
			{
				throw new app_base_AppException("Couldn't resolve view '$view'. Is the class correctly defined in '$filepath'?", 601);
			} 
		}
		else
		{
			throw new app_base_AppException("Couldn't find command view '$view'. Does the file '$filepath' exist?", 404);
		} 
	}
}


/**
 * Stores cached configuration data.
 * @package Framework
 */
class app_controller_ControllerMap
{
	private $viewMap = array();
	private $forwardMap = array();
	private $classrootMap = array();
	
	function addClassroot($command, $classroot)
	{
		$this->classrootMap[$command] = $classroot;
	}
	
	/**
	 * Get the class root file required for a command.
	 * @param string $command
	 */
	function getClassroot($command)
	{
//		return ($name = $this->classrootMap[$command]) ? $name : $command;
		
		// TODO - want to be shown notices
		if (isset($this->classrootMap[$command]))
		{
			$name = $this->classrootMap[$command];
			return $name;
		}
		else
		{
			return $command;
		}
	}
	
	/**
	 * @param string $command
	 * @param integer $status
	 * @param string $view
	 */
	public function addView($command = 'default', $status = 0, $view)
	{
		$this->viewMap[$command][$status] = $view;
	}
	
	/**
	 * @param string $command
	 * @param integer $status
	 * @return string
	 */
	public function getView($command, $status)
	{
		/* TODO Check whether this is correct */
		if (isset($this->viewMap[$command][$status]))
		{
		/* End TO DO */
		
		return $this->viewMap[$command][$status];
		
		/* Continuation of TO DO */
		}
		/* End TO DO */
	}
	
	/**
	 * @param string $command
	 * @param integer $status
	 * @param string $newCommand
	 */
	public function addForward($command, $status = 0, $newCommand)
	{
		$this->forwardMap[$command][$status] = $newCommand;
	}
	
	/**
	 * @param string $command
	 * @param integer $status
	 * @return string
	 */
	public function getForward($command, $status)
	{
		/* TODO Check whether this is correct */
		if (isset($this->forwardMap[$command][$status]))
		{
		/* End TO DO */
		
		return $this->forwardMap[$command][$status];
		
		/* Continuation of TO DO */
		}
		/* End TO DO */
	}

}

?>