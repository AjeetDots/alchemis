<?php

/**
 * Defines the app_command_ActionDelete class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Company.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ActionDelete extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'save')
		{
			if ($errors = $this->getFormErrors($request))
			{
				// Ensure validation errors are passed in to the view
				$this->handleValidationErrors($request, $errors);
				
				// Ensure field values are made sticky
				$this->handleStickyFields($request);
				
				$this->init($request);
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
				$request->addFeedback('Deletion Successful');
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
		$is_deleted = $request->getProperty('app_domain_Action_deleted');
		if (!$is_deleted)
		{
			$errors['app_domain_Action_deleted'] = new app_base_ValidationError("Action cannot be deleted until the 'Confirm Deletion' checkbox is ticked.");
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
		if ($request->getProperty('app_domain_Action_deleted'))
		{
			$action = app_domain_Action::find($request->getProperty('action_id'));
//			$date = 
			$action->markDeleted();
			$action->commit();
		}
		
		$request->setObject('app_domain_Action_due_date', $action->getDueDate());
		$action = app_domain_Action::find($request->getProperty('action_id'));
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
		$sticky_fields = array('action_id');
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		$action = app_domain_Action::find($request->getProperty('action_id'));
		$request->setObject('action', $action);
		
		// Set referrer
//		echo $action->getDueDate();
//		$date = strftime('%Y-%m-%d', $action->getDueDate());
		$date = substr($action->getDueDate(), 0, 10);
		$request->setObject('referrer', $request->getProperty('referrer') . '&date=' . $date);
		$request->setObject('referrer_date', $request->getProperty('referrer_date') . $date);
	}
	
}

?>