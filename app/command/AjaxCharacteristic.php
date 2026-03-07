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
		error_reporting (E_ALL & ~E_NOTICE);
		
		$debug = false;
		if ($debug) echo "<pre>";
		if ($debug) print_r($this->request);
		if ($debug) echo "</pre>";
		
		// Instantiate the object
		$id = $this->request->item_id;
			
		switch ($this->request->cmd_action)
		{
			case 'add_characteristic':
				$characteristic = new app_domain_Characteristic();
				$characteristic->setName($this->request->name);
				$characteristic->setDescription($this->request->description);
				$characteristic->setType($this->request->type);
				$characteristic->setAttributes((bool)$this->request->attributes);
				$characteristic->setOptions((bool)$this->request->options);
				$characteristic->setMultipleSelect((bool)$this->request->multiple_select);
				$characteristic->setDataType($this->request->data_type);
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