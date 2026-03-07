<?php

/**
 * Defines the app_command_CommunicationEmailCreate class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('include/Zend/Mail.php');

/**
 * @package Alchemis
 */
class app_command_CommunicationEmailCreate extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'send')
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
				$request->addFeedback('E-mail has been sent');
				return self::statuses('CMD_OK');
			}
			else
			{
				$request->setProperty('success', true);
				$request->addFeedback('E-mail could not be sent. Please advise system administrator.');
				return self::statuses('CMD_OK');
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
		if ($request->getProperty('subject') == '')
		{
			$errors['subject'] = new app_base_ValidationError('Subject cannot be blank');
		}
		if ($request->getProperty('body') == '')
		{
			$errors['body'] = new app_base_ValidationError('Body cannot be blank');
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
		// Post fields
		$sticky_fields = array(	'to',
								'from',
								'subject',
								'body');
		
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{

		$mail = new Zend_Mail();
		
		$mail->setFrom($request->getProperty('from'), $request->getProperty('campaign_nbm_user_alias'));
		$mail->addTo($request->getProperty('to'));
		// cc to nbm email account and database.email@alchemis.co.uk
		$mail->addBcc( $_SESSION['auth_session']['user']['email']);
		$mail->addBcc('database.email@alchemis.co.uk');
		$mail->setSubject($request->getProperty('subject'));
		$mail->setBodyText($request->getProperty('body'));
		
		if ($request->propertyExists('attachment_id'))
		{
			$attachments = $request->getProperty('attachment_id');
			foreach ($attachments as $attachment)
			{
				if ($attachment > 0)
				{
					$document = app_domain_Document::find($attachment);
					
					$storage_file = '.' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . $document->getId();
					$file_contents = file_get_contents($storage_file);
					  
					$attachment = $mail->createAttachment($file_contents);
					$attachment->type        = $document->getMimeType();
					$attachment->filename    = $document->getFilename();
				}
			}
		}
		else
		{
			$attachments = null;
		}
		
		
		
		if ($mail->send())
//		if (1 == 1)
		{
			// log non-effective communication
			$communication_id = $this->logNonEffectiveCommunication($request->getProperty('post_initiative_id'), 
																	$request->getProperty('subject'), 
																	$request->getProperty('body'), 
																	$attachments);
			// TODO:
			// log an information request action as completed
			if ($request->getProperty('action_id') > 0)
			{
				if ($action = app_domain_action::find($request->getProperty('action_id')))
				{
					$action->setCompletedDate(date('Y-m-d'));
					$action->commit();
				}
				else
				{
					// TODO: raise error that action could not be marked as completed
				}
			}
		}
		else
		{
			// mail could not be sent for some reason
			// TODO: Raise error
			return false;
		}
		
		return true;
	
	}
	
	protected function init(app_controller_Request $request)
	{
		// Pass thru' fields
		$post_initiative_id = $request->getProperty('post_initiative_id');
		
		$request->setProperty('post_initiative_id', $post_initiative_id);
		$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
		
		$post = app_domain_Post::findByPostInitiativeId($post_initiative_id);
		$request->setObject('post', $post);

		$campaign_id = app_domain_Campaign::findCampaignIdByInitiativeId($post_initiative->getInitiativeId());
		
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		
		$campaign_nbm = app_domain_CampaignNbm::findByUserIdAndCampaignId($user['id'], $campaign_id);
		$request->setObject('campaign_nbm', $campaign_nbm);
		
		// attachments
		if ($items = app_domain_Document::findByCampaignId($campaign_id))
		{
			$options = array();
//			$options[0] = '-- select if required--';
			foreach ($items as $item)
			{
				$options[$item->getId()] = @C_String::htmlDisplay(ucfirst($item->getDescription()));
			}
			$request->setObject('attachments', $options);
		}
		
		// current actions
		// find all actions still oustanding for this post initiative. The user can select from this list
		// to indicate that the email is being used to complete an action
		if ($items = app_domain_Action::findCurrentByPostInitiativeIdAndTypeId($post_initiative_id, 2))
		{
			$options = array();
			$options[0] = '-- select if required--';
			foreach ($items as $item)
			{
				$options[$item->getId()] = @C_String::htmlDisplay(ucfirst($item->getSubject()) . ' - (by ' 
				. $item->getCommunicationType() . ' due ' . date('d-m-Y', strtotime($item->getDueDate())) . ')');
			}
			$request->setObject('information_requests', $options);
		}
	}

	/**
	 * Logs a new non effective communication.
	 * @param integer $post_id
	 * @param string $note
	 */
	protected function logNonEffectiveCommunication($post_initiative_id, $subject, $body, $attachments)
	{
		// find the last communication for this post_initiative_id so we can use some of the detail (eg status_id)	
		if ($post_initiative_id != '')
		{
			$last_communication = app_domain_Communication::findLastByPostInitiativeId($post_initiative_id);
		}
		else
		{
			// TODO: raise error
		}
		
		if (is_object($last_communication))
		{
			$next_action_by = $last_communication->getNextActionBy(); 
			$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
			$status_id = $post_initiative->getStatusId();
		}	
		else // no previous communication 
		{
			if ($post_initiative_id != '')
			{
				$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
				$status_id = $post_initiative->getStatusId();
				$next_action_by = 1; // Alchemis
				$post_initiative->setNextActionBy($next_action_by);	
			}
			else
			{
				// TODO: raise error
			}
		}
		
		
		// commit the post_initiative at this point else the communication insert will fail on foreign key
		$post_initiative->commit();
		
		// Instantiate the communication at this point so we can set the last_communication_id in tbl_post_initiative
		$communication = new app_domain_Communication();
		$communication->setPostInitiativeId($post_initiative_id);
		$communication->setCommunicationDate(date('Y-m-d H:i:s'));
		$communication->setUserId($_SESSION['auth_session']['user']['id']);
		$communication->setTypeId(6);
		$communication->setDirection('out');
		$communication->setStatusId($status_id);
		$communication->setNextActionBy($next_action_by);
		$communication->setEffective('non-effective');
		$communication->setIsEffective(false);
		$communication->setComments("Subject:\n" . $subject);
		$communication->setNotes("Body:\n" . $body);
		
		// create any attachments
		if (!is_null($attachments))
		{
			foreach ($attachments as $attachment)
			{
				$communication_attachment = new app_domain_CommunicationAttachment();
				$communication_attachment->setCommunicationId($communication->getId());
				$communication_attachment->setDocumentId($attachment);
				$communication->addAttachment($communication_attachment);
			}
			$communication->setHasAttachment(true);
		}
		$communication->commit();

		// now set the last communication id for the post initiative
		// NOTE: cannot do before this point else the update will fail on foreign key constraint
		$post_initiative->setLastCommunicationId($communication->getId());
		$post_initiative->commit();
		
		return $communication->getId();
	
	}
	
}

?>