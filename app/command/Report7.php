<?php

/**
 * Defines the app_command_Report5 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report7.php');

/**
 * @package Alchemis
 */
class app_command_Report7 extends app_command_Command
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

        if ($request->propertyExists('client_fact_summary')) $client_fact_summary = true;
        if ($request->propertyExists('campaign_statistics')) $campaign_statistics = true;
        if ($request->propertyExists('nbm_statistics')) $nbm_statistics = true;
        if ($request->propertyExists('meetings_set_summary')) $meetings_set_summary = true;
        if ($request->propertyExists('cancellation_clinic')) $cancellation_clinic = true;
        if ($request->propertyExists('opportunities_and_wins_clinic'))$opportunities_and_wins_clinic = true;
        if ($request->propertyExists('targeting_clinic')) $targeting_clinic = true;
        if ($request->propertyExists('database_analysis')) $database_analysis = true;
        if ($request->propertyExists('effectives_analysis')) $effectives_analysis = true;
        if ($request->propertyExists('nbm_discipline_effectiveness')) $nbm_discipline_effectiveness = true;
        if ($request->propertyExists('nbm_industry_effectiveness')) $nbm_industry_effectiveness = true;
        if ($request->propertyExists('pipeline_report')) $pipeline_report = true;
        if ($request->propertyExists('effective_notes')) $effective_notes = true;
        
        // Validate and set
        $this->validateParameters($start, $end, $client_id);
        $pdf = new app_report_Report7($start, $end, $client_id, 
                                  $client_fact_summary, $campaign_statistics, $nbm_statistics,
                                  $meetings_set_summary, $cancellation_clinic, $opportunities_and_wins_clinic, 
                                  $targeting_clinic, $database_analysis, $effectives_analysis, 
                                  $nbm_discipline_effectiveness, $nbm_industry_effectiveness, $pipeline_report, 
                                  $effective_notes);
        $pdf->Output(date('Y-m-d') . ' Client Clinic Report.pdf', 'I');
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