<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_PostInitiative objects.
 * @package alchemis
 */
class app_command_AjaxPostInitiative extends app_command_AjaxCommand
{
	
	protected $post_initiative;
//	protected $contact;
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		// Instantiate the object
		$post_initiative_id = $this->request->item_id;
			
		switch ($this->request->cmd_action)
		{
			case 'add_post_initiative':
			case 'add_post_initiative_with_call':
                // check if a post initiative already exists for this post/initiative combo
				if ($this->post_initiative = app_domain_PostInitiative::findByPostAndInitiative($this->request->post_id, $this->request->initiative_id))
				{
					// do nothing - a post initiative already exists
					$this->request->currently_exists = true;
				}
				else
				{
                    $post = app_model_Post::with('contact')->where('id', $this->request->post_id)->first();

                    if ($post && $post->contact->linked_in) {
                        $data_source = app_model_DataSource::where('description', 'Linked-in')->first();
                    } else {
                        $data_source = app_model_DataSource::where('description', 'Colleague suggested')->first();
                    }
					// create new post initiative domain object					
                    $this->post_initiative = new app_domain_PostInitiative();
					// set properties of the newly created post initiative
                    $this->post_initiative->setPostId($this->request->post_id);
                    $this->post_initiative->setInitiativeId($this->request->initiative_id);
                    $this->post_initiative->setStatusId(7);
                    $this->post_initiative->setNextActionBy(1);
                    $this->post_initiative->setLeadSourceId(7);
                    $this->post_initiative->setDataSourceId($data_source->id);
                    $this->post_initiative->setDataSourceChangedDate(Utils::getTimestamp());
                    $this->post_initiative->commit();
                    $this->request->currently_exists = false;
                    
                    $post_initiative_model = app_model_PostInitiative::where('id', $this->post_initiative->getId())->first();
                    $current_tag = $post_initiative_model->tags()->wherePivot('data_source', true)->first();

                    if ($current_tag) {
                        $current_tag->value = $data_source->description;
                        $current_tag->save();
                    } else {
                        $tag = new app_model_Tag(['value' => $data_source->description, 'category_id' => 3]);
                        $post_initiative_model->tags()->save($tag, ['data_source' => true]);
                    }
				}
				$this->request->post_initiative_id = $this->post_initiative->getId();
				break;
			case 'display_meetings':
				$this->request->meetings_list = $this->displayMeetings($this->request->item_id, $this->request->company_id);
				break;
			case 'display_information_requests':
				$this->request->information_requests_list = $this->displayInformationRequests($this->request->item_id, $this->request->post_id, $this->request->company_id, $this->request->source_tab);
				break;
			case 'delete_last_call':
				$this->request->return_data = $this->deleteLastCall($this->request->post_initiative_id);
				break;	
			case 'display_project_refs':
				$this->request->return_data = $this->displayProjectRefs($post_initiative_id);
				break;		
			default:
				break;
		}
		
		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
		if ($this->request->item_id == null && isset($this->post_initiative))
		{
			$this->request->item_id = $this->post_initiative->getId();
		}
		
		array_push($this->response->data, $this->request);
		
	}
	
	protected function displayMeetings($post_initiative_id, $company_id)
	{
		//get meeting information
		$meetings = new app_mapper_MeetingCollection();
		
		$meetings_db = app_domain_Meeting::findByPostInitiativeId($post_initiative_id);
		if ($meetings_db)
		{
			foreach($meetings_db as $meeting)
			{
				$meetings->add($meeting);
			} 
		}
		
		if(isset($_SESSION['auth_session']['communication']['meetings']))
		{
			foreach($_SESSION['auth_session']['communication']['meetings'] as $meeting)
			{
				$meetings->add($meeting);
			} 
		}
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('meetings', $meetings);
		$smarty->assign('post_initiative_id', $post_initiative_id);
		$smarty->assign('company_id', $company_id);
		
		
		$return_data = new stdClass();
		
		$return_data->template = $smarty->fetch('html_MeetingsList.tpl');
		$return_data->meeting_count = $meetings->count();
		
		return $return_data;
	}
	
	protected function displayInformationRequests($post_initiative_id, $post_id, $company_id, $source_tab)
	{
		//get information request data
		$information_requests = new app_mapper_InformationRequestCollection();
		
		if ($information_requests_db = app_domain_InformationRequest::findByPostInitiativeId($post_initiative_id));
//		if ($information_requests_db)
		{
			foreach($information_requests_db as $information_request)
			{
				$information_requests->add($information_request);
			} 
		}
		
		if(isset($_SESSION['auth_session']['communication']['information_requests']))
		{
			foreach($_SESSION['auth_session']['communication']['information_requests'] as $information_request)
			{
				$information_requests->add($information_request);
			} 
		}
		
		if ($post_initiative_id != '')
		{
			$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
		}
		else
		{
			$post_initiative = '';
		}
		
		$company = app_domain_Company::find($company_id);
		$post = app_domain_Post::find($post_id);
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('information_requests', $information_requests);
		$smarty->assign('post_initiative', $post_initiative);
		$smarty->assign('company', $company);
		$smarty->assign('post', $post);
		$smarty->assign('source_tab', $source_tab);
		
		$return_data = new stdClass();
		
		$return_data->template = $smarty->fetch('html_InformationRequestsList.tpl');
		$return_data->information_request_count = $information_requests->count();
		
		return $return_data;
	}
	
	protected function deleteLastCall($post_initiative_id)
	{
		$result = true;
		$do_delete_communication = false;
		$found_information_request = false;
		$found_meeting = false;
		$found_actions = false;
		
		//get max communication id
		$max_communication_id = app_domain_Communication::doFindByPostInitiativeIdAndTypeId($post_initiative_id, 1);
		if (is_null($max_communication_id))
		{
			$result = false;
			$feedback = 'No communications found';
		}
		else
		{
			$communication_to_delete = app_domain_Communication::find($max_communication_id);
			//check that we are able to process this - ie check previous communication wasn't meeting related
			switch ($communication_to_delete->getStatusId())
			{
				case 12: //meet set
				case 13: //f/up meet set
					// delete the meeting - if there's a meeting where communication id = $max_communication_id
					$meeting = app_domain_Meeting::findByCommunicationId($max_communication_id);
					if (!is_object($meeting))
					{
//						$result = false;
//						$feedback = 'No associated meeting could be found for the communication ' . $max_communication_id;
						// do nothing 
					}
					else
					{
						$found_meeting = true;
					}
					// delete any meeting actions for that meeting
					if (is_object($meeting))
					{
						if ($actions = app_domain_Action::findByMeetingId($meeting->getId()))
						{
							$found_actions = true;
						}
					}
					break;
				case 14:
				case 15:
				case 16:
				case 17:
				case 18:
				case 19:
				case 20:
				case 21:
				case 22:
				case 23:
				case 24:
				case 25:
				case 26:
				case 27:
				case 28:
				case 29:
				case 30:
				case 31:
				case 32:
					$result = false;
					$feedback = 'Status of last telephone communication (' . $communication_to_delete->getStatusId() . ') is meeting related so the communication cannot be deleted'; 
					break;
				default:
					break;
			}
				
			// is there an information request associated with $max_communication_id
			if ($information_requests = app_domain_Action::findByCommunicationIdAndTypeId($max_communication_id, 2))
			{
				$found_information_request = true;
			}				
			
			//get previous communication id
			$previous_communication_id = app_domain_Communication::findPreviousByPostInitiativeIdAndCommunicationId($post_initiative_id, $max_communication_id);

			if (is_null($previous_communication_id))
			{
				// set all post_initiative information to null
				$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
				$post_initiative->setStatusId(7); //fresh lead
				$post_initiative->setComment(null);
				$post_initiative->setLastCommunicationId(null);
				$post_initiative->setLastEffectiveCommunicationId(null);
				$post_initiative->setNextCommunicationDate(null);
				$post_initiative->setLastMailerCommunicationId(null);
				$post_initiative->setNextActionBy(1);
				
				$do_delete_communication = true;
			}
			else
			{
				$previous_communication = app_domain_Communication::find($previous_communication_id);
				
				if ($result)
				{
					//set post_initiative information equal to that in the previous communication
					$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
					$post_initiative->setStatusId($previous_communication->getStatusId()); 
					$post_initiative->setComment($previous_communication->getComments());
					$post_initiative->setLastCommunicationId($previous_communication_id);
					if ($previous_communication->getIsEffective())
					{
						$post_initiative->setLastEffectiveCommunicationId($previous_communication_id);
					}
					else
					{
						$previous_effective_communication_id = app_domain_Communication::findPreviousEffectiveByPostInitiativeIdAndCommunicationId($post_initiative_id, $max_communication_id);
						$post_initiative->setLastEffectiveCommunicationId($previous_effective_communication_id);
					}
					$post_initiative->setNextCommunicationDate($previous_communication->getNextCommunicationDate());
					$previous_mailer_communication_id = app_domain_Communication::findPreviousByCommunicationIdAndTypeId($max_communication_id, 5);
					$post_initiative->setLastMailerCommunicationId($previous_mailer_communication_id);
//					$post_initiative->setLeadSourceId($previous_communication->getLeadSourceId());
					$post_initiative->setNextActionBy($previous_communication->getNextActionBy());
					
					$do_delete_communication = true;
					
				}
			}
			
			// now delete the $max_communication_id
			if ($do_delete_communication)
			{
				// update the post initiative
				$post_initiative->commit();
				
				// delete associated notes
				// NOTE: the communication to be deleted may not have a note - in which case we need to 
				// skip this section
				if (!is_null($communication_to_delete->getNoteId()))
				{
					$note = app_domain_PostInitiativeNote::find($communication_to_delete->getNoteId());
					$note->markDeleted();
					$note->commit();
				}
				
				// delete information request
				if ($found_information_request)
				{
					foreach ($information_requests as $information_request)
					{
						$information_request->markDeleted();
						$information_request->commit();
					}			
				}
				
				// delete meeting
				if ($found_meeting)
				{
					$meeting->markDeleted();
					$meeting->commit();
				}
				
				// delete actions
				if ($found_actions)
				{
					foreach ($actions as $action)
					{
						$action->markDeleted();
						$action->commit();
					}					
				}
								
				// delete any post decision maker records
				app_domain_PostDecisionMaker::setCommunicationIdNullByCommunicationId($max_communication_id);
				
				// delete any post agency user records
				app_domain_PostAgencyUser::setCommunicationIdNullByCommunicationId($max_communication_id);
				
				// delete any post discipline review date records
				app_domain_PostDisciplineReviewDate::setCommunicationIdNullByCommunicationId($max_communication_id);
				
				// delete any post incumbent agency records
				app_domain_PostIncumbentAgency::setCommunicationIdNullByCommunicationId($max_communication_id);
				
				// delete the communication
				$communication_to_delete->markDeleted();
				$communication_to_delete->commit();
			}		
		}
	
		
		$return_data = new stdClass();
		$return_data->result = $result;
		$return_data->feedback = $feedback;
		
		if ($result)
		{
			$return_data->post_id = $post_initiative->getPostId();
			$return_data->initiative_id = $post_initiative->getInitiativeId();
			$return_data->post_initiative_id = $post_initiative_id;
		}
		return $return_data;
		
	}
	
	function displayProjectRefs($post_initiative_id)
	{
		$project_refs = app_domain_PostInitiative::findTagsByPostInitiativeIdAndCategoryId($post_initiative_id, 3);
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('project_refs', $project_refs);
		$return_data = new stdClass();
		$return_data->template = $smarty->fetch('html_PostInitiativeProjectRefs.tpl');
		return $return_data;
	}
}

?>