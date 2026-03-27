<?php

/**
 * Defines the app_command_AjaxCharacteristic class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Characteristic.php');
require_once('app/mapper/CharacteristicMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_TieredCharacteristic objects.
 * @package Alchemis
 */
class app_command_AjaxCharacteristic extends app_command_AjaxCommand
{
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);
		
		$debug = false;
		if ($debug) echo "<pre>";
		if ($debug) print_r($this->request);
		if ($debug) echo "</pre>";
		
		// Instantiate the object
		$id = isset($this->request->item_id) ? $this->request->item_id : null;
			
		switch (isset($this->request->cmd_action) ? $this->request->cmd_action : null)
		{
			case 'add_characteristic':
				if (!isset($this->request->name) || empty(trim($this->request->name))) {
					$this->request->success = false;
					$this->request->feedback = "Characteristic name is required";
					break;
				}
				$characteristic = new app_domain_Characteristic();
				$characteristic->setName(isset($this->request->name) ? $this->request->name : null);
				$characteristic->setDescription(isset($this->request->description) ? $this->request->description : null);
				$characteristic->setType(isset($this->request->type) ? $this->request->type : null);
				$characteristic->setAttributes(isset($this->request->attributes) ? (bool)$this->request->attributes : false);
				$characteristic->setOptions(isset($this->request->options) ? (bool)$this->request->options : false);
				$characteristic->setMultipleSelect(isset($this->request->multiple_select) ? (bool)$this->request->multiple_select : false);
				$characteristic->setDataType(isset($this->request->data_type) ? $this->request->data_type : null);
				$characteristic->commit();
				$this->request->line_html = $this->getCharacteristicListLine($characteristic);
				$this->request->success = true;
				break;

			case 'add_characteristic_text':
				
				// Create an instance of a text characteristic
				$obj = new app_domain_CharacteristicText($this->request->characteristic_id);
				$obj->setValue($this->request->value);
//				$obj->commit();
				
				$company = app_domain_Company::find($this->request->company_id);
//				$obj->setValue($company, $this->request->company_id);
				$company->addCharacteristic($obj);
				
				$this->request->success = true;
				break;

			default:
				// TODO
				//  - should throw/log an error of some sort?
				break;
		}
		
		$this->response->data[] = $this->request;
	}

	/**
	 * @param app_domain_Characteristic $characteristic
	 */
	protected function getCharacteristicListLine(app_domain_Characteristic $characteristic)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('characteristic', $characteristic);
		return $smarty->fetch('html_CharacteristicListLine.tpl');
	}

}

?>