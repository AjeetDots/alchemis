<?php

/**
 * Defines the app_command_ActionsFrame class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Client.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_ActionsFrame extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$client_id = $request->getProperty('client_id');
		
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$request->setObject('user_id', $user['id']);
		
		// Find client associated with current user
		if ($items = app_domain_Client::findByUserId($user['id'])) {
			$selected_client = 0;
			$options = array();
			$options[0] = '-- select --';
			foreach ($items as $item) {
				$options[$item['client_id']] = @C_String::htmlDisplay($item['client_name']);
				if ($client_id == $item['client_id']) {
					$selected_client = $item['client_id'];
				}
			}
			$request->setObject('client_options', $options);
			$request->setObject('client_selected', $selected_client);
		}
		
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

		// Default menu item
		$menu_item = $request->getProperty('menu_item');
		$request->setObject('menu_item', $menu_item);
		
		// Redirect info - if it exists
		if ($request->propertyExists('redirect')) {
			$request->setProperty('redirect', $request->getProperty('redirect'));
		} else {
			$request->setProperty('redirect', '');
		}
		
		return self::statuses('CMD_OK');
	}
}

?>