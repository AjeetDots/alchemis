<?php

/**
 * Defines the app_command_WorkspacePost class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_WorkspacePost extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Get request parameters
		$post_id = $request->getProperty('id');
		$company_id = $request->getProperty('company_id');
		$request->setObject('company_id', $company_id);
		
		
		
		if (!is_null($post_id))
		{
			$post = app_domain_Post::find($post_id);
			$request->setObject('post', $post);
			
			if ($post)
			{
				$contact = app_domain_Contact::findCurrentByPostId($post_id);
				$request->setObject('contact', $contact);
			}
			
//			$post_initiatives = app_domain_Post::findPostInitiatives($post_id);
			
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
		}
		

//		echo "<pre>";
//		print_r($post_initiatives);
//		echo "</pre>";
		
		return self::statuses('CMD_OK');
	}
}

?>