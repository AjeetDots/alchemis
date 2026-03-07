<?php

/**
 * Defines the app_command_AjaxCommunication class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Communication.php');
require_once('app/mapper/CommunicationMapper.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_Communication objects.
 * @package Alchemis
 */
class app_command_AjaxCommunication extends app_command_AjaxCommand
{
	
	protected $communication;
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
//		throw new exception ($this->request->cmd_action);
		switch ($this->request->cmd_action)
		{
			case 'update_note':
				
				
				$post_initiative_note_id = $this->request->item_id;
				$this->post_initiative_note = app_domain_PostInitiativeNote::find($post_initiative_note_id);
				$this->post_initiative_note->setNote($this->request->note);
//				echo $this->request->note;
//				$this->post_initiative_note->setNote(mb_convert_encoding($this->request->note, 'ISO-8859-1', 'UTF-8'));
				
				// $this->post_initiative_note->setNote(iconv('UTF-8', 'ISO-8859-1', $this->request->note));
				
				$this->post_initiative_note->commit();
				break;
			case 'update_comment':
				$communication_id = $this->request->item_id;
				$this->communication = app_domain_Communication::find($communication_id);
				$this->communication->setComments(mb_convert_encoding((string) $this->request->comment, 'UTF-8', 'ISO-8859-1'));
				$this->communication->commit();
				break;
				
			case 'log_non_effective':
				
 				// find the last communication for this post_initiative_id so we can use some of the detail (eg status_id)	
 				if ($this->request->post_initiative_id != '')
 				{
 					$last_communication = app_domain_Communication::findLastByPostInitiativeId($this->request->post_initiative_id);
 				}
 				else
 				{
 					$last_communication = app_domain_Communication::findLastByPostIdAndInitiativeId($this->request->post_id, $this->request->initiative_id);
 				}
				
 				$this->request->result = true;
				
 				if (is_object($last_communication))
 				{
 					$next_action_by = $last_communication->getNextActionBy(); 
 					$post_initiative = app_domain_PostInitiative::find($last_communication->getPostInitiativeId());
 					$status_id = $post_initiative->getStatusId();
 				}	
 				else // no previous communication 
				{
 					if ($this->request->post_initiative_id != '')
 					{
 						$post_initiative = app_domain_PostInitiative::find($this->request->post_initiative_id);
 						$status_id = $post_initiative->getStatusId();
 						$next_action_by = 1; // Alchemis
 						$post_initiative->setNextActionBy($next_action_by);	
 					}
 					else
 					{
 						// check if a post initiative already exists for this post/initiative combo
 						if ($post_initiative = app_domain_PostInitiative::findByPostAndInitiative($this->request->post_id, $this->request->initiative_id))
 						{
 							// do nothing
 							$this->request->result = false;
							
 						}
 						else
 						// create new post initiative
 						{
                            $data_source = app_model_DataSource::where('description', 'Colleague suggested')->first();

 							// set default values for new post initiative object
 							$status_id = 7;
 							$next_action_by = 1; // Alchemis
                            $lead_source_id = 7;
                            $data_source_id = $data_source->id;
							
 							$post_initiative = new app_domain_PostInitiative();
 							$post_initiative->setPostId($this->request->post_id);
 							$post_initiative->setInitiativeId($this->request->initiative_id);
 							$post_initiative->setStatusId($status_id);
 							$post_initiative->setNextActionBy($next_action_by);
                            $post_initiative->setLeadSourceId($lead_source_id);	
                            $post_initiative->setDataSourceId($data_source_id);
                            $post_initiative->setDataSourceChangedDate(Utils::getTimestamp());	
 						}
 					}
 				}
				
				
 				// commit the post_initiative at this point else the communication insert will fail on foreign key
 				$post_initiative->commit();
				
 				// Instantiate the communication at this point so we can set the last_communication_id in tbl_post_initiative
				$communication = new app_domain_Communication();
 				$communication->setPostInitiativeId($post_initiative->getId());
 				$communication->setCommunicationDate(date('Y-m-d H:i:s'));
 				$communication->setUserId($_SESSION['auth_session']['user']['id']);
 				$communication->setTypeId(1);
 				$communication->setDirection('out');
 				$communication->setStatusId($status_id);
 				$communication->setNextActionBy($next_action_by);
 				$communication->setEffective('non-effective');
 				$communication->setIsEffective(false);
// //				$communication->setHasAttachment(false);
 				$communication->commit();

				
//				$communication = app_class_Communication::logNonEffective($_SESSION['auth_session']['user']['id'], 1, $this->request->post_initiative_id, $this->request->initiative_id, $this->request->post_id);
 				// now set the last communication id for the post initiative
 				// NOTE: cannot do before this point else the update will fail on foreign key constraint
 				$post_initiative->setLastCommunicationId($communication->getId());
 				$post_initiative->commit();
						
				$this->request->post_initiative_id = $post_initiative->getId();
//				$this->request->post_initiative_id = $communication['post_initiative_id'];
				break;
			
			case 'make_discipline_row':
				$this->request->result = $this->makeDisciplineRow($this->request->discipline_id, $this->request->discipline);
				break;
			case 'get_post_initiative_actions':
//				$actions = new app_mapper_ActionCollection();
				$actions = array();
//				$actions[] = 1;
				$action_ids[] = array();
				
				if(isset($_SESSION['auth_session']['communication']['post_initiative_actions']))
				{
					foreach($_SESSION['auth_session']['communication']['post_initiative_actions'] as $action)
					{
						if (is_object($action))
						{
							$action_ids[] = $action->getId();
							$actions[] = $action->getTypeId();	
						}
					} 
				}
				
				if ($actions_db = app_domain_Action::findCurrentByPostInitiativeId($this->request->post_initiative_id))
				{
					foreach($actions_db as $action_db)
					{
						if (!in_array($action_db->getId(), $action_ids))
						{
							$actions[] = $action_db->getTypeId();
						}
					} 
				}
				
				$this->request->post_initiative_actions = $actions;
				break;
			default:
				break;
		}
		
		// Return result data
//		// Update the item_id element of the request string in case we have added a
//		// new object. Useful to return the new id
//		if ($this->request->item_id == null)
//		{
//			$this->request->item_id = $this->communication->getId();
//		}
		
		array_push($this->response->data, $this->request);
	}

	protected function makeDisciplineRow($discipline_id, $discipline)
	{
		$return_data = new stdClass();
		
		// decision maker options
		$decison_maker_options = app_domain_Communication::lookupDecisonMakerOptions();
		
		// agency user options
		$agency_user_options = app_domain_Communication::lookupAgencyUserOptions();
				
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('discipline_id', $discipline_id);
		$smarty->assign('discipline', $discipline);
		$smarty->assign('decison_maker_options', $decison_maker_options);
		$smarty->assign('agency_user_options', $agency_user_options);
		
		$return_data->template = $smarty->fetch('html_CommunicationDisciplineRow.tpl');
				
		return $return_data;
	}
		
}

?>