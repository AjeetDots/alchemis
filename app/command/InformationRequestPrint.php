<?php

/**
 * Defines the app_command_InformationRequestPrint class.
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
class app_command_InformationRequestPrint extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		if ($action_id = $request->getProperty('id'))
		{
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

//			$post_initiative_id = $meeting->getPostInitiativeId();
//			$request->setObject('post_initiative_id', $post_initiative_id);

			$notes = app_domain_PostInitiative::findCommunicationNotes($post_initiative->getId());
			$request->setObject('notes', $notes);
//			print_r($notes);

//			$post_initiative = app_domain_PostInitiative::find($post_initiative->getId());
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
						$discipline_note . '<br />';
			}
		}

		return $note;
	}
}

?>