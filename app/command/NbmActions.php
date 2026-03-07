<?php

/**
 * Defines the app_command_Actions class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('Auth/Session.php');
require_once('app/domain/Action.php');

/**
 * @package Alchemis
 */
class app_command_NbmActions extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$request->setObject('user', $user);
		
		$actions = app_domain_Action::findByUserId($user['id']);
		$request->setObject('actions', $actions);
		
		return self::statuses('CMD_OK');
	}
}

?>