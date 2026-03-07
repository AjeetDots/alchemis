<?php

/**
 * Defines the app_command_TeamCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_TeamCreate extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
//		echo '<pre>';
//		print_r($request->getProperties());
//		echo '</pre>';
		
		$task = $request->getProperty('task');
		if ($task == 'save')
		{
			if ($errors = $this->getFormErrors($request))
			{
				$this->init($request);

				// Ensure validation errors are passed in to the view
				$this->handleValidationErrors($request, $errors);
				
				// Ensure field values are made sticky
				$this->handleStickyFields($request);
				
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
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
		$errors = array();
		$errors['app_domain_Team_name'] = app_domain_Team::validate($request->getProperty('app_domain_Team_name'), app_domain_Team::getFieldSpec('name'));
		foreach ($errors as $key => $error)
		{
			if (empty($error))
			{
				unset($errors[$key]);
			}
		}
		return $errors;
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		// Team
		$id = $request->getProperty('app_domain_Team_id');
		if ($id)
		{
			$team = app_domain_Team::find($id);
		}
		else
		{
			$team = new app_domain_Team();
		}
		
		$team->setName($request->getProperty('app_domain_Team_name'));
		$team->commit();
		
		// set the request properties 'id' and 'name' so we can redirect to this new company
		$request->setProperty('id', $team->getId());
		return true;
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
		$sticky_fields = array('app_domain_Team_id', 'app_domain_Team_name');
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
//		echo '<pre>';
//		print_r($request->getProperties());
//		echo '</pre>';
		// Get supplied ID if relevant
		$team_id = $request->getProperty('team_id');
		
		if ($team_id)
		{
			$team = app_domain_Team::find($team_id);
			$fields = array();
			$fields['app_domain_Team_id']   = $team->getId();
			$fields['app_domain_Team_name'] = $team->getName();
			$request->setObject('fields', $fields);
		}
		
		// Set referrer
		$request->setObject('referrer', $request->getProperty('referrer'));
	}

}

?>