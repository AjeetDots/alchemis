<?php

/**
 * Defines the app_command_PostEdit class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Post.php');
require_once('app/mapper/ContactMapper.php');
require_once('app/domain/Contact.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_PostEdit extends app_command_ManipulationCommand
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
				$request->setProperty('success', true);
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
	 * Takes a list of the fields being used and re-assigns any values entered 
	 * to make sticky.
	 * @param app_controller_Request $request 
	 * @param array $field_spec associative array of fields in use, where the 
	 *        key is the field name
	 */
	protected function handleStickyFields(app_controller_Request $request)
	{
		// Post fields
		$sticky_fields = array(	'app_domain_Post_job_title',
								'app_domain_Post_telephone_1',
								'app_domain_Post_telephone_2',
								'app_domain_Post_telephone_switchboard',
								'app_domain_Post_telephone_fax');
		
		// Contact fields
		if ($request->getProperty('chk_display_contact'))
		{
			$sticky_fields = array_merge($sticky_fields, array(	'chk_display_contact'));
		}
		
		$sticky_fields = array_merge($sticky_fields, array(		'chk_change_contact',
																'app_domain_Contact_title',
																'app_domain_Contact_first_name',
																'app_domain_Contact_surname',
																'app_domain_Contact_telephone_mobile',
																'app_domain_Contact_email'));
		
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		$post = app_domain_Post::find($request->getProperty('id'));

		if ($post->getJobTitle() !== $request->getProperty('app_domain_Post_job_title'))
		{
			// Job title has changed - add a post initiative note
			$this->logNote($request->getProperty('id'), 
							'Job title changed from ' . $post->getJobTitle() . ' to ' . $request->getProperty('app_domain_Post_job_title') . '.');
		}
		
		$post->setCompanyId($request->getProperty('company_id'));
		$post->setJobTitle($request->getProperty('app_domain_Post_job_title'));
		$post->setTelephone1($request->getProperty('app_domain_Post_telephone_1'));
		$post->setTelephone2($request->getProperty('app_domain_Post_telephone_2'));
		$post->setTelephoneSwitchboard($request->getProperty('app_domain_Post_telephone_switchboard'));
        $post->setTelephoneFax($request->getProperty('app_domain_Post_telephone_fax'));
        if ($request->getProperty('app_domain_Post_data_source_id') != $post->getDataSourceId()) {
            $post->setDataSourceChangedDate(Utils::getTimestamp());
        }
        $post->setDataSourceId($request->getProperty('app_domain_Post_data_source_id'));
		$post->commit();
		$this->saveContact($request);
		return true;
	}

	/**
	 * Handles the saving of a contact. 
	 * @param app_controller_Request $request
	 */
	protected function saveContact(app_controller_Request $request)
	{
		$contact = app_domain_Contact::findCurrentByPostId($request->getProperty('id'));
			
		if (empty($contact))
		{	
			// check if user has selected the display contact checkbox
			if ($request->getProperty('chk_display_contact') == 'on')
			{
				$contact = new app_domain_Contact();
			}
			else
			{
				return true;
			}
		}
		else
		{
			if ($request->getProperty('chk_change_contact') == 'on')
			{
				$contact->setDeleted(true);
				$contact->commit();
				
				// Post holder changed - log post initiative note
				$name_before = $contact->getName(app_domain_Contact::CONTACT_ORDER_TITLE_FORENAME_SURNAME);
				$name_after  = trim($request->getProperty('app_domain_Contact_title')) . ' ' .
								trim($request->getProperty('app_domain_Contact_first_name')) . ' ' .
								trim($request->getProperty('app_domain_Contact_surname'));
				if (trim($name_after) == '')
				{
					$name_after = 'POST VACANT';
				}
				$this->logNote($request->getProperty('id'), "Post holder changed from $name_before to $name_after.");
				
				$contact = new app_domain_Contact();
			}
		}
		
		$contact->setPostId($request->getProperty('id'));
		$contact->setTitle($request->getProperty('app_domain_Contact_title'));
		$contact->setFirstName($request->getProperty('app_domain_Contact_first_name'));
		$contact->setSurname($request->getProperty('app_domain_Contact_surname'));
		$contact->setTelephoneMobile($request->getProperty('app_domain_Contact_telephone_mobile'));
		$contact->setEmail($request->getProperty('app_domain_Contact_email'));
		$contact->commit();
		return true;
	}

	protected function init(app_controller_Request $request)
	{
		// Pass post
		$post = app_domain_Post::find($request->getProperty('id'));
        $request->setObject('post', $post);
        
        // Post data source options
        $items = $post->lookupDataSourcesAll();
        $options = array();
        foreach ($items as $item) {
            $options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
        }
        $request->setObject('post_data_source_options', $options);
		
		// Pass contact
		$contact = app_domain_Contact::findCurrentByPostId($request->getProperty('id'));
		$request->setObject('contact', $contact);
		
		// Get company name
		$company = app_domain_Company::find($post->getCompanyId());
		$request->setProperty('company_id', $company->getId());
		$request->setProperty('company_name', $company->getName());
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