<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Company.php');
//require_once('app/mapper/SiteMapper.php');
//require_once('app/domain/Site.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Post.php');
//require_once('include/Utils/Utils.class.php');
//require_once('include/Utils/String.class.php');

class app_command_PostEditLocation extends app_command_ManipulationCommand
{
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
			elseif ($this->processForm($request))
			{
				$this->init($request);
				$request->addFeedback('Save Successful');
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
		$errors['app_domain_Post_new_company_id'] = app_domain_Post::validate($request->getProperty('app_domain_Post_new_company_id'), app_domain_Post::getFieldSpec('new_company_id'));
		
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
		$sticky_fields = array(	'app_domain_Post_new_company_id');
		
		$this->doHandleStickyFields($request, $sticky_fields);
	}
	
	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		
		$post_id = $request->getProperty('id');
		$post = app_domain_Post::find($post_id);
			
		//log post initiative note to record location change
		$company = app_domain_Company::find($post->getCompanyId());
		$company_name_before = $company->getName();
		$address_before = $company->getSiteAddress(null, 'paragraph');
		
		$company = app_domain_Company::find($request->getProperty('app_domain_Post_new_company_id'));
		$company_name_after = $company->getName();
		$address_after = $company->getSiteAddress(null, 'paragraph');
		
		$this->logNote($post_id, "Post location changed from $company_name_before at $address_before to $company_name_after at $address_after.");
				
		$post->setCompanyId($request->getProperty('app_domain_Post_new_company_id'));
		$post->commit();
		
		return true;
	}
		
	protected function init(app_controller_Request $request)
	{
		
		
		// Pass post
		$post = app_domain_Post::find($request->getProperty('id'));
		$request->setObject('post', $post);
		
		// Get company 
		$company = app_domain_Company::find($post->getCompanyId());
		$request->setObject('company', $company);
		
	}
	
	/**
	 * Logs a new post initiative note.
	 * @param integer $post_id
	 * @param string $note
	 */
	protected function logNote($post_id, $note)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();

		// Get all post initiatives
		$results = app_domain_PostInitiative::findByPostId($post_id);
		foreach ($results as $result)
		{
			$obj = new app_domain_PostInitiativeNote();
			$obj->setPostInitiativeId($result->getId());
			$obj->setCreatedAt(date('Y-m-d H:i:s'));
			$obj->setCreatedBy($user['id']);
			$obj->setNote($note);
			$obj->commit();
		}
	}
	
}

?>