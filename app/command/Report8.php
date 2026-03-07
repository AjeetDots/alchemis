<?php

/**
 * Defines the app_command_Report5 class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report8.php');

/**
 * @package Alchemis
 */
class app_command_Report8 extends app_command_Command
{
    public function doExecute(app_controller_Request $request)
    {
        $report_id = $request->getProperty('report_id');
        $request->setObject('report_id', $report_id);

        // Start date
        if ($request->propertyExists('start'))
        {
            $start = $request->getProperty('start');
        }
        else
        {
            // Use first day of current month
            $start = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        }

        // End date
        if ($request->propertyExists('end'))
        {
            $end = $request->getProperty('end');
        }
        else
        {
            // Use last day of current month
            $end = date('Y-m-d', mktime(0, 0, 0, date('m')+1, 0, date('Y')));
        }

        // Client ID
        if ($request->propertyExists('client_id'))
        {
            $client_id = $request->getProperty('client_id');
        }
        else
        {
            throw new Exception('Client ID not supplied');
        }

        // Filter ID
        if ($request->propertyExists('filter_id'))
        {
            $filter_id = $request->getProperty('filter_id');
        }
        else
        {
            throw new Exception('Filter ID not supplied');
        }

	    // display front page statuses
        if ($request->propertyExists('front_page_statuses'))
        {
            $front_page_statuses = $request->getProperty('front_page_statuses');
        }
        else
        {
            throw new Exception('front_page_statuses not supplied');
        }

    	// display front page figures
        if ($request->propertyExists('front_page_figures'))
        {
            $front_page_figures = $request->getProperty('front_page_figures');
        }
        else
        {
            throw new Exception('$front_page_figures not supplied');
        }

    	// display front page figures
        if ($request->propertyExists('summary_figures'))
        {
            $summary_figures = $request->getProperty('summary_figures');
        }
        else
        {
            throw new Exception('$summary_figures not supplied');
        }

//        if ($request->propertyExists('summary_figures')) $summary_figures = true;

//        if ($request->propertyExists('meetings_set_summary')) $meetings_set_summary = true;
//        if ($request->propertyExists('cancellation_clinic')) $cancellation_clinic = true;
//        if ($request->propertyExists('opportunities_and_wins_clinic'))$opportunities_and_wins_clinic = true;
//        if ($request->propertyExists('targeting_clinic')) $targeting_clinic = true;
//        if ($request->propertyExists('database_analysis')) $database_analysis = true;
//        if ($request->propertyExists('effectives_analysis')) $effectives_analysis = true;
//        if ($request->propertyExists('nbm_discipline_effectiveness')) $nbm_discipline_effectiveness = true;
//        if ($request->propertyExists('nbm_industry_effectiveness')) $nbm_industry_effectiveness = true;
//        if ($request->propertyExists('pipeline_report')) $pipeline_report = true;
//        if ($request->propertyExists('effective_notes')) $effective_notes = true;

        // Validate and set
        $this->validateParameters($start, $end, $client_id);
        $pdf = new app_report_Report8($start, $end, $client_id, $filter_id, $front_page_statuses, $front_page_figures, $summary_figures);
        $pdf->Output(date('Y-m-d') . 'Line Listing Report.pdf', 'I');
    }

    /**
     * Validates the input.
     * @param string $start
     * @param string $end
     * @param integer $client_id
     * @param boolean $full_history
     * @return boolean
     */
    protected function validateParameters($start, $end, $client_id)
    {
        if ($end < $start)
        {
            throw new Exception('End date is before start date: (start => ' . $start . ', end => ' . $end . ')');
        }
    }

}

?>