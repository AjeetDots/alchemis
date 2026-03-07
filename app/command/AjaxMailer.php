<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Mailer.php');
require_once('app/mapper/MailerMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_Mailer objects.
 * @package alchemis
 */
class app_command_AjaxMailer extends app_command_AjaxCommand
{
	
//	protected $post_initiative;
//	protected $contact;
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		// Instantiate the object
//		$post_initiative_id = $this->request->item_id;
			
		switch ($this->request->cmd_action)
		{
			case 'add_mailer':
				$this->request->template = $this->getBlankMailerForm();
				break;
			default:
				break;
		}
		
		array_push($this->response->data, $this->request);
		
	}
	
	protected function getBlankMailerForm()
	{
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		return $smarty->fetch('html_addMailer.tpl');
	}
	
	protected function editResponse($mailer_id, $mailer_item_id)
	{
		$possible_responses = app_domain_Mailer::findPossibleResponsesByMailerId($mailer_id);
						
		$responses = app_domain_MailerItemResponse::findByMailerItemId($mailer_item_id);
		
		foreach ($possible_responses as &$possible_response)
		{
			foreach ($responses as $response)
			{
				if ($response['mailer_response_id'] == $possible_response['id'])
				{
					$possible_response['checked'] = true;
				}
			}
		}
		
		$mailer_item = app_domain_MailerItem::find($mailer_item_id);
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();

		$smarty->assign('mailer_id', $mailer_id);
		$smarty->assign('mailer_item', $mailer_item);		
		$smarty->assign('responses', $possible_responses);

		
		
//		$return_data = new stdClass();
		
		return $smarty->fetch('html_editMailerResponse.tpl');
//		$return_data->meeting_count = $meetings->count();
		
//		return $return_data;
	}
	
	protected function addResponses($mailer_item_id,$field_data)
	{
		$result = false;
		
		$response_ids = array();
		
		foreach ($field_data as $field)
		{
			
			if ($field[0] == 'chk')
			{
				$response_ids[] = $field[1];
			}
			elseif ($field[0] == 'date')
			{
				$response_date = $field[1];
			}
			elseif ($field[0] == 'note')
			{
				$response_note = $field[2];
			}
		}
		
		// remove un-selected items
		$existing_responses = app_domain_MailerItemResponse::findByMailerItemId($mailer_item_id);
		$items_to_remove = array();
		foreach ($existing_responses as $existing_response)
		{
			$found = false;
			foreach ($response_ids as $response)
			{
				if ($existing_response['mailer_response_id'] == $response)
				{
					$found = true;
					break;
				}
			}
			
			if (!$found)
			{
				$items_to_remove[] = $existing_response['id'];
			}
		}
		
		
		// add a mailer response item for each of the mailer item responses
		foreach ($items_to_remove as $item_to_remove)
		{
			
			$mailer_item_response = app_domain_MailerItemResponse::find($item_to_remove);
			$mailer_item_response->markDeleted();
			$mailer_item_response->commit();		
		}
		
		
		
//		// create a communication at this point	since we need the id when saving the responses
//		$communication = new app_domain_Communication();
		
		// add a mailer response item for each of the mailer item responses
		foreach ($response_ids as $response)
		{
			if ($mailer_item_response = app_domain_MailerItemResponse::findByMailerItemIdAndMailerResponseId($mailer_item_id, $response))
			{
				// we have an existing object
				// do nothing
			}
			else
			{
				$mailer_item_response = new app_domain_MailerItemResponse();
				$mailer_item_response->setMailerItemId($mailer_item_id);
				$mailer_item_response->setMailerResponseId($response);
				$mailer_item_response->commit();	
			}
			$mailer_item_response_descriptions[] = app_domain_MailerItemResponse::lookupMailerResponseDescription($response);
		}
		
		// set up a mailer_item object
		$mailer_item = app_domain_MailerItem::find($mailer_item_id);	
		
		// get the mailer details in order to create communication comments/notes
		$mailer = app_domain_Mailer::find($mailer_item->getMailerId());
		
		// make note to be added to tbl_communications
		$communication_comment = 'Mailer response received to mailer \'' . $mailer->getName() . '\' on ' . $response_date . '<br />';
		
		foreach ($mailer_item_response_descriptions as $mailer_item_response_description)
		{
			$communication_note .= '&nbsp;&nbsp;&ndash;' . $mailer_item_response_description . '<br />';
		}
		$communication_note = $communication_comment . '<br />Response(s):<br />' . $communication_note . '<br />Notes:<br />' . $response_note;
		
		$response_communication_id = $this->addMailerCommunication(
									$mailer_item->getResponseCommunicationId(), 
									$mailer_item->getPostInitiativeId(), 
									$response_date, 
									$communication_comment,
									$communication_note);
		
		// update the mailer item
		$mailer_item->setResponseDate($response_date);
		$mailer_item->setNote($response_note);
		$mailer_item->setResponseCommunicationId($response_communication_id);
		$mailer_item->commit();
		
		$result = true;
		
		$return_data = new stdClass();
		
		$return_data->result = $result;
		$return_data->response_date = $response_date;
		
		return $return_data;
//		return $result;
	}
	
	protected function addMailerCommunication($response_communicaton_id = null, $post_initiative_id, $response_date, $comments, $notes)
	{
		if (is_null($response_communicaton_id))
		{
			$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
			
			$communication = new app_domain_Communication();
			$communication->setPostInitiativeId($post_initiative_id);
			$communication->setUserId($_SESSION['auth_session']['user']['id']);
			$communication->setTypeId(5);
			$communication->setStatusId($post_initiative->getStatusId());
//			$communication->setCommunicationDate(date('Y-m-d H:i:s'));
			$communication->setCommunicationDate($response_date);
			$communication->setDirection('out');
			$communication->setEffective('non-effective');
		}
		else
		{
			$communication = app_domain_Communication::find($response_communicaton_id);
		}
		
		$communication->setComments($comments);
		$communication->setNotes($notes);
		
		$communication->commit();
		
		return $communication->getId();
		
	}
	
}

?>