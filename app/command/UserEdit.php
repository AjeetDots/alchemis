<?php

/**
 * Defines the app_command_UserEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('app/domain/RbacUser.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_UserEdit extends app_command_ManipulationCommand
{
	protected $debug = false;
	
	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		return $this->session_user->hasPermission('permission_admin_users');
	}

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
				$request->addFeedback('Save Successful');
				$request->setObject('success', true);
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
		}
	}

	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function init(app_controller_Request $request)
	{
		$user_id = $request->getProperty('user_id');
		$user = app_domain_RbacUser::find($user_id);
		$request->setObject('user', $user);

		$request->setObject('app_domain_RbacUser_handle', $user->getHandle());
		$request->setObject('app_domain_RbacUser_name', $user->getName());
		$request->setObject('app_domain_RbacUser_email', $user->getEmail());
		$request->setObject('app_domain_RbacUser_is_active', $user->isActive());
		$request->setObject('app_domain_RbacUser_client_id', $user->getClientId());
		$clients = app_model_Clients::orderBy('name')
			->where('is_current', true)
			->get();
		$request->setObject('clients', $clients);
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		// User
		$obj = app_domain_RbacUser::find($request->getProperty('user_id'));
		$obj->setHandle($request->getProperty('app_domain_RbacUser_handle'));
		$obj->setName($request->getProperty('app_domain_RbacUser_name'));
		$obj->setEmail($request->getProperty('app_domain_RbacUser_email'));
		$obj->setActive($request->propertyExists('app_domain_RbacUser_is_active'));
		
		$client_id = $request->getProperty('app_domain_RbacUser_client_id') ? $request->getProperty('app_domain_RbacUser_client_id') : null;
		$obj->setClientId($client_id);
		
		if ($request->propertyExists('chk_change_password'))
		{
			$obj->setPassword(md5($request->getProperty('app_domain_RbacUser_password')));
		}
		
		$permissions = $this->getPermissionProperties($request);
		
		// We first revoke all permissions to ensure the user doesn't have any that aren't checked
		$obj->revokeAllPermissions();
		
		// Then we add back each permission that is checked
		foreach ($permissions as $permission => $value)
		{
			$obj->$permission = $value;	
		}
		
		// Save the object
		$obj->commit();
		
		return true;
	}

	/**
	 * Get an array of the permissions returned 
	 * @param app_controller_Request $request
	 */
	protected function getPermissionProperties(app_controller_Request $request)
	{
		$array = array();
		foreach ($request->getProperties() as $property => $value)
		{
			if (substr($property, 0, 11) == 'permission_')
			{
				$array[$property] = true;
			}
		}
		return $array;
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
		$errors['app_domain_RbacUser_handle'] = app_domain_RbacUser::validate($request->getProperty('app_domain_RbacUser_handle'), app_domain_RbacUser::getFieldSpec('handle'));
		$errors['app_domain_RbacUser_name']   = app_domain_RbacUser::validate($request->getProperty('app_domain_RbacUser_name'), app_domain_RbacUser::getFieldSpec('name'));
		$errors['app_domain_RbacUser_email']   = app_domain_RbacUser::validate($request->getProperty('app_domain_RbacUser_email'), app_domain_RbacUser::getFieldSpec('email'));
		
		if ($request->getProperty('chk_change_password'))
		{
			$errors['app_domain_RbacUser_password'] = app_domain_RbacUser::validate($request->getProperty('app_domain_RbacUser_password'), app_domain_RbacUser::getFieldSpec('password'));
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
	 * Takes a list of the fields being used and re-assigns any values entered 
	 * to make sticky.
	 * @param app_controller_Request $request 
	 * @param array $field_spec associative array of fields in use, where the 
	 *        key is the field name
	 */
	protected function handleStickyFields(app_controller_Request $request)
	{
		$sticky_fields = array('app_domain_RbacUser_handle', 'app_domain_RbacUser_name', 'app_domain_RbacUser_email', 'app_domain_RbacUser_is_active');
		if ($request->getProperty('chk_change_password'))
		{
			$sticky_fields[] = 'app_domain_RbacUser_password';
		}
		$this->doHandleStickyFields($request, $sticky_fields);
	}

}

?>