<?php

/**
 * Defines the app_command_TieredCharacteristicEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Characteristic.php');
require_once('app/domain/CharacteristicElement.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_TieredCharacteristicEdit extends app_command_Command
{
	protected $debug = false;
	
	public function doExecute(app_controller_Request $request)
	{
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($request);
		if ($this->debug) echo "</pre>";

		$task = $request->getProperty('task');
		if ($task == 'save')
		{
			if (!$this->getErrors())
			{
				if ($this->debug) echo "<p>should now save them</p>";
				
				$obj = new app_domain_TieredCharacteristic($request->getProperty('id'));
				$obj->setValue($request->getProperty('value'));
				$obj->setParentId($request->getProperty('parent_id'));
				$obj->setCategoryId($request->getProperty('category_id'));
				$obj->commit();

				$this->init($request);
				$request->addFeedback('Save Successful');
				return self::statuses('CMD_OK');
			}
			else
			{
				// there were errors so make sticky
				$characteristic_id = $request->getProperty('id');
				$characteristic = app_domain_Characteristic::find($characteristic_id);
				$request->setObject('characteristic', $characteristic);
			}
		}
		else
		{
			$this->init($request);
		}
	}


	function init(app_controller_Request $request)
	{
		$id = $request->getProperty('id');
		$characteristic = app_domain_TieredCharacteristic::find($id);
		$request->setObject('characteristic', $characteristic);
		
		// Parent options
		$parents = app_domain_TieredCharacteristic::findRootTieredCharacteristicsForDropdown();
		$request->setObject('parents', $parents);
		
		// Category options
		$categories = app_domain_TieredCharacteristic::lookupCategoriesForDropdown();
		$request->setObject('categories', $categories);
	}

	/**
	 * Check the posted elements for errors.
	 * @return array
	 */
	function getErrors()
	{
		$errors = array();
		return $errors;
	}

}

?>