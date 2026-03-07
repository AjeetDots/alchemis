<?php

/**
 * Defines the app_domain_ObjectTieredCharacteristicHelper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ObjectCharacteristicHelperMapper.php');
require_once('app/mapper/ObjectCharacteristicBooleanMapper.php');
require_once('app/mapper/ObjectCharacteristicDateMapper.php');
require_once('app/mapper/ObjectCharacteristicTextMapper.php');

/**
 * @package Alchemis
 */
class app_domain_ObjectTieredCharacteristicHelper 
{
	
	/**
	 * Instantiate and return the appropriate app_domain_ObjectTieredCharacteristic 
	 * object.
	 * @param integer $id
	 * @return app_domain_objectCharacteristic
	 */
	public static function factory($id = null)
	{
		if (is_null($id))
		{
			return new app_domain_ObjectTieredCharacteristic();
		}
		else
		{
			return app_domain_ObjectTieredCharacteristic::find($id);
		}
	}

	/**
	 * Determine if a given object (company) is associated with a given tiered characteristic. 
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return boolean
	 */
	public static function isAssociated($object_id, $tiered_characteristic_id)
	{
		return app_domain_ObjectTieredCharacteristic::isAssociated($object_id, $tiered_characteristic_id);
	}
	
	/**
	 * Determine if a given object (parent company) is associated with a given tiered characteristic. 
	 * @param integer $parent_company_id
	 * @param integer $tiered_characteristic_id
	 * @return boolean
	 */
	public static function isAssociatedParent($parent_company_id, $tiered_characteristic_id)
	{
		return app_domain_ObjectTieredCharacteristic::isAssociatedParent($parent_company_id, $tiered_characteristic_id);
	}

}

?>