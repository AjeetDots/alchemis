<?php

/**
 * Defines the app_command_Report5 class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
//require_once('app/report/Report9.php');
require_once ('Spreadsheet/Writer.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/StatisticsReader.php');
require_once('app/mapper/StatisticsReaderMapper.php');

/**
 * @package Alchemis
 */
class app_command_Report9 extends app_command_Command
{
    public function doExecute(app_controller_Request $request)
    {

    	$date_from = $request->getProperty('start');
        $date_to = $request->getProperty('end');

        $report_name = 'Sales Team Performance Against KPI Targets';
        $xls = new Spreadsheet_Excel_Writer();

        $xls =& $this->exportXLS($date_from, $date_to);
        $xls->send($report_name . '.xls');
        $xls->close();
        exit();
    }

    function exportXLS($date_from, $date_to)
    {
        $xls = new Spreadsheet_Excel_Writer();

        $header_format =& $xls->addFormat(array('Size' => 10,
                                      'Align' => 'left',
                                      'Bold'  => 1));

        $sheet_data =& $xls->addWorksheet('Data');

        $this->writeHeaderRow1($sheet_data, 1, 1, $header_format);
        $this->writeHeaderRow2($sheet_data, 2, 1, $header_format);
        $this->insertBlankRow($sheet_data, 3, 0);

        if ($items = app_domain_RbacUser::getAllActiveUsersArray())
        {
            $row = 4;
            $date_array = date_parse($date_from);

            $start_date     = date('Y-m-d', mktime(0, 0, 0, $date_array['month'], 1,  $date_array['year']));
            $end_date       = date('Y-m-d', mktime(0, 0, 0, $date_array['month'] + 1, 0, $date_array['year']));
            $year_month     = $date_array['year'] . str_pad($date_array['month'], 2, '0', STR_PAD_LEFT);

            $this->debug = false;

            if ($this->debug) {
	            echo $date_from . '<br />';
	            print_r($date_array);
	            echo '$end_date = ' . $end_date;
	            echo '$start_date = ' . $start_date;
	            echo '$year_month = ' . $year_month;
	            exit();
            }

            foreach ($items as $item)
            {
            	$start_col = 0;
//            	print_r($item);
                $sheet_data->writeString($row, $start_col, $item['name']);
                $start_col++;
                $data_row = $this->getRowData($item['id'], $start_date, $end_date, $year_month);
//                print_r($data);
                foreach($data_row as $data_cell) {
                	$sheet_data->writeString($row, $start_col, $data_cell);
                	$start_col++;
                }
                $row++;
            }
        }
        return $xls;
    }

    function insertBlankRow($sheet_data, $start_row, $start_col, $format = null)
    {
        $sheet_data->writeString($start_row, $start_col, '', $format);
    }

    function writeHeaderRow1($sheet_data, $start_row, $start_col, $format = null)
    {
        // header row 1
        $sheet_data->writeString($start_row, $start_col, 'Call days', $format);
        $start_col+=3;
        $sheet_data->writeString($start_row, $start_col, 'Calls', $format);
        $start_col+=4;
        $sheet_data->writeString($start_row, $start_col, 'Effectives', $format);
        $start_col+=4;
        $sheet_data->writeString($start_row, $start_col, 'Meets', $format);
    }

    function writeHeaderRow2($sheet_data, $start_row, $start_col, $format = null)
    {
    	// header row 2
        $sheet_data->writeString($start_row, $start_col, 'Max Month', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Elapsed', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Remain', $format);

        $sheet_data->writeString($start_row, ++$start_col, 'Target', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Actual', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Need', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'P day', $format);

        $sheet_data->writeString($start_row, ++$start_col, 'Target', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Actual', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Need', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'P day', $format);

        $sheet_data->writeString($start_row, ++$start_col, 'Target', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Actual', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Need', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'P day', $format);

        $sheet_data->writeString($start_row, ++$start_col, 'Call rate', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Effective rate', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Meets per day', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Conversion', $format);
        $sheet_data->writeString($start_row, ++$start_col, 'Access', $format);
    }

//    function writeDataRow($sheet_data, $row, $start_col, $item)
//    {
//        $sheet_data->writeString($row, $start_col, $item['name']);
//    }

    function writePostData($sheet_data, $row, $start_col, $item)
    {
        // post
        $sheet_data->writeString($row, $start_col, $item['post_id']);
        $sheet_data->writeString($row, ++$start_col, $item['job_title']);
        $sheet_data->writeString($row, ++$start_col, $item['post_telephone_1']);
        $sheet_data->writeString($row, ++$start_col, $item['post_telephone_2']);
        $sheet_data->writeString($row, ++$start_col, $item['post_telephone_switchboard']);
        $sheet_data->writeString($row, ++$start_col, $item['post_telephone_fax']);

        // contact
        $sheet_data->writeString($row, ++$start_col, $item['contact_id']);
        $sheet_data->writeString($row, ++$start_col, $item['title']);
        $sheet_data->writeString($row, ++$start_col, $item['first_name']);
        $sheet_data->writeString($row, ++$start_col, $item['surname']);
        $sheet_data->writeString($row, ++$start_col, $item['email']);
        $sheet_data->writeString($row, ++$start_col, $item['contact_telephone_mobile']);
    }

    function writePostInitiativeData($sheet_data, $row, $start_col, $item)
    {
        // post initiative
        $sheet_data->writeString($row, $start_col, $item['post_initiative_id']);
        $sheet_data->writeString($row, ++$start_col, $item['client_name']);
        $sheet_data->writeString($row, ++$start_col, $item['initiative_name']);
        $sheet_data->writeString($row, ++$start_col, $item['status']);
        $sheet_data->writeString($row, ++$start_col, $item['comment']);
        $sheet_data->writeString($row, ++$start_col, $item['last_communication_date']);
        $sheet_data->writeString($row, ++$start_col, $item['last_communication_note']);
    }

    function writeMeetingData($sheet_data, $row, $start_col, $item, $format = null)
    {
        // new biz meeting report data
        $sheet_data->writeString($row, $start_col, $item['company_name'], $format);
        $sheet_data->writeString($row, ++$start_col, $item['title']);
        $sheet_data->writeString($row, ++$start_col, $item['first_name']);
        $sheet_data->writeString($row, ++$start_col, $item['surname']);
        $sheet_data->writeString($row, ++$start_col, $item['status']);
        $sheet_data->writeString($row, ++$start_col, $item['lead_source']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_created_by']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_modified_by']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_set_date']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_date']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_attended_date']);
        $sheet_data->writeString($row, ++$start_col, $item['']);
        $sheet_data->writeString($row, ++$start_col, $item['comment']);
        $sheet_data->writeString($row, ++$start_col, $item['last_communication_date']);
        $sheet_data->writeString($row, ++$start_col, $item['last_communication_note']);
    }


    function getRowData($user_id, $start_date, $end_date, $year_month)
    {
    	$return = array();

    	$current_date = date('Y-m-d');

    	// Working days
        $working_days_month_total = Utils::getWorkingDays($start_date, $end_date);
//        echo '$working_days_month_total = ' . $working_days_month_total;
//        exit();
//         if we are looking at the current month then calculate the number of working days that have elapsed so far in the month
//         if looking at any other month then calculate the number of working days in the whole month
//        echo '$year_month = ' . $year_month . '<br />';
//        echo "date('Ym') = " . date('Ym'). '<br />';
        if ($year_month == date('Ym')) {
              $working_days_month_to_date = Utils::getWorkingDays($start_date, $current_date)-1;
        } else {
            $working_days_month_to_date = Utils::getWorkingDays($start_date, $end_date)-1;
        }
//        echo '$$working_days_month_to_date '. $working_days_month_to_date . '<br />';

        // Days booked in month
        $days_booked = app_domain_Event::countByUserIdYearMonth($user_id, $year_month);

//        print_r($days_booked);
//        exit();

        $days_booked_total = 0;
        foreach ($days_booked as $booked) {
           $days_booked_total += $booked['count'];
        }


        $return['call_days_max_month'] = $working_days_month_total - $days_booked_total;

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

//        $dates_booked_display = array();
//        $days_booked_total = 0;
//        foreach ($days_booked as $booked) {
//           $temp = 0;
//           foreach ($days_booked_to_date as $booked_to_date) {
//               if ($booked['name'] == $booked_to_date['name']) {
//                   $temp += $booked_to_date['count'];
//               }
//           }
//           $days_booked_total += $booked['count'];
//           $dates_booked_display[] = array('name' => $booked['name'],
//                                           'count'=> number_format($temp,2),
//                                            'count_total'  => $booked['count']);
//        }

        $worked_days = $working_days_month_to_date - $days_booked_to_date_total;

        $return['call_days_elapsed'] = $worked_days;

        $working_days_for_remainder_of_month = $working_days_month_total - $working_days_month_to_date - $days_booked_total + $days_booked_to_date_total;
        $return['call_days_remain'] = $working_days_for_remainder_of_month;

//        $request->setObject('working_days_for_remainder_of_month', $working_days_for_remainder_of_month);

//        $working_days_for_month_less_booked_days = $working_days_month_total - $days_booked_total;
//        $request->setObject('working_days_for_month_less_booked_days', $working_days_for_month_less_booked_days);


//        echo '$$working_days_for_remainder_of_month ' . $working_days_for_remainder_of_month;
//        echo '$working_days_month_total ' . $working_days_month_total;
//        echo '$working_days_month_to_date ' . $working_days_month_to_date;

        // Averages
//        echo '$user_id = ' .$user_id;
        $monthly_call_data = app_domain_StatisticsReader::findCallsByUserIdAndYearMonth($user_id, $year_month);
//        echo 'here' . $monthly_call_data['call_count'];
//        exit();
//        // Add average to call data
//        $monthly_call_data['average_calls']        = round($monthly_call_data['call_count'] / $worked_days);
//        $monthly_call_data['average_effectives']   = round($monthly_call_data['call_effective_count'] / $worked_days, 1);
//        $monthly_call_data['average_meetings_set'] = round($monthly_call_data['meeting_set_count'] / $worked_days, 1);
//
//        if ($monthly_call_data['call_count'] > 0)
//        {
//            $monthly_call_data['access'] = round(($monthly_call_data['call_effective_count'] / $monthly_call_data['call_count']) * 100);
//        }
//        else
//        {
//            $monthly_call_data['access'] = 0;
//        }
//
//        if ($monthly_call_data['call_effective_count'] > 0)
//        {
//            $monthly_call_data['conversion'] = round(($monthly_call_data['meeting_set_count'] / $monthly_call_data['call_effective_count']) * 100);
//        }
//        else
//        {
//            $monthly_call_data['conversion'] = 0;
//        }

        $kpis['calls_per_call_day'] = 80;
        $kpis['effectives_per_call_day'] = 12;
        $kpis['meets_set_per_call_day'] = 0.8;
        $kpis['access'] = 15; //%
        $kpis['conversion'] = 7; // %

//        $monthly_call_data['average_calls_variance'] = $monthly_call_data['average_calls'] - $kpis['calls_per_call_day'];
//        $monthly_call_data['average_effectives_variance'] = $monthly_call_data['average_effectives'] - $kpis['effectives_per_call_day'];
//        $monthly_call_data['average_meetings_set_variance'] = $monthly_call_data['average_meetings_set'] - $kpis['meets_set_per_call_day'];
//        $monthly_call_data['access_variance'] = $monthly_call_data['access'] - $kpis['access'];
//        $monthly_call_data['conversion_variance'] = $monthly_call_data['conversion'] - $kpis['conversion'];

        $return['calls_target'] = $kpis['calls_per_call_day'] * $return['call_days_max_month'];
        if ($monthly_call_data['call_count'] > 0) {
            $return['calls_actual'] = $monthly_call_data['call_count'];
        } else {
        	$return['calls_actual'] = 0;
        }
        // targets for remaining calls required calculated by
        // What should I have achived by this point in the month?
        // less
        // What have actually achieved to date in the month?
        // plus
        // What have I got left to achieve before the end of the month
        // all of the above divided by the remaining working days available in the month (including any 'booked' days)
        $return['calls_need'] = round((($kpis['calls_per_call_day'] * $worked_days) - $monthly_call_data['call_count'] + ($kpis['calls_per_call_day'] * $working_days_for_remainder_of_month)));
        $return['calls_need_per_day'] = round((($kpis['calls_per_call_day'] * $worked_days) - $monthly_call_data['call_count'] + ($kpis['calls_per_call_day'] * $working_days_for_remainder_of_month))/$working_days_for_remainder_of_month);


        $return['effectives_target'] = $kpis['effectives_per_call_day'] * $return['call_days_max_month'];
        if ($monthly_call_data['call_effective_count'] > 0) {
            $return['effectives_actual'] = $monthly_call_data['call_effective_count'];
        } else {
            $return['effectives_actual'] = 0;
        }
        $return['effectives_need'] = round((($kpis['effectives_per_call_day'] * $worked_days) - $monthly_call_data['call_effective_count'] + ($kpis['effectives_per_call_day'] * $working_days_for_remainder_of_month)));
        $return['effectives_need_per_day'] = round((($kpis['effectives_per_call_day'] * $worked_days) - $monthly_call_data['call_effective_count'] + ($kpis['effectives_per_call_day'] * $working_days_for_remainder_of_month))/$working_days_for_remainder_of_month);


        $return['meets_target'] = $kpis['meets_set_per_call_day'] * $return['call_days_max_month'];
        if ($monthly_call_data['meeting_set_count'] > 0) {
            $return['meets_actual'] = $monthly_call_data['meeting_set_count'];
        } else {
            $return['meets_actual'] = 0;
        }
        $return['meets_need'] = round((($kpis['meets_set_per_call_day'] * $worked_days) - $monthly_call_data['meeting_set_count'] + ($kpis['meets_set_per_call_day'] * $working_days_for_remainder_of_month)), 2);
        $return['meets_need_per_day'] = round((($kpis['meets_set_per_call_day'] * $worked_days) - $monthly_call_data['meeting_set_count'] + ($kpis['meets_set_per_call_day'] * $working_days_for_remainder_of_month))/$working_days_for_remainder_of_month, 2);

        // call rate = actual calls / elapsed time
        $return['call_rate'] = round($return['calls_actual'] / $return['call_days_elapsed'], 2);

        // effective rate = actual effectives / elapsed time
        $return['effectives_rate'] = round($return['effectives_actual'] / $return['call_days_elapsed'], 2);

        // meets rate = meets / elapsed time
        $return['meets_per_day'] = round($return['meets_actual'] / $return['call_days_elapsed'], 2);

        $return['conversion'] = round($return['meets_actual'] * 100 / $return['effectives_actual'], 2);

        $return['access'] = round($return['effectives_actual'] * 100 / $return['calls_actual'], 2);


        return $return;

    }
}

//{
//    public function doExecute(app_controller_Request $request)
//    {
//        $report_id = $request->getProperty('report_id');
//        $request->setObject('report_id', $report_id);
//
//        // Start date
//        if ($request->propertyExists('start'))
//        {
//            $start = $request->getProperty('start');
//        }
//        else
//        {
//            // Use first day of current month
//            $start = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
//        }
//
//        // End date
//        if ($request->propertyExists('end'))
//        {
//            $end = $request->getProperty('end');
//        }
//        else
//        {
//            // Use last day of current month
//            $end = date('Y-m-d', mktime(0, 0, 0, date('m')+1, 0, date('Y')));
//        }
//
//        // Client ID
//        if ($request->propertyExists('client_id'))
//        {
//            $client_id = $request->getProperty('client_id');
//        }
//        else
//        {
//            throw new Exception('Client ID not supplied');
//        }
//
//        // Filter ID
//        if ($request->propertyExists('filter_id'))
//        {
//            $filter_id = $request->getProperty('filter_id');
//        }
//        else
//        {
//            throw new Exception('Filter ID not supplied');
//        }
//
//	    // display front page statuses
//        if ($request->propertyExists('front_page_statuses'))
//        {
//            $front_page_statuses = $request->getProperty('front_page_statuses');
//        }
//        else
//        {
//            throw new Exception('front_page_statuses not supplied');
//        }
//
//    	// display front page figures
//        if ($request->propertyExists('front_page_figures'))
//        {
//            $front_page_figures = $request->getProperty('front_page_figures');
//        }
//        else
//        {
//            throw new Exception('$front_page_figures not supplied');
//        }
//
//    	// display front page figures
//        if ($request->propertyExists('summary_figures'))
//        {
//            $summary_figures = $request->getProperty('summary_figures');
//        }
//        else
//        {
//            throw new Exception('$summary_figures not supplied');
//        }
//
////        if ($request->propertyExists('summary_figures')) $summary_figures = true;
//
////        if ($request->propertyExists('meetings_set_summary')) $meetings_set_summary = true;
////        if ($request->propertyExists('cancellation_clinic')) $cancellation_clinic = true;
////        if ($request->propertyExists('opportunities_and_wins_clinic'))$opportunities_and_wins_clinic = true;
////        if ($request->propertyExists('targeting_clinic')) $targeting_clinic = true;
////        if ($request->propertyExists('database_analysis')) $database_analysis = true;
////        if ($request->propertyExists('effectives_analysis')) $effectives_analysis = true;
////        if ($request->propertyExists('nbm_discipline_effectiveness')) $nbm_discipline_effectiveness = true;
////        if ($request->propertyExists('nbm_industry_effectiveness')) $nbm_industry_effectiveness = true;
////        if ($request->propertyExists('pipeline_report')) $pipeline_report = true;
////        if ($request->propertyExists('effective_notes')) $effective_notes = true;
//
//        // Validate and set
//        $this->validateParameters($start, $end, $client_id);
//        $pdf = new app_report_Report8($start, $end, $client_id, $filter_id, $front_page_statuses, $front_page_figures, $summary_figures);
//        $pdf->Output(date('Y-m-d') . 'Line Listing Report.pdf', 'I');
//    }
//
//    /**
//     * Validates the input.
//     * @param string $start
//     * @param string $end
//     * @param integer $client_id
//     * @param boolean $full_history
//     * @return boolean
//     */
//    protected function validateParameters($start, $end, $client_id)
//    {
//        if ($end < $start)
//        {
//            throw new Exception('End date is before start date: (start => ' . $start . ', end => ' . $end . ')');
//        }
//    }
//
//}

?>