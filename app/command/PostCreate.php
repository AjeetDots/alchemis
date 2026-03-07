<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Company.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Post.php');
require_once('app/mapper/ContactMapper.php');
require_once('app/domain/Contact.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

class app_command_PostCreate extends app_command_ManipulationCommand
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
				//$this->init($request);
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
		$errors['app_domain_Post_job_title'] = app_domain_Post::validate($request->getProperty('app_domain_Post_job_title'), app_domain_Post::getFieldSpec('job_title'));
		$errors['app_domain_Post_telephone_1'] = app_domain_Post::validate($request->getProperty('app_domain_Post_telephone_1'), app_domain_Post::getFieldSpec('telephone_1'));
		$errors['app_domain_Post_telephone_2'] = app_domain_Post::validate($request->getProperty('app_domain_Post_telephone_2'), app_domain_Post::getFieldSpec('telephone_2'));
		$errors['app_domain_Post_telephone_switchboard'] = app_domain_Post::validate($request->getProperty('app_domain_Post_telephone_switchboard'), app_domain_Post::getFieldSpec('telephone_switchboard'));
		$errors['app_domain_Post_telephone_fax'] = app_domain_Post::validate($request->getProperty('app_domain_Post_telephone_fax'), app_domain_Post::getFieldSpec('telephone_fax'));
		$errors['app_domain_Contact_title'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_title'), app_domain_Contact::getFieldSpec('title'));
		$errors['app_domain_Contact_first_name'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_first_name'), app_domain_Contact::getFieldSpec('first_name'));
		$errors['app_domain_Contact_surname'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_surname'), app_domain_Contact::getFieldSpec('surname'));
		$errors['app_domain_Contact_telephone_mobile'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_telephone_mobile'), app_domain_Contact::getFieldSpec('telephone_mobile'));
		$errors['app_domain_Contact_email'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_email'), app_domain_Contact::getFieldSpec('email'));
		
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
		
		$post = new app_domain_Post();
		$post->setCompanyId($request->getProperty('company_id'));
		$post->setJobTitle($request->getProperty('app_domain_Post_job_title'));
		$post->setTelephone1($request->getProperty('app_domain_Post_telephone_1'));
		$post->setTelephone2($request->getProperty('app_domain_Post_telephone_2'));
		$post->setTelephoneSwitchboard($request->getProperty('app_domain_Post_telephone_switchboard'));
        $post->setTelephoneFax($request->getProperty('app_domain_Post_telephone_fax'));
        $post->setDataSourceId($request->getProperty('app_domain_Post_data_source_id'));
        $post->setDataSourceChangedDate(Utils::getTimestamp());
        
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();

        if (!empty($user['client_id'])) {
            $post->setDataOwnerId($user['client_id']);
        }
		
		$post->commit();
		
		// Put the new post_id into the request array so we can access it in order to redirect the form if
		// save is successful
		$request->setProperty('id', $post->getId());
		
		$contact = new app_domain_Contact();
		$contact->setPostId($post->getId());
		$contact->setTitle($request->getProperty('app_domain_Contact_title'));
		$contact->setFirstName($request->getProperty('app_domain_Contact_first_name'));
		$contact->setSurname($request->getProperty('app_domain_Contact_surname'));
		$contact->setTelephoneMobile($request->getProperty('app_domain_Contact_telephone_mobile'));
		$contact->setEmail($request->getProperty('app_domain_Contact_email'));
	
		$contact->commit();
		
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
		$sticky_fields = array(	'app_domain_Post_job_title',
								'app_domain_Post_telephone_1',
								'app_domain_Post_telephone_2',
								'app_domain_Post_telephone_switchboard',
								'app_domain_Post_telephone_fax',
								'app_domain_Contact_title',
								'app_domain_Contact_first_name',
								'app_domain_Contact_surname',
								'app_domain_Contact_telephone_mobile',
								'app_domain_Contact_email');
		
		$this->doHandleStickyFields($request, $sticky_fields);
	}
	
	/** Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		$company_id = $request->getProperty('company_id');
        $company = app_domain_Company::find($company_id);
        $items = app_domain_Post::lookupDataSourcesAll();
        $options = array();
        foreach ($items as $item) {
            $options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
        }
        $request->setObject('post_data_source_options', $options);
        $suggested = app_model_DataSource::where('description', 'Colleague suggested')->first();
        $request->setProperty('suggested_id', $suggested->id);
		$request->setProperty('company_id', $company->getId());
		$request->setProperty('company_name', $company->getName());
	}
}

?>