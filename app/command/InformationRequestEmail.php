<?php

/**
 * Defines the app_command_InformationRequestEmail class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Action.php');
require_once('app/mapper/ActionMapper.php');

/**
 * @package Alchemis
 */
class app_command_InformationRequestEmail extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		if ($action_id = $request->getProperty('id'))
		{
			// user email and name
			$request->setProperty('nbm_name', $_SESSION['auth_session']['user']['name']);
			$request->setProperty('nbm_email', $_SESSION['auth_session']['user']['email']);
			
			// Action (information request) details
			$action = app_domain_Action::find($action_id);
			$request->setObject('action', $action);
			
			// Post initiative details
			if (!$post_initiative = app_domain_PostInitiative::find($action->getPostInitiativeId()))
			{
				throw new app_base_AppException('Action post_initiative_id could not be found.');
			}
			$request->setObject('post_initiative', $post_initiative);
			
			// Post details			
			$post = $post_initiative->getPost();
			$request->setObject('post', $post);
			
			// Company details
			$company = app_domain_Company::find($post->getCompanyId());
			$request->setObject('company', $company);
			
			$notes = app_domain_PostInitiative::findCommunicationNotes($post_initiative->getId());
			$request->setObject('notes', $notes);

			$initiative = app_domain_Initiative::find($post_initiative->getInitiativeId());
			$request->setObject('initiative', $initiative);
			
			// Client
			$client = app_domain_Client::findByInitiativeId($initiative->getId());
			$request->setObject('client', $client);
			
			// Confirmation process
			$actions = app_domain_Action::findCurrentByPostInitiativeIdAndTypeId($post_initiative->getId(), 2);
			$request->setObject('actions', $actions);
						
			// Campaign DM/Agency user information
			$discipline_note = self::makeDisciplineNoteByCampaignIdPostId($initiative->getCampaignId(), $post_initiative->getPostId());
			$request->setProperty('discipline_note', $discipline_note);
			
			// Characteristics
			$object_characteristics = self::getObjectCharacteristicsByTypeAndId('company', $company->getId());
			$request->setObject('characteristics', $object_characteristics->characteristics);
			
			//
			// Handle form submission
			//
			if ($request->propertyExists('task') && $request->getProperty('task') == 'save')
			{
				require_once('app/view/ViewHelper.php');
				$this->smarty = ViewHelper::getSmarty();
				
				$this->request = $request;
				$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		
				// Action details
				$this->smarty->assign('action', $action);
				
				// Post details			
//				$post = $post_initiative->getPost();
				$this->smarty->assign('post', $post);
				
				// Contact
				$contacts = $post->getContacts();
				$contact = $post->getContact();
				$this->smarty->assign('contact', $contact);
				
				// Company details
//				$company = app_domain_Company::find($post->getCompanyId());
				$this->smarty->assign('company', $company);
				
//				$notes = app_domain_PostInitiative::findCommunicationNotes($post_initiative->getId());
				$this->smarty->assign('notes', $notes);
	
//				$initiative = app_domain_Initiative::find($post_initiative->getInitiativeId());
				$this->smarty->assign('initiative', $initiative);
				
				// Client
//				$client = app_domain_Client::findByInitiativeId($initiative->getId());
				$this->smarty->assign('client', $client);
				
				// Confirmation process
//				$actions = app_domain_Action::findCurrentByPostInitiativeIdAndTypeId($post_initiative->getId(), 2);
				$this->smarty->assign('actions', $actions);
							
				// Campaign DM/Agency user information
//				$discipline_note = self::makeDisciplineNoteByCampaignIdPostId($initiative->getCampaignId(), $post_initiative->getPostId());
				$this->smarty->assign('discipline_note', $discipline_note);
				
				// Characteristics
//				$object_characteristics = self::getObjectCharacteristicsByTypeAndId('company', $company->getId());
//				$this->smarty->assign('characteristics', $object_characteristics->characteristics);

				//
				// Send the email
				//
				// Get email parameters
				$to_name    = $request->getProperty('to_name');
				$to_email   = $request->getProperty('to_email');
				$from_name  = $request->getProperty('from_name');
				$from_email = $request->getProperty('from_email');
				$subject    = trim($request->getProperty('subject'));
				$body       = trim($request->getProperty('body'));
				
				// Set parameters
				$request->setObject('to_name',    $to_name);
				$request->setObject('to_email',   $to_email);
				$request->setObject('from_name',  $from_name);
				$request->setObject('from_email', $from_email);
				$request->setObject('subject',    $subject);
				$request->setObject('body',       $body);
				
				require_once('Zend/Mail.php');
				$mail = new Zend_Mail();
				$mail->setFrom($from_email, $from_name);
				// split multi part to recipients
				if (strpos($to_email, ';') !== false)
				{
					$to_email = explode(';', $to_email);
					foreach($to_email as $recipient)
					{
						$mail->addTo(trim($recipient));
					}
				}
				else
				{
					$mail->addTo($to_email, $to_name);
				}
				$mail->addBcc($from_email);
				$mail->addBcc('database.email@alchemis.co.uk');
				$mail->setSubject($subject);
				
				// Create HTML body
				$html_notes = $this->smarty->fetch('InformationRequestPrint.html.tpl');
				$html_body = '<div style="border-bottom: 1px solid #000; font-family: Georgia, Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 9pt; margin-bottom: 20px; padding-bottom: 20px">' .
								nl2br($body) . '</div>';
				$mail->setBodyHtml($html_body . $html_notes);

				// Create plain text body
				$plain_notes = trim($this->smarty->fetch('InformationRequestPrint.plain.tpl'));
				$plain_notes = strip_tags($plain_notes);
				$plain_notes = preg_replace('/\t/', '', $plain_notes);
				$mail->setBodyText($body . "\n\n\n----------------------------------------\n\n" . $plain_notes);
				
				// Send email
				$mail->send();
				$request->setObject('email_sent', true);
			}
			
			return self::statuses('CMD_OK');
		}
		else
		{
			throw new app_base_AppException('Action ID not supplied.');
		}
	}


	public static function getObjectCharacteristicsByTypeAndId($type, $id)
	{
		if ($type == 'company')
		{
			$available = app_domain_Characteristic::selectAvailableByCompanyId($id);
			$collection = app_domain_Characteristic::findByCompanyId($id);
			$characteristics = $collection->toRawArray();
			foreach ($characteristics as &$characteristic)
			{
				if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0)
				{
					$characteristic_item = app_domain_ObjectCharacteristicHelper::getValueByCompanyId($characteristic['id'], $characteristic['data_type'], $id);
					$characteristic['value'] = $characteristic_item[0]['value'];
					$characteristic['object_characteristic_value_id'] = $characteristic_item[0]['id'];
				}
				else
				{
					$characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();
					foreach ($characteristic['elements'] as &$element)
					{
						if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByCompanyId($element['id'], $element['data_type'], $id))
						{
							$element['value']                    = $record['value'];
							$element['object_characteristic_id'] = $record['object_characteristic_id'];
							$element['object_characteristic_element_id'] = $record['id'];
						}
						else
						{
							$record = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByCompanyIdAndCharacteristicId($id, $characteristic['id']);
							$element['object_characteristic_id'] = $record;
						}  
					}
				}
			}
		}
		elseif ($type == 'post')
		{
			$available = app_domain_Characteristic::selectAvailableByPostId($id);
			$collection = app_domain_Characteristic::findByPostId($id);
			$characteristics = $collection->toRawArray();
			foreach ($characteristics as &$characteristic)
			{
				if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0)
				{
					$characteristic_item = app_domain_ObjectCharacteristicHelper::getValueByPostId($characteristic['id'], $characteristic['data_type'], $id);
					
					$characteristic['value'] = $characteristic_item[0]['value'];
					$characteristic['object_characteristic_value_id'] = $characteristic_item[0]['id'];
				}
				else
				{
					$characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();
					foreach ($characteristic['elements'] as &$element)
					{
						if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByCompanyId($element['id'], $element['data_type'], $id))
						{
							$element['value']                    = $record['value'];
							$element['object_characteristic_id'] = $record['object_characteristic_id'];
							$element['object_characteristic_element_id'] = $record['id'];
						}
						else
						{
							$record = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByCompanyIdAndCharacteristicId($id, $characteristic['id']);
							$element['object_characteristic_id'] = $record['object_characteristic_id'];
						}    
					}
				}
			}
		}
		elseif ($type == 'post_initiative')
		{
			$available = app_domain_Characteristic::selectAvailableByPostInitiativeId($id);
			$collection = app_domain_Characteristic::findByPostInitiativeId($id);
			$characteristics = $collection->toRawArray();
			foreach ($characteristics as &$characteristic)
			{
				if ($characteristic['attributes'] == 0 && $characteristic['options'] == 0)
				{
					$characteristic_item = app_domain_ObjectCharacteristicHelper::getValueByPostInitiativeId($characteristic['id'], $characteristic['data_type'], $id);
					
					$characteristic['value'] = $characteristic_item[0]['value'];
					$characteristic['object_characteristic_value_id'] = $characteristic_item[0]['id'];
				}
				else
				{
					$characteristic['elements'] = app_domain_CharacteristicElement::findByCharacteristicId($characteristic['id'])->toRawArray();
					foreach ($characteristic['elements'] as &$element)
					{
						if ($record = app_domain_ObjectCharacteristicElementHelper::getRecordByCompanyId($element['id'], $element['data_type'], $id))
						{
							$element['value']                    = $record['value'];
							$element['object_characteristic_id'] = $record['object_characteristic_id'];
							$element['object_characteristic_element_id'] = $record['id'];
						}
						else
						{
							$record = app_domain_ObjectCharacteristicHelper::getObjectCharacteristicIdByCompanyIdAndCharacteristicId($id, $characteristic['id']);
							$element['object_characteristic_id'] = $record['object_characteristic_id'];
						}  
					}
				}
			}
		}
		else
		{
				throw new Exception('Invalid object type: ' . $type);
		}
		
		$return_data = new stdClass();
		$return_data->available = $available;
		$return_data->collection = $collection;
		$return_data->characteristics = $characteristics;	
		
		return $return_data;
	}

	public static function makeDisciplineNoteByCampaignIdPostId($campaign_id, $post_id)
	{
		$post = app_domain_Post::find($post_id);
		
		if (is_object($post->getContact()))
		{
			$contact_first_name = ucfirst($post->getContact()->getFirstName());
		}
		else
		{
			$contact_first_name = 'the contact';
		}
		
		// dm
		// agency user
		// review date
		$disciplines = app_domain_Campaign::findCampaignDisciplineRecordsByCampaignIdPostId($campaign_id, $post_id);
		$discipline_note = '';
		$note = '';
		
		foreach ($disciplines as $discipline)
		{
			// DM /Agency user note
			$discipline_note = app_domain_Campaign::makeDecisionMakerAndAgencyUserStandardNote($discipline);
			
			// incumbents note
			$post_incumbent_agencies = app_domain_PostIncumbentAgency::findAllByPostIdAndDisciplineId($post_id, $discipline['tiered_characteristic_id']);
			$discipline_note .= app_domain_Campaign::makeIncumbentAgencyStandardNote($post_incumbent_agencies, $contact_first_name);
			 		
			if ($discipline_note != '')
			{
				$note .= 'For ' . $discipline['discipline'] . ' ' . 
						$contact_first_name . 
						$discipline_note . "\n\n";
			}
		}
		
		return $note;
	}
}

?>