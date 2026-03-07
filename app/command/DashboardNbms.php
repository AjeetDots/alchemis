<?php

/**
 * Defines the app_command_DashboardNbms class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Team.php');

/**
 * @package Alchemis
 */
class app_command_DashboardNbms extends app_command_Command
{
	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		return $this->session_user->hasPermission('permission_admin_nbm_teams');
	}

	public function doExecute(app_controller_Request $request)
	{
//		echo '<pre>';
//		print_r($request->getProperties());
//		echo '</pre>';
		
//		// Get user information from the session
//		$session = Auth_Session::singleton();
//		$user = $session->getSessionUser();
		
//		$teams = app_domain_Team::findAll();
//		$request->setObject('teams', $teams);
		
		
		if ($request->propertyExists('save_button'))
		{
			if ($this->processForm($request))
			{
				$request->addFeedback('Save Successful');
				$request->setProperty('success', true);
			}
			else
			{
				$request->addFeedback('Save Error');
				$request->setProperty('success', false);
			}
		}
		
		$nbms = app_domain_RbacUser::findTeamDetails();
		$request->setObject('nbms', $nbms);
		
		// Teams for drop-down
		$teams = app_domain_Team::findForDropdown();
		$request->setObject('teams', $teams);

		return self::statuses('CMD_OK');
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		$properties = $request->getProperties();
		
		foreach ($properties as $key => $value)
		{
			$nbm_id = null;
			
			if (preg_match('/team_\d+/', $key))
			{
				$nbm_id  = str_replace('team_', '', $key);
				$team_id = $value;
				
				if ($team_id > 0)
				{
					$team_nbm_id = app_domain_TeamNbm::findIdByUserId($nbm_id);
					
					if (!empty($team_nbm_id))
					{
						$team_nbm = app_domain_TeamNbm::find($team_nbm_id);
					}
					else
					{
						$team_nbm = new app_domain_TeamNbm();
					} 
					
					$team_nbm->setTeamId($team_id);
					$team_nbm->setUserId($nbm_id);
					$team_nbm->commit();
				}
				else
				{
					// Remove team
					if ($team_nbm_id = app_domain_TeamNbm::findIdByUserId($nbm_id))
					{
						$team_nbm = app_domain_TeamNbm::find($team_nbm_id);
						$team_nbm->markDeleted();
						$team_nbm->commit();
					}
				}
			}
		}
		
		return true;
	}

}

?>