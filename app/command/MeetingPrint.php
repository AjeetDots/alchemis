<?php

/**
 * Defines the app_command_MeetingPrint class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');

/**
 * @package Alchemis
 */
class app_command_MeetingPrint extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		if ($meeting_id = $request->getProperty('id'))
		{
			// Meeting details
			$meeting = app_domain_Meeting::find($meeting_id);
			
			// Company details
			$company = app_domain_Company::find(app_domain_Meeting::findCompanyId($meeting_id));
			
			$request->setObject('meeting', $meeting);
			$request->setObject('company', $company);
			
			$post_initiative_id = $meeting->getPostInitiativeId();
			$request->setObject('post_initiative_id', $post_initiative_id);
			
			$notes = app_domain_PostInitiative::findEffectiveCommunicationNotes($post_initiative_id);
			$request->setObject('notes', $notes);
			
			$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
			$initiative = app_domain_Initiative::find($post_initiative->getInitiativeId());
			$request->setObject('initiative', $initiative);
			
			// Client
			$client = app_domain_Client::findByInitiativeId($initiative->getId());
			$request->setObject('client', $client);
			
			// Confirmation process
			$actions = app_domain_Action::findCurrentByPostInitiativeIdAndTypeId($post_initiative->getId(), 1);
			$request->setObject('actions', $actions);
//			$request->setObject('actions', $actions->toRawArray());
						
//			echo '<pre>';
//			print_r($actions);
//			echo '</pre>';
			// Campaign DM/Agency user information
			$discipline_note = self::makeDisciplineNoteByCampaignIdPostId($initiative->getCampaignId(), $post_initiative->getPostId());
			$request->setProperty('discipline_note', $discipline_note);
			
			// Characteristics
			$object_characteristics = self::getObjectCharacteristicsByTypeAndId('company', $company->getId());
			$request->setObject('characteristics', $object_characteristics->characteristics);
			return self::statuses('CMD_OK');
		}
		else
		{
			throw new app_base_AppException('Meeting ID not supplied.');
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