<?php

/**
 * Defines the app_view_NbmMonthlyPlanner class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_NbmMonthlyPlanner extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('user_options',        		$this->request->getObject('user_options'));
		$this->smarty->assign('user_selected',       		$this->request->getProperty('user_selected'));
		$this->smarty->assign('year_month_day',      		$this->request->getProperty('year_month_day'));
		$this->smarty->assign('year_month',          		$this->request->getProperty('year_month'));
		$this->smarty->assign('end_date',            		$this->request->getProperty('end_date'));
		$this->smarty->assign('year',                		$this->request->getProperty('year'));
		$this->smarty->assign('month',               		$this->request->getProperty('month'));
		$this->smarty->assign('planning_data',       		$this->request->getObject('planning_data'));
		$this->smarty->assign('planning_data_zero_targets', $this->request->getObject('planning_data_zero_targets'));
		$this->smarty->assign('planning_data_total', 		$this->request->getObject('planning_data_total'));
		$this->smarty->assign('monthly_call_data',   		$this->request->getObject('monthly_call_data'));
		$this->smarty->assign('kpis',                       $this->request->getObject('kpis'));
		$this->smarty->assign('targets',                     $this->request->getObject('targets'));

		$this->smarty->assign('campaigns',           		$this->request->getObject('campaigns'));

		// Days booked in month
		$this->smarty->assign('days_booked', $this->request->getObject('days_booked'));

		// Averages
		$this->smarty->assign('average_calls_per_day', $this->request->getObject('average_calls_per_day'));
		$this->smarty->assign('average_effectives_per_day', $this->request->getObject('average_effectives_per_day'));
		$this->smarty->assign('working_days_month_total', $this->request->getObject('working_days_month_total'));
		$this->smarty->assign('working_days_month_to_date', $this->request->getObject('working_days_month_to_date'));
		$this->smarty->assign('working_days_for_remainder_of_month', $this->request->getObject('working_days_for_remainder_of_month'));
//        $this->smarty->assign('working_days_for_month_less_booked_days', $this->request->getObject('working_days_for_month_less_booked_days'));


		$this->smarty->assign('days_booked_to_date_total', $this->request->getObject('days_booked_to_date_total'));
        $this->smarty->assign('worked_days', $this->request->getObject('worked_days'));


		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('success', $this->request->getProperty('success'));

		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();

			// Ensure field values are made sticky
			$this->handleStickyFields();
		}

		// Determine media type
		$this->smarty->assign('media', $this->request->getObject('media'));
		$media = $this->request->getObject('media');
//		if ($media == 'print')
//		{
//			$this->smarty->display('NbmMonthlyPlanner.print.tpl');
//		}
//		else
//		{
			$this->smarty->display('NbmMonthlyPlanner.tpl');
//		}
	}
}

?>