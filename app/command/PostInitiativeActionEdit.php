<?php

/**
 * Defines the app_command_PostInitiativeActionEdit class. 
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
class app_command_PostInitiativeActionEdit extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		if ($task == 'save')
		{
			if ($errors = $this->getFormErrors($request))
			{
				$this->init($request, $errors);

				// Ensure validation errors are passed in to the view
				$this->handleValidationErrors($request, $errors);
				
				// Ensure field values are made sticky
				$this->handleStickyFields($request);
				
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
				header('Location: index.php?cmd=PostInitiativeActions&post_initiative_id=' . $request->getProperty('post_initiative_id') . '&referrer_type=' . $request->getProperty('referrer_type') . '&type_id=' . $request->getProperty('app_domain_Action_type_id'));
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
		if (app_base_ApplicationRegistry::getItem('action_subject_required')) {
			$errors['app_domain_Action_subject'] = app_domain_Action::validate($request->getProperty('app_domain_Action_subject'), app_domain_Action::getFieldSpec('subject_mandatory'));
		} else {
			$errors['app_domain_Action_subject'] = app_domain_Action::validate($request->getProperty('app_domain_Action_subject'), app_domain_Action::getFieldSpec('subject'));
		}
		
		if (app_base_ApplicationRegistry::getItem('action_notes_required')) {
			$errors['app_domain_Action_notes']   = app_domain_Action::validate($request->getProperty('app_domain_Action_notes'), app_domain_Action::getFieldSpec('notes_mandatory'));
		} else {
			$errors['app_domain_Action_notes']   = app_domain_Action::validate($request->getProperty('app_domain_Action_notes'), app_domain_Action::getFieldSpec('notes'));
		}
		
		$due_date = Utils::DateFormat($request->getProperty('app_domain_Action_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('due_date_time');
		
		$actionDueDateRequired = true;
		$actionDueDateRequired = app_base_ApplicationRegistry::getItem('action_reminder_date_required');
		if ($actionDueDateRequired) {
			$errors['app_domain_Action_due_date'] = app_domain_Action::validate($due_date, app_domain_Action::getFieldSpec('due_date_mandatory'));
		} else {
			$errors['app_domain_Action_due_date'] = app_domain_Action::validate($due_date, app_domain_Action::getFieldSpec('due_date'));
		}
	
		$actionReminderDateRequired = true;
		$actionReminderDateRequired = app_base_ApplicationRegistry::getItem('action_reminder_date_required');
		if (!$request->propertyExists('chk_display_reminder') && $actionReminderDateRequired) {
			$reminder_date = null;
			$errors['app_domain_Action_reminder_date'] = app_domain_Action::validate($reminder_date, app_domain_Action::getFieldSpec('reminder_date_mandatory'));
		} elseif ($request->propertyExists('chk_display_reminder') && $actionReminderDateRequired) {
			$reminder_date = Utils::DateFormat($request->getProperty('app_domain_Action_reminder_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('reminder_date_time');
			$errors['app_domain_Action_reminder_date'] = app_domain_Action::validate($reminder_date, app_domain_Action::getFieldSpec('reminder_date_mandatory'));
		} elseif ($request->propertyExists('chk_display_reminder') && !$actionReminderDateRequired) {
			$reminder_date = Utils::DateFormat($request->getProperty('app_domain_Action_reminder_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('reminder_date_time');
			$errors['app_domain_Action_reminder_date'] = app_domain_Action::validate($reminder_date, app_domain_Action::getFieldSpec('reminder_date'));
		}

		// meeting action specific checks
		$errors['app_domain_Action_type_id'] = app_domain_Action::validate($request->getProperty('app_domain_Action_type_id'), app_domain_Action::getFieldSpec('type_id'));
		if (app_base_ApplicationRegistry::getItem('action_communication_type_required')) {
			$errors['app_domain_Action_communication_type_id']   = app_domain_Action::validate($request->getProperty('app_domain_Action_communication_type_id'), app_domain_Action::getFieldSpec('communication_type_id_mandatory'));
		} else {
			$errors['app_domain_Action_communication_type_id']   = app_domain_Action::validate($request->getProperty('app_domain_Action_communication_type_id'), app_domain_Action::getFieldSpec('communication_type_id'));
		}
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
		$id = $request->getProperty('action_id');
		
		if ($id)
		{
			if ($request->getProperty('referrer_type') == 'communication')
			{
				// check if this action id is in the session array for post_initiative_actions
				// add this action to the session
				if (isset($_SESSION['auth_session']['communication']))
				{
					if (isset($_SESSION['auth_session']['communication']['post_initiative_actions']))
					{
						$action = $_SESSION['auth_session']['communication']['post_initiative_actions'][$id];	
					}
				}
			}
		
			if (!is_object($action))
			{
				$action = app_domain_Action::find($id);	
			}
		
		}
		else
		{
			$action = new app_domain_Action();
		}
		
		$action->setSubject($request->getProperty('app_domain_Action_subject'));
		$action->setNotes($request->getProperty('app_domain_Action_notes'));
		
		if ($request->getProperty('app_domain_Action_meeting_id') != '')
		{
			$action->setMeetingId($request->getProperty('app_domain_Action_meeting_id'));
		}
		
		if ($request->getProperty('post_initiative_id') != '')
		{
			$action->setPostInitiativeId($request->getProperty('post_initiative_id'));
		}
		
		$action->setTypeId($request->getProperty('app_domain_Action_type_id'));
		$action->setCommunicationTypeId($request->getProperty('app_domain_Action_communication_type_id'));
		
//		print_r ($request->getProperty('app_domain_Action_resources'));
//		exit();
		$action->setResourceIds($request->getProperty('app_domain_Action_resources'));
		
		
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
		
		if ($request->getProperty('referrer_type') == 'communication')
		{
			// add this action to the session
			if (isset($_SESSION['auth_session']['communication']))
			{
				if (!isset($_SESSION['auth_session']['communication']['post_initiative_actions']))
				{
					$_SESSION['auth_session']['communication']['post_initiative_actions'] = array();
					$_SESSION['auth_session']['communication']['post_initiative_actions'][$action->getId()] = $action;
				}
				else
				{
					$added = false;
					foreach ($_SESSION['auth_session']['communication']['post_initiative_actions'] as $session_action)
					{
						if ($session_action->getId() == $action->getId())
						{
							$_SESSION['auth_session']['communication']['post_initiative_actions'][$action->getId()] = $action;
							$added = true;
						}
						
						if (!$added)
						{
							$_SESSION['auth_session']['communication']['post_initiative_actions'][$action->getId()] = $action;
						}
					}	
									
				}
				
			}
		}	
		else
		{	
			$action->commit();
		}

		// set the request properties 'id' and 'name' so we can redirect to this new action
		$request->setProperty('action_id', $action->getId());

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
		
		$sticky_fields = array('action_id',
		                       'app_domain_Action_subject',
		                       'app_domain_Action_notes',
		                       'app_domain_Action_due_date',
		                       'app_domain_Action_reminder_date',
		                       'post_initiative_id',
							   'app_domain_Action_type_id', 
							   'app_domain_Action_communication_type_id',
							   'app_domain_Action_resources',
							   'app_domain_Action_actioned_by_client');
		                       
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request, $errors=null)
	{
		// Pass thru' fields
		$request->setProperty('referrer_type', $request->getProperty('referrer_type'));
		$request->setProperty('post_initiative_id', $request->getProperty('post_initiative_id'));

		$post_initiative = app_domain_PostInitiative::find($request->getProperty('post_initiative_id'));
		$request->setObject('post', $post_initiative->getPost());
		
		$initiative_name = $post_initiative->getInitiative()->getClientName() . ': ' . $post_initiative->getInitiative()->getName(); 
		$request->setProperty('initiative_name', $initiative_name);		
		
		// Set referrer
		$request->setObject('referrer', $request->getProperty('referrer'));
		
		// Get supplied ID if relevant
		$action_id = $request->getProperty('action_id');
		
		if (!$errors)
		{
			if ($action_id)
			{
				
				// if we have accessed this screen via the communication screen then need to look in the 
				// session post_initiative_actions  
				if ($request->getProperty('referrer_type') == 'communication')
				{
					// check if this action id is in the session array for post_initiative_actions
					// add this action to the session
					if (isset($_SESSION['auth_session']['communication']))
					{
						if (isset($_SESSION['auth_session']['communication']['post_initiative_actions']))
						{
							$action = $_SESSION['auth_session']['communication']['post_initiative_actions'][$action_id];	
						}
					}
				}

				if (!is_object($action))
				{
					$action = app_domain_Action::find($action_id);	
				}
								
				$fields = array();
				// standard action fields
				$fields['action_id']		          = $action->getId();
				$fields['app_domain_Action_subject']  = $action->getSubject();
				$fields['app_domain_Action_notes']    = $action->getNotes();
				$fields['app_domain_Action_due_date'] = $action->getDueDate();
				
				if ($action->getReminderDate())
				{
					$fields['app_domain_Action_reminder_date'] = $action->getReminderDate();
				}
				$fields['app_domain_Action_is_completed'] = $action->isCompleted();
				
				// post initiative related fields
				$fields['post_initiative_id'] = $action->getPostInitiativeId();
				$fields['app_domain_Action_type_id']       		= $action->getTypeId();
				$fields['app_domain_Action_communication_type_id'] = $action->getCommunicationTypeId();
				$fields['app_domain_Action_actioned_by_client'] = $action->getActionedByClient();
//				$fields['app_domain_Action_resources'] 			= $action->getResourceIds();
				
//				print_r($action->getResourceIds());
				$output = array();
				foreach ($action->getResourceIds() as $resource)
				{
					$output[] = $resource['resource_id'];
				}
//				echo '<br />';
//				print_r($output);
				$fields['app_domain_Action_resources'] 			= $output;
				$request->setObject('fields', $fields);
				
			}
			else
			{
//				throw new exception('No action id supplied');
			// Preset a supplied date and reformat if neccessary
			$date = date('Y-m-d H:i:s');
			$fields = array();
			$fields['app_domain_Action_due_date'] = $date;
			$fields['post_initiative_id'] = $request->getProperty('post_initiative_id');
			$fields['app_domain_Action_type_id']       		= $request->getProperty('type_id');
			$request->setObject('fields', $fields);
			}		
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
	}

}

?>