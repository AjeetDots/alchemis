<?php

/**
 * Defines the app_command_Report13 class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report13.php');

/**
 * @package Alchemis
 */
class app_command_Report13 extends app_command_Command
{
    public function doExecute(app_controller_Request $request)
    {
         $report_id = $request->getProperty('report_id');
        $request->setObject('report_id', $report_id);

        $year = $request->getProperty('year');
        $request->setObject('year', $year);

        // Handle NBM exclusions
        if ($request->propertyExists('nbm_exclusions') && trim($request->getProperty('nbm_exclusions')) != '')
        {
            $nbm_exclusions = explode(',', $request->getProperty('nbm_exclusions'));
        }
        else
        {
            // Assume no exclusions
            $nbm_exclusions = null;
        }

        // Validate and set
        $session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$user = app_model_User::find($user['id']);
        // only alchemis users
        if ($user->client_id !== null) die;
        
        $pdf = new app_report_Report13($year, $nbm_exclusions, $client_id);
        $pdf->Output(date('Y-m-d') . 'NBM Bonus report.pdf', 'I');
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