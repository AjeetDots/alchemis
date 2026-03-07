<?php

/**
 * Defines the app_command_PostDelete class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Company.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Post.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_PostDelete extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		if (!$this->session_user->hasPermission('permission_delete_post')) throw new app_base_PermissionException('You do not have the correct permission');
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
		$is_deleted = $request->getProperty('app_domain_Post_deleted');
		if (!$is_deleted)
		{
			$errors['app_domain_Post_deleted'] = new app_base_ValidationError("Post cannot be deleted until the 'Confirm Deletion' checkbox is ticked.");
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
		if ($request->getProperty('app_domain_Post_deleted'))
		{
			$post = app_domain_Post::find($request->getProperty('id'));
			$post->markDeleted();
			$post->commit();
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
		// Post fields
		// No need to do anything as these are all set in the init function
//		$sticky_fields = array('id', 'post_count', 'source_tab');
//		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		$post = app_domain_Post::find($request->getProperty('id'));
		$request->setProperty('id', $request->getProperty('id'));
		$request->setObject('post', $post);
		
		$company = app_domain_Company::find($request->getProperty('company_id'));
		$request->setObject('company', $company);
		$request->setProperty('post_count',$company->getPostCount());
		
		// 'data_source' field used to track the data source of the company being deleted - eg was it from a filter or search recordset.
		// We will use this field to return the user the appropriate filter/search after the company is deleted 
		$request->setProperty('source_tab', $request->getProperty('source_tab'));
	}
	
}

?>