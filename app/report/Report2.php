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
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_report_Report2 extends FPDF
{
	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 */
	public function __construct($start, $end, $nbm_exclusions = null, $client_id = null)
	{
		$this->params['start']          = $start . ' 00:00:00';
		$this->params['end']            = $end . ' 23:59:59';
		$this->params['nbm_exclusions'] = $nbm_exclusions;
		$this->params['client_id'] = $client_id;
		parent::__construct('P');
		$this->AliasNbPages();
		$this->Body();
		$this->Legend();
	}
	
	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 2', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(90, 5, 'Basic Sales Team Activity Statistics', 0, 0, 'C', 0);
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'R', 0);
		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
		$this->Cell(90, 5, 'For period ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end'])), 0, 0, 'C', 0);
		$this->Cell(50, 5, '', 0, 0, 'R', 0);
		$this->Ln(10);
	}

	/**
	 * Output report body
	 */
	public function Body()
	{
		$this->AddPage();
		$this->SetFont('Arial', 'B', 8);
		$this->OutputHeader();
		$this->SetFont('Arial','', 8);

		// Layout
		$this->SetFont('Arial', '', 8);
		
		// Initiate cumulative totals
		$cumulative_totals = array();
		$cumulative_totals['impv']   = 0;
		$cumulative_totals['effect'] = 0;
		$cumulative_totals['atd']    = 0;
		
		// Initiate totals
		$totals = array();
		$totals['calls']                = 0;
		$totals['non_effectives']       = 0;
		$totals['on_target']            = 0;
		$totals['off_target']           = 0;
		$totals['total_effectives']     = 0;
		$totals['conversion']           = 0;
		$totals['meetings']             = 0;
		$totals['attended']             = 0;
		$totals['kpis']                 = 0;
		$totals['calls_logged_by_10am'] = 0;
		$totals['tasks_completed']      = 0;
		$totals['tasks_outstanding']    = 0;
		$totals['meets_diary_overdue']  = 0;
		$totals['meets_diary_pending']  = 0;
		$totals['super_kpis']           = 0;
		
		// Get the data
		$data = app_domain_ReportReader::getReport2Data($this->params['start'], $this->params['end'], $this->params['nbm_exclusions'], $this->params['client_id']);
		$banding = floor(count($data) / 3);
		for ($i = 0; $i < count($data); $i++)
		{
			$data[$i]['highlight'] = 0;
		}
		
		// Sort the data in ascending order to mark the bottom 3rd
		$data = Utils::msort($data, 'kpis');
		for ($i = 0; $i < $banding; $i++)
		{
			$data[$i]['highlight'] = -1;
		}
		
		// Sort the data in descending order to mark the bottom 3rd
		$data = Utils::msort($data, 'kpis', true);
		for ($i = 0; $i < $banding; $i++)
		{
			$data[$i]['highlight'] = 1;
		}
		
		// Sort by NBM name
		$data = Utils::msort($data, 'nbm');
		$team_totals = array();
		$i = 0;
		foreach($data as $row)
		{
			if ($row['highlight'] < 0)
			{
				$this->SetFillColor(105, 255, 255);
				$fill = 1;
			}
			elseif ($row['highlight'] > 0)
			{
				$this->SetFillColor(255, 255, 0);
				$fill = 1;
			}
			else
			{
				$this->SetFillColor(255, 255, 255);
				$fill = 1;
			}
			
			$totals['calls']                += $row['calls'];
			$totals['non_effectives']       += $row['non_effectives'];
			$totals['on_target']            += $row['on_target'];
			$totals['off_target']           += $row['off_target'];
			$totals['total_effectives']     += $row['effectives'];
			$totals['conversion']           += $row['conversion'];
			$totals['meetings']             += $row['meetings'];
			$totals['attended']             += $row['attended'];
			$totals['kpis']                 += $row['kpis'];
			$totals['calls_logged_by_10am'] += $row['calls_logged_by_10am'];
			$totals['tasks_completed']      += $row['tasks_completed'];
			$totals['tasks_outstanding']    += $row['tasks_outstanding'];
			$totals['meets_diary_overdue']  += $row['meets_diary_overdue'];
			$totals['meets_diary_pending']  += $row['meets_diary_pending'];
			$totals['super_kpis']           += $row['super_kpis'];
			
			if (!isset($team_totals[$row['team']]))
			{
				$team_totals[$row['team']]['calls']                = 0;
				$team_totals[$row['team']]['non_effectives']       = 0;
				$team_totals[$row['team']]['on_target']            = 0;
				$team_totals[$row['team']]['off_target']           = 0;
				$team_totals[$row['team']]['total_effectives']     = 0;
				$team_totals[$row['team']]['conversion']           = 0;
				$team_totals[$row['team']]['meetings']             = 0;
				$team_totals[$row['team']]['attended']             = 0;
				$team_totals[$row['team']]['kpis']                 = 0;
				$team_totals[$row['team']]['calls_logged_by_10am'] = 0;
				$team_totals[$row['team']]['tasks_completed']      = 0;
				$team_totals[$row['team']]['tasks_outstanding']    = 0;
				$team_totals[$row['team']]['meets_diary_overdue']  = 0;
				$team_totals[$row['team']]['meets_diary_pending']  = 0;
				$team_totals[$row['team']]['super_kpis']           = 0;
			}
			
			$team_totals[$row['team']]['calls']                += $row['calls'];
			$team_totals[$row['team']]['non_effectives']       += $row['non_effectives'];
			$team_totals[$row['team']]['on_target']            += $row['on_target'];
			$team_totals[$row['team']]['off_target']           += $row['off_target'];
			$team_totals[$row['team']]['total_effectives']     += $row['effectives'];
			$team_totals[$row['team']]['conversion']           += $row['conversion'];
			$team_totals[$row['team']]['meetings']             += $row['meetings'];
			$team_totals[$row['team']]['attended']             += $row['attended'];
			$team_totals[$row['team']]['kpis']                 += $row['kpis'];
			$team_totals[$row['team']]['calls_logged_by_10am'] += $row['calls_logged_by_10am'];
			$team_totals[$row['team']]['tasks_completed']      += $row['tasks_completed'];
			$team_totals[$row['team']]['tasks_outstanding']    += $row['tasks_outstanding'];
			$team_totals[$row['team']]['meets_diary_overdue']  += $row['meets_diary_overdue'];
			$team_totals[$row['team']]['meets_diary_pending']  += $row['meets_diary_pending'];
			$team_totals[$row['team']]['super_kpis']           += $row['super_kpis'];
			
			$this->SetFont('Arial', '', 8);
			$this->Cell(20, 7, $row['team'],                                           'TBL',  0, 'L', $fill);
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(20, 7, C_String::formatName($row['nbm']),                        'TRB',  0, 'L', $fill);
			$this->SetFont('Arial', '', 8);
			$this->Cell(10, 7, number_format($row['calls']),                           'TBL',  0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['non_effectives']),                  'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['on_target']),                       'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['off_target']),                      'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['effectives']),                      'TBR',  0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['conversion']) . '%', 'TBL',  0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['meetings']),                        'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['attended']),                        'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['kpis']),                            'TRB',  0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['calls_logged_by_10am']),            'TBL',  0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['tasks_completed']),                 'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['tasks_outstanding']),               'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['meets_diary_overdue']),             'TB',   0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['meets_diary_pending']),             'TRB',  0, 'C', $fill);
			$this->Cell(10, 7, number_format($row['super_kpis']),                      'TRBL', 0, 'C', $fill);
			$this->Ln();
			$i++;
		}
		
		// Do cumulative totals
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 7, '',                                             'TBL',  0, 'C', 0);
		$this->Cell(20, 7, 'Total',                                        'TBR',  0, 'L', 0);
		$this->Cell(10, 7, number_format($totals['calls']),                'TBL',  0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['non_effectives']),       'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['on_target']),            'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['off_target']),           'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['total_effectives']),     'TRB',  0, 'C', 0);
		if ($totals['total_effectives'] > 0)
		{
			$conversion = ($totals['meetings'] / $totals['total_effectives']) * 100;
			$this->Cell(10, 7, number_format($conversion) . '%',           'TBL', 0, 'C', 0);
		}
		else
		{
			$this->Cell(10, 7, '0%',           'TBL', 0, 'C', 0);	
		}
		$this->Cell(10, 7, number_format($totals['meetings']),             'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['attended']),             'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['kpis']),                 'TRB',  0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['calls_logged_by_10am']), 'TBL',  0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['tasks_completed']),      'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['tasks_outstanding']),    'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['meets_diary_overdue']),  'TB',   0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['meets_diary_pending']),  'TRB',  0, 'C', 0);
		$this->Cell(10, 7, number_format($totals['super_kpis']),           'TRBL', 0, 'C', 0);
		$this->Ln(10);

		// Do team totals
		foreach ($team_totals as $key => $team)
		{
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(40, 7, $key,                                         'TRBL', 0, 'R', 0);
			$this->Cell(10, 7, number_format($team['calls']),                'TBL',  0, 'C', 0);
			$this->Cell(10, 7, number_format($team['non_effectives']),       'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['on_target']),            'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['off_target']),           'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['total_effectives']),     'TRB',  0, 'C', 0);
			if ($team['total_effectives'] > 0)
			{
				$conversion = ($team['meetings'] / $team['total_effectives']) * 100;
				$this->Cell(10, 7, number_format($conversion) . '%',         'TBL',  0, 'C', 0);
			}
			else
			{
				$this->Cell(10, 7, '0%',           'TBL', 0, 'C', 0);	
			}
			$this->Cell(10, 7, number_format($team['meetings']),             'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['attended']),             'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['kpis']),                 'TRB',  0, 'C', 0);
			$this->Cell(10, 7, number_format($team['calls_logged_by_10am']), 'TBL',  0, 'C', 0);
			$this->Cell(10, 7, number_format($team['tasks_completed']),      'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['tasks_outstanding']),    'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['meets_diary_overdue']),  'TB',   0, 'C', 0);
			$this->Cell(10, 7, number_format($team['meets_diary_pending']),  'TRB',  0, 'C', 0);
			$this->Cell(10, 7, number_format($team['super_kpis']),           'TRBL', 0, 'C', 0);
			$this->Ln();
		}
		$this->Ln(3);
		
		// Do averages
		$rows = count($data);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 7, '', 'TBL', 0, 'C', 0);
		$this->Cell(20, 7, 'NBM Average', 'TBR', 0, 'L', 0);
		$this->Cell(10, 7, $this->average($totals['calls'], $rows),                'TBL',  0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['non_effectives'], $rows),       'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['on_target'], $rows),            'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['off_target'], $rows),           'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['total_effectives'], $rows),     'TRB',  0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['conversion'], $rows) . '%',     'TBL',  0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['meetings'], $rows),             'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['attended'], $rows),             'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['kpis'], $rows),                 'TRB',  0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['calls_logged_by_10am'], $rows), 'TBL',  0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['tasks_completed'], $rows),      'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['tasks_outstanding'], $rows),    'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['meets_diary_overdue'], $rows),  'TB',   0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['meets_diary_pending'], $rows),  'TRB',  0, 'C', 0);
		$this->Cell(10, 7, $this->average($totals['super_kpis'], $rows),           'TRBL', 0, 'C', 0);
		$this->Ln();
	}

	public function OutputHeader()
	{
		$path = 'app/report/images/2/';
		$this->Cell(20, 36, $this->Image($path . 'team.png',                 $this->GetX() + 8, $this->GetY() + 1, 4), 'TBL',  0, 'C', 0);
		$this->Cell(20, 36, $this->Image($path . 'nbm.png',                  $this->GetX() + 8, $this->GetY() + 1, 4), 'TRB',  0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'calls.png',                $this->GetX() + 3, $this->GetY() + 1, 4), 'TBL',  0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'non_effectives.png',       $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'on_target.png',            $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'off_target.png',           $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'total_effectives.png',     $this->GetX() + 3, $this->GetY() + 1, 4), 'TRB',  0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'conversion.png',           $this->GetX() + 3, $this->GetY() + 1, 4), 'TBL',  0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'meetings.png',             $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'attended.png',             $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'kpis.png',                 $this->GetX() + 3, $this->GetY() + 1, 4), 'TRB',  0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'calls_logged_by_10am.png', $this->GetX() + 3, $this->GetY() + 1, 4), 'TBL',  0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'tasks_completed.png',      $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'tasks_outstanding.png',    $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'meets_diary_overdue.png',  $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',   0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'meets_diary_pending.png',  $this->GetX() + 3, $this->GetY() + 1, 4), 'TRB',  0, 'C', 0);
		$this->Cell(10, 36, $this->Image($path . 'super_kpis.png',           $this->GetX() + 3, $this->GetY() + 1, 4), 'TRBL', 0, 'C', 0);
		$this->Ln();
	}

	/**
	 * Outputs a legend
	 */
	public function Legend()
	{
		$this->Ln(7);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(35, 4, 'Legend', 0, 0, 'R', 0);
		$this->Cell(5, 4, '',        0, 0, 'C', 0);

		// Top 3rd
		$this->SetFillColor(255, 255, 0);
		$this->Cell(5, 4, '',                  1, 0, 'C', 1);
		$this->Cell(30, 4, 'Top third by KPI', 0,  0, 'L', 0);
		
		// Bottom 3rd
		$this->SetFillColor(105, 255, 255);
		$this->Cell(5, 4, '',                     1, 0, 'C', 1);
		$this->Cell(40, 4, 'Bottom third by KPI', 0,  0, 'L', 0);
	}

	/**
	 * Returns formatted x divided by y.
	 * @param mixed $x
	 * @param mixed $y
	 * @param string
	 */
	private function average($x, $y)
	{
		if ($y > 0)
		{
			return number_format($x / $y);
		}
		else
		{
			return 0;
		}
	}

}

?>