<?php

/**
 * Defines the app_command_MeetingActionCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */


require_once('app/domain/Action.php');
require_once('app/mapper/ActionMapper.php');
require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_MeetingActionCreate extends app_command_ManipulationCommand
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
//				$request->addFeedback('Save Successful');
//				$request->setProperty('success', true);
//				return self::statuses('CMD_OK');

				header('Location: index.php?cmd=MeetingActionSaved');
				exit;
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
		// general action checks
//		$errors['app_domain_Action_subject'] = app_domain_Action::validate($request->getProperty('app_domain_Action_subject'), app_domain_Action::getFieldSpec('subject'));
		$errors['app_domain_Action_notes']   = app_domain_Action::validate($request->getProperty('app_domain_Action_notes'), app_domain_Action::getFieldSpec('notes'));
		$errors['app_domain_Action_due_date'] = app_domain_Action::validate($request->getProperty('app_domain_Action_due_date'), app_domain_Action::getFieldSpec('due_date'));
		
		if ($request->propertyExists('chk_display_reminder'))
		{
			$errors['app_domain_Action_reminder_date'] = app_domain_Action::validate($request->getProperty('app_domain_Action_reminder_date'), app_domain_Action::getFieldSpec('reminder_date'));
		}

		// meeting action specific checks
		$errors['app_domain_Action_type_id'] = app_domain_Action::validate($request->getProperty('app_domain_Action_type_id'), app_domain_Action::getFieldSpec('type_id'));
		$errors['app_domain_Action_communication_type_id']   = app_domain_Action::validate($request->getProperty('app_domain_Action_communication_type_id'), app_domain_Action::getFieldSpec('communication_type_id'));
		$errors['app_domain_Action_resource_type_id'] = app_domain_Action::validate($request->getProperty('app_domain_Action_resource_type_id'), app_domain_Action::getFieldSpec('resource_type_id'));
		$errors['app_domain_Action_actioned_by_client'] = app_domain_Action::validate($request->getProperty('app_domain_Action_actioned_by_client'), app_domain_Action::getFieldSpec('actioned_by_client'));

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
		
//		$subject = app_domain_Action::findActionTypeById($request->getProperty('app_domain_Action_type_id'));
		$action->setSubject('Hello');
		$action->setNotes($request->getProperty('app_domain_Action_notes'));
		
		if ($request->getProperty('app_domain_Action_meeting_id') != '')
		{
			$action->setMeetingId($request->getProperty('app_domain_Action_meeting_id'));
		}
		
		if ($request->getProperty('app_domain_Action_post_initiative_id') != '')
		{
			$action->setPostInitiativeId($request->getProperty('app_domain_Action_post_initiative_id'));
		}
		
		$action->setTypeId($request->getProperty('app_domain_Action_type_id'));
		$action->setCommunicationTypeId($request->getProperty('app_domain_Action_communication_type_id'));
//		$action->setResourceTypeId($request->getProperty('app_domain_Action_communication_type_id'));
		
		$actioned_by_client = $request->getProperty('app_domain_Action_actioned_by_client');
		if (isset($actioned_by_client))
		{
			$actioned_by_client = true;
		}
		else
		{
			$actioned_by_client = false;
		}
		$action->setActionedByClient($actioned_by_client);
			
		$due_date = Utils::DateFormat($request->getProperty('app_domain_Action_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('due_date_time');
		$action->setDueDate($due_date);
		
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
//		print_r($user);
		$action->setUserId($user['id']);
		
		// If reminder checked
		if ($request->getProperty('chk_display_reminder'))
		{
			$reminder_date = Utils::DateFormat($request->getProperty('app_domain_Action_reminder_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('reminder_date_time');
			$action->setReminderDate($reminder_date);
		}

		
		
		if ($request->getProperty('referrer_type') == 'communication')
		{
			echo 'before adding to meeting_actions array';
			app_domain_objectWatcher::showObjectWatcher();
			
			// add this action to the session
//			if (isset($_SESSION['auth_session']['communication']))
//			{
//				if (!isset($_SESSION['auth_session']['communication']['meeting_actions']))
//				{
//					$_SESSION['auth_session']['communication']['meeting_actions'] = array();
//				}
//				$_SESSION['auth_session']['communication']['meeting_actions'][$action->getId()] = $action;
//				
//				
//			}
			
			return true;
		}	
//		else
//		{	
//			echo "in commit";
//			$action->commit();
//		}
//
//		echo 'after adding to meeting_actions array';
//		app_domain_objectWatcher::showObjectWatcher();
			
//		// Set date for redirection
//		$fields = array();
//		$fields['referrer'] = $request->getProperty('referrer');
//		$fields['app_domain_Action_due_date'] = Utils::DateFormat($request->getProperty('app_domain_Action_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD');
//		$request->setObject('fields', $fields);
//		$request->setObject('fields', $fields);
////		$request->setObject('app_domain_Action_due_date', $due_date);
//
//		// set the request properties 'id' and 'name' so we can redirect to this new action
//		$request->setProperty('id', $action->getId());

//		return true;
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
		// Company fields
		$sticky_fields = array('referrer',
		                       'app_domain_Action_id',
//		                       'app_domain_Action_subject',
							   'app_domain_Action_type_id', 
							   'app_domain_Action_communication_type_id',
							   'app_domain_Action_resource_type_id',
							   'app_domain_Action_actioned_by_client',
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
		// Pass thru' fields
		$request->setProperty('referrer_type', $request->getProperty('referrer_type'));
		$request->setProperty('app_domain_Action_post_initiative_id', $request->getProperty('post_initiative_id'));
		$request->setProperty('app_domain_Action_meeting_id', $request->getProperty('meeting_id'));
		
		// Get supplied ID if relevant
		$action_id = $request->getProperty('action_id');
		
		if ($action_id)
		{
			$action = app_domain_Action::find($action_id);
			$fields = array();
			$fields['app_domain_Action_id']       = $action->getId();
			$fields['app_domain_Action_subject']  = $action->getSubject();
			$fields['app_domain_Action_notes']    = $action->getNotes();
			$fields['app_domain_Action_due_date'] = Utils::DateFormat($action->getDueDate(), 'YYYY-MM-DD HH:MM:SS', 'DD/MM/YYYY');
			
			if ($action->getReminderDate())
			{
				$fields['app_domain_Action_reminder_date'] = Utils::DateFormat($action->getReminderDate(), 'YYYY-MM-DD HH:MM:SS', 'DD/MM/YYYY');
			}
			$request->setObject('fields', $fields);
		}
		else
		{
			// Preset a supplied date and reformat if neccessary
			$date = $request->getProperty('date');
			if (preg_match(REGEX_MYSQL_DATE, $date))
			{
				$date = Utils::DateFormat($date, 'YYYY-MM-DD', 'DD/MM/YYYY');
			}
			
			$fields = array();
			$fields['app_domain_Action_due_date'] = $date;
			$request->setObject('fields', $fields);
		}
		
		// Set type options
		if ($items = app_domain_Action::findActionTypesAll())
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('type_options', $options);
		} 
		
		// Set communication type options
		if ($items = app_domain_Action::findActionCommunicationTypesAll())
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('communication_type_options', $options);
		}
		
		// Set resource type options
		if ($items = app_domain_Action::findActionResourceTypesAll())
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('resource_type_options', $options);
		}
		
		// Set referrer
		$request->setObject('referrer', $request->getProperty('referrer'));
	}

}

?>