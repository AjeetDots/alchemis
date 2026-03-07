<?php

/**
 * Defines the app_command_DashboardMessages class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Message.php');

/**
 * @package Alchemis
 */
class app_command_DashboardMessages extends app_command_Command
{
	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		return $this->session_user->hasPermission('permission_admin_messages');
	}

	public function doExecute(app_controller_Request $request)
	{
//		// Get user information from the session
//		$session = Auth_Session::singleton();
//		$user = $session->getSessionUser();
		
		$messages = app_domain_Message::findAll();
		$request->setObject('messages', $messages);
		
		return self::statuses('CMD_OK');
	}
}

?>