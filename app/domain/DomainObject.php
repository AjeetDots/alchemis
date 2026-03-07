<?php

/**
 * Defines the app_domain_DomainObject class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/RuleValidator.php');
require_once('app/domain/Collections.php');
require_once('app/domain/ObjectWatcher.php');
require_once('app/domain/HelperFactory.php');
require_once('include/Auth/Session.php');

/**
 * @package Framework
 */
abstract class app_domain_DomainObject
{
	/**
	 * ID unique to object type.
	 * @var integer
	 */
	protected $id;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		$this->id = $id;
		
		if (!$this->id)
		{
			$this->id = $this->finder()->newId();
			$this->markNew();
		}
	}

	/**
	 * Commit any changes to the object to the database.
	 */
	public function commit()
	{
		return app_domain_ObjectWatcher::commit($this);
	}

	/**
	 * Adds the object to the list of new objects to be inserted.
	 */
	public function markNew()
	{
		app_domain_ObjectWatcher::addNew($this);
	}
	
	/**
	 * Adds the object to the list of objects to be deleted.
	 */
	public function markDeleted()
	{
		app_domain_ObjectWatcher::addDelete($this);
	}

	/**
	 * Adds the object to the list of objects which are clean and don't need inserting, updating or 
	 * deleting.
	 */
	public function markDirty()
	{
		app_domain_ObjectWatcher::addDirty($this);
	}
	
	/**
	 * Adds the object to the list of objects which need to be updated in the database.
	 */
	public function markClean()
	{
		app_domain_ObjectWatcher::addClean($this);
	}
	
	/**
	 * Returns the unique ID.
	 * @return integer $id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Static method for acquiring a Collection object.
	 * @param string $type
	 * @return app_mapper_Collection the collection object.
	 */
	static function getCollection($type)
	{
		return app_domain_HelperFactory::getCollection($type);
	}
 
	/**
	 * Instance method for acquiring a Collection object.
	 * @return app_mapper_Collection the collection object.
	 * @see getCollection()
	 */
	function collection()
	{
		return self::getCollection(get_class($this));
	}
	
	/**
	 * Returns the mapper (finder) object (data layer) which handles the transition from database 
	 * to object.
	 * @return app_mapper_Mapper the mapper object 
	 */
	function finder()
	{
		return self::getFinder(get_class($this));
	}

	/**
	 * @param string $type name of the app_domain_DomainObject
	 * @return app_mapper_Mapper the mapper object (data layer) which handles the transition from 
	 * database to object.
	 */
	static function getFinder($type)
	{
		$finder = app_domain_HelperFactory::getFinder($type);
//		$finder->attach(new SqlLogger());
		return $finder;
	}

	/**
	 * 
	 */
	public function __clone()
	{
		$this->id = null;
	}

	/**
	 * Validates a value against a set of rules.
	 * @param mixed $value the value that need to be validated
	 * @param array $rules associative array defining the validation rules
	 * @throws app_base_ValidationException if 1 or more valdiation rules are broken
	 * @see app_base_RuleValidator
	 */
	public static function validate($value, $rules)
	{
		return app_base_RuleValidator::validate($value, $rules);
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