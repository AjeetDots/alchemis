<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Post.php');
require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');
require_once('app/domain/InformationRequest.php');
require_once('app/mapper/InformationRequestMapper.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Command class to handle Ajax operations on app_domain_Post objects.
 * @package alchemis
 */
class app_command_AjaxPost extends app_command_AjaxCommand
{
	
	protected $post;
	protected $contact;
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
				
		// echo '<pre>';
		// print_r($this->request);
		// echo '</pre>';
		// echo $this->request->cmd_action;
		
		switch ($this->request->cmd_action)
		{
			case 'edit_telephone_1':
				// Instantiate the object
				$post_id = $this->request->item_id;
					
				if ($post_id)
				{
					$this->post = app_domain_Post::find($post_id);
				}
				else
				{
					$this->post = new app_domain_Post();
				} 
		
				// call the relevant accessors or mutators
                // $this->post->setTelephone1(mb_convert_encoding($this->request->telephone_1, 'ISO-8859-1', 'UTF-8'));
				$this->post->setTelephone1($this->request->telephone_1);
				$this->post->commit();
				
				break;
			case 'update_additional_info':
				$post_id = $this->request->item_id;
			
				if ($post_id)
				{
					$this->post = app_domain_Post::find($post_id);
				}
				else
				{
					$this->post = new app_domain_Post();
				} 
				
				// call the relevant accessors or mutators
				// $this->company->setAdditionalInfo(mb_convert_encoding($this->request->additional_info, 'ISO-8859-1', 'UTF-8'));
				$this->post->setAdditionalInfo($this->request->additional_info);
				$this->post->commit();
				$this->request->post_detail = $this->getPostDetail();
				break;
			case 'edit_telephone_mobile':
				$post_id = $this->request->item_id;
				if ($post_id)
				{
						$this->post = app_domain_Post::find($post_id);
						$this->contact = app_domain_Contact::findCurrentByPostId($post_id);
				}
				else
				{
						$this->post = new app_domain_Post();
						$this->contact = new app_domain_Contact();
				} 
				if (empty($this->contact))
				{
						$this->contact = new app_domain_Contact();
						$this->contact->setPostId($post_id);
				}
				$this->contact->setTelephoneMobile($this->request->telephone_mobile);
				$this->contact->commit();
				$this->request->telephone_mobile = $this->contact->getTelephoneMobile();
				break;
			case 'edit_email':
				// Instantiate the object
				$post_id = $this->request->item_id;
					
				if ($post_id)
				{
					$this->post = app_domain_Post::find($post_id);
					$this->contact = app_domain_Contact::findCurrentByPostId($post_id);
				}
				else
				{
					$this->post = new app_domain_Post();
					$this->contact = new app_domain_Contact();
				} 
				// call the relevant accessors or mutators
				if (empty($this->contact))
				{
					$this->contact = new app_domain_Contact();
					$this->contact->setPostId($post_id);
				}
				// $this->contact->setEmail(mb_convert_encoding($this->request->email, 'ISO-8859-1', 'UTF-8'));
				$this->contact->setEmail($this->request->email);
				
				$this->contact->commit();
				
				break;
			case 'edit_linked_in':
					// Instantiate the object
					$post_id = $this->request->item_id;
						
					if ($post_id)
					{
						$this->post = app_domain_Post::find($post_id);
						$this->contact = app_domain_Contact::findCurrentByPostId($post_id);
					}
					else
					{
						$this->post = new app_domain_Post();
						$this->contact = new app_domain_Contact();
					}
					// call the relevant accessors or mutators
					if (empty($this->contact))
					{
						$this->contact = new app_domain_Contact();
						$this->contact->setPostId($post_id);
					}
					//				$this->contact->setEmail(mb_convert_encoding($this->request->email, 'ISO-8859-1', 'UTF-8'));
					$this->contact->setLinkedIn($this->request->linked_in);
					$this->contact->commit();
					$this->request->linked_in = $this->contact->getLinkedIn();
					break;
			case 'get_post_detail':
				// Instantiate the object
				$post_id = $this->request->item_id;
					
				if ($post_id)
				{
					$this->post = app_domain_Post::find($post_id);
					$this->contact = app_domain_Contact::findCurrentByPostId($post_id);
					if (!$this->contact)
					{
						$this->contact = new app_domain_Contact();
					}
				}
				else
				{
					$this->post = new app_domain_Post();
					$this->contact = new app_domain_Contact();
				} 
				
				if ($this->request->initiative_id == 'null' || $this->request->initiative_id == '' || $this->request->initiative_id == null
					|| is_null($this->request->initiative_id))
				{
					$this->initiative_id = null;
				}
				else
				{
					$this->initiative_id = $this->request->initiative_id;
				}
				
				if ($this->request->post_initiative_id == 'null' || $this->request->post_initiative_id == '' || $this->request->post_initiative_id == null
					|| is_null($this->request->post_initiative_id))
				{
					$this->post_initiative_id = null;
				}
				else
				{
					$this->post_initiative_id = $this->request->post_initiative_id;
				}
				
				
				
				$this->request->post_detail = $this->getPostDetail();
				break;
			case 'get_post_initiative_detail':
				// Instantiate the object
				$post_id = $this->request->item_id;
					
				if ($post_id)
				{
					$this->post_id = $post_id;
					$this->post = app_domain_Post::find($post_id);
				}
				else
				{
					$this->post = new app_domain_Post();
				} 
						
				if ($this->request->initiative_id == 'null' || $this->request->initiative_id == '' || $this->request->initiative_id == null
					|| is_null($this->request->initiative_id))
				{
					$this->initiative_id = null;
				}
				else
				{
					$this->initiative_id = $this->request->initiative_id;
				}
				
				if ($this->request->post_initiative_id == 'null' || $this->request->post_initiative_id == '' || $this->request->post_initiative_id == null
					|| is_null($this->request->post_initiative_id))
				{
					$this->post_initiative_id = null;
				}
				else
				{
					$this->post_initiative_id = $this->request->post_initiative_id;
				}
				
                /*
				 first we call getRecordIds to make sure we are using the 'correct' post, initiative
				 and/or post intitiative records. The reason for this is that we may be passing in either:
				   1. a post id and/or
				   2. an initiative_id and/or 
				   3. a post initiative id
				
				 If we have passed a post initiative id then this takes priority over any post or initiative
				 ids which are also passed in. ie we extract the post and initiative ids from the post initiative
				 record.
				 If only a post is passed in without an initiative then we only use that post id
				 If a post AND initiative are both passed in then we need to the following should happen:
				   1. See if a post initiative record exists for the post/initiative combo (to which the current
				      user has access)
				   2. If a relevant post initiative record does exists then need to use this post initiative id
				   3. If there is no relevant post initiative record then we need to see there are any other 
				      post initiatiave records for this post/initiative combo (to which the user does have access)
						If there is one or more records then we should use the id for the 'first' one of them
                */
				
				$this->getRecordIds();
				$this->request->post_initiative_detail = $this->getPostInitiativeDetail();
				break;
			case 'get_company_initiatives_detail':
				// Instantiate the object
				$post_id = $this->request->item_id;
					
				if ($post_id)
				{
					$this->post_id = $post_id;
					$this->post = app_domain_Post::find($post_id);
				}
				else
				{
					$this->post = new app_domain_Post();
				} 
				
				if ($this->request->initiative_id == 'null' || $this->request->initiative_id == '' || $this->request->initiative_id == null
					|| is_null($this->request->initiative_id))
				{
					$this->initiative_id = null;
				}
				else
				{
					$this->initiative_id = $this->request->initiative_id;
				}
				
				if ($this->request->post_initiative_id == 'null' || $this->request->post_initiative_id == '' || $this->request->post_initiative_id == null
					|| is_null($this->request->post_initiative_id))
				{
					$this->post_initiative_id = null;
				}
				else
				{
					$this->post_initiative_id = $this->request->post_initiative_id;
				}
                /*
				 first we call getRecordIds to make sure we are using the 'correct' post, initiative
				 and/or post intitiative records. The reason for this is that we may be passing in either:
				   1. a post id and/or
				   2. an initiative_id and/or 
				   3. a post initiative id
				
				 If we have passed a post initiative id then this takes priority over any post or initiative
				 ids which are also passed in. ie we extract the post and initiative ids from the post initiative
				 record.
				 If only a post is passed in without an initiative then we only use that post id
				 If a post AND initiative are both passed in then we need to the following should happen:
				   1. See if a post initiative record exists for the post/initiative combo (to which the current
				      user has access)
				   2. If a relevant post initiative record does exists then need to use this post initiative id
				   3. If there is no relevant post initiative record then we need to see there are any other 
				      post initiatiave records for this post/initiative combo (to which the user does have access)
						If there is one or more records then we should use the id for the 'first' one of them
                */
				$this->getRecordIds();
				$this->request->company_initiatives_detail = $this->getCompanyInitiativesDetail();
				break;
			case 'get_workspace_notes':
				
				$this->post_id = $this->request->item_id;
				
				if ($this->request->initiative_id == 'null' || $this->request->initiative_id == '' || $this->request->initiative_id == null
					|| is_null($this->request->initiative_id))
				{
					$this->initiative_id = null;
				}
				else
				{
					$this->initiative_id = $this->request->initiative_id;
				}
				
				if ($this->request->post_initiative_id == 'null' || $this->request->post_initiative_id == '' || $this->request->post_initiative_id == null
					|| is_null($this->request->post_initiative_id))
				{
					$this->post_initiative_id = null;
				}
				else
				{
					$this->post_initiative_id = $this->request->post_initiative_id;
				}
                /*
				 first we call getRecordIds to make sure we are using the 'correct' post, initiative
				 and/or post intitiative records. The reason for this is that we may be passing in either:
				   1. a post id and/or
				   2. an initiative_id and/or 
				   3. a post initiative id
				
				 If we have passed a post initiative id then this takes priority over any post or initiative
				 ids which are also passed in. ie we extract the post and initiative ids from the post initiative
				 record.
				 If only a post is passed in without an initiative then we only use that post id
				 If a post AND initiative are both passed in then we need to the following should happen:
				   1. See if a post initiative record exists for the post/initiative combo (to which the current
				      user has access)
				   2. If a relevant post initiative record does exists then need to use this post initiative id
				   3. If there is no relevant post initiative record then we need to see there are any other 
				      post initiatiave records for this post/initiative combo (to which the user does have access)
						If there is one or more records then we should use the id for the 'first' one of them
                */
				$this->getRecordIds();
				$this->request->workspace_notes = $this->getWorkspaceNotes();
				break;
			case 'update_note':
				$this->note_id = $this->request->item_id;
			
				if ($this->note_id)
				{
					$this->note = app_domain_PostNote::find($this->note_id);
				}
				else
				{
					// raise error
				} 
				
				// call the relevant accessors or mutators
				// exit();

				$this->note->setNote($this->request->note);
				// $this->note->setNote(mb_convert_encoding($this->request->note, 'ISO-8859-1', 'UTF-8'));
				// $this->note->setNote(mb_convert_encoding($this->request->note, 'ISO-8859-1', 'HTML-ENTITIES'));
				$this->note->commit();
				break;
				
			default:
				break;
		}
		
		// throw new Exception('Stop');
		
		// echo '<pre>';
		// print_r($this->request);
		// echo '</pre>';
		
		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
		if ($this->request->item_id == null)
		{
			$this->request->item_id = $this->post->getId();
		}

        // echo '<pre>';
        // print_r($this->request);
        // echo '</pre>';
		
		// print_r($this->request);
        // die('Here');
		 
		array_push($this->response->data, $this->request);
		
		// echo '<pre>';
		// print_r($this->response->data);
		// echo '</pre>';
		
		// array_push($this->response->data, );
		
	}
	
	protected function getPostDetail()
	{
		$return_data = new stdClass();
		
		// Get company note count		
		$post_note_count = app_domain_PostNote::findCountByPostId($this->post->getId());
				
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('post', $this->post);
		$smarty->assign('contact', $this->contact);

		
		$tpsResponse = app_lib_Tps::check($this->post->getTelephone1(),(isset($this->request->refresh_number) && $this->request->refresh_number)?TRUE:FALSE);
		$smarty->assign('postTelephoneTpsStatus', (array)$tpsResponse);
		
        // $return_data->template = mb_convert_encoding($smarty->fetch('WorkspacePost.tpl'), 'UTF-8', 'HTML-ENTITIES');
		$return_data->template = $smarty->fetch('WorkspacePost.tpl');
		
		$return_data->post_note_count = $post_note_count;
		
		return $return_data;
	}
	
	
	protected function getPostInitiativeDetail()
	{
		$return_data = new stdClass();
		$post_initiative = app_domain_PostInitiative::find($this->post_initiative_id);		
		if (is_object($post_initiative))
		{					
			// set $this->initiative_id equal to the inititive_id of $this->post_initiative_id since this must be the 
			// initiative the user wishes to work with. If we don't do this then we will always set the default of the 
			// post_initiative_id dropdown list (in the post intiaitive screen) to that of teh Default Initiative.
			$this->initiative_id = $post_initiative->getInitiativeId();
			
			// get project ref tag list
			$project_refs = app_domain_PostInitiative::findTagsByPostInitiativeIdAndCategoryId($post_initiative->getId(), 3);
						
			//get meeting information
			$meetings = app_domain_Meeting::findByPostInitiativeId($post_initiative->getId());
			
			//get actions information
			$actions = app_domain_Action::findCurrentCountByPostInitiativeId($post_initiative->getId());
				
			//get overdue actions information
			$overdue_actions = app_domain_Action::findOverdueCountByPostInitiativeId($post_initiative->getId());
			
			//get information request information
			$information_requests = app_domain_InformationRequest::findByPostInitiativeId($post_initiative->getId());
		}
		else
		{
			//Need to bail out here as no post_initiative found
			$post_initiative = null; 
		}		
				
		$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
		$options = array();
		$selected_option = null;
		if (count($post_initiatives) > 0)
		{
			$this->found_default_initiative = false;
			foreach ($post_initiatives as $item)
			{
				$options[$item['post_initiative_id']] = @C_String::htmlDisplay(ucfirst($item['client_name'] . ': ' . $item['initiative_name'] . ' ('. $item['status'] . ')'));
				if ($item['initiative_id'] == $this->initiative_id)
				{	
					$this->found_default_initiative = true;
					$selected_option = $item['post_initiative_id']; 
				}
			}
			
			// DMC: new section 12/01/2009
			if (!$this->found_default_initiative)
			{
				$options[0] = 'No records for default initiative';
				$selected_option = 0;
			}
			// End new section-----------------------
		}
		
		$company_id = $this->post->getCompanyId();
		$company = app_domain_Company::find($company_id);
		$company_do_not_call = app_domain_CampaignCompanyDoNotCall::isCompanyDoNotCall($this->initiative_id, $company_id);
			
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		// workspace post initiatives screen vars
		$smarty->assign('company_id', $company_id);
		$smarty->assign('company', $company);
		$smarty->assign('post_id', $this->post_id);
		
		$smarty->assign('post_initiatives_options', $options);
		$smarty->assign('post_initiatives_selected_option', $selected_option);

		$smarty->assign('initiative_id',  $this->initiative_id);
		$smarty->assign('post_initiative_id',  $this->post_initiative_id);
		$smarty->assign('post_initiative',  $post_initiative);
		$smarty->assign('project_refs',  $project_refs);
		
		if($meetings)
		{
			if ($meetings->count() > 0)
			{
				$smarty->assign('meetings',  $meetings);
			}
		}
					
		if ($actions > 0)
		{
			$smarty->assign('actions', $actions);
		}
		
		if ($overdue_actions > 0)
		{
			$smarty->assign('overdue_actions', $overdue_actions);
		}
					
		if ($information_requests)
		{
			if ($information_requests->count() > 0)
			{
				$smarty->assign('information_requests',  $information_requests);
			}
		}

		// $return_data->template = mb_convert_encoding($smarty->fetch('WorkspacePostInitiative.tpl'), 'UTF-8', 'HTML-ENTITIES');
		$return_data->template = $smarty->fetch('WorkspacePostInitiative.tpl');

		$return_data->post_initiatives_count = count($post_initiatives);
		if($meetings)
		{
			if ($meetings->count() > 0)
			{
				$return_data->meetings = 1;		
			}
			else
			{
				$return_data->meetings = 0;
			}
		}
		$return_data->actions = $actions;
		$return_data->overdue_actions = $overdue_actions;
		$return_data->company_do_not_call = $company_do_not_call;
		
		return $return_data;
	}

	protected function getRecordIds()
	{
		// if we have a post initiative id then we should use this to calculate the initiative id
		// and post id				
		if (!is_null($this->post_initiative_id) || $this->post_initiative_id != null)
		{
			$post_initiative = app_domain_PostInitiative::find($this->post_initiative_id);
			if (is_object($post_initiative))
			{
				$this->post_id = $post_initiative->getPostId();
				// its safe to set the initiative id at this point since a post initiative
				// record does exist
				$this->initiative_id = $post_initiative->getInitiativeId();
			}
			else
			{
				throw new exception ("Invalid post initiative id supplied");
			}
		}
		else
		{
			// check we have a post available 
			if (is_null($this->post_id))
			{
				throw new exception ("No post id specified");
			}
			
			// work out the initiative_id
			if (is_null($this->initiative_id))
			{
				// see if there are any post initiative records for this post/user
				// combo 
				$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
				
				// get a default post_initiative_id
				if (count($post_initiatives) > 0)
				{
					$this->initiative_id = $post_initiatives[0]['initiative_id'];
					$this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
				}
				else
				{
					$this->initiative_id = null;
					$this->post_initiative_id = null;	
				}
				
			}
			else
			{
				// if an initiative is passed in then need to see if there is a 
				// post initiative record for this post/initiative combo available to the 
				// current user. If not, then we need to if there are any post initiative 
				// records for this post/user combo. If so then set the initiative id equal
				// to the first one of these
				$post_initiative = app_domain_PostInitiative::findByPostAndInitiativeForCurrentUser($this->post_id, $this->initiative_id);
				
				//need to do check to see if any other post intiatives exist for this user for this post
				if (!is_object($post_initiative))
				{	
					$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
					
					if (count($post_initiatives) > 0)
					{
						// DMC: new section 12/01/2009 -
						$this->found_default_initiative = false;
						foreach ($post_initiatives as $item)
						{
							if ($item['initiative_id'] == $this->initiative_id)
							{
								$this->found_default_initiative = true; 
								$this->initiative_id = $item['initiative_id'];
								$this->post_initiative_id = $item['post_initiative_id'];
							}
						}
						
						if (!$this->found_default_initiative)
						{
							// $this->initiative_id = $post_initiatives[0]['initiative_id'];
							// $this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
						}
					}
					else
					{
						$this->post_initiative_id = null;
					}
					
					// End section------------------
					
					// Removed section------------------------------
					// get a default post_initiative_id
					/* if (count($post_initiatives) > 0)
					{
						$this->initiative_id = $post_initiatives[0]['initiative_id'];
						$this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
						// $post_initiative = app_domain_PostInitiative::find($this->post_initiative_id);
					}
					else
					{
						$this->post_initiative_id = null;
					} */
					//-------------------------------
				}
				else
				{
					$this->post_initiative_id = $post_initiative->getId();
				}						
				
			}
		}	
		
	}
	
	protected function getCompanyInitiativesDetail()
	{
		$return_data = new stdClass();
		$posts = app_domain_Post::findPostsByCompanyAndInitiativeForCurrentUser($this->post->getCompanyId(), $this->initiative_id, $this->post->getId());

		//if no posts contacted for selected initiative then need to see if any other initiatives
		//exist for this post/user combo - if so then display posts for the first one of these
		
		
		// DMC: new section 12/01/2009 -
		if (count($posts) > 0)
		{
			$found_default_initiative = false;
			foreach ($posts as $item)
			{
				if ($item['initiative_id'] == $this->initiative_id)
				{
					$found_default_initiative = true; 
					// $this->initiative_id = $item['initiative_id'];
					// $this->post_initiative_id = $item['post_initiative_id'];
				}
			}
			
			if (!$found_default_initiative)
			{
				$posts = null;
			}
			
			// if ($found_default_initiative)
			// {
				// $this->initiative_id = $post_initiatives[0]['initiative_id'];
				// $this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
			// }
		}
		else
		{
		
		}
					
		// if (count($posts) == 0)
		// {
			// $post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
		// }					
		
		if (! is_null($this->initiative_id))
		{
			$initiative_name = app_domain_Initiative::findClientInitiativeNameById($this->initiative_id);
		}	
		
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		// workspace company initiatives screen vars
		$smarty->assign('post_id', $this->post->getId());
		$smarty->assign('posts',  $posts);
		$smarty->assign('initiative_name',  $initiative_name);
		
		// $return_data->template = mb_convert_encoding($smarty->fetch('WorkspaceCompanyInitiatives.tpl'), 'UTF-8', "HTML-ENTITIES");
		$return_data->template = $smarty->fetch('WorkspaceCompanyInitiatives.tpl');
				
		return $return_data;
	
	}
	
	protected function getWorkspaceNotes()
	{
		$return_data = new stdClass();
		
		$do_notes = true;
		$notes = app_domain_PostInitiative::findNotes($this->post_initiative_id);
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('post_initiative_id', $this->post_initiative_id);
		$smarty->assign('notes', $notes);
		
		$return_data->template = $smarty->fetch('html_WorkspaceNotes.tpl');
		// $return_data->template = mb_convert_encoding($smarty->fetch('html_WorkspaceNotes.tpl'), 'UTF-8', "HTML-ENTITIES");
		$return_data->post_initiative_id = $this->post_initiative_id;
			
		return $return_data;
	}
	

}

?>