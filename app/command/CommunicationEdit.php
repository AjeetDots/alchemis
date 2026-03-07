<?php

/**
 * Defines the app_command_CommunicationEdit class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_CommunicationEdit extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			if ($errors = $this->getFormErrors($request))
			{
				$this->init($request);
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
				$this->init($request);
				$request->addFeedback('Save Successful');
				$request->setProperty('success', true);
				return self::statuses('CMD_OK');
			}
			else
			{
				return self::statuses('CMD_ERROR');
			}
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}

	/**
	 * Checks form fields against validation rules.
	 * @param app_controller_Request $request the object from which the form 
	 *        values can be accessed
	 * @return array an array of errors, empty if none found
	 */
	protected function getFormErrors(app_controller_Request $request)
	{
	}

	/**
	 * Takes a list of the fields being used and re-assigns any values entered 
	 * to make sticky.
	 * @param app_controller_Request $request 
	 * @param array $field_spec associative array of fields in use, where the 
	 *        key is the field name
	 */
	protected function handleStickyFields(app_controller_Request $request)
	{
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		// change communication status id
		$communication = app_domain_Communication::find($request->getProperty('id'));
		$communication->setStatusId($request->getProperty('status_id'));
		$communication->commit();
		
		// change post initiative status id
		$post_initiative = app_domain_PostInitiative::find($communication->getPostInitiativeId());
		$post_initiative->setStatusId($request->getProperty('status_id'));
		$post_initiative->commit();
		
		return true;
	}

	protected function init(app_controller_Request $request)
	{
		// Get post initiative details
		$communication = app_domain_Communication::find($request->getProperty('id'));
		$request->setObject('communication', $communication);
		
		$request->setProperty('parent_tab', $request->getProperty('parent_tab'));
		
		$post_initiative = app_domain_PostInitiative::find($communication->getPostInitiativeId());
		$request->setObject('post_initiative', $post_initiative);
		
		$status_id = $communication->getStatusId();
		$request->setProperty('status_id', $status_id);
		
		// get status options list
		if ($status_id < 12)
		{
			if ($items = app_domain_Communication::findStatusAll());
			{
				$options = array();
				foreach ($items as $item)
				{
					if ($item['id'] < 12)
					{
						$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
					}
				}
				
				$request->setObject('status_options', $options);
			}
		}
		else
		{
			$request->setObject('status_options', null);
		}
		
	}

	
}

?>