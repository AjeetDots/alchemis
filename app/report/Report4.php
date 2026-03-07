<?php

/**
 * Defines the app_report_Report1 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');
require_once('include/fpdf/fpdf.php');
require_once('include/EasySql/EasySql.class.php');
require_once('include/Utils/Utils.class.php');

/**
 * @package Alchemis
 */
class app_report_Report4 extends FPDF
{

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
		parent::__construct('P');
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
	 * NB Function was failing due to Undefined index: auth_session in Session.php::getSessionUser. 
	 * Writes the graph image to a file so that it can be imported by FPDF.
	 * NB Function was failing due to Undefined index: auth_session in Session.php::getSessionUser. 
	 * @param string $dest
	 */
	private function writeImage($dest)
	{
		if ($_SERVER['SERVER_PORT'] == 443)
		{
			$url = 'http://localhost' . app_base_ApplicationRegistry::getUrl();
		}
		else
		{
			$url = 'http://localhost' . app_base_ApplicationRegistry::getUrl();
		}
		
		$full_url = $url . $dest;
		$ch = curl_init($full_url);
		if (!$ch)
		{
			die('Cannot allocate a new PHP-CURL handle');
		}
		curl_setopt($ch, CURLOPT_FAILONERROR,    1);
//		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  // allow redirects 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
	}

}

?>