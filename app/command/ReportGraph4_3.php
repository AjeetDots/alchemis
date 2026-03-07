<?php

/**
 * Defines the app_command_ReportGraph4_3 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Team.php');
require_once('app/domain/Configuration.php');
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
 * Performance vs Target: Calls & Effectives
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
class app_command_ReportGraph4_3 extends app_command_Command
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
		$graph->title->SetFont(FF_FONT1);
		$title = "Performance vs Target: Calls & Effectives\n\n\n";
		$title .= $this->getTargetCallsText($start_date, $end_date, $team_id, $nbm_id) . "\n";
		$title .= $this->getTargetEffectivesText($start_date, $end_date, $team_id, $nbm_id) . "\n\n";
		$title .= $this->getActualCallsText($start_date, $end_date, $team_id, $nbm_id) . "\n";
		$title .= $this->getActualEffectivesText($start_date, $end_date, $team_id, $nbm_id) . "\n\n";
		$graph->title->Set($title);
		
		// Labels
		$labels = array('Target', 'Actual');
		$graph->xaxis->SetTickLabels($labels);
		
		// Get data arrays
		$data = $this->getData($start_date, $end_date, $team_id, $nbm_id);
		$data1y = array($data['target']['calls'], $data['actual']['calls']);
		$data2y = array($data['target']['effectives'], $data['actual']['effectives']);

		// Create the bar plots
		$b1plot = new BarPlot($data1y);
		$b1plot->SetFillColor(Illumen_Graph::getColor(0));
		$b1plot->SetPattern(PATTERN_DIAG1, 'black');
		$b1plot->value->Show();
		$b1plot->value->SetFormat('%d');
		$b1plot->value->SetColor('black');
		$b1plot->SetLegend('Calls');
		
		$b2plot = new BarPlot($data2y);
		$b2plot->SetFillColor(Illumen_Graph::getColor(2));
		$b2plot->value->Show();
		$b2plot->value->SetFormat('%d');
		$b2plot->value->SetColor('black');
		$b2plot->SetLegend('Effectives');
		
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
		$target_calls      = app_domain_ReportReader::getTargetCalls($start_date, $end_date, $team_id, $nbm_id);
		$target_effectives = app_domain_ReportReader::getTargetEffectives($start_date, $end_date, $team_id, $nbm_id);
		$actual_calls      = app_domain_ReportReader::getActualCalls($start_date, $end_date, $team_id, $nbm_id);
		$actual_effectives = app_domain_ReportReader::getActualEffectives($start_date, $end_date, $team_id, $nbm_id);
		
		// Construct an array and return
		return array('target' => array('calls' => $target_calls, 'effectives' => $target_effectives),
		             'actual' => array('calls' => $actual_calls, 'effectives' => $actual_effectives));
	}

	/**
	 * Return a string describing the remaining calls that need to be made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getTargetCallsText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Get the target number of calls 
		$calls = app_domain_ReportReader::getTargetCalls($start_date, $end_date, $team_id, $nbm_id);
		
		// Get number of working days
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		
		return 'Target of ' . $calls . ' calls made in ' . $working_days . ' working days';
	}

	/**
	 * Return a string describing the remaining effectives that need to be made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getTargetEffectivesText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Get the target number of effectives that should be made by the end of the month 
		$effectives = app_domain_ReportReader::getTargetEffectives($start_date, $end_date, $team_id, $nbm_id);
		
		// Get number of working days
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		
		return 'Target of ' . $effectives . ' effectives made in ' . $working_days . ' working days';
	}

	/**
	 * Return a string describing the number of calls made to date.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getActualCallsText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Get the current number of calls made
		$calls = app_domain_ReportReader::getActualCalls($start_date, $end_date, $team_id, $nbm_id);
		
		// Get number of working days over which these have been made
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		
		return 'Actually made ' . $calls . ' calls in ' . $working_days . ' working days';
	}

	/**
	 * Return a string describing the remaining effectives that need to be made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return string
	 */
	protected function getActualEffectivesText($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Get the current number of effectives made
		$effectives = app_domain_ReportReader::getActualEffectives($start_date, $end_date, $team_id, $nbm_id);
		
		// Get number of working days over which these have been made
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		
		return 'Actually made ' . $effectives . ' effectives in ' . $working_days . ' working days';
	}

}

?>