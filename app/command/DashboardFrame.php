<?php

/**
 * Defines the app_command_DashboardFrame class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_DashboardFrame extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$request->setObject('user_id', $user['id']);
		
		// Find NBMs 
		$user_id = null;
		if (true || $this->session_user->hasPermission('permission_admin_nbm_monthly_planner'))
		{
			$items = app_domain_RbacUser::findAllActive();
			$items = $items->toRawArray();
		}
		else
		{
			$user_obj = app_domain_RbacUser::find($user['id']);
			// Just get current user
			$items = array(array('id' => $user['id'], 'name' => $user_obj->getName())); 
		}
		
		if ($items)
		{
			$options = array();
			$options[0] = '-- select --';
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay($item['name']);
				if ($user_id == $item['id'])
				{
					$selected_user = $item['id'];
				}
			}
			$request->setObject('nbm_options', $options);
			if (empty($selected_user))
			{
				$selected_user = $user['id'];
			}
			$request->setProperty('nbm_selected', $selected_user);
		}
		
		return self::statuses('CMD_OK');
	}
}

?>