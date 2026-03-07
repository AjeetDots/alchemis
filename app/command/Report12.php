<?php

/**
 * Defines the app_command_Report12 class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report12.php');

/**
 * @package Alchemis
 */
class app_command_Report12 extends app_command_Command
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

        // Validate and set
        $this->validateParameters($start, $end, $client_id);
        $pdf = new app_report_Report12($start, $end);
        $pdf->Output(date('Y-m-d') . 'Sector Discipline Report.pdf', 'I');
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