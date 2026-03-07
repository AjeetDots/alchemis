<?php

/**
 * Defines the app_domain_Configuration class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Framework
 */
class app_domain_Configuration extends app_domain_DomainObject
{

	/**
	 * Returns the value of a given property. Alias for find.
	 * @param string property to return value for
	 * @return string the property value
	 * @see find()
	 */
	public static function getProperty($property)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($property);
	}

	/**
	 * Sets the name.
	 * @param string
	 * @access public
	 */
	public static function setProperty($property, $value)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->save($property, $value);
	}



	/**
 	 * Find a campaign target by a given id
	 * @param string 
	 * @return app_mapper_CampaignTargetCollection collection of app_domain_CampaignTarget objects
	 */
	public static function find($id)
	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->find($id);
		return self::getProperty($id);
	}	
	

	/**
 	 * Find all campaign targets
	 * @return app_mapper_CampaignTargetCollection collection of app_domain_CampaignTarget objects
	 */
	public static function findAll()
	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->findAll();
		return null;
	}
	
}

?>