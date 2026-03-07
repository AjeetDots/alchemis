<?php

require_once('app/domain/Collections.php');
require_once('app/domain/ObjectWatcher.php');
require_once('app/domain/HelperFactory.php');


/**
 * @package Framework
 */
abstract class app_domain_ReaderObject
{
	protected function __construct($id = null)
	{
		// nothing
	}
	
//	/**
//	 * Static method for acquiring a Collection object.
//	 * @param string $type
//	 * @return app_mapper_Collection the collection object.
//	 */
//	static function getCollection($type)
//	{
//		return app_domain_HelperFactory::getCollection($type);
//	}
// 
//	/**
//	 * Instance method for acquiring a Collection object.
//	 * @return app_mapper_Collection the collection object.
//	 * @see getCollection()
//	 */
//	function collection()
//	{
//		return self::getCollection(get_class($this));
//	}
	
	/**
	 * Returns the mapper (finder) object (data layer) which handles the transition from database 
	 * to object.
	 * @return app_mapper_Mapper the mapper object 
	 */
	function reader()
	{
		return self::getReader(get_class($this));
	}

	/**
	 * @param string $type name of the app_domain_ReaderObject
	 * @return app_mapper_Mapper the mapper object (data layer) which handles the transition from 
	 * database to object.
	 */
	static function getReader($type)
	{
		$finder = app_domain_HelperFactory::getReader($type);
		return $finder;
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