<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/CampaignNbmTarget.php');
require_once('app/mapper/CampaignNbmTargetMapper.php');
require_once('app/view/ViewHelper.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_Region objects.
 * @package alchemis
 */
class app_command_AjaxNbmCampaignTarget extends app_command_AjaxCommand
{

	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);

		switch ($this->request->cmd_action)
		{
			case 'save_client_line':
				$form_data  = $this->request->form_data;
				$start_date = $this->request->start_date;
				$end_date   = $this->request->end_date;

				// array to hold incoming field data arrays
				$field_data = array();
				$date_data  = array();

				foreach ($form_data as $item)
				{
					$field_data[] = explode('-', $item[0]);
				}
				$this->request->return_data = $this->saveLine($field_data);
				$this->request->return_data_total = $this->getTotalLine($start_date, $end_date);
				break;

			default:
				break;
		}

		// Return result data
		array_push($this->response->data, $this->request);
	}

	protected function saveLine($field_data)
	{
		$nbm_campaign_target = app_domain_CampaignNbmTarget::findByCampaignIdUserIdAndYearMonth($this->request->campaign_id, $this->request->user_id, $this->request->year_month);

		foreach ($field_data as $field)
		{
			switch ($field[1])
			{
				case 'planned_days':
					$nbm_campaign_target->setPlannedDays($field[2]);
					break;

				case 'project_management_days':
					$nbm_campaign_target->setProjectManagementDays($field[2]);
					break;

				case 'effectives_target':
					$nbm_campaign_target->setEffectives($field[2]);
					break;

				case 'meetings_set_target':
					$nbm_campaign_target->setMeetingsSet($field[2]);
					break;

				case 'meetings_set_imperative_target':
					$nbm_campaign_target->setMeetingsSetImperative($field[2]);
					break;

				case 'meetings_attended_target':
					$nbm_campaign_target->setMeetingsAttended($field[2]);
					break;
			}
		}
		$nbm_campaign_target->commit();

		$return_data = $this->getLine($nbm_campaign_target->getId(), $this->request->start_date, $this->request->end_date);
		return $return_data;
	}

	/**
	 * Get the total line based on the recalculated values. Date params used to
	 * calculate number of working days.
	 * @param integer $id
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 */
	protected function getLine($id, $start_date, $end_date)
	{
		// Get Smarty
		$smarty = ViewHelper::getSmarty();

		// Get number of working days
		$working_days = Utils::getWorkingDays($start_date, $end_date)-1;

		$return_data = array();
		$planning_data = app_domain_CampaignNbmTarget::findStatisticsById($id);
		foreach ($planning_data as &$data)
		{
			if ($data['call_days_actual'] > 0)
			{
				$data['average_effectives_per_day'] = round($data['effectives'] / $data['call_days_actual'], 1);
			}
			else
			{
				$data['average_effectives_per_day'] = 0;
			}
		}


		$smarty->assign('planning_data', $planning_data);
		return $smarty->fetch('html_NbmMonthlyPlannerLine.tpl');
	}

	/**
	 * Get the total line based on the recalculated values. Date params used to
	 * calculate number of working days.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 */
	protected function getTotalLine($start_date, $end_date)
	{
		// Get Smarty
		$smarty = ViewHelper::getSmarty();

		// Get number of working days
		$working_days = Utils::getWorkingDays($start_date, $end_date)-1;

		$return_data = array();
		$planning_data_total = app_domain_CampaignNbmTarget::findTotalStatisticsByUserIdAndYearMonth($this->request->user_id, $this->request->year_month);
		if ($planning_data_total['call_days_actual'] > 0)
		{
			$planning_data_total['average_effectives_per_day'] = round($planning_data_total['effectives'] / $planning_data_total['call_days_actual'], 1);
		}
		else
		{
			$planning_data_total['average_effectives_per_day'] = 0;
		}
		$smarty->assign('planning_data_total', $planning_data_total);
		return $smarty->fetch('html_NbmMonthlyPlannerTotalLine.tpl');
	}

}

?>