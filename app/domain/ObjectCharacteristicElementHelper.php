<?php

/**
 * Defines the app_domain_ObjectCharacteristicElementHelper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ObjectCharacteristicElementBooleanMapper.php');
require_once('app/mapper/ObjectCharacteristicElementDateMapper.php');
require_once('app/mapper/ObjectCharacteristicElementTextMapper.php');

/**
 * @package Alchemis
 */
class app_domain_ObjectCharacteristicElementHelper
{
	/**
	 * Create a concrete instance.
	 * @param string $type in the set{boolean, date, text}
	 * @param integer $id
	 * @return app_domain_ObjectCharacteristicElement
	 */
	public static function factory($type = null, $id = null)
	{
		switch ($type) {
			case 'boolean':
				return new app_domain_ObjectCharacteristicElementBoolean($id);

			case 'date':
				return new app_domain_ObjectCharacteristicElementDate($id);

			case 'text':
				return new app_domain_ObjectCharacteristicElementText($id);

			default:
				throw new Exception('Invalid type: ' . $type);
				//				return new app_domain_ObjectCharacteristicElement($id);
				break;
		}
	}

	/**
	 * Gets the all element records by object characteristic id.
	 * @param integer $object_characteristic_id
	 * @return array
	 */
	public static function getAllRecordsByObjectCharacteristicId($object_characteristic_id)
	{
		$finder = new app_mapper_ObjectCharacteristicElementBooleanMapper();
		$result['boolean'] = $finder->getAllRecordsByObjectCharacteristicId($object_characteristic_id);

		$finder = new app_mapper_ObjectCharacteristicElementDateMapper();
		$result['date'] = $finder->getAllRecordsByObjectCharacteristicId($object_characteristic_id);

		$finder = new app_mapper_ObjectCharacteristicElementTextMapper();
		$result['text'] = $finder->getAllRecordsByObjectCharacteristicId($object_characteristic_id);

		return $result;
	}



	/**
	 * Gets the value of a characteristic element for a company.
	 * @param integer $characteristic_id
	 * @param string $characteristic_type
	 * @param integer $post_initiative_id
	 * @return array
	 */
	public static function getValueByCompanyId($element_id, $element_type, $company_id)
	{
		switch ($element_type) {
			case 'boolean':
				$finder = new app_mapper_ObjectCharacteristicElementBooleanMapper();
				break;

			case 'date':
				$finder = new app_mapper_ObjectCharacteristicElementDateMapper();
				break;

			case 'text':
				$finder = new app_mapper_ObjectCharacteristicElementTextMapper();
				break;

			default:
				throw new Exception('Invalid characteristic element type');
		}
		return $finder->getValueByCompanyId($element_id, $company_id);
	}

	public static function getRecordByCompanyId($element_id, $element_type, $company_id)
	{
		$element_type = 'text';
		// print_r($element_id);
		// print_r($element_type);
		switch ($element_type) {
			case 'boolean':
				$finder = new app_mapper_ObjectCharacteristicElementBooleanMapper();
				break;

			case 'date':
				$finder = new app_mapper_ObjectCharacteristicElementDateMapper();
				break;

			case 'text':
				$finder = new app_mapper_ObjectCharacteristicElementTextMapper();
				break;

			default:
				throw new Exception('Invalid characteristic element type');
		}
		// echo '<pre>';
		// print_r($finder);
		return $finder->getRecordByCompanyId($element_id, $company_id);
	}

	public static function getValueByPostId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getValueByPostId($id);
	}

	public static function getValueByPostInitiativeId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getValueByPostInitiativeId($id);
	}
}
