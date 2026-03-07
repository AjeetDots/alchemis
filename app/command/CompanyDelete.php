<?php

/**
 * Defines the app_command_CompanyDelete class. 
 * @author    David Carter <david.carter@illumen.co.uk>
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
class app_command_CompanyDelete extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		if (!$this->session_user->hasPermission('permission_delete_company')) throw new app_base_PermissionException('You do not have the correct permission');
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
		$is_deleted = $request->getProperty('app_domain_Company_deleted');
		if (!$is_deleted)
		{
			$errors['app_domain_Company_deleted'] = new app_base_ValidationError("Company cannot be deleted until the 'Confirm Deletion' checkbox is ticked.");
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
		if ($request->getProperty('app_domain_Company_deleted'))
		{
			$company = app_domain_Company::find($request->getProperty('id'));
			$company->markDeleted();
			$company->commit();
		}
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
		// Company fields
		$sticky_fields = array('id', 'post_count', 'data_source');
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		$company = app_domain_Company::find($request->getProperty('id'));
		$post_count = app_domain_Company::findPostCount($request->getProperty('id'));
		
		$request->setObject('company', $company);
		$request->setProperty('id', $request->getProperty('id'));
		$request->setProperty('post_count', $post_count);
		// 'data_source' field used to track the data source of the company being deleted - eg was it from a filter or search recordset.
		// We will use this field to return the user the appropriate filter/search after the company is deleted 
		$request->setProperty('data_source', $request->getProperty('data_source'));
	}
	
}

?>