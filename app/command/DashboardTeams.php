<?php

/**
 * Defines the app_command_DashboardTeams class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Team.php');

/**
 * @package Alchemis
 */
class app_command_DashboardTeams extends app_command_Command
{
	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		return $this->session_user->hasPermission('permission_admin_teams');
	}
	
	public function doExecute(app_controller_Request $request)
	{
//		// Get user information from the session
//		$session = Auth_Session::singleton();
//		$user = $session->getSessionUser();
		
		$teams = app_domain_Team::findAll();
		$request->setObject('teams', $teams);
		
		return self::statuses('CMD_OK');
	}
}

?>