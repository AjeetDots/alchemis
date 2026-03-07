<?php

/**
 * Defines the app_command_ReportGraph4_1 class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */


require_once('app/domain/ReportReader.php');
//require_once('app/domain/Team.php');
require_once('include/Illumen/Graph.php');
require_once('include/jpgraph-2.3/jpgraph.php');
require_once('include/jpgraph-2.3/jpgraph_bar.php');
require_once('include/jpgraph-2.3/jpgraph_pie.php');
require_once('include/Utils/Utils.class.php');

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
class app_command_ReportGraph8_1 extends app_command_Command
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
		//echo "Here:";

		// Start date
		$start_date = $request->getProperty('start');
		//		if (!Utils::isValidDate($start_date))
		//		{
		//			throw new Exception('Start date either not provided or wrong type of date format');
		//		}
		//
		//		// End date
		$end_date = $request->getProperty('end');

		// 		echo 'end_date = ' . $request->getProperty('end') . '<br />';

		//		if (!Utils::isValidDate($end_date))
		//		{
		//			throw new Exception('End date either not provided or wrong type of date format');
		//		}

		//		// Team ID
		//		$team_id = $request->getProperty('team_id');
		//		if (!$team_id)
		//		{
		//			$team_id = null;
		//		}

		// Client ID
		$client_id = $request->getProperty('client_id');
		if (!$client_id) {
			$client_id = null;
		}

		// 		echo 'start_date =  ' . $start_date . '<br />';
		// 		echo 'end_date = ' . $end_date . '<br />';
		// 		echo 'client_id = ' . $client_id . '<br />';

		//
		//		$data = app_domain_ReportReader::getReport8CampaignSummary($start_date, $end_date, $client_id);
		////		$data = app_domain_ReportReader::getReport8CampaignSummary($this->params['start'], $this->params['end'], $this->params['client_id']);
		//
		//		echo ('test2:<p>' . $data[0]['meets_live']);
		//		echo "Here";
		//		throw new exception (print_r($data));
		//		exit();

		// Media
		$media = $request->getProperty('media');
		if ($media == 'print') {
			$bgcolor = '#FFFFFF';
		} else {
			$bgcolor = '#E9E9E9';
		}


		#  // Standard inclusions

		require_once("include/pChart/pChart/pData.class");
		require_once("include/pChart/pChart/pChart.class");

		// Dataset definition
		$DataSet = new pData;
		// Get data arrays

		//		require_once('/var/www/html/alchemis/include/EasySql/EasySql.class.php');
		//		require_once('/var/www/html/alchemis/include/Utils/Utils.class.php');

		require_once('include/EasySql/EasySql.class.php');
		require_once('include/Utils/Utils.class.php');


		$data = app_domain_ReportReader::getReport8CampaignSummary($start_date, $end_date, $client_id);
		$data1y = array($data[0]['meets_set_target_to_date'], $data[0]['meets_attended_target_to_date']);
		$data2y = array($data[0]['meets_set_to_date'], $data[0]['meets_attended_to_date']);


		$DataSet->AddPoint($data1y, "Serie1");
		$DataSet->AddPoint($data2y, "Serie2");


		//$DataSet->AddPoint(array(1,4,-3,2,-3,3,2,1,0,7,4),"Serie1");
		//$DataSet->AddPoint(array(3,3,-4,1,-2,2,1,0,-1,6,3),"Serie2");

		$DataSet->AddPoint(array('Meets set', 'Meets Attended'), "Serie3");

		//		  $DataSet->AddAllSeries();
		$DataSet->AddSerie("Serie1");
		$DataSet->AddSerie("Serie2");


		$DataSet->SetAbsciseLabelSerie("Serie3");
		$DataSet->SetSerieName("Target", "Serie1");
		$DataSet->SetSerieName("Actual", "Serie2");
		//		  $DataSet->SetSerieName("March","Serie3");

		// Initialise the graph
		$Test = new pChart(400, 240);

		$Test->setFontProperties("include/pChart/Fonts/tahoma.ttf", 8);
		$Test->setGraphArea(40, 20, 360, 210);
		//		  $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
		//	  $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
		$Test->drawGraphArea(255, 255, 255);
		$scale = SCALE_ADDALLSTART0;
		$num = $data[0]['meets_set_target_to_date'] + $data[0]['meets_set_to_date'];
		if ($num <= 10) {
			$scale = SCALE_NORMAL0;
			$Test->VMin = 0;
			$Test->VMax = 10;
			$Test->Divisions = 5;
		}

		$Test->drawScale($DataSet->GetData(), $DataSet->GetDataDescription(), $scale, 0, 0, 0, TRUE, 0, 0, TRUE);
		//		  $Test->drawGrid(4,TRUE,230,230,230,50);

		//		  // Draw the 0 line
		//		  $Test->setFontProperties("include/pChart/Fonts/tahoma.ttf",6);
		//		  $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

		// Draw the bar graph
		$Test->drawBarGraph($DataSet->GetData(), $DataSet->GetDataDescription(), TRUE);

		// Finish the graph
		$Test->setFontProperties("include/pChart/Fonts/tahoma.ttf", 8);
		$Test->drawLegend(150, 40, $DataSet->GetDataDescription(), 255, 255, 255);
		//$Test->setFontProperties("include/pChart/Fonts/tahoma.ttf",10);
		//$Test->drawTitle(50,22,"Example 12",50,50,50,585);

		$Test->addBorder(1);

		// Output the graph
		$file = $request->getProperty('file');


		//		  $Test->Render("example12.png");
		$Test->Render('app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file);
		// echo '<img src="app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file . '"/>';






		//		// Set sizes
		//		$topMargin    = 30;
		//		$bottomMargin = 32;
		//		$leftMargin   = 30;
		//		$rightMargin  = 30;
		//		$width        = 100;
		//		$height       = 100;
		//
		//		// Create a new graph
		//		$gJpgBrandTiming = false;
		//		$graph = new Graph($width, $height, 'auto');
		//		$graph->SetColor('#F9F9F9');
		//
		//		// Background
		//		$graph->SetMarginColor($bgcolor);
		//		$graph->SetScale('textint');
		//		$graph->SetBox(false);
		//		$graph->SetFrame(true, $bgcolor, 1);
		//		$graph->yaxis->scale->SetGrace(10);
		//
		//		// Title
		//		$graph->title->SetFont(FF_FONT1);
		//		$title = "Set & Attended vs Target\n\n\n";
		//		$graph->title->Set($title);
		//
		//		// Labels
		//		$labels = array('Target', 'Actual');
		//		$graph->xaxis->SetTickLabels($labels);
		//
		//		// Get data arrays
		////		$data = $this->getData($start_date, $end_date, $client_id);
		//		$data = app_domain_ReportReader::getReport8CampaignSummary($start_date, $end_date, $client_id);
		////		echo "Here";
		////		print_r($data_test);
		////		exit();
		//
		//		$data1y = array($data[0]['meets_set_target_to_date'], $data[0]['meets_set_to_date']);
		//		$data2y = array($data[0]['meets_attended_target_to_date'], $data[0]['meets_attended_to_date']);
		//
		////		$data3y = array($data['target_att']['meets_set'], $data['actual_set']['meets_set']);
		////		$data4y = array($data['target_att']['meets_attended'], $data['actual_set']['meets_attended']);
		//
		//		// Create the bar plots
		//		$b1plot = new BarPlot($data1y);
		//		$b1plot->SetFillColor(Illumen_Graph::getColor(0));
		//		$b1plot->SetPattern(PATTERN_DIAG1, 'black');
		//		$b1plot->value->Show();
		//		$b1plot->value->SetFormat('%d');
		//		$b1plot->value->SetColor('black');
		//		$b1plot->SetLegend('Meets Set');
		//
		//		$b2plot = new BarPlot($data2y);
		//		$b2plot->SetFillColor(Illumen_Graph::getColor(2));
		//		$b2plot->value->Show();
		//		$b2plot->value->SetFormat('%d');
		//		$b2plot->value->SetColor('black');
		//		$b2plot->SetLegend('Meets Attended');
		//
		//		// Create the grouped bar plot
		//		$gbplot = new GroupBarPlot(array($b1plot, $b2plot));
		//
		//		// Add it to the graph
		//		$graph->Add($gbplot);
		//
		//		// Adjust the legend position
		//		$graph->legend->SetLayout(LEGEND_HOR);
		//		$graph->legend->Pos(0.5, 0.95, 'center', 'bottom');
		//		$graph->legend->SetFillColor('white');
		//		$graph->legend->SetShadow(false);
		//
		//		// Output the graph
		//		$file = $request->getProperty('file');
		//
		//		echo $file;
		//
		//		if ($file)
		//		{
		//			$graph->Stroke('/var/www/html/alchemis/app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file);
		//		}
		//		else
		//		{
		//			$graph->Stroke();
		//		}
	}
}
