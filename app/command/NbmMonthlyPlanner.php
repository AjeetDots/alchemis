<?php

/**
 * Defines the app_command_NbmMonthlyPlanner class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/StatisticsReader.php');
require_once('app/mapper/StatisticsReaderMapper.php');

/**
 * @package Alchemis
 */
class app_command_NbmMonthlyPlanner extends app_command_ManipulationCommand
{
	//	/**
	//	 * Override parent::hasPermission()
	//	 * @param app_controller_Request $request
	//	 */
	//	protected function hasPermission(app_controller_Request $request)
	//	{
	//		return $this->session_user->hasPermission('permission_admin_nbm_monthly_planner');
	//	}

	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');

		if ($task == 'cancel') {
			// ???
		} elseif ($task == 'save') {
			if ($this->processForm($request)) {
				$request->addFeedback('Save Successful');
				$request->setProperty('success', true);
				return self::statuses('CMD_OK');
			} else {
				return self::statuses('CMD_ERROR');
			}
		} else {
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes
	 * any validation has already been performed.
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		return true;
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$user_id = $request->getProperty('user_options');
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();

		// Get user information from the session
		if (!$user_id) $user_id = $user['id'];

		// Set up user options
		if ($this->session_user->hasPermission('permission_admin_nbm_monthly_planner')) {
			$user = app_model_User::find($user['id']);
			$items = app_domain_RbacUser::findAllActive($user->client_id);
			$items = $items->toRawArray();
		} else {
			$user_obj = app_domain_RbacUser::find($user['id']);
			// Just get current user
			$items = array(array('id' => $user['id'], 'name' => $user_obj->getName()));
		}

		if ($items) {
			$options = array();
			$options[0] = '-- select --';
			foreach ($items as $item) {
				$options[$item['id']] = @@C_String::htmlDisplay($item['name']);
				if ($user_id == $item['id']) {
					$selected_user = $item['id'];
				}
			}
			$request->setObject('user_options', $options);
			if (empty($selected_user)) {
				$selected_user = $user['id'];
			}
			$request->setProperty('user_selected', $selected_user);
		}

		// Month / year
		if ($request->getProperty('Date_Year') != '' && $request->getProperty('Date_Month') != '') {
			$year_month_day = $request->getProperty('Date_Year') . '-' . $request->getProperty('Date_Month') . '-01';
			$end_date       = date('Y-m-d', mktime(0, 0, 0, $request->getProperty('Date_Month') + 1, 0, $request->getProperty('Date_Year')));
			$start_date     = date('Y-m-d', mktime(0, 0, 0, $request->getProperty('Date_Month'), 1, $request->getProperty('Date_Year')));
			$year_month     = $request->getProperty('Date_Year') . $request->getProperty('Date_Month');
			$year           = $request->getProperty('Date_Year');
			$month          = $request->getProperty('Date_Month');
		} else {
			$year_month_day = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
			//			$year_month_day = date('Y-m-d');
			$end_date       = date('Y-m-d', mktime(0, 0, 0, date('m') + 1, 0, date('Y')));
			$start_date     = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
			//			$start_date     = date('Y-m-d', mktime(0, 0, 0, date('m'), 0, date('Y')));
			$year_month     = date('Ym');
			$year           = date('Y');
			$month          = date('m');
		}

		//		 echo $request->getProperty('Date_Month');
		//        echo $start_date;
		//		echo $end_date;

		$current_date = date('Y-m-d');
		$current_date = date('Y-m-d', strtotime($current_date . '-1 day'));

		$request->setProperty('year_month_day', $year_month_day);
		$request->setProperty('year_month',     $year_month);
		$request->setProperty('end_date',       $end_date);
		$request->setProperty('year',           $year);
		$request->setProperty('month',          $month);
		$request->setProperty('current_date',   $current_date);

		// Get max year_month for each current campaign
		$maxYearMonthByCampaign = app_domain_CampaignNbmTarget::findMaxYearMonthByUserId($selected_user);
		//		echo $selected_user;
		//		echo '<pre>';
		//		print_r($maxYearMonthByCampaign);
		//		echo '<pre>';
		// Get planning data for selected user and year/month - where effectives and meetings targets > 0
		$planning_data = app_domain_CampaignNbmTarget::findStatisticsNonZeroTargetsByUserIdAndYearMonth($selected_user, $year_month);
		foreach ($planning_data as &$data) {
			//			echo $data['campaign_id'] . '<br />';
			if ($data['call_days_actual']) {
				$data['average_effectives_per_day'] = round($data['effectives'] / $data['call_days_actual'], 1);
			} else {
				$data['average_effectives_per_day'] = 0;
			}

			foreach ($maxYearMonthByCampaign as $maxTargetMonthYear) {
				//				echo $maxTargetMonthYear['campaign_id'];
				if ($maxTargetMonthYear['campaign_id'] == $data['campaign_id']) {
					//                	echo 'Found campaign_id <br />';
					if ($maxTargetMonthYear['max_year_month'] < $data['year_month']) {
						$data['client_name'] = $data['client_name'] . '*';
					}
				}
			}
		}


		$request->setObject('planning_data', $planning_data);

		// Get planning data for selected user and year/month - where effectives and meetings targets == 0
		$planning_data_zero_targets = app_domain_CampaignNbmTarget::findStatisticsZeroTargetsByUserIdAndYearMonth($selected_user, $year_month);
		foreach ($planning_data_zero_targets as &$data) {
			if ($data['call_days_actual']) {
				$data['average_effectives_per_day'] = round($data['effectives'] / $data['call_days_actual'], 1);
			} else {
				$data['average_effectives_per_day'] = 0;
			}

			foreach ($maxYearMonthByCampaign as $maxTargetMonthYear) {
				//                echo $maxTargetMonthYear['campaign_id'];
				if ($maxTargetMonthYear['campaign_id'] == $data['campaign_id']) {
					//                    echo 'Found campaign_id. ' . $maxTargetMonthYear['max_year_month'] .' <br />';
					if (isset($data['year_month']) && $maxTargetMonthYear['max_year_month'] < $data['year_month']) {
						$data['client_name'] = $data['client_name'] . '*';
					}
				}
			}
		}

		$request->setObject('planning_data_zero_targets', $planning_data_zero_targets);

		// Get the totals for selected user and year/month
		$planning_data_total = app_domain_CampaignNbmTarget::findTotalStatisticsByUserIdAndYearMonth($selected_user, $year_month);
		if ($planning_data_total['call_days_actual'] > 0) {
			$planning_data_total['average_effectives_per_day'] = round($planning_data_total['effectives'] / $planning_data_total['call_days_actual'], 1);
		} else {
			$planning_data_total['average_effectives_per_day'] = 0;
		}
		$request->setObject('planning_data_total', $planning_data_total);

		// Working days
		$working_days_month_total = Utils::getWorkingDays($year_month_day, $end_date);

		// if we are looking at the current month then calculate the number of working days that have elapsed so far in the month
		// if looking at any other month then calculate the number of working days in the whole month
		// NOTE: take one day off as the getWorkingDays function incudes the current date - which don't want to do at this point
		if ($year_month == date('Ym')) {
			$working_days_month_to_date = Utils::getWorkingDays($year_month_day, $current_date);
		} else {
			$working_days_month_to_date = Utils::getWorkingDays($year_month_day, $end_date);
		}
		//        echo '$$working_days_month_to_date '. $working_days_month_to_date . '<br />';

		// Days booked in month
		$days_booked = app_domain_Event::countByUserIdYearMonth($user_id, $year_month);


		// Days booked so far in month
		// if we are looking at the current month then calculate the number of working days that have elapsed so far in the month
		// if looking at any other month then calculate the number of working days in the whole month
		if ($year_month == date('Ym')) {
			$days_booked_to_date = app_domain_Event::countByUserIdAndDates($user_id, $start_date, $current_date);
		} else {
			$days_booked_to_date = app_domain_Event::countByUserIdAndDates($user_id, $start_date, $end_date);
		}

		//        print_r($days_booked_to_date);

		$days_booked_to_date_total = 0;
		foreach ($days_booked_to_date as $item) {
			$days_booked_to_date_total += $item['count'];
		}
		//        echo '$days_booked_to_date_total '. $days_booked_to_date_total . '<br />';

		$dates_booked_display = array();
		$days_booked_total = 0;
		foreach ($days_booked as $booked) {
			$temp = 0;
			foreach ($days_booked_to_date as $booked_to_date) {
				if ($booked['name'] == $booked_to_date['name']) {
					$temp += $booked_to_date['count'];
				}
			}
			$days_booked_total += $booked['count'];
			$dates_booked_display[] = array(
				'name' => $booked['name'],
				'count' => number_format($temp, 2),
				'count_total'  => $booked['count']
			);
		}

		$worked_days = $working_days_month_to_date - $days_booked_to_date_total;
		$request->setObject('working_days_month_total', $working_days_month_total);
		$request->setObject('working_days_month_to_date', $working_days_month_to_date);

		$request->setObject('worked_days', $worked_days);


		$request->setObject('days_booked_to_date', $days_booked_to_date);
		$request->setObject('days_booked_to_date_total', $days_booked_to_date_total);

		$request->setObject('days_booked', $dates_booked_display);

		$working_days_for_remainder_of_month = $working_days_month_total - $working_days_month_to_date - $days_booked_total + $days_booked_to_date_total;
		$request->setObject('working_days_for_remainder_of_month', $working_days_for_remainder_of_month);

		$working_days_for_month_less_booked_days = $working_days_month_total - $days_booked_total;
		//        $request->setObject('working_days_for_month_less_booked_days', $working_days_for_month_less_booked_days);


		//        echo '$$working_days_for_remainder_of_month ' . $working_days_for_remainder_of_month;
		//        echo '$working_days_month_total ' . $working_days_month_total;
		//        echo '$working_days_month_to_date ' . $working_days_month_to_date;

		// Averages
		$monthly_call_data = app_domain_StatisticsReader::findCallsByUserIdAndYearMonth($selected_user, $year_month);

		// Add average to call data
		$monthly_call_data['average_calls']        = round($monthly_call_data['call_count'] / $worked_days);
		$monthly_call_data['average_effectives']   = round($monthly_call_data['call_effective_count'] / $worked_days, 1);
		$monthly_call_data['average_meetings_set'] = round($monthly_call_data['meeting_set_count'] / $worked_days, 1);

		if ($monthly_call_data['call_count'] > 0) {
			$monthly_call_data['access'] = round(($monthly_call_data['call_effective_count'] / $monthly_call_data['call_count']) * 100);
		} else {
			$monthly_call_data['access'] = 0;
		}

		if ($monthly_call_data['call_effective_count'] > 0) {
			$monthly_call_data['conversion'] = round(($monthly_call_data['meeting_set_count'] / $monthly_call_data['call_effective_count']) * 100);
		} else {
			$monthly_call_data['conversion'] = 0;
		}

		$kpis['calls_per_call_day'] = 80;
		$kpis['effectives_per_call_day'] = 12;
		$kpis['meets_set_per_call_day'] = 0.8;
		$kpis['access'] = 15; //%
		$kpis['conversion'] = 7; // %

		$monthly_call_data['average_calls_variance'] = $monthly_call_data['average_calls'] - $kpis['calls_per_call_day'];
		$monthly_call_data['average_effectives_variance'] = $monthly_call_data['average_effectives'] - $kpis['effectives_per_call_day'];
		$monthly_call_data['average_meetings_set_variance'] = $monthly_call_data['average_meetings_set'] - $kpis['meets_set_per_call_day'];
		$monthly_call_data['access_variance'] = $monthly_call_data['access'] - $kpis['access'];
		$monthly_call_data['conversion_variance'] = $monthly_call_data['conversion'] - $kpis['conversion'];

		// targets calculated by
		// What should I have achived by this point in the month?
		// less
		// What have actually achieved to date in the month?
		// plus
		// What have I got left to achieve before the end of the month
		// all of the above divided by the remaining working days available in the month (including any 'booked' days)
		$targets['calls_per_call_day'] = round((($kpis['calls_per_call_day'] * $worked_days) - $monthly_call_data['call_count'] + ($kpis['calls_per_call_day'] * $working_days_for_remainder_of_month)) / $working_days_for_remainder_of_month);
		$targets['effectives_per_call_day'] = round((($kpis['effectives_per_call_day'] * $worked_days) - $monthly_call_data['call_effective_count'] + ($kpis['effectives_per_call_day'] * $working_days_for_remainder_of_month)) / $working_days_for_remainder_of_month);
		$targets['meets_set_per_call_day'] = round((($kpis['meets_set_per_call_day'] * $worked_days) - $monthly_call_data['meeting_set_count'] + ($kpis['meets_set_per_call_day'] * $working_days_for_remainder_of_month)) / $working_days_for_remainder_of_month, 2);
		$targets['access'] = 0;
		$targets['conversion'] = 0; // %

		// Determine media type
		$request->setObject('media', $request->getProperty('media'));

		// Assign to smarty
		$request->setObject('monthly_call_data', $monthly_call_data);
		$request->setObject('kpis', $kpis);
		$request->setObject('targets', $targets);

		if($_GET['Monthly'] == 3){
			echo '<pre>';
			print_r($request);
			echo '</pre>';
		}
	}
}
