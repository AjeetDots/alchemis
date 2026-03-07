<?php

/**
 * Defines the app_command_WorkspacePostInitiative class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');
require_once('app/domain/InformationRequest.php');
require_once('app/mapper/InformationRequestMapper.php');

/**
 * @package Alchemis
 */
class app_command_WorkspacePostInitiative extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Get request parameters
		$post_id = $request->getProperty('post_id');
		$request->setObject('post_id', $post_id);
		
		$initiative_id = $request->getProperty('initiative_id');
		$request->setObject('initiative_id', $initiative_id);
		
		$company_id = $request->getProperty('company_id');
		$request->setObject('company_id', $company_id);
			
//		echo '$company_id = ' . $company_id . '<br />';
//		echo '$post_id = ' . $post_id . '<br />';
//		echo '$initiative_id = ' . $initiative_id . '<br />';
		
		// ------------------------------------
		if ($post_initiatives = app_domain_Post::findPostInitiatives($post_id))
		{
			$options = array();
			foreach ($post_initiatives as $item)
			{
				$options[$item['post_initiative_id']] = @C_String::htmlDisplay(ucfirst($item['client_name'] . ': ' . $item['initiative_name']));
			}
		}
		$request->setObject('post_initiatives', $options);
		
		// if no post initiatives then show list of client initiatives for user to choose to add one
		if (count($post_initiatives) == 0)
		{
//				$client_initiatives = app_domain_Client::findAllClientInitiatives();
			if ($items = app_domain_Client::findAllClientInitiatives())
			{
				$options = array();
				foreach ($items as $item)
				{
					$options[$item['initiative_id']] = @C_String::htmlDisplay(ucfirst($item['client_name'] . ': ' . $item['initiative_name']));
				}
			}
			$request->setObject('client_initiatives', $options);
			
		}
		
		
		// ------------------------------------
		if (!is_null($post_id) && !is_null($initiative_id))
		{
			
			$post_initiative = app_domain_PostInitiative::findByPostAndInitiative($post_id, $initiative_id);
			$request->setObject('post_initiative', $post_initiative);
			
			// get project ref tag list
			$project_refs = app_domain_PostInitiative::findTagsByPostInitiativeIdAndCategoryId($post_initiative->getId(), 3);
			$request->setObject('project_refs', $project_refs);
			
//			echo '<pre>';
//			print_r($project_refs);
//			echo '</pre>';
						
			//get meeting information
			$meetings = app_domain_Meeting::findByPostInitiativeId($post_initiative->getId());
			$request->setObject('meetings', $meetings);
			
//			echo 'Meetings:<br />';
//			echo '<pre>';
//			print_r($meetings);
//			echo '</pre>';

			//get information request information
			$information_requests = app_domain_InformationRequest::findByPostInitiativeId($post_initiative->getId());
			$request->setObject('information_requests', $information_requests);
		}
		else
		{
			die("Insufficient request variables supplied.");
		}
		

		return self::statuses('CMD_OK');
	}
}

?>