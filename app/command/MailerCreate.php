<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/domain/Mailer.php');
require_once('app/mapper/MailerMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_MailerCreate extends app_command_ManipulationCommand
{
//	protected $post_id;
//	protected $initiative_id;
//	protected $post_initiative_id;
	protected $source_tab;
	
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
				// Ensure validation errors are passed in to the view
				$this->handleValidationErrors($request, $errors);
				
				// Ensure field values are made sticky
				$this->handleStickyFields($request);
				
				$this->init($request);
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($mailer = $this->processForm($request))
			{
				$request->addFeedback('Mailer added successfully');
				$request->setObject('new_mailer', $mailer);
				return self::statuses('CMD_OK');
//				header('Location: index.php?cmd=MailerSaved&id=' . $mailer_id . '&feedback=' . 'Mailer added successfully');
//				exit();
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

	/** Checks form fields against validation rules.
	 * @param app_controller_Request $request the object from which the form 
	 *        values can be accessed
	 * @return array an array of errors, empty if none found
	 */
	protected function getFormErrors(app_controller_Request $request)
	{
		$errors = array();
		$errors['app_domain_Mailer_client_initiative_id'] = app_domain_Mailer::validate($request->getProperty('app_domain_Mailer_client_initiative_id'), app_domain_Mailer::getFieldSpec('client_initiative_id'));
		$errors['app_domain_Mailer_name'] = app_domain_Mailer::validate($request->getProperty('app_domain_Mailer_name'), app_domain_Mailer::getFieldSpec('name'));
		$errors['app_domain_Mailer_description'] = app_domain_Mailer::validate($request->getProperty('app_domain_Mailer_description'), app_domain_Mailer::getFieldSpec('description'));
		$errors['app_domain_Mailer_type_id'] = app_domain_Mailer::validate($request->getProperty('app_domain_Mailer_type_id'), app_domain_Mailer::getFieldSpec('type_id'));
		$errors['app_domain_Mailer_response_group_id'] = app_domain_Mailer::validate($request->getProperty('app_domain_Mailer_response_group_id'), app_domain_Mailer::getFieldSpec('response_group_id'));
		
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
		// Mailer
		$mailer = new app_domain_Mailer();
		$mailer->setClientInitiativeId($request->getProperty('app_domain_Mailer_client_initiative_id'));
		$mailer->setName($request->getProperty('app_domain_Mailer_name'));
		$mailer->setDescription($request->getProperty('app_domain_Mailer_description'));
		$mailer->setTypeId($request->getProperty('app_domain_Mailer_type_id'));
		$mailer->setResponseGroupId($request->getProperty('app_domain_Mailer_response_group_id'));
		$mailer->setCreatedAt(date('Y-m-d H:i:s'));
		$mailer->setCreatedBy($_SESSION['auth_session']['user']['id']);
		$mailer->commit();
		return $mailer;
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
		$sticky_fields = array('app_domain_Mailer_client_initiative_id', 'app_domain_Mailer_name', 'app_domain_Mailer_description', 'app_domain_Mailer_type_id', 'app_domain_Mailer_response_group_id');
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('source_tab', $request->getProperty('source_tab'));

		// client initiative list
//		$client_initiatives = app_domain_Client::findAllClientInitiatives();
//		$request->setObject('client_initiatives', $client_initiatives);
		
		// Get lookup information
		if ($items = app_domain_Client::findAllClientInitiatives())
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['initiative_id']] = @C_String::htmlDisplay(ucfirst($item['client_initiative_display']));
			}
			$request->setObject('client_initiatives', $options);
		}
		
		if ($items = app_domain_Mailer::lookupTypes())
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('mailer_types', $options);
		}
		
		if ($items = app_domain_Mailer::lookupResponseGroups())
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('mailer_response_groups', $options);
		}
		
//		$mailer_types = app_domain_Mailer::lookupTypes();
//		$request->setObject('mailer_types', $mailer_types);
//		$mailer_response_groups = app_domain_Mailer::lookupResponseGroups();
//		$request->setObject('mailer_response_groups', $mailer_response_groups);
	
	}	
}
?>
