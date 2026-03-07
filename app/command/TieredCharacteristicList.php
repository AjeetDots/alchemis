<?php

/**
 * Defines the app_command_TieredCharacteristicList class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/TieredCharacteristic.php');

/**
 * @package Alchemis
 */
class app_command_TieredCharacteristicList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$collection = app_domain_TieredCharacteristic::findAll();
		$request->setObject('characteristics', $collection);
		
		// Parent options
		$parents = app_domain_TieredCharacteristic::findRootTieredCharacteristicsForDropdown();
		$request->setObject('parents', $parents);
		
		// Category options
		$categories = app_domain_TieredCharacteristic::lookupCategoriesForDropdown();
		$request->setObject('categories', $categories);
		
		return self::statuses('CMD_OK');
	}
}

?>