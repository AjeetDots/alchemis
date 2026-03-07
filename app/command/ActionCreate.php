<?php

/**
 * Defines the app_command_ActionCreate class. 
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
class app_command_ActionCreate extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
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
		$errors['app_domain_Action_subject'] = app_domain_Action::validate($request->getProperty('app_domain_Action_subject'), app_domain_Action::getFieldSpec('subject'));
		$errors['app_domain_Action_notes']   = app_domain_Action::validate($request->getProperty('app_domain_Action_notes'), app_domain_Action::getFieldSpec('notes'));
//		$errors['app_domain_Action_due_date'] = app_domain_Action::validate($request->getProperty('app_domain_Action_due_date'), app_domain_Action::getFieldSpec('due_date'));
		
		$due_date = Utils::DateFormat($request->getProperty('app_domain_Action_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('due_date_time');
		$errors['app_domain_Action_due_date'] = app_domain_Action::validate($due_date, app_domain_Action::getFieldSpec('due_date'));
		
		if ($request->propertyExists('chk_display_reminder'))
		{
			$reminder_date = Utils::DateFormat($request->getProperty('app_domain_Action_reminder_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('reminder_date_time');
			$errors['app_domain_Action_reminder_date'] = app_domain_Action::validate($reminder_date, app_domain_Action::getFieldSpec('reminder_date'));
		}
		
//		if ($request->propertyExists('chk_display_reminder'))
//		{
//			$errors['app_domain_Action_reminder_date'] = app_domain_Action::validate($request->getProperty('app_domain_Action_reminder_date'), app_domain_Action::getFieldSpec('reminder_date'));
//		}

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
		// Action
		$id = $request->getProperty('app_domain_Action_id');
		if ($id)
		{
			$action = app_domain_Action::find($id);
		}
		else
		{
			$action = new app_domain_Action();
		}
		$action->setSubject($request->getProperty('app_domain_Action_subject'));
		$action->setNotes($request->getProperty('app_domain_Action_notes'));
		
		$due_date = Utils::DateFormat($request->getProperty('app_domain_Action_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('due_date_time');
		$action->setDueDate($due_date);
		
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$action->setUserId($user['id']);
		
		// If reminder checked
		if ($request->getProperty('chk_display_reminder'))
		{
			$reminder_date = Utils::DateFormat($request->getProperty('app_domain_Action_reminder_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('reminder_date_time');
			$action->setReminderDate($reminder_date);
		}

		// If completed checked
		if ($request->getProperty('app_domain_Action_is_completed'))
		{
			$action->setCompletedDate(date('Y-m-d H:i:s'));
		}
		
		$action->commit();

		// Set date for redirection
		$fields = array();
		$fields['referrer']  = $request->getProperty('referrer');
		$fields['client_id'] = $request->getProperty('client_id');
		$fields['nbm_id']    = $request->getProperty('nbm_id');
		$fields['app_domain_Action_due_date'] = Utils::DateFormat($request->getProperty('app_domain_Action_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD');
		$request->setObject('fields', $fields);

		// set the request properties 'id' and 'name' so we can redirect to this new company
		$request->setProperty('id', $action->getId());
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
		// set specific action field values
		$due_date = Utils::DateFormat($request->getProperty('app_domain_Action_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('due_date_time');
		$request->setProperty('app_domain_Action_due_date', $due_date);
		
		if ($request->propertyExists('chk_display_reminder'))
		{
			$reminder_date = Utils::DateFormat($request->getProperty('app_domain_Action_reminder_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('reminder_date_time');
			$request->setProperty('app_domain_Action_reminder_date', $reminder_date);
		}
		$sticky_fields = array('referrer',
		                       'app_domain_Action_id',
		                       'app_domain_Action_subject',
		                       'app_domain_Action_notes',
		                       'app_domain_Action_due_date',
		                       'app_domain_Action_reminder_date');
		
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		// Get supplied ID if relevant
		$action_id = $request->getProperty('action_id');
		
		if ($action_id)
		{
			$action = app_domain_Action::find($action_id);
			$fields = array();
			$fields['app_domain_Action_id']       = $action->getId();
			$fields['app_domain_Action_subject']  = $action->getSubject();
			$fields['app_domain_Action_notes']    = $action->getNotes();
			
			$fields['app_domain_Action_due_date'] = $action->getDueDate();
				
				if ($action->getReminderDate())
				{
					$fields['app_domain_Action_reminder_date'] = $action->getReminderDate();
				}
				
			$fields['app_domain_Action_is_completed'] = $action->isCompleted();

			$request->setObject('fields', $fields);
		}
		else
		{
			// Preset a supplied date and reformat if neccessary
			$date = $request->getProperty('date');
			if (preg_match(REGEX_MYSQL_DATE, $date))
			{
				$date = date($date, 'Y-m-d H:i:s');
			}
			$fields = array();
			$fields['app_domain_Action_due_date'] = $date;
			$request->setObject('fields', $fields);
		}
		
		// Set referrer
		$request->setObject('referrer', $request->getProperty('referrer'));
	}

}

?>