<?php

/**
 * Defines the app_command_MessageCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
//require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Message.php');
//require_once('app/mapper/SiteMapper.php');
//require_once('app/domain/Site.php');
//require_once('include/Utils/Utils.class.php');
//require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_MessageCreate extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
//		echo '<pre>';
//		print_r($request->getProperties());
//		echo '</pre>';

		$task = $request->getProperty('task');
		if ($task == 'cancel')
		{
			echo 'cancel';
			// ???
		}
		elseif ($task == 'save')
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
			// Preset a supplied date
			$date = $request->getProperty('date');
			$request->setObject('date', $date);
			
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
		$errors['app_domain_Message_message'] = app_domain_Message::validate($request->getProperty('app_domain_Message_message'), app_domain_Message::getFieldSpec('message'));
		foreach ($errors as $key => $error)
		{
			if (empty($error))
			{
				unset($errors[$key]);
			}
		}
//		echo '<pre>';
//		print_r($errors);
//		echo '</pre>';
		return $errors;
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		
		// Message
		if ($message_id = $request->getProperty('message_id'))
		{
			$message = app_domain_Message::find($message_id);
		}
		else
		{
			$message = new app_domain_Message();
		}
		$message->setMessage($request->getProperty('app_domain_Message_message'));
		$message->setPublished($request->propertyExists('app_domain_Message_published'));
		$message->setTimestamp($request->getProperty('app_domain_Message_timestamp'));
//		$message->setTimestamp(date('Y-m-d H:i:s'));
		$message->setUserId($user['id']);
		$message->commit();
		
		$request->setProperty('id', $message->getId());
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
		$sticky_fields = array('app_domain_Message_message', 'app_domain_Message_published');
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		// Load existing object if ID passed
		if ($message_id = $request->getProperty('message_id'))
		{
			$message = app_domain_Message::find($message_id);
			
//			$sticky_fields = array('app_domain_Message_message', 'app_domain_Message_published');
//			$request->setObject('fields', $fields);

			$fields = array();
			$fields['app_domain_Message_id']        = $message->getId();
			$fields['app_domain_Message_message']   = $message->getMessage();
			$fields['app_domain_Message_timestamp'] = $message->getTimestamp();
			$fields['app_domain_Message_published'] = $message->isPublished();
//			echo '<pre>';
//			print_r($fields);
//			echo '</pre>';
			$request->setObject('fields', $fields);
		}
		
		
		
		
//		// county_id
//		if ($items = app_domain_Site::getCountiesAll())
//		{
//			$options = array();
//			$options[0] = '-- select if required--';
//			foreach ($items as $item)
//			{
//				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
//			}
//			$request->setObject('site_counties_options', $options);
//		}
//		
//		// country_id
//		if ($items = app_domain_Site::getCountriesAll())
//		{
//			$options = array();
//			foreach ($items as $item)
//			{
//				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
//			}
//			$request->setObject('site_countries_options', $options);
//		}
	}

}

?>