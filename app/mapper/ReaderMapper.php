<?php

/**
 * Defines the app_mapper_ReaderMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');
require_once('app/base/Exceptions.php');
require_once('app/domain/Readers.php');
require_once('app/controller/ApplicationHelper.php');

/**
 * @package Framework
 */
abstract class app_mapper_ReaderMapper implements app_domain_Reader
{
	private $debug = false;

	/**
	 * Holds db connection resource
	 * @var resource
	 */
	protected static $DB;

	/**
	 * Uses and ApplicationHelper to get a MDB2_Common object
	 */
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
	}
	
	/**
	 * Responsible for extracting an associative array from the MDB2_Result object. Having acquired 
	 * the array, it calls loadArray().
	 * @param MDB2_Result $result
	 * @return app_domain_DomainObject
	 * @see loadArray()
	 */
	public function load(MDB2_Result $result)
	{
		$array = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		
		if (!is_array($array))
		{
			return null;
		}
		
		if (!$array['id'])
		{
			return null;
		}
		
		$object = $this->loadArray($array);
		return $object;
	}
	
//	/**
//	 * Returns a given object, if it exists, from the identity map.
//	 * @param integer $id
//	 * @return app_domain_DomainObject
//	 */
//	public function getFromMap($id)
//	{
//		return app_domain_ObjectWatcher::exists($this->targetClass(), $id);
//	}
//	
//	/**
//	 * 
//	 * @param app_domain_DomainObject $obj
//	 * @return 
//	 */
//	public function addToMap(app_domain_DomainObject $obj)
//	{
//		return app_domain_ObjectWatcher::add($obj);
//	}
	
	/**
	 * Delegate to abstract doFind() which should be implemented in concrete child class.
	 * @see doFind()
	 */
	public function find($id)
	{
////		echo "<p><b>app_mapper_Mapper::find($id)</b></p>";
//		$old = $this->getFromMap($id);
//		if ($old)
//		{
////			echo "\$old found<br />";
//			return $old;
//		}
		return $this->doFind($id);
	}
	
	/**
	 * Responsible for for transforming an associative array into a DomainObject instance, but 
	 * delegating to the child class's implementation of doLoad().
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	public function loadArray($array)
	{
//		echo "<h1>app_mapper_Mapper::loadArray($array)</h1>";
//		$old = $this->getFromMap($array['id']);
////		echo "<br />here 1";
//		if ($old)
//		{
////			echo "<br />here 1a";
//			return $old;
//		}
//		echo "<br />here 2";
		$obj = $this->doLoad($array);
//		echo "<br />here 3";
//		$this->addToMap($obj);
		
		
		// Because setting up an object involves marking it new via the constructor's call to 
		// ObjectWatcher::addNew(), we must call markClean(), or every single object extracted from 
		// the database will be saved at the end of the request, which is not what we want.
		$obj->markClean();
		return $obj;
	}

	/**
	 * @param MDB2_Statement_Common $stmt the statement to execute
	 * @param array $values array of data values to pass to use with the statement
	 * @return a result handle or MDB2_OK on success, a MDB2 error on failure
	 */
	public function doStatement(MDB2_Statement_Common $stmt, $values)
	{
//		$this->debug = true;
		
//		echo "<hr /><p><b>app_mapper_Mapper::doStatement(".get_class($stmt).", $values)</b></p>";

	
		$res = $stmt->execute($values);

		if ($this->debug)
		{
			echo "\$stmt type = " . get_class($stmt);
			echo "<br />\$res type = " . get_class($res);
			echo "<pre>";
			print_r($stmt->query);
			echo "<br />";
			print_r($stmt->values);
			echo "</pre>";
		}

		if (MDB2::isError($res))
		{
//			echo "<pre>";
//			print_r($res);
//			echo "</pre>";
			throw new app_base_MDB2Exception($res);
		}
		
		
//		$this->audit($sth, $values);
		
		return $res;
	}

//	/**
//	 * Load an object from an associative array. Concrete implementation in child class(es).
//	 * @param array $array an associative array
//	 * @return app_domain_DomainObject
//	 */
//	protected abstract function doLoad($array);
//	
//	/**
//	 * Responsible for constructing and running any queries that are needed. Individual classes 
//	 * take responsibility for that, finishing up by calling load().
//	 * 
//	 * @see load()
//	 */
//	protected abstract function doFind($id);
	
//	/**
//	 * @return string
//	 */
//	protected abstract function targetClass();

	/**
	 * Return an MDB2_Result set as an array.
	 * @param MDB2_Result $result
	 * @return array
	 */
	public static function mdb2ResultToArray(MDB2_Result $result)
	{
		$raw = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$raw[] = $row;
			$result->nextResult();
		}
		return $raw;
	}
	
	/**
	 * Get current user information from the session.
	 * @return array associative array containing user data
	 */
	public static function getCurrentUser()
	{
		$session = Auth_Session::singleton();
		return $session->getSessionUser();
	}

	/**
	 * Get user ID from the session.
	 * @return integer current user ID
	 */
	public static function getCurrentUserId()
	{
		$user = self::getCurrentUser();
		return $user['id'];
	}

}

?>