<?php

/**
 * Defines the app_command_CharacteristicElements class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Characteristic.php');

/**
 * @package Alchemis
 */
class app_command_CharacteristicElements extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get request parameters
//		$parent_object_type = $request->getProperty('parent_object_type');
//				
//		$parent_object_id = $request->getProperty('parent_object_id');
//		$request->setObject('parent_object_id', $parent_object_id);
//		
//		$category_id = $request->getProperty('category_id');
//		$request->setObject('category_id', $category_id);
		
		// Get elements
		$elements = app_domain_Characteristic::getElements($request->getProperty('characteristic_id'));
		$request->setObject('elements', $elements);
		
		
//		$characteristic = new app_domain_Characteristic($request->getProperty('characteristic_id'));
		$characteristic = app_domain_Characteristic::find($request->getProperty('characteristic_id'));
		$request->setObject('characteristic', $characteristic);
		
//		// redefine parent_object_type to useful English
//		switch ($parent_object_type)
//		{
//			case 'app_domain_Company':
//				$parent_object_type = 'Company';
//				break;
//			case 'app_domain_Post':
//				$parent_object_type = 'Post';
//				break;
//			case 'app_domain_PostInitiative':
//				$parent_object_type = 'PostInitiative';
//				break;
//			default:
//				throw new Exception('Unspecified parent_object_type');
//				break;
//		}
//		
//		$request->setObject('parent_object_type', $parent_object_type);
//		
//		$request->setProperty('category', app_domain_Tag::lookupCategoryById($category_id));
		
		
//		echo "<pre>";
//		print_r($client_initiatives);
//		echo "</pre>";
				
		return self::statuses('CMD_OK');
	}
}

?>