<?php

/**
 * Defines the app_command_NbmMonthlyPlannerGraph4 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/domain/Team.php');
require_once('include/Illumen/Graph.php');
require_once('include/jpgraph-2.2/jpgraph.php');
require_once('include/jpgraph-2.2/jpgraph_pie.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Performance vs Target: Meetings Set & Attended
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
class app_command_NbmMonthlyPlannerGraph4 extends app_command_Command
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
		$year_month = $request->getProperty('year_month');
		if (!Utils::isValidYearMonth($year_month))
		{
			throw new Exception('Year month either not provided or wrong type of date format');
		}
		
		// NBM ID
		$nbm_id = $request->getProperty('nbm_id');
		if (!$nbm_id)
		{
			throw new Exception('NBM ID not provided');
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
		$width        = 400;
		$height       = 250;
		
		// Create a new graph
		$gJpgBrandTiming = false;
		$graph = new PieGraph($width, $height, 'auto');
//		$graph->SetColor('#F9F9F9');
		$graph->SetColor('#FFFFFF');
		
		// Background
		$graph->SetMarginColor($bgcolor);
		$graph->SetScale('textint');
		$graph->SetBox(false);
		$graph->SetFrame(true, $bgcolor, 1);
		$graph->yaxis->scale->SetGrace(10);
		
		// Title
		$graph->title->SetFont(FF_FONT1);
		$title = 'Activity by Client';
		$graph->title->Set($title);
		
		// Labels
		$labels = array('Target', 'Actual');
		$graph->xaxis->SetTickLabels($labels);
		
		// Get data arrays
		$data = $this->getData($nbm_id, $year_month);
		
		if (count($data) > 0)
		{
			// Create the bar plots
			$plot = new PiePlot(array_values($data));
			$plot->value->Show();
			$plot->value->SetFormat('%d');
			$plot->value->SetColor('black');
			$plot->SetLegends(array_keys($data));
			$plot->SetCenter(0.27);
//			$plot->Explode(array(5, 5, 5));
			
			// Add it to the graph
			$graph->Add($plot);
			
			// Adjust the legend position
			$graph->legend->SetLayout(LEGEND_VERT);
			$graph->legend->Pos(0.77, 0.5, 'center', 'bottom');
			$graph->legend->SetFillColor('white');
			$graph->legend->SetShadow(false);
		}
		else
		{
			$txt = new Text('Pie chart cannot be shown, sum of all data is zero.');
			$txt->SetPos(0.5, 0.5, 'center', 'middle');
			$graph->AddText($txt);
		}	

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
	 * @param string $nbm_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return array
	 */	
	private function getData($nbm_id, $year_month)
	{
		$planning_data = app_domain_CampaignNbmTarget::findStatisticsByUserIdAndYearMonth($nbm_id, $year_month);
		$array = array();
		foreach ($planning_data as $data)
		{
			$actual_effectives = $data['offte'] + $data['ote'];
			if ($actual_effectives > 0)
			{
				$client_name = C_String::truncate($data['client_name'], 23); 
				$array[$client_name] = $actual_effectives;
			}
		}
		return $array;
	}

}

?>