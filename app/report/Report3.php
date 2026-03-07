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
class app_report_Report3 extends FPDF
{

	/**
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param integer $user_id
	 */
	public function __construct($start_date = null, $user_id = 0)
	{
		if (is_null($start_date) || !Utils::isValidDate($start_date))
		{
			// Beginning of last month
			$start_date = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
		}
		$year = substr($start_date, 0, 4);
		$month = substr($start_date, 4, 2);
		$this->params['start_date'] = $start_date;
		$this->params['end_date']   = date('Y-m-d', mktime(0, 0, 0, $month + 3, 0, $year));
		$this->params['user_id'] = $user_id;

		parent::__construct('P');
		$this->AliasNbPages();
		$this->LineItems();
	}
	
	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 3', 0, 0, 'L', 0);
		
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(90, 5, 'Source of Meetings Set', 0, 0, 'C', 0);
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'R', 0);
		$this->Ln();

		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
		$this->Cell(90, 5, 'For the period ' . date('d/m/y', strtotime($this->params['start_date'])) . ' to ' . date('d/m/Y'), 0, 0, 'C', 0);
		$this->Cell(50, 5, '', 0, 0, 'R', 0);
		
		$this->Ln(10);
	}

	public function LineItems()
	{
		$this->AddPage();
//		$this->SetFont('Arial', 'B', 8);
//		$this->OutputHeader();
//		$this->SetFont('Arial','', 8);
		$this->SetFont('Arial','',8);
		$this->Ln();

		
		//
		// Team Summary
		//
		$this->OutputHeader('Team Summary');
		$rows = app_domain_ReportReader::getReport3SummaryData($this->params['start_date'], $this->params['user_id']);
		
		// Set up totals
		$totals = array();
		$totals['information_request_count']                = 0;
		$totals['information_request_pending']              = 0;
		$totals['information_request_failed']               = 0;
		$totals['information_request_converted']            = 0;
		$totals['information_request_percentage_failed']    = 0;
		$totals['information_request_percentage_converted'] = 0;
		$totals['call_fresh_effective_count']               = 0;
		$totals['call_back_effective_count']                = 0;
		
//		$cumulative_totals = array();
//		$cumulative_totals['impv'] = 0;
//		$cumulative_totals['effect'] = 0;
//		$cumulative_totals['atd'] = 0;

		// Print Team Summary data
		foreach($rows as $row)
		{
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(40, 5, $row['nbm'], 1, 0, 'L', 0);
			$this->SetFont('Arial', '', 8);
			$this->Cell(10, 5, number_format($row['information_request_count']),                      'TBL', 0, 'C', 0);
			$this->Cell(10, 5, number_format($row['information_request_pending']),                    'TB', 0, 'C', 0);
			$this->Cell(10, 5, number_format($row['information_request_failed']),                     'TB', 0, 'C', 0);
			$this->Cell(10, 5, number_format($row['information_request_converted']),                  'TB', 0, 'C', 0);
			$this->Cell(10, 5, number_format($row['information_request_percentage_failed']) . '%',    'TB', 0, 'C', 0);
			$this->Cell(10, 5, number_format($row['information_request_percentage_converted']) . '%', 'TRB', 0, 'C', 0);
			
			$this->Cell(10, 5, number_format($row['call_back_effective_count'] - $row['information_request_count']), 'TBL', 0, 'C', 0);
//			$this->Cell(10, 5, number_format($row['call_back_effective_count']), 'TBL', 0, 'C', 0);
			$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
			$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
			
			$this->Cell(10, 5, number_format($row['call_fresh_effective_count']), 'TBL', 0, 'C', 0);
			$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
			$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
			
			$this->Cell(10, 5, number_format($row['call_back_effective_count'] + $row['call_fresh_effective_count']), 'TBL', 0, 'C', 0);
			$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
			$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
			$this->Ln();
			
			// Increment totals
			$totals['information_request_count']                += $row['information_request_count'];
			$totals['information_request_pending']              += $row['information_request_pending'];
			$totals['information_request_failed']               += $row['information_request_failed'];
			$totals['information_request_converted']            += $row['information_request_converted'];
			$totals['information_request_percentage_failed']    += $row['information_request_percentage_failed'];
			$totals['information_request_percentage_converted'] += $row['information_request_percentage_converted'];
			$totals['call_fresh_effective_count']               += $row['call_fresh_effective_count'];
			$totals['call_back_effective_count']                += $row['call_back_effective_count'];
		}

		// Summary Total
		$this->OutputTotals($totals);
		
		// Summary Average
		$nbm_count = count($rows);
		$this->OutputAverages($totals, $nbm_count);

		// Break between team summary NBM detail
//		$this->Ln(10);


		//
		// NBM Detail
		//
		$totals = array();
		$totals['information_request_count']                = 0;
		$totals['information_request_pending']              = 0;
		$totals['information_request_failed']               = 0;
		$totals['information_request_converted']            = 0;
		$totals['information_request_percentage_failed']    = 0;
		$totals['information_request_percentage_converted'] = 0;
		$totals['call_fresh_effective_count']               = 0;
		$totals['call_back_effective_count']                = 0;
		$client_count = 0;
		
		
		$rows = app_domain_ReportReader::getReport3DetailData($this->params['start_date'], $this->params['user_id']);
		$previous_user_id = null;
		$current_user_id = null;
		
		for ($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];
			$current_user_id = $row['user_id'];
			if ($current_user_id != $previous_user_id)
			{
				if ($i != 0)
				{
					// Summary Total
					$this->OutputTotals($totals, $nbm_count);
					
					// Summary Average
					$this->OutputAverages($totals, $client_count);
				}
				
				// Reset totals and counter for next NBM
				$totals = array();
				$totals['information_request_count']                = 0;
				$totals['information_request_pending']              = 0;
				$totals['information_request_failed']               = 0;
				$totals['information_request_converted']            = 0;
				$totals['information_request_percentage_failed']    = 0;
				$totals['information_request_percentage_converted'] = 0;
				$totals['call_fresh_effective_count']               = 0;
				$totals['call_back_effective_count']                = 0;
				$client_count = 0;
				
				// Output header for next NBM clients data
				$this->Outputheader($row['nbm']);
			}
			
			$client_count++;
			$this->SetFillColor(255, 255, 255);
			$fill = 1;
			
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(40, 5, $row['client'], 1, 0, 'L', 0);
			$this->SetFont('Arial', '', 8);
			$this->Cell(10, 5, number_format($row['information_request_count']),                      'TBL', 0, 'C', $fill);
			$this->Cell(10, 5, number_format($row['information_request_pending']),                    'TB',  0, 'C', $fill);
			$this->Cell(10, 5, number_format($row['information_request_failed']),                     'TB',  0, 'C', $fill);
			$this->Cell(10, 5, number_format($row['information_request_converted']),                  'TB',  0, 'C', $fill);
			$this->Cell(10, 5, number_format($row['information_request_percentage_failed']) . '%',    'TB',  0, 'C', $fill);
			$this->Cell(10, 5, number_format($row['information_request_percentage_converted']) . '%', 'TRB', 0, 'C', $fill);
			
			$this->Cell(10, 5, number_format($row['call_back_effective_count'] - $row['information_request_count']), 'TBL', 0, 'C', 0);
			$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
			$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
			
			$this->Cell(10, 5, number_format($row['call_fresh_effective_count']), 'TBL', 0, 'C', 0);
			$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
			$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
			
			$this->Cell(10, 5, number_format($row['call_back_effective_count'] + $row['call_fresh_effective_count']), 'TBL', 0, 'C', 0);
			$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
			$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
			$this->Ln();
			
			$totals['information_request_count']                += $row['information_request_count'];
			$totals['information_request_pending']              += $row['information_request_pending'];
			$totals['information_request_failed']               += $row['information_request_failed'];
			$totals['information_request_converted']            += $row['information_request_converted'];
			$totals['information_request_percentage_failed']    += $row['information_request_percentage_failed'];
			$totals['information_request_percentage_converted'] += $row['information_request_percentage_converted'];
			$totals['call_fresh_effective_count']               += $row['call_fresh_effective_count'];
			$totals['call_back_effective_count']                += $row['call_back_effective_count'];
			
			$previous_user_id = $current_user_id;
		}	
		
		
		// Do summary total and average for the last nbm
		// Summary Total
		$this->OutputTotals($totals);
		
		// Summary Average
		$nbm_count = count($rows);
		$this->OutputAverages($totals, $nbm_count);		
	}


	private function OutputTotals($array)
	{
		// Summary Total
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(40, 5, 'Total', 1, 0, 'L', 0);
		$this->Cell(10, 5, number_format($array['information_request_count']),     'TBL', 0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_pending']),   'TB',  0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_failed']),    'TB',  0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_converted']), 'TB',  0, 'C', 0);
		$this->Cell(10, 5, '-', 'TB', 0, 'C', 0);
		$this->Cell(10, 5, '-', 'TRB', 0, 'C', 0);
		
		$this->Cell(10, 5, number_format($array['call_back_effective_count'] - $array['information_request_count']), 'TBL', 0, 'C', 0);
		$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
		$this->Cell(10, 5, '-', 'TRB', 0, 'C', 0);
		
		$this->Cell(10, 5, number_format($array['call_fresh_effective_count']), 'TBL', 0, 'C', 0);
		$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
		$this->Cell(10, 5, '-', 'TRB', 0, 'C', 0);
		
		$this->Cell(10, 5, number_format($array['call_back_effective_count'] + $array['call_fresh_effective_count']), 'TBL', 0, 'C', 0);
		$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
		$this->Cell(10, 5, '-', 'TRB', 0, 'C', 0);
		$this->Ln();
		$this->SetFont('Arial', '', 8);
	}
	
	private function OutputAverages($array, $record_count)
	{
		// Summary Average
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(40, 5, 'Average', 1, 0, 'L', 0);
		$this->Cell(10, 5, number_format($array['information_request_count'] / $record_count),                      'TBL', 0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_pending'] / $record_count),                    'TB',  0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_failed'] / $record_count),                     'TB',  0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_converted'] / $record_count),                  'TB',  0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_percentage_failed'] / $record_count) . '%',    'TB',  0, 'C', 0);
		$this->Cell(10, 5, number_format($array['information_request_percentage_converted'] / $record_count) . '%', 'TRB', 0, 'C', 0);
		
		$this->Cell(10, 5, number_format($array['call_back_effective_count'] - $array['information_request_count']), 'TBL', 0, 'C', 0);
		$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
		$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
		
		$this->Cell(10, 5, number_format($array['call_fresh_effective_count']), 'TBL', 0, 'C', 0);
		$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
		$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
		
		$this->Cell(10, 5, number_format(($array['information_request_failed'] + $array['information_request_converted'] + $array['call_fresh_effective_count']) / $record_count), 'TBL', 0, 'C', 0);
		$this->Cell(10, 5, '', 'TB', 0, 'C', 0);
		$this->Cell(10, 5, '%', 'TRB', 0, 'C', 0);
		$this->Ln(10);
		$this->SetFont('Arial', '', 8);
	}

	private function OutputHeader($str = '')
	{
		$path = 'app/report/images/3/';
		
//		$this->Ln(10);
				// Team Summary
		$this->SetFont('Arial', 'B', 8);
		
		$this->Cell(40, 5, $str,         'TLR', 0, 'L', 0);
		$this->Cell(60, 5, 'Information Requests', 'TLR', 0, 'C', 0);
		$this->Cell(30, 5, 'Call Backs',           'TLR', 0, 'C', 0);
		$this->Cell(30, 5, 'Fresh Effectives',     'TLR', 0, 'C', 0);
		$this->Cell(30, 5, 'Grand Total',          'TLR', 0, 'C', 0);
		$this->Ln();

		$this->Cell(40, 22, '', 'LBR', 0, 'L', 0);
		
		$this->Cell(10, 22, $this->Image($path . 'total.png',                $this->GetX() + 3, $this->GetY() + 1, 4), 'BL', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'pending.png',              $this->GetX() + 3, $this->GetY() + 1, 4), 'B', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'failed.png',               $this->GetX() + 3, $this->GetY() + 1, 4), 'B', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'converted.png',            $this->GetX() + 3, $this->GetY() + 1, 4), 'B', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'percentage_failed.png',    $this->GetX() + 3, $this->GetY() + 1, 4), 'B', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'percentage_converted.png', $this->GetX() + 3, $this->GetY() + 1, 4), 'RB', 0, 'C', 0);		

		$this->Cell(10, 22, $this->Image($path . 'total.png',                $this->GetX() + 3, $this->GetY() + 1, 4), 'BL', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'converted.png',            $this->GetX() + 3, $this->GetY() + 1, 4), 'B', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'percentage_converted.png', $this->GetX() + 3, $this->GetY() + 1, 4), 'RB', 0, 'C', 0);
		
		$this->Cell(10, 22, $this->Image($path . 'total.png',                $this->GetX() + 3, $this->GetY() + 1, 4), 'BL', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'converted.png',            $this->GetX() + 3, $this->GetY() + 1, 4), 'B', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'percentage_converted.png', $this->GetX() + 3, $this->GetY() + 1, 4), 'RB', 0, 'C', 0);
		
		$this->Cell(10, 22, $this->Image($path . 'total.png',                $this->GetX() + 3, $this->GetY() + 1, 4), 'BL', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'converted.png',            $this->GetX() + 3, $this->GetY() + 1, 4), 'B', 0, 'C', 0);
		$this->Cell(10, 22, $this->Image($path . 'percentage_converted.png', $this->GetX() + 3, $this->GetY() + 1, 4), 'RB', 0, 'C', 0);
		$this->Ln();
		$this->SetFont('Arial', '', 8);	
		
//		$this->Cell(20, 36, $this->Image($path . 'team.png',                 $this->GetX() + 8, $this->GetY() + 1, 4), 'TBL', 0, 'C', 0);
//		$this->Cell(20, 36, $this->Image($path . 'nbm.png',                  $this->GetX() + 8, $this->GetY() + 1, 4), 'TRB', 0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'calls.png',                $this->GetX() + 3, $this->GetY() + 1, 4), 'TBL', 0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'non_effectives.png',             $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'on_target.png',            $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'off_target.png',           $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'total_effectives.png',     $this->GetX() + 3, $this->GetY() + 1, 4), 'TRB', 0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'conversion.png',           $this->GetX() + 3, $this->GetY() + 1, 4), 'TBL', 0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'meetings.png',             $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'attended.png',             $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'kpis.png',                 $this->GetX() + 3, $this->GetY() + 1, 4), 'TRB', 0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'calls_logged_by_10am.png', $this->GetX() + 3, $this->GetY() + 1, 4), 'TBL', 0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'tasks_completed.png',      $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'tasks_outstanding.png',    $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'meets_diary_overdue.png',  $this->GetX() + 3, $this->GetY() + 1, 4), 'TB',  0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'meets_diary_pending.png',  $this->GetX() + 3, $this->GetY() + 1, 4), 'TRB', 0, 'C', 0);
//		$this->Cell(10, 36, $this->Image($path . 'super_kpis.png',           $this->GetX() + 3, $this->GetY() + 1, 4), 1,     0, 'C', 0);
//		$this->Ln();
	}

}

?>