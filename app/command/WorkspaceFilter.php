<?php

/**
 * Defines the app_command_WorkspaceFilter class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');
require_once('app/domain/InformationRequest.php');
require_once('app/mapper/InformationRequestMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_WorkspaceFilter extends app_command_Command
{
	
	protected $company_id;
	protected $company;
	protected $post_id;
	protected $post;
	protected $contact_id;
	protected $contact;
	protected $post_initiative;
	protected $post_initiatives;
	protected $initiative_id;
	
	public function doExecute(app_controller_Request $request)
	{
		if ($request->getProperty('id') == '' || is_null($request->getProperty('id')))
		{
			// do nothing
		}
		else
		{
			// Parameters
			$this->company_id  = $request->getProperty('id');
			$this->post_id = $request->getProperty('post_id');
			
			$this->makeWorkspaceCompany($request);
			
			$this->makeWorkspacePost($request);
	
			$this->makeWorkspacePostInitiative($request);
			
			$this->makeWorkspaceCompanyInitiatives($request);
					
			$this->makeWorkspaceNotes($request);
					
			$request->setObject('company_id', $this->company_id);
			$request->setObject('company', $this->company);
			$request->setObject('post_id', $this->post_id);
			$request->setObject('post', $this->post);
			$request->setObject('contact_id', $this->contact_id);
			$request->setObject('contact', $this->contact);
		}
		return self::statuses('CMD_OK');
	}


	protected function makeWorkspaceCompany($request)
	{
		
		// Get company
		$this->company = app_domain_Company::find($this->company_id);
		
		// Get company note count		
		$company_note_count = app_domain_CompanyNote::findCountByCompanyId($this->company_id);
		$request->setProperty('company_note_count', $company_note_count);
		
		// Get post lists
		$company_posts_job_title = app_domain_Company::findPostsOrderByJobTitle($this->company_id);
		$request->setObject('company_posts_job_title', $company_posts_job_title);
		
		// Get clients contacted for this post
		if (is_null($this->post_id) || $this->post_id == '' || $this->post_id == null || $this->post_id == 'null')
		{
			if (count($company_posts_job_title) > 0)
			{
				$this->post_id = $company_posts_job_title[0]['id'];
			}
			else
			{
				$this->post_id = null;
			}
		}
		
		if (!is_null($this->post_id))
		{
			$this->post = app_domain_Post::find($this->post_id);
		}
	}
	
	protected function makeWorkspacePost($request)
	{
		if (!is_null($this->post_id))
		{
				
			// Get company note count		
			$post_note_count = app_domain_PostNote::findCountByPostId($this->post_id);
			$request->setProperty('post_note_count', $post_note_count);
		
			$this->contact = app_domain_Contact::findCurrentByPostId($this->post_id);
		}
	}
	
	protected function makeWorkspacePostInitiative($request)
	{
		if (!is_null($this->post_id))
		{
			$options = array();
			$selected_option = null;
			$this->initiative_id = null;
				
			$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);

			if ($request->getProperty('initiative_id') != '')
			{
				$this->initiative_id = $request->getProperty('initiative_id');
			}
			
			if (count($post_initiatives) > 0)
			{
				if (is_null($this->initiative_id))
				{	
					$this->initiative_id = $post_initiatives[0]['initiative_id'];
				}
								
				$found_default_initiative = false;
				foreach ($post_initiatives as $item)
				{
					$options[$item['post_initiative_id']] = @C_String::htmlDisplay(ucfirst($item['client_name'] . ': ' . $item['initiative_name'] . ' ('. $item['status'] . ')'));
					if ($item['initiative_id'] == $this->initiative_id)
					{
						$selected_option = $item['post_initiative_id'];
						$found_default_initiative = true; 
					}
				}
				
				if (!$found_default_initiative)
				{
					// DMC: 2 new lines added 12/01/2009
					$options[0] = 'No records for default initiative';
					$selected_option = 0;
					
					// DMC: following line removed 12/01/2009
					//$this->initiative_id = $post_initiatives[0]['initiative_id'];
				}
			}

			
			$request->setObject('initiative_id', $this->initiative_id);
			$request->setObject('post_initiatives_options', $options);
			$request->setObject('post_initiatives_selected_option', $selected_option);
			
//			echo '$this->post_id = ' . $this->post_id . '<br />$this->initiative_id = ' . $this->initiative_id;
			if (!is_null($this->post_id) && !is_null($this->initiative_id))
			{
				$this->post_initiative = app_domain_PostInitiative::findByPostAndInitiativeForCurrentUser($this->post_id, $this->initiative_id);
				
				if (!is_object($this->post_initiative))
				{
			
				}
				else
				{
				
					$request->setObject('post_initiative', $this->post_initiative);
					
					// get project ref tag list
					$project_refs = app_domain_PostInitiative::findTagsByPostInitiativeIdAndCategoryId($this->post_initiative->getId(), 3);
					$request->setObject('project_refs', $project_refs);
								
					//get meeting information
					$meetings = app_domain_Meeting::findByPostInitiativeId($this->post_initiative->getId());
					if ($meetings->count() > 0)
					{
						$request->setObject('meetings', $meetings);
					}
					
					//get actions information
					$actions = app_domain_Action::findCurrentCountByPostInitiativeId($this->post_initiative->getId());
					if ($actions > 0)
					{
						$request->setProperty('actions', $actions);
					}
					
					//get overdue actions information
					$overdue_actions = app_domain_Action::findOverdueCountByPostInitiativeId($this->post_initiative->getId());
					if ($overdue_actions > 0)
					{
						$request->setProperty('overdue_actions', $overdue_actions);
					}
					
					//get information request information
					$information_requests = app_domain_InformationRequest::findByPostInitiativeId($this->post_initiative->getId());
					if ($information_requests->count() > 0)
					{
						$request->setObject('information_requests', $information_requests);
					}
				}
				
				$company_do_not_call = app_domain_CampaignCompanyDoNotCall::isCompanyDoNotCall($this->initiative_id, $this->company_id);
				$request->setProperty('company_do_not_call', $company_do_not_call);
				
//				if (app_domain_Campaign::isCompanyDoNotCall($this->initiative_id, $this->company_id))
//				{
//					$request->setProperty('company_do_not_call', true);
//				}
//				else
//				{
//					$request->setProperty('company_do_not_call', false);
//				}
				
			}
		}
	}
	
	protected function makeWorkspaceCompanyInitiatives($request)
	{
		$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
		
		if (!is_null($this->initiative_id))
		{
			if (count($post_initiatives) > 0)
			{
				$found_default_initiative = false;
				foreach ($post_initiatives as $item)
				{
					if ($item['initiative_id'] == $this->initiative_id)
					{
						$found_default_initiative = true;
					}
				}
				
				if (!$found_default_initiative)
				{
//					$this->initiative_id = $post_initiatives[0]['initiative_id'];	
				}
				else
				{
					$this->initiative_id = $this->initiative_id;					
					
				}
			}
			
			$posts = app_domain_Post::findPostsByCompanyAndInitiativeForCurrentUser($this->company_id, $this->initiative_id, $this->post_id);
			$request->setObject('posts', $posts);
		}
		else
		{
			if (count($post_initiatives) > 0)
			{
				$this->initiative_id = $post_initiatives[0]['initiative_id'];
			}
			else
			{
				$this->initiative_id = null;
			}
		}
		
		if (! is_null($this->initiative_id))
		{
			$initiative_name = app_domain_initiative::findClientInitiativeNameById($this->initiative_id);
			$request->setProperty('initiative_name', $initiative_name);
		}	
	}

	/**
	 * @param app_controller_Request $request
	 */
	protected function makeWorkspaceNotes(app_controller_Request $request)
	{
		if (!is_null($this->post_initiative))
		{
			$notes = app_domain_PostInitiative::findNotes($this->post_initiative->getId());
			$request->setObject('notes', $notes);
		}
	}

}
?>