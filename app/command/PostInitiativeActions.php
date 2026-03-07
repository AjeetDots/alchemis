<?php

/**
 * Defines the app_command_PostInitiativeActions class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
require_once('app/domain/Action.php');

/**
 * @package Alchemis
 */
class app_command_PostInitiativeActions extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$post_initiative_id = $request->getProperty('post_initiative_id');
		$request->setProperty('post_initiative_id', $post_initiative_id);
		
		$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
		$request->setObject('post', $post_initiative->getPost());
		
		$initiative_name = $post_initiative->getInitiative()->getClientName() . ': ' . $post_initiative->getInitiative()->getName(); 
		$request->setProperty('initiative_name', $initiative_name);		
		
		$request->setProperty('referrer_type', $request->getProperty('referrer_type'));
		
		$request->setProperty('type_id', $request->getProperty('type_id'));
		
		$type_id = $request->getProperty('type_id');
//		echo '$type_id null = ' . (is_null($type_id) ? 1: 0);
//		echo '<br />';
//		echo '$type_id empty string = ' . ($type_id == '' ? 1: 0);
		if($type_id != '')
		{
			switch ($type_id)
			{
				case 1:
					$type = 'Meeting';
					break;
				case 2:
					$type = 'Information request';
					break;
				case 3:
					$type = 'Meeting re-arrangement';
					break;
				case 4:
					$type = 'Meeting follow-up';
					break;
				default:
					$type = 'All';
					break;
			}
			
		}
		else
		{
			$type = 'All';
		}
		$request->setProperty('action_type', $type);
		
		$request->setProperty('category', $request->getProperty('category'));
		$category = $request->getProperty('category');
		if($category != '')
		{
			switch ($category)
			{
				case 'meeting':
					$type = 'Meeting';
					$type_ids = array(1,3,4);
					break;
				case 'information_request':
					$type = 'Information request';
					$type_ids = array(2);
					break;
				default:
					$type = 'All';
					$type_ids = array(1,2,3,4);
					break;
			}
			$request->setProperty('action_type', $type);
		}
//		echo $type;
//		echo '<pre>';
//		print_r($type_ids);
//		echo '</pre>';
		
		if ($post_initiative_id != '')
		{
			// if we have accessed this screen via the communication screen then need to use any actions which exist in the 
			// session post_initiative_actions, in preference to those pulled out of the database as the session based actions
			// will have had something changed which has not yet been recorded in the database
			$action_ids = array();
			$actions = new app_mapper_ActionCollection();
			if ($request->getProperty('referrer_type') == 'communication')
			{
				if(isset($_SESSION['auth_session']['communication']['post_initiative_actions']))
				{
					foreach($_SESSION['auth_session']['communication']['post_initiative_actions'] as $action)
					{
						if (is_object($action))
						{
							if($type_id != '')
							{
								if ($action->getTypeId() == $type_id)
								{
									$action_ids[] = $action->getId();
									$actions->add($action);
								}	
							}
							elseif($category != '')
							{
								if (in_array($action->getTypeId(), $type_ids))
								{
									$action_ids[] = $action->getId();
									$actions->add($action);
								}
							}
							else
							{
								$action_ids[] = $action->getId();
								$actions->add($action);	
							}
						}
					} 
				}
				
				if ($type_id != '')
				{
					$actions_db = app_domain_Action::findByPostInitiativeIdAndTypeId($post_initiative_id, $type_id);
				}
				else
				{
					if ($category != '')
					{
						$actions_db = app_domain_Action::findByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids);
					}
					else
					{
						$actions_db = app_domain_Action::findByPostInitiativeId($post_initiative_id);
					}
				}
				
				foreach ($actions_db as $action_db)
				{
					if (!in_array($action_db->getId(), $action_ids))
					{
						$actions->add($action_db);
					}
				}
			}
			else	
			{
				if ($type_id != '')
				{
					$actions = app_domain_Action::findByPostInitiativeIdAndTypeId($post_initiative_id, $type_id);
				}
				else
				{
					if ($category != '')
					{
						$actions = app_domain_Action::findByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids);
					}
					else
					{
						$actions = app_domain_Action::findByPostInitiativeId($post_initiative_id);
					}
				}
			}
			
			$request->setObject('actions', $actions);
			return self::statuses('CMD_OK');
		}
		else
		{
			$request->addFeedback('No post initiative id supplied');
			return self::statuses('CMD_OK');
		}
	}
}

?>