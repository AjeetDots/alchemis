<?php

/**
 * Defines the app_report_Report1 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');
require_once('app/controller/Request.php');
require_once('app/command/ReportGraph4_1.php');
require_once('app/command/ReportGraph4_2.php');
require_once('app/command/ReportGraph4_3.php');
require_once('app/command/ReportGraph4_4.php');
require_once('include/fpdf/fpdf.php');
require_once('include/EasySql/EasySql.class.php');
require_once('include/Utils/Utils.class.php');

/**
 * @package Alchemis
 */
class app_report_Report4 extends FPDF
{

    /**
     * Parameters used to build the report header and image URLs.
     *
     * @var array
     */
    protected $params = array();

	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param integer $team_id
	 * @param integer $nbm_id
	 */
	public function __construct($start, $end, $team_id = null, $nbm_id = null)
	{
		$this->params['start']   = $start;
		$this->params['end']     = $end;
		$this->params['team_id'] = $team_id;
		$this->params['nbm_id']  = $nbm_id;

		// PHP 8 compatibility: some environments use the legacy PHP4-style
		// FPDF() constructor, others use __construct(). Call whichever exists.
		if (method_exists('FPDF', '__construct')) {
			parent::__construct('P');
		} else {
			// Fallback for very old FPDF versions that only define FPDF()
			parent::FPDF('P');
		}

		$this->AliasNbPages();
//		$this->LineItems();
	}
	
	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 4', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(90, 5, 'Sales Team Summary vs Target for Period', 0, 0, 'C', 0);
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'R', 0);
		$this->Ln();
		
		// Work out title
		if (!is_null($this->params['nbm_id']))
		{
			$nbm_name = app_domain_RbacUser::getUserName($this->params['nbm_id']);
			$title = $nbm_name . ' for the period ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end']));
			$datasource = 'Data source: NBM Monthly Planner';
		}
		elseif (!is_null($this->params['team_id']))
		{
			$team_name = app_domain_Team::getTeamName($this->params['team_id']);
			$title = $team_name . ' for the period ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end']));
			$datasource = 'Data source: NBM Monthly Planner';
		}
		else
		{
			$title = 'For the period ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end']));
			$datasource = 'Data source: Campaign Targets';
		}
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
		$this->Cell(90, 5, $title, 0, 0, 'C', 0);
		$this->Cell(50, 5, $datasource, 0, 0, 'R', 0);
//		$this->Ln();
//		
//		$this->SetFont('Arial', '', 6);
//		$this->Cell(50, 5, '', 0, 0, 'L', 0);
//		$this->Cell(90, 5, $datasource, 0, 0, 'C', 0);
//		$this->Cell(50, 5, '', 0, 0, 'R', 0);
		$this->Ln(10);
		$this->addGraphs();
		
//		$this->Ln(10);
//		$this->SetFont('Arial', '', 8);
//		$this->Cell(90, 5, 'Performance vs Target: Meetings Set & Attended', 0, 0, 'C', 0);
//		$this->Cell(10, 5, '', 0, 0, 'C', 0);
//		$this->Cell(90, 5, 'Required per Day to Keep on Target: Meetings Set & Attended', 0, 0, 'C', 0);
//		$this->Ln(130);
//		$this->SetFont('Arial', '', 8);
//		$this->Cell(90, 5, 'Performance vs Target: Calls & Effectives', 0, 0, 'C', 0);
//		$this->Cell(10, 5, '', 0, 0, 'C', 0);
//		$this->Cell(90, 5, 'Required per Day to Keep on Target: Calls & Effectives', 0, 0, 'C', 0);
//		$this->Ln(5);
	}

	private function outputHeader()
	{
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(14, 5, '', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'IMPV', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Effect', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Atd', 1, 0, 'C', 0);

		$this->SetFont('Arial', '', 8);
		$this->Cell(5, 5, '', 0, 0, 'C', 0);
		$this->Cell(14, 5, '', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Days', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Effect', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Campaign', 1, 0, 'C', 0);
		
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(14, 5, 'Target', 1, 0, 'C', 0);
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(14, 5, 'Impv', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Set actual', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Camp atd', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Atd tgt', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Atd act', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Diary', 1, 0, 'C', 0);

		$this->Cell(5, 5, '', 0, 0, 'C', 0);

		$this->Cell(14, 5, '', 1, 0, 'C', 0);
		$this->Cell(14, 5, 'Effect', 1, 0, 'C', 0);

		$this->SetFont('Arial', 'B', 8);
		$this->Cell(14, 5, 'Target', 1, 0, 'C', 0);

		$this->SetFont('Arial', '', 8);
		$this->Cell(14, 5, 'Impv', 1, 0, 'C', 0);
		$this->Ln();
	}

	private function addGraphs()
	{
		$path = 'app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
		
		if (!is_null($this->params['nbm_id']))
		{
			$str = '&nbm_id=' . $this->params['nbm_id'];
		}
		elseif (!is_null($this->params['team_id']))
		{
			$str = '&team_id=' . $this->params['team_id'];
		}
		else
		{
			$str = '';
		}
		
		// Image 1
		$filename = 'ReportGraph4_1_' . mt_rand() . '.png';
		$dest = 'index.php?cmd=ReportGraph4_1&media=print&file=' . $filename . '&start=' . $this->params['start'] . '&end=' . $this->params['end'] . $str;
		$this->writeImage($dest);
		$img = $path . $filename;
		// Ensure the graph image was actually created before attempting to embed it
		if (!file_exists($img)) {
			throw new Exception('No graph file found! Looking for: ' . $img);
		}
		$this->Image($img, 10, 30, 90, 120);
		unlink($path . $filename);
		
		// Image 2
		$filename = 'ReportGraph4_2_' . mt_rand() . '.png';
		$dest = 'index.php?cmd=ReportGraph4_2&media=print&file=' . $filename . '&start=' . $this->params['start'] . '&end=' . $this->params['end'] . $str;
		$this->writeImage($dest);
		$img = $path . $filename;
		$this->Image($img, 110, 30, 90, 120);
		unlink($path . $filename);
		
		// Image 3
		$filename = 'ReportGraph4_3_' . mt_rand() . '.png';
		$dest = 'index.php?cmd=ReportGraph4_3&media=print&file=' . $filename . '&start=' . $this->params['start'] . '&end=' . $this->params['end'] . $str;
		$this->writeImage($dest);
		$img = $path . $filename;
		$this->Image($img, 10, 160, 90, 120);
		unlink($path . $filename);
		
		// Image 4
		$filename = 'ReportGraph4_4_' . mt_rand() . '.png';
		$dest = 'index.php?cmd=ReportGraph4_4&media=print&file=' . $filename . '&start=' . $this->params['start'] . '&end=' . $this->params['end'] . $str;
		$this->writeImage($dest);
		$img = $path . $filename;
		$this->Image($img, 110, 160, 90, 120);
		unlink($path . $filename);
	}

	/**
	 * Generate a graph image file by invoking the appropriate ReportGraph4_X
	 * command directly (avoids HTTP/cURL and file path issues).
	 *
	 * @param string $dest e.g. 'index.php?cmd=ReportGraph4_1&media=print&file=...'
	 * @throws Exception if the graph command is unknown or fails.
	 */
	private function writeImage($dest)
	{
		$query = parse_url($dest, PHP_URL_QUERY);
		if ($query === null) {
			throw new Exception('Invalid graph destination: ' . $dest);
		}

		$params = array();
		parse_str($query, $params);

		if (!isset($params['cmd'])) {
			throw new Exception('Missing graph command in destination: ' . $dest);
		}

		$cmdName = $params['cmd'];

		// Map cmd parameter to concrete command class
		switch ($cmdName) {
			case 'ReportGraph4_1':
				$command = new app_command_ReportGraph4_1();
				break;
			case 'ReportGraph4_2':
				$command = new app_command_ReportGraph4_2();
				break;
			case 'ReportGraph4_3':
				$command = new app_command_ReportGraph4_3();
				break;
			case 'ReportGraph4_4':
				$command = new app_command_ReportGraph4_4();
				break;
			default:
				throw new Exception('Unknown graph command: ' . $cmdName);
		}

		// Ensure the output directory exists
		$outputDir = 'app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
		if (!is_dir($outputDir)) {
			@mkdir($outputDir, 0777, true);
		}

		// Build an internal request carrying the same parameters
		$request = new app_controller_Request();
		foreach ($params as $key => $value) {
			$request->setProperty($key, $value);
		}

		// Execute the graph command; it will write the PNG into app/report/tmp
		$command->execute($request);
	}

}

?>