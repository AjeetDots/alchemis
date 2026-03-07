<?php

/**
 * Defines the app_command_ReportGraph4_2 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Team.php');
require_once('include/Illumen/Graph.php');
require_once('include/jpgraph-2.2/jpgraph.php');
require_once('include/jpgraph-2.2/jpgraph_bar.php');
require_once('include/jpgraph-2.2/jpgraph_pie.php');
require_once('include/Utils/Utils.class.php');

//$dsn = app_base_ApplicationRegistry::getDSN();
//$username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
//$password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
//$database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
//$hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
//define('DB_HOST',     $hostname);
//define('DB_NAME',     $database);
//define('DB_USER',     $username);
//define('DB_PASSWORD', $password);

/**
 * Required per Day to Keep on Target: Meetings Set & Attended
 * 
 * Expects to be able to find the following parameters in the request:
 *  - string   start_date              in the format 'YYYY-MM-DD'
 *  - string   end_date                in the format 'YYYY-MM-DD'
 *  - integer  team_id     (optional)  filters by NBMs in a given team
 *  - integer  nbm_id      (optional)  filters by NBMs in a given team, overriding any supplied team_id
 *  - string   media       (optional)  if 'print' supplied, ensures background is white
 *  - string   file        (optional)  saves the graph to 'app/report/tmp/' if a file name is supplied
 * 
 * @package Alchemis
 */
class app_command_ReportGraph4_2 extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$this->init($request);
		return self::statuses('CMD_OK');
	}

	/**
	 * Initialise the graph.
	 * Expects to be able to find the following parameters in the request:
	 *  - string   start_date              in the format 'YYYY-MM-DD'
	 *  - string   end_date                in the format 'YYYY-MM-DD'
	 *  - integer  team_id     (optional)  filters by NBMs in a given team
	 *  - integer  nbm_id      (optional)  filters by NBMs in a given team, overriding any supplied team_id
	 *  - string   media       (optional)  if 'print' supplied, ensures background is white
	 *  - string   file        (optional)  saves the graph to 'app/report/tmp/' if a file name is supplied
	 */
	protected function init(app_controller_Request $request)
	{
		// Start date
		$start_date = $request->getProperty('start');
		if (!Utils::isValidDate($start_date))
		{
			throw new Exception('Start date either not provided or wrong type of date format');
		}
		
		// End date
		$end_date = $request->getProperty('end');
		if (!Utils::isValidDate($end_date))
		{
			throw new Exception('End date either not provided or wrong type of date format');
		}
		
		// Team ID
		$team_id = $request->getProperty('team_id');
		if (!$team_id)
		{
			$team_id = null;
		}
		
		// NBM ID
		$nbm_id = $request->getProperty('nbm_id');
		if (!$nbm_id)
		{
			$nbm_id = null;
		}

		// Media
		$media = $request->getProperty('media');
		if ($media == 'print')
		{
			$bgcolor = '#FFFFFF';
		}
		else
		{
			$bgcolor = '#E9E9E9';
		}

		// Set sizes
		$topMargin    = 30;
		$bottomMargin = 32;
		$leftMargin   = 30;
		$rightMargin  = 30;
		$width        = 450;
		$height       = 600;
		
		// Create a new graph
		$gJpgBrandTiming = false;
		$graph = new Graph($width, $height, 'auto');
		$graph->SetColor('#F9F9F9');
		
		// Background
		$graph->SetMarginColor($bgcolor);
		$graph->SetScale('textint');
		$graph->SetBox(false);
		$graph->SetFrame(true, $bgcolor, 1);
		$graph->yaxis->scale->SetGrace(10);
		
		// Title
		$month_end_date = date('d/m/Y', mktime(0, 0, 0, date('m', strtotime($end_date)) + 1, 0, date('Y', strtotime($end_date))));
		$graph->title->SetFont(FF_FONT1);
		$title = "Required per Day to be on Target at the end of " . $month_end_date . ":\n";
		$title .= "Meetings Set & Attended\n\n";
		$title .= $this->getCurrentMeetsSetText($start_date, $end_date, $team_id, $nbm_id) . "\n";
		$title .= $this->getCurrentMeetsAttendedText($start_date, $end_date, $team_id, $nbm_id) . "\n\n";
		$title .= $this->getRemainingMeetsSetText($start_date, $end_date, $team_id, $nbm_id) . "\n";
		$title .= $this->getRemainingMeetsAttendedText($start_date, $end_date, $team_id, $nbm_id) . "\n\n";
		$graph->title->Set($title);
		
		// Labels
		$labels = array('Current per Day', 'Required per Day');
		$graph->xaxis->SetTickLabels($labels);
		
		// Get data arrays
		$data = $this->getData($start_date, $end_date, $team_id, $nbm_id);
		$data1y = array($data['current']['meets_set'], $data['required']['meets_set']);
		$data2y = array($data['current']['meets_attended'], $data['required']['meets_attended']);

		// Create the bar plots
		$b1plot = new BarPlot($data1y);
		$b1plot->SetFillColor(Illumen_Graph::getColor(0));
		$b1plot->SetPattern(PATTERN_DIAG1, 'black');
		$b1plot->value->Show();
		$b1plot->value->SetFormat('%d');
		$b1plot->value->SetColor('black');
		$b1plot->SetLegend('Meets Set');
		
		$b2plot = new BarPlot($data2y);
		$b2plot->SetFillColor(Illumen_Graph::getColor(2));
		$b2plot->value->Show();
		$b2plot->value->SetFormat('%d');
		$b2plot->value->SetColor('black');
		$b2plot->SetLegend('Meets Attended');
		
		// Create the grouped bar plot 
		$gbplot = new GroupBarPlot(array($b1plot, $b2plot));
		
		// Add it to the graph
		$graph->Add($gbplot);
		
		// Adjust the legend position
		$graph->legend->SetLayout(LEGEND_HOR);
		$graph->legend->Pos(0.5, 0.95, 'center', 'bottom');
		$graph->legend->SetFillColor('white');
		$graph->legend->SetShadow(false);
		
		// Output the graph
		$file = $request->getProperty('file');
		if ($file)
		{
			$graph->Stroke('app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file);
		}
		else
		{
			$graph->Stroke();
		}
	}

	/**
	 * Pull together the data and return.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return array
	 */	
	private function getData($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Get the individual figures
		$current_meetings_set       = round(app_domain_ReportReader::getAverageMeetingsSet($start_date, $end_date, $team_id, $nbm_id));
		$current_meetings_attended  = round(app_domain_ReportReader::getAverageMeetingsAttended($start_date, $end_date, $team_id, $nbm_id));
		$required_meetings_set      = round(app_domain_ReportReader::getRequiredMeetingsSet($start_date, $end_date, $team_id, $nbm_id));
		$required_meetings_attended = round(app_domain_ReportReader::getRequiredMeetingsAttended($start_date, $end_date, $team_id, $nbm_id));
		
		// Construct an array and return
		return array('current'  => array('meets_set' => $current_meetings_set, 'meets_attended' => $current_meetings_attended),
		             'required' => array('meets_set' => $required_meetings_set, 'meets_attended' => $required_meetings_attended));
	}

	/**
	 * Return a string describing the number of calls made to date.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getCurrentMeetsSetText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Get the current number of calls made
		$meets_set = app_domain_ReportReader::getActualMeetingsSet($start_date, $end_date, $team_id, $nbm_id);
		
		// Get number of working days over which these have been made
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		
		// Work out the average number of calls per day
		$average = round($meets_set / $working_days);
		
		return $meets_set . ' meets set in ' . $working_days . ' working days @ ' . $average . ' per day';
	}

	/**
	 * Return a string describing the remaining effectives that need to be made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getCurrentMeetsAttendedText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Get the current number of effectives made
		$meets_attended = app_domain_ReportReader::getActualMeetingsAttended($start_date, $end_date, $team_id, $nbm_id);
		
		// Get number of working days over which these have been made
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		
		// Work out the average number of effectives per day
		$average = round($meets_attended / $working_days);
		
		return $meets_attended . ' meets attended in ' . $working_days . ' working days @ ' . $average . ' per day';
	}

	/**
	 * Return a string describing the remaining meetings that need to be set.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getRemainingMeetsSetText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Work out last day of the month for the period end month
		$month_end_date = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($end_date)) + 1, 0, date('Y', strtotime($end_date))));
		
		// Get the current number of meeting set
		$meets_set = app_domain_ReportReader::getActualMeetingsSet($start_date, $end_date, $team_id, $nbm_id);
		
		// Get the target number of meetings that should be set by the end of the month 
		$end_of_month_target = app_domain_ReportReader::getTargetMeetingsSet($start_date, $month_end_date, $team_id, $nbm_id);
		
		// Get number of meets that remain to be set
		$remaining_meets = ($end_of_month_target - $meets_set);

		// Get number of working days remaining in month (we minus 1 intentionally)
		$working_days_remaining_in_month = Utils::getWorkingDays($end_date, $month_end_date) - 1;
		
		// Work out the average number of calls that need to be made for each day remaining
		if ($working_days_remaining_in_month > 0)
		{
			$average = round(($end_of_month_target - $meets_set) / $working_days_remaining_in_month);
		}
		else
		{
			$average = 0;
		}
		
		return $remaining_meets . ' meets to be set in ' . $working_days_remaining_in_month . ' working days @ ' . $average . ' per day';
	}

	/**
	 * Return a string describing the remaining meets attended that that need to be set.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getRemainingMeetsAttendedText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Work out last day of the month for the period end month
		$month_end_date = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($end_date)) + 1, 0, date('Y', strtotime($end_date))));
		
		// Get the current number of meeting set
		$meets_set = app_domain_ReportReader::getActualMeetingsAttended($start_date, $end_date, $team_id, $nbm_id);
		
		// Get the target number of meetings that should be set by the end of the month 
		$end_of_month_target = app_domain_ReportReader::getTargetMeetingsAttended($start_date, $month_end_date, $team_id, $nbm_id);
		
		// Get number of meets that remain to be set
		$remaining_meets = ($end_of_month_target - $meets_set);

		// Get number of working days remaining in month (we minus 1 intentionally)
		$working_days_remaining_in_month = Utils::getWorkingDays($end_date, $month_end_date) - 1;
		
		// Work out the average number of calls that need to be made for each day remaining
		if ($working_days_remaining_in_month > 0)
		{
			$average = round(($end_of_month_target - $meets_set) / $working_days_remaining_in_month);
		}
		else
		{
			$average = 0;
		}
		
		return $remaining_meets . ' meets to be attended in ' . $working_days_remaining_in_month . ' working days @ ' . $average . ' per day';
	}

}

?>