<?php

/**
 * Defines the app_command_ReportParams class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/domain/RbacUser.php');

/**
 * @package Alchemis
 */
class app_command_ReportParams extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$request->setObject('user', $this->session_user);

		$report_id = $request->getProperty('report_id');
		$request->setObject('report_id', $report_id);
		
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$user = app_model_User::find($user['id']);

		switch ($report_id)
		{
			case 1:
				$report = 'Alchemis Allocation Report';
				$date_to = date('d/m/Y');
				break;

			case 2:
				$report = 'Basic Sales Team Activity Statistics';
				$date_to = date('d/m/Y');
				break;

			case 3:
				$report = 'Source of Meetings Set';
				$date_to = date('d/m/Y');
				break;

			case 4:
				$report = 'Sales Team Summary vs Target for Period';
				$date_to = date('d/m/Y', mktime(0, 0, 0, date('m')+1, 0, date('Y')));
				break;

			case 5:
				$report = 'Alchemis Activity Report of Conversation Notes';
				$date_to = date('d/m/Y');
				break;

			case 6:
				$report = 'Client Services Report';
				$date_to = date('d/m/Y');
				break;

			case 7:
				$report = 'Client Clinic Report';
				$date_to = date('d/m/Y');
				break;

			case 8:
				$report = 'Line Listing Report';
				$date_to = date('d/m/Y');
				break;

			case 9:
                $report = 'Sales Team Performance Against KPI Targets';
                $date_to = date('d/m/Y');
                break;
            case 10:
                $report = 'Global Sector Analysis';
                $date_to = date('d/m/Y');
                break;

            case 11:
                $report = 'Global Discipline Analysis';
                $date_to = date('d/m/Y');
                break;
            case 12:
                $report = 'Global Sector Discipline Analysis';
                $date_to = date('d/m/Y');
                break;

            case 13:
				// only alchemis users
				if ($user->client_id !== null) die;
                $report = 'Global NBM Bonus Report';
                $date_to = date('d/m/Y');
                break;
            case 14:
                $report = 'NBM Bonus Detail Report';
                $date_to = date('d/m/Y');
                break;

            case 15:
                $report = 'Client Exception Report';
                $date_to = date('d/m/Y');
                break;

	        default:
				$report = 'Unknown';
				break;
		}
		$request->setObject('report', $report);
		$request->setObject('date_to', $date_to);
		
		// From date
		$date_from = date('d/m/Y', mktime(0, 0, 0, date('m'), 1, date('Y')));
		$request->setObject('date_from', $date_from);

		// Teams for drop down
		$teams = app_domain_Team::findForDropdown();
		$request->setObject('teams', $teams);

		// NBMs for drop down
		$users = app_domain_RbacUser::findAllActiveForDropdown($report_id != 2, $user->client_id);
		$request->setObject('users', $users);

		// Clients for drop down
		$clients = app_domain_Client::findByUserIdForDropdown($this->session_user->getId());
		$request->setObject('clients', $clients);

		// Communication status for drop down
		$status = app_domain_Communication::findStatusAllForDropdown();
		$request->setObject('status', $status);



		return self::statuses('CMD_OK');
	}
}

?>