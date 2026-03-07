<?php

/**
 * Defines the app_command_ObjectTieredCharacteristics class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/TieredCharacteristic.php');

/**
 * @package Alchemis
 */
class app_command_ObjectTieredCharacteristics extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get type of object the tiered characteristic is associated with
		//  - this should always be app_domain_Company
		$parent_object_type = $request->getProperty('parent_object_type');
		if (!$parent_object_type)
		{
			$parent_object_type = 'app_domain_Company';
		}
		$request->setObject('parent_object_type', $parent_object_type);
		
		// Get the ID of the object the tiered characteristics is associated with
		// - this should always be the company ID
		$parent_object_id = $request->getProperty('parent_object_id');
		$request->setObject('parent_object_id', $parent_object_id);
		
		// Root (top level, no parents) tiered characteristics
		$root_tiered_characteristics = app_domain_TieredCharacteristic::findRootTieredCharacteristics();
		$request->setObject('root_tiered_characteristics', $root_tiered_characteristics);
		
		// Parent options
		$parents = app_domain_TieredCharacteristic::findAllForDropdown();
		$request->setObject('parents', $parents);
		
		// Get the tiered characteristics associated with this object (company).
		$object_tiered_characteristics = app_domain_TieredCharacteristic::findByCompanyId($parent_object_id);
		$object_tiered_characteristics = $this->findCharacteristicChildren($object_tiered_characteristics);
		$request->setObject('object_tiered_characteristics', $object_tiered_characteristics);
		
		// get parent company tiered characterstics
		$company = app_model_Company::find($parent_object_id);
		$request->setObject('company', $company);
		if($company->parent_company_id){
			$parent_object_tiered_characteristics = app_domain_TieredCharacteristic::findByParentCompanyId($company->parent_company_id);
			$parent_object_tiered_characteristics = $this->findCharacteristicChildren($parent_object_tiered_characteristics);
			$request->setObject('parent_object_tiered_characteristics', $parent_object_tiered_characteristics);
		}
		
		// Get those which are available
		$available = app_domain_TieredCharacteristic::selectAvailableByCompanyIdForDropdown($parent_object_id);

		$request->setObject('available', $available);
		
		return self::statuses('CMD_OK');
	}
	
	public function findCharacteristicChildren($object_tiered_characteristics)
	{
		// Mark those in this list that have sub-categories also associated with the object (company)
		$parent_ids = array();
		foreach ($object_tiered_characteristics as $obj)
		{
			if ($obj['parent_id'])
			{
				$parent_ids[] = $obj['parent_id'];
			} 
		}
		
		foreach ($object_tiered_characteristics as &$obj)
		{
			$obj['has_children'] = in_array($obj['tiered_characteristic_id'], $parent_ids);
		}
		
		return $object_tiered_characteristics;
	}
}

?>