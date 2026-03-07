<?php

/**
 * Defines the app_domain_ObjectWatcher class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');

/**
 * An Identity Map is simply an object whose task it is to keep track of all the objects in a 
 * system, and thereby help to ensure that nothing that should be one object becomes two. The 
 * Identity Map itself does not prevent this from happening in any active way. Its roles is to 
 * manage information about objects. The ObjectWatcher is a simple Identity Map.
 * @package Framework
 */
class app_domain_ObjectWatcher
{
	/**
	 * Array for collecting all objects.
	 * @var array
	 * @private
	 */
	private $all = array();

	/**
	 * Array for collecting dirty objects which need updating.
	 * @var array
	 * @private
	 */
	private $dirty = array();

	/**
	 * Array for collecting new objects.
	 * @var array
	 * @private
	 */
	private $new_ones = array();

	/**
	 * Array for collecting objects to be deleted.
	 * @var array
	 * @private
	 */
	private $delete = array();

	/**
	 * Hold the singleton instance of self.
	 * @var app_domain_ObjectWatcher
	 * @private
	 */
	private static $instance;

	/**
	 * Construtor is private to ensure an ObjectWatcher can only be instanted from the instance() 
	 * function. Singleton pattern.
	 */
	private function __construct() {}

	/**
	 * Returns the single app_domain_ObjectWatcher. Ensures only ever acting one one and the same 
	 * instance.
	 * @return app_domain_ObjectWatcher instance
	 */
	public static function instance()
	{
		if (!self::$instance)
		{
			self::$instance = new app_domain_ObjectWatcher();
		}
		return self::$instance;
	}

	/**
	 * Constructs and returns a globally unique ID based on the concrete type of the DomainObject 
	 * and its ID. 
	 * @param app_domain_DomainObject $obj
	 * @return string the globally unique ID 
	 */
	public function globalKey(app_domain_DomainObject $obj)
	{
		// Use ID is Unique ID is not present
		if (method_exists($obj, 'getUid'))
		{
			$key = get_class($obj) . '.' . $obj->getUid();
		}
		elseif (method_exists($obj, 'objectWatcherKey'))
		{
			$key = get_class($obj) . '.' . $obj->objectWatcherKey() . '.' . $obj->getId();
		}
		else
		{
			$key = get_class($obj) . '.' . $obj->getId();
		}
		return $key;
	}

	/**
	 * Adds an object to the ObjectWatcher.
	 * @param app_domain_DomainObject $obj
	 */
	public static function add(app_domain_DomainObject $obj)
	{
		$inst = self::instance();
		$inst->all[$inst->globalKey($obj)] = $obj;
	}

	/**
	 * TODO
	 *  - $id may refer to UID?
	 *  
	 * Builds a key from the parameters and checks if it indexes an element in the $all property. 
	 * If an object is found, a reference is duly returned.
	 * @param string $classname
	 * @param integer $id
	 * @return app_domain_DomainObject object found at index
	 */
	public static function exists($classname, $id)
	{
		$inst = self::instance();
		$key = "$classname.$id";
		
		/* TODO Check whether this is correct */
//		return $inst->all[$key];

		if (isset($inst->all[$key]))
		{
		/* End TO DO */
			return $inst->all[$key];
		/* Continuation of TO DO */
		}
		else
		{
			return null;
		}
		/* End TO DO */
	}

	/**
	 * Adds the object to the list of objects to be deleted.
	 * @param app_domain_DomainObject $obj object to be added
	 */
	public static function addDelete(app_domain_DomainObject $obj)
	{
		$self = self::instance();
		$self->delete[$self->globalKey($obj)] = $obj;
	}

	/**
	 * Adds the object to the list of objects which need to be updated in the database.
	 * @param app_domain_DomainObject $obj object to be added
	 */
	public static function addDirty(app_domain_DomainObject $obj)
	{
		$inst = self::instance();
		
		/* TODO Check whether this is correct */
//		if (!$inst->new_ones[$inst->globalKey($obj)])
		if (!isset($inst->new_ones[$inst->globalKey($obj)]))
		{
			$inst->dirty[$inst->globalKey($obj)] = $obj;
		}
	}

	/**
	 * Adds the object to the list of new objects to be inserted.
	 * @param app_domain_DomainObject $obj object to be added 
	 */
	public static function addNew(app_domain_DomainObject $obj)
	{
		$inst = self::instance();
		$key = $inst->globalKey($obj);
		$inst->new_ones[$key] = $obj;
		
//		// Called here to force the insert
//		$inst->performOperations();
	}

	/**
	 * Adds the object to the list of objects which are clean and don't need inserting, updating or 
	 * deleting.
	 * @param app_domain_DomainObject $obj object to be added 
	 */
	public static function addClean(app_domain_DomainObject $obj)
	{
		$self = self::instance();
		unset($self->delete[$self->globalKey($obj)]);
		unset($self->dirty[$self->globalKey($obj)]);
		unset($self->new_ones[$self->globalKey($obj)]);
	}

	/**
	 * Ensures operations are performed on a given object.
	 * @param app_domain_DomainObject $obj object 
	 */
	public static function commit(app_domain_DomainObject $obj)
	{
		$self = self::instance();
		
		if (isset($self->new_ones[$self->globalKey($obj)]))
		{
			if ($myobj = $self->new_ones[$self->globalKey($obj)])
			{
				$myobj->finder()->insert($myobj);
			}
		}
		
		if (isset($self->dirty[$self->globalKey($obj)]))
		{
			if ($myobj = $self->dirty[$self->globalKey($obj)])
			{
				$obj->finder()->update($obj);
			}
		}
		
		if (isset($self->delete[$self->globalKey($obj)]))
		{
			if ($myobj = $self->delete[$self->globalKey($obj)])
			{
				$obj->finder()->delete($obj);
			}
		}
		
		self::addClean($obj);
	}

	/**
	 * Ensures all operations are performed on the new, dirty and delete objects. We can call this 
	 * from client code at any time, but we also added an invocation to the __destruct() method 
	 * making object update and insertion entirely automatic.
	 */
	public function performOperations()
	{
		foreach ($this->dirty as $key => $obj)
		{
			$obj->finder()->update($obj);
		}
		
		foreach ($this->new_ones as $key => $obj)
		{
			$obj->finder()->insert($obj);
		}
		
		foreach ($this->delete as $key => $obj)
		{
			$obj->finder()->delete($obj);
		}
        
        $this->dirty = array();
        $this->new_ones = array();
        $this->delete = array();
    } 

//	/**
//	 * Called during the script shutdown phase, which is typically right before the execution of 
//	 * the script finishes.
//	 */
//	function __destruct()
//	{
//		$inst = self::instance();
//		$inst->performOperations();
//	}

	/**
	 * Clears the object from the object watcher and re-adds it.
	 * @param app_domain_DomainObject $obj object to be reloaded
	 */
	public static function reload(app_domain_DomainObject $obj)
	{
		$instance = self::instance();
		unset($instance->all[$instance->globalKey($obj)]);
		unset($instance->delete[$instance->globalKey($obj)]);
		unset($instance->dirty[$instance->globalKey($obj)]);
		unset($instance->new_ones[$instance->globalKey($obj)]);
		self::add($obj);
	}


	/**
	 * Removes the object from the object watcher.
	 * @param app_domain_DomainObject $obj object to be removed
	 */
	public static function remove(app_domain_DomainObject $obj)
	{
		$instance = self::instance();
		unset($instance->all[$instance->globalKey($obj)]);
		unset($instance->delete[$instance->globalKey($obj)]);
		unset($instance->dirty[$instance->globalKey($obj)]);
		unset($instance->new_ones[$instance->globalKey($obj)]);
	}
	
}

?>