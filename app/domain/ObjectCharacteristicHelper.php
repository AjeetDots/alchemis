<?php

/**
 * Defines the app_domain_ObjectCharacteristicHelper class. 
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
class app_domain_ObjectCharacteristicHelper 
{
	
	/**
	 * Instantiate and return the appropriate app_domain_ObjectCharacteristic 
	 * object.
	 * @param string $datatype
	 * @param integer $id
	 * @return app_domain_ObjectCharacteristic
	 */
	public static function factory($datatype = null, $id = null)
	{
		switch ($datatype)
		{
			case 'boolean':
				return new app_domain_ObjectCharacteristicBoolean($id);
			
			case 'date':
				return new app_domain_ObjectCharacteristicDate($id);
			
			case 'text':
				return new app_domain_ObjectCharacteristicText($id);
			
			default:
				return new app_domain_ObjectCharacteristic($id);
		}
	}

	/**
	 * Gets the value of a characteristic for a company.
	 * @param integer $characteristic_id
	 * @param string $characteristic_type
	 * @param integer $post_initiative_id
	 * @return array
	 */
	public static function getValueByCompanyId($characteristic_id, $characteristic_type, $company_id)
	{
		switch ($characteristic_type)
		{
			case 'boolean':
				$finder = new app_mapper_ObjectCharacteristicBooleanMapper();
				break;
			
			case 'date':
				$finder = new app_mapper_ObjectCharacteristicDateMapper();
				break;
			
			case 'text':
				$finder = new app_mapper_ObjectCharacteristicTextMapper();
				break;
			
			default:
				throw new Exception('Invalid characteristic type');
		}
		return $finder->getValueByCompanyId($characteristic_id, $company_id);
	}

	/**
	 * Gets the value of a characteristic for a post.
	 * @param integer $characteristic_id
	 * @param string $characteristic_type
	 * @param integer $post_id
	 * @return array
	 */
	public static function getValueByPostId($characteristic_id, $characteristic_type, $post_id)
	{
		switch ($characteristic_type)
		{
			case 'boolean':
				$finder = new app_mapper_ObjectCharacteristicBooleanMapper();
				break;
			
			case 'date':
				$finder = new app_mapper_ObjectCharacteristicDateMapper();
				break;
			
			case 'text':
				$finder = new app_mapper_ObjectCharacteristicTextMapper();
				break;
			
			default:
				throw new Exception('Invalid characteristic type');
		}
		return $finder->getValueByPostId($characteristic_id, $post_id);
	}

	/**
	 * Gets the value of a characteristic for a post initiative.
	 * @param integer $characteristic_id
	 * @param string $characteristic_type
	 * @param integer $post_initiative_id
	 * @return array
	 */
	public static function getValueByPostInitiativeId($characteristic_id, $characteristic_type, $post_initiative_id)
	{
		switch ($characteristic_type)
		{
			case 'boolean':
				$finder = new app_mapper_ObjectCharacteristicBooleanMapper();
				break;
			
			case 'date':
				$finder = new app_mapper_ObjectCharacteristicDateMapper();
				break;
			
			case 'text':
				$finder = new app_mapper_ObjectCharacteristicTextMapper();
				break;
			
			default:
				throw new Exception('Invalid characteristic type');
		}
		return $finder->getValueByPostInitiativeId($characteristic_id, $post_initiative_id);
	}

	/**
	 * Returns the object characteristic ID for a given company and characteristic combination. 
	 * @param integer $company_id
	 * @param integer $characteristic_id
	 * @return integer
	 */
	public static function getObjectCharacteristicIdByCompanyIdAndCharacteristicId($company_id, $characteristic_id)
	{
		$finder = new app_mapper_ObjectCharacteristicHelperMapper();
		return $finder->getObjectCharacteristicIdByParentObjectIdAndCharacteristicId($company_id, 'app_domain_Company', $characteristic_id);
	}

	/**
	 * Returns the object characteristic ID for a given post and characteristic combination. 
	 * @param integer $post_id
	 * @param integer $characteristic_id
	 * @return integer
	 */
	public static function getObjectCharacteristicIdByPostIdAndCharacteristicId($post_id, $characteristic_id)
	{
		$finder = new app_mapper_ObjectCharacteristicHelperMapper();
		return $finder->getObjectCharacteristicIdByParentObjectIdAndCharacteristicId($post_id, 'app_domain_Post', $characteristic_id);
	}

	/**
	 * Returns the object characteristic ID for a given post initiative and characteristic combination. 
	 * @param integer $post_initiative_id
	 * @param integer $characteristic_id
	 * @return integer
	 */
	public static function getObjectCharacteristicIdByPostInitiativeIdAndCharacteristicId($post_initiative_id, $characteristic_id)
	{
		$finder = new app_mapper_ObjectCharacteristicHelperMapper();
		return $finder->getObjectCharacteristicIdByParentObjectIdAndCharacteristicId($post_initiative_id, 'app_domain_PostInitiative', $characteristic_id);
	}

}

?>