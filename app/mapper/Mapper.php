<?php

/**
 * Defines the app_mapper_Mapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');
require_once('app/base/Exceptions.php');
require_once('app/domain/Finders.php');
require_once('app/controller/ApplicationHelper.php');
require_once('app/mapper/Collections.php');
require_once('include/Auth/Session.php');

/**
 * Responsible for handling the transition from database to object.
 * @package Framework
 */
abstract class app_mapper_Mapper implements app_domain_Finder
{
	private $debug = false;

	/**
	 * Holds database connection resource
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

	/**
	 * Returns a given object, if it exists, from the identity map.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 */
	public function getFromMap($id)
	{
		return app_domain_ObjectWatcher::exists($this->targetClass(), $id);
	}

	/**
	 *
	 * @param app_domain_DomainObject $obj
	 * @return
	 */
	public function addToMap(app_domain_DomainObject $obj)
	{
		return app_domain_ObjectWatcher::add($obj);
	}

	/**
	 * Delegate to abstract doFind() which should be implemented in concrete child class.
	 * @see doFind()
	 */
	public function find($id)
	{
//		echo "<h1>app_mapper_Mapper::find($id)</h1>";
		$old = $this->getFromMap($id);
		if ($old)
		{
			return $old;
		}
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
		$old = $this->getFromMap($array['id']);

		if ($old)
		{
			return $old;
		}

		$obj = $this->doLoad($array);
		$this->addToMap($obj);

		// Because setting up an object involves marking it new via the constructor's call to
		// ObjectWatcher::addNew(), we must call markClean(), or every single object extracted from
		// the database will be saved at the end of the request, which is not what we want.
		$obj->markClean();
		return $obj;
	}

	/**
	 *
	 * @param app_domain_DomainObject $obj
	 */
	public function insert(app_domain_DomainObject $obj)
	{
		$this->doInsert($obj);
	}

	/**
	 *
	 * @param MDB2_Statement_Common $stmt the statement to execute
	 * @param array $values array of data values to pass to use with the statement
	 * @return a result handle or MDB2_OK on success, a MDB2 error on failure
	 *         NB to test whether any results where returned use $res->numRows()
	 */
//	public function doStatement(MDB2_Statement_Common $sth, $values)
	public function doStatement($sth, $values = null)
	{
//		echo "doStatement";
//		echo "<pre>";
//		print_r($sth);
//		echo "</pre>";

		$this->debug = (get_class($sth) == 'MDB2_Error');

		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($sth);
//		if ($this->debug) echo '<p>[error_message_prefix] => ' . $sth->error_message_prefix . '</p>';
//		if ($this->debug) echo '<p>[message] => ' . $sth->message . '</p>';
//		if ($this->debug) echo '<p>[userinfo] => ' . $sth->userinfo . '</p>';
		if ($this->debug) echo "</pre>";

//		$this->debug = true;
		if ($this->debug) echo "<h2>app_mapper_Mapper::doStatement(".get_class($sth).", $values)</h2>";
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($values);
		if ($this->debug) echo "</pre>";
		if ($this->debug) echo "\$sth type = " . get_class($sth);

		try
		{
			$res = $sth->execute($values);
			$sth->free();
		}
		catch (Exception $e)
		{
			exit($e->getMessage());
		}

		if (MDB2::isError($res))
		{
			throw new app_base_MDB2Exception($res);
		}
		return $res;
	}

	/**
	 * Load an object from an associative array. Concrete implementation in child class(es).
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected abstract function doLoad($array);

	/**
	 * Responsible for constructing and running any queries that are needed. Individual classes
	 * take responsibility for that, finishing up by calling load().
	 *
	 * @see load()
	 */
	protected abstract function doFind($id);

//	/**
//	 * @param app_domain_DomainObject $object
//	 */
//	protected abstract function update(app_domain_DomainObject $object);

	/**
	 * @param app_domain_DomainObject $object
	 */
	protected abstract function doInsert(app_domain_DomainObject $object);

	/**
	 * Returns the target class name.
	 * @return string
	 */
	protected function targetClass()
	{
		$type = preg_replace('/^.*_|Mapper$/', '', get_class($this));
		return "app_domain_{$type}";
	}

	/**
	 * Return an MDB2_Result set as an array. Returns the results in the same
	 * format as MDB2_Driver_Common::queryAll()
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