<?php

/**
 * Defines the app_command_EventCreate class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
require_once('app/base/ValidationError.php');
require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_EventCreate extends app_command_ManipulationCommand
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
		$errors['app_domain_Event_subject']  = app_domain_Event::validate($request->getProperty('app_domain_Event_subject'), app_domain_Event::getFieldSpec('subject'));
		$errors['app_domain_Event_notes']    = app_domain_Event::validate($request->getProperty('app_domain_Event_notes'), app_domain_Event::getFieldSpec('notes'));
		$errors['app_domain_Event_due_date'] = app_domain_Event::validate($request->getProperty('app_domain_Event_due_date'), app_domain_Event::getFieldSpec('date'));


		if ($request->getProperty('app_domain_Event_day_part') <= 0 || $request->getProperty('app_domain_Event_day_part') > 1) {
		  $errors['app_domain_Event_day_part'] = new app_base_ValidationError('Day Part value must be greater than 0 and less than 1');
		}

		if ($request->getProperty('chk_display_reminder'))
		{
			$errors['app_domain_Event_reminder_date'] = app_domain_Event::validate($request->getProperty('app_domain_Event_reminder_date'), app_domain_Event::getFieldSpec('reminder_date'));
		}

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
		$id = $request->getProperty('app_domain_Event_id');
		if ($id)
		{
			$event = app_domain_Event::find($id);
		}
		else
		{
			$event = new app_domain_Event();
		}
		$event->setSubject($request->getProperty('app_domain_Event_subject'));
		$event->setNotes($request->getProperty('app_domain_Event_notes'));

		$date = Utils::DateFormat($request->getProperty('app_domain_Event_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD');
		$event->setDate($date);

		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
//		print_r($user);
		$event->setUserId($user['id']);
		$event->setTypeId($request->getProperty('app_domain_Event_type_id'));
		$event->setDayPart($request->getProperty('app_domain_Event_day_part'));

		// If reminder checked
		if ($display_site = $request->getProperty('chk_display_reminder'))
		{
			$reminder_date = Utils::DateFormat($request->getProperty('app_domain_Event_reminder_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('reminder_date_time');
			$event->setReminderDate($reminder_date);
		}

		$event->commit();

		// Set date for redirection
		$fields = array();
		$fields['referrer'] = $request->getProperty('referrer');

		$fields['app_domain_Event_due_date'] = Utils::DateFormat($request->getProperty('app_domain_Event_due_date'), 'DD/MM/YYYY', 'YYYY-MM-DD');
//		$request->setObject('fields', $fields);
		$request->setObject('fields', $fields);
//		$request->setObject('app_domain_Event_due_date', $due_date);

		// set the request properties 'id' and 'name' so we can redirect to this new company
		$request->setProperty('id', $event->getId());
		$request->setProperty('user_id',$user['id']);
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
		$sticky_fields = array('referrer',
		                       'app_domain_Event_id',
		                       'app_domain_Event_subject',
		                       'app_domain_Event_notes',
		                       'app_domain_Event_due_date',
		                       'app_domain_Event_reminder_date',
		                       'app_domain_Event_type_id',
		                       'app_domain_Event_day_part');

//		// Site fields
//		if ($request->getProperty('chk_display_site'))
//		{
//			$sticky_fields = array_merge($sticky_fields, array(	'chk_display_site'));
//		}

		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request
	 */
	protected function init(app_controller_Request $request)
	{
		// Get supplied ID if relevant
		$event_id = $request->getProperty('event_id');

		if ($event_id)
		{
			$event = app_domain_Event::find($event_id);
			$fields = array();
			$fields['app_domain_Event_id']       = $event->getId();
			$fields['app_domain_Event_subject']  = $event->getSubject();
			$fields['app_domain_Event_notes']    = $event->getNotes();
			$fields['app_domain_Event_due_date'] = Utils::DateFormat($event->getDate(), 'YYYY-MM-DD', 'DD/MM/YYYY');
			$fields['app_domain_Event_type_id']  = $event->getTypeId();
			$fields['app_domain_Event_day_part']  = $event->getDayPart();

			if ($event->getReminderDate())
			{
				$fields['app_domain_Event_reminder_date'] = Utils::DateFormat($event->getReminderDate(), 'YYYY-MM-DD', 'DD/MM/YYYY');
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
			$fields['app_domain_Event_due_date'] = $date;

			// preset the day_part value - assume a whole day
			$fields['app_domain_Event_day_part'] = 1;

			$request->setObject('fields', $fields);
		}

		// Set referrer
		$request->setObject('referrer', $request->getProperty('referrer'));

		$request->setObject('event_types', app_domain_Event::lookupTypesForDropdown());
	}

}

?>