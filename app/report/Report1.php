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
class app_report_Report1 extends FPDF
{

	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @param string $year_month in the format 'YYYYMM'
	 * @param integer $user_id
	 * @param bool $include_zero_targets
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 */
	public function __construct($year_month = null, $user_id = 0, $include_zero_targets = false, $nbm_exclusions = null, $client_id = null)
	{
		parent::__construct('L');
		
		// Year month
		if (is_null($year_month) || !Utils::isValidYearMonth($year_month))
		{
			$year_month = date('Ym');
		}
		$this->params['year_month'] = $year_month;
		
		// Work out previous and next month
		$year  = substr($year_month, 0, 4);
		$month = substr($year_month, 4, 2);
		$this->params['previous_year_month']          = date('Ym', mktime(0, 0, 0, $month-1, 1, $year));
		$this->params['next_year_month']              = date('Ym', mktime(0, 0, 0, $month+1, 1, $year));
		$this->params['previous_year_month_friendly'] = date('F Y', mktime(0, 0, 0, $month-1, 1, $year));
		$this->params['next_year_month_friendly']     = date('F Y', mktime(0, 0, 0, $month+1, 1, $year));
		$this->params['year_month_friendly']          = date('F Y', mktime(0, 0, 0, $month, 1, $year));
		$this->params['user_id']                      = $user_id;
		
		$this->params['include_zero_targets'] = $include_zero_targets;
		$this->params['nbm_exclusions'] = $nbm_exclusions;
		$this->params['client_id'] = $client_id;

		$this->AliasNbPages();
		$this->SetFillColor(255, 255, 255);
		$this->doBody();
	}

	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(66, 5, 'Report ID 1', 0, 0, 'L', 0);

		$this->SetFont('Arial', 'B', 10);
		$this->Cell(150, 5, 'Alchemis Allocation Report', 0, 0, 'C', 0);
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(61, 5, date('d/m/Y'), 0, 0, 'R', 0);
		$this->Ln();
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(66, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
		$this->Cell(150, 5, 'For ' . $this->params['previous_year_month_friendly'] . ', ' . $this->params['year_month_friendly'] . ' and ' . $this->params['next_year_month_friendly'], 0, 0, 'C', 0);
		$this->Cell(61, 5, '', 0, 0, 'R', 0);
		$this->Ln(10);
		
		// Set up date columns
		$this->SetFont('Arial', 'B', 8);
		$this->Cell( 66, 5, $this->params['previous_year_month_friendly'],    0, 0, 'L', 0);
		$this->Cell(150, 5, $this->params['year_month_friendly'], 0, 0, 'L', 0);
		$this->Cell( 61, 5, $this->params['next_year_month_friendly'],   0, 0, 'L', 0);
		$this->Ln();
	}

	/**
	 * Manage the output of the first page
	 */
	protected function doSummary()
	{
		$fill = 1;
		
		// Working days
		$this->SetFont('Arial', 'B', 8);
		$this->Cell( 66, 5, Utils::getWorkingDaysInMonth($this->params['previous_year_month']) . ' Days', 0, 0, 'L', $fill);
		$this->Cell(150, 5, Utils::getWorkingDaysInMonth($this->params['year_month']) . ' Days',          0, 0, 'L', $fill);
		$this->Cell( 61, 5, Utils::getWorkingDaysInMonth($this->params['next_year_month']) . ' Days',     0, 0, 'L', $fill);
		$this->Ln(10);
		
		// New starters
		$this->doNewStarters();
		
		// Reallocation
//		$this->doReallocation();
		
		// Support calling
//		$this->doSupportCalling();
		
		// Holiday
		$this->doHoliday();
		
		// Ensure now start a new page
		$this->AddPage();
	}

	/**
	 * Output the new starter data.
	 */
	protected function doNewStarters()
	{
		$fill = 0;
		
		// Get new starters data
		$items = array(	$this->params['previous_year_month'] => app_domain_ReportReader::getNewStarters($this->params['previous_year_month'], $this->params['user_id'], $this->params['client_id']),
						$this->params['year_month']          => app_domain_ReportReader::getNewStarters($this->params['year_month'], $this->params['user_id'], $this->params['client_id']),
						$this->params['next_year_month']     => app_domain_ReportReader::getNewStarters($this->params['next_year_month'], $this->params['user_id'], $this->params['client_id']));
//		echo '<pre>';
//		print_r($items);
//		echo '</pre>';
		
		// Determine max number of records we need to deal with (i.e. the number of lines we need to output
		$max = 0;
		foreach ($items as $item)
		{
			 if (count($item) > $max)
			 {
			 	$max = count($item);
			 }
		}

		if ($max == 0) $border = 'B';
		else $border = '';

		// Output header row
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(31, 5, 'New Starters',   'TL' . $border, 0, 'L', $fill);
		$this->Cell(30, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
		$this->Cell( 5, 5, '',               '',             0, 'L', $fill);
		$this->Cell(35, 5, 'New Starters',   'TL' . $border, 0, 'L', $fill);
		$this->Cell(35, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
		$this->Cell( 5, 5, '',               '',             0, 'L', $fill);
		$this->Cell(35, 5, 'Days Capacity',  'TL' . $border, 0, 'L', $fill);
		$this->Cell(35, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
		$this->Cell( 5, 5, '',               '',             0, 'L', $fill);
		$this->Cell(31, 5, 'New Starters',   'TL' . $border, 0, 'L', $fill);
		$this->Cell(30, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
		$this->Ln();

		$this->SetFont('Arial', '', 8);
		for ($i = 0; $i < $max; $i++)
		{
//			echo '<pre>';
//			print_r($items[$this->params['previous_year_month']][$i]);
//			echo '</pre>';
			
			// Previous
			if (isset($items[$this->params['previous_year_month']][$i]))
			{
				$prev_client = $items[$this->params['previous_year_month']][$i]['client'];
				$prev_nbm    = $items[$this->params['previous_year_month']][$i]['nbm'];
			}
			else
			{
				$prev_client = '';
				$prev_nbm    = '';
			}
			
			// Current
			if (isset($items[$this->params['year_month']][$i]))
			{
				$curr_client = $items[$this->params['year_month']][$i]['client'];
				$curr_nbm    = $items[$this->params['year_month']][$i]['nbm'];
			}
			else
			{
				$curr_client = '';
				$curr_nbm    = '';
			}

			// Next
			if (isset($items[$this->params['next_year_month']][$i]))
			{
				$next_client = $items[$this->params['next_year_month']][$i]['client'];
				$next_nbm    = $items[$this->params['next_year_month']][$i]['nbm'];
			}
			else
			{
				$next_client = '';
				$next_nbm    = '';
			}
			
			if ($i == $max-1)
			{
				$border = 'B';
			}
			else
			{
				$border = '';
			}
			
			// Output a line
			$this->Cell( 31, 3, $prev_client, 'L' . $border, 0, 'L', $fill);
			$this->Cell( 30, 3, $prev_nbm,    'R' . $border, 0, 'L', $fill);

			$this->Cell(  5, 3, '', 0, 0, 'L', $fill);

			$this->Cell( 35, 3, $curr_client, 'L' . $border, 0, 'L', $fill);
			$this->Cell( 35, 3, $curr_nbm, 'R' . $border, 0, 'L', $fill);
			
			$this->Cell(  5, 3, '', 0, 0, 'L', $fill);

			$this->Cell( 35, 3, '', 'L' . $border, 0, 'L', $fill);
			$this->Cell( 35, 3, '', 'R' . $border, 0, 'L', $fill);

			$this->Cell(  5, 3, '', 0, 0, 'L', $fill);

			$this->Cell( 31, 3, $next_client, 'L' . $border, 0, 'L', $fill);
			$this->Cell( 30, 3, $next_nbm, 'R' . $border, 0, 'L', $fill);

			$this->Ln();
		}		
		
		$this->Ln(5);
	}

	/**
	 * Output the reallocation data.
	 */
	protected function doReallocation()
	{
		$fill = 1;
		
		// Get reallocation data
		$items = array(	$this->params['previous_year_month'] => null,
						$this->params['year_month']          => null,
						$this->params['next_year_month']     => null);
		$max = 0;
		
		foreach ($items as $item)
		{
//			echo "<p>abnc</p>";
			 if (count($item) > $max)
			 {
			 	$max = count($item);
			 }
		}


		if ($max == 0) $border = 'B';
		else $border = '';
		
		$this->SetFont('Arial', 'B', 8);
		$this->Cell( 61, 5, 'Reallocation',    'TRL' . $border, 0, 'L', $fill);
		$this->Cell(  5, 5, '',                '',              0, 'L', $fill);
		$this->Cell(145, 5, 'Reallocation',    'TRL' . $border, 0, 'L', $fill);
		$this->Cell(  5, 5, '',                '',              0, 'L', $fill);
		$this->Cell( 61, 5, 'Reallocation',    'TRL' . $border, 0, 'L', $fill);
		$this->SetTextColor(0,0,0);
		$this->Ln(10);
	}
	
	/**
	 * Output the reallocation data.
	 */
	protected function doSupportCalling()
	{
		$fill = 1;
		
		// Get reallocation data
		$items = array(	$this->params['previous_year_month'] => null,
						$this->params['year_month']          => null,
						$this->params['next_year_month']     => null);
		$max = 0;
		
		foreach ($items as $item)
		{
//			echo "<p>abnc</p>";
			 if (count($item) > $max)
			 {
			 	$max = count($item);
			 }
		}


		if ($max == 0) $border = 'B';
		else $border = '';
		
//		$this->SetFont('Arial', 'B', 8);
//		$this->Cell( 61, 5, 'Reallocation',    'TRL' . $border, 0, 'L', $fill);
//		$this->Cell(  5, 5, '',                '',              0, 'L', $fill);
//		$this->Cell(145, 5, 'Reallocation',    'TRL' . $border, 0, 'L', $fill);
//		$this->Cell(  5, 5, '',                '',              0, 'L', $fill);
//		$this->Cell( 61, 5, 'Reallocation',    'TRL' . $border, 0, 'L', $fill);
//		$this->SetTextColor(0,0,0);
//		$this->Ln(10);
//		
		$this->SetFont('Arial', 'B', 8);
		$this->Cell( 61, 5, 'Support Calling',    'TRL' . $border, 0, 'L', $fill);
		$this->Cell(  5, 5, '',                   '',              0, 'L', $fill);
		$this->Cell(145, 5, 'Support Calling',    'TRL' . $border, 0, 'L', $fill);
		$this->Cell(  5, 5, '',                   '',              0, 'L', $fill);
		$this->Cell( 61, 5, 'Support Calling',    'TRL' . $border, 0, 'L', $fill);
		$this->SetTextColor(0, 0, 0);
		$this->Ln(10);
	}

	/**
	 * Output the reallocation data.
	 */
	protected function doHoliday()
	{
		$fill = 1;
		
		// Get holiday data
		$items = array(	$this->params['previous_year_month'] => app_domain_ReportReader::getReport1HolidayData($this->params['previous_year_month'], $this->params['user_id']),
						$this->params['year_month']          => app_domain_ReportReader::getReport1HolidayData($this->params['year_month'],          $this->params['user_id']),
						$this->params['next_year_month']     => app_domain_ReportReader::getReport1HolidayData($this->params['next_year_month'],     $this->params['user_id']));
		
		// Process the array into a format we can use to output from
		// Previous month
		$array = array();
		$count = 0;
		foreach ($items[$this->params['previous_year_month']] as $month)
		{
			$array[$month['user']] = $month['count'];
			$count += $month['count'];
		}
		$items[$this->params['previous_year_month']] = $array;
		$sum[$this->params['previous_year_month']] = $count;
				
		// Current month
		$array = array();
		$count = 0;
		foreach ($items[$this->params['year_month']] as $month)
		{
			$array[$month['user']] = $month['count'];
			$count += $month['count'];
		}
		$items[$this->params['year_month']] = $array;
		$sum[$this->params['year_month']] = $count;
		
		// Next month
		$array = array();
		$count = 0;
		foreach ($items[$this->params['next_year_month']] as $month)
		{
			$array[$month['user']] = $month['count'];
			$count += $month['count'];
		}
		$items[$this->params['next_year_month']] = $array;
		$sum[$this->params['next_year_month']] = $count;
		
		// Determine max number of records we need to deal with (i.e. the number of lines we need to output
		$max = 0;
		foreach ($items as $item)
		{
			 if (count($item) > $max)
			 {
			 	$max = count($item);
			 }
		}
		
//		if ($max == 0)
//		{
//			$border = '';
//		}
//		else
//		{
			$border = '';
//		}
		
		// Output header
		$this->SetFont('Arial', 'B', 8);
		$this->Cell( 61, 5, 'Holiday',    'TRL' . $border, 0, 'L', $fill);
		$this->Cell(  5, 5, '',           '',              0, 'L', 0);
		$this->Cell(145, 5, 'Holiday',    'TRL' . $border, 0, 'L', $fill);
		$this->Cell(  5, 5, '',           '',              0, 'L', 0);
		$this->Cell( 61, 5, 'Holiday',    'TRL' . $border, 0, 'L', $fill);
		$this->Ln();
		
//		
//				$this->Cell(31, 5, 'New Starters',   'TL' . $border, 0, 'L', $fill);
//		$this->Cell(30, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
//		$this->Cell( 5, 5, '',               '',             0, 'L', $fill);
//		$this->Cell(35, 5, 'New Starters',   'TL' . $border, 0, 'L', $fill);
//		$this->Cell(35, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
//		$this->Cell( 5, 5, '',               '',             0, 'L', $fill);
//		$this->Cell(35, 5, 'Days Capacity',  'TL' . $border, 0, 'L', $fill);
//		$this->Cell(35, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
//		$this->Cell( 5, 5, '',               '',             0, 'L', $fill);
//		$this->Cell(31, 5, 'New Starters',   'TL' . $border, 0, 'L', $fill);
//		$this->Cell(30, 5, 'NBM',            'TR' . $border, 0, 'L', $fill);
		
		// Output data lines
		$this->SetFont('Arial', '', 7);
		for ($i = 0; $i < $max; $i++)
		{
			$border = '';
			
			// Output line
			$this->Cell( 37, 3, key($items[$this->params['previous_year_month']]),     'L' . $border, 0, 'R', $fill);
			$this->Cell( 24, 3, current($items[$this->params['previous_year_month']]), 'R' . $border, 0, 'C', $fill);
			$this->Cell(  5, 3, '',                                                    'R',           0, 'L', 0);
			$this->Cell( 37, 3, key($items[$this->params['year_month']]),              'L' . $border, 0, 'R', $fill);
			$this->Cell( 24, 3, current($items[$this->params['year_month']]),          '' . $border,  0, 'C', $fill);
			$this->Cell( 84, 3, '',                                                    'R' . $border, 0, 'C', $fill);
			$this->Cell(  5, 3, '',                                                    '',            0, 'L', 0);
			$this->Cell( 37, 3, key($items[$this->params['next_year_month']]),         'L' . $border, 0, 'R', $fill);
			$this->Cell( 24, 3, current($items[$this->params['next_year_month']]),     'R' . $border, 0, 'C', $fill);
			$this->Ln();
			
			next($items[$this->params['previous_year_month']]);
			next($items[$this->params['year_month']]);
			next($items[$this->params['next_year_month']]);
		}
		
		$border = 'B';
		$this->SetFont('Arial', 'B', 7);
		$this->Cell(37, 4, 'Total',                                    'L' . $border, 0, 'R', $fill);
		$this->Cell(24, 4, $sum[$this->params['previous_year_month']], 'R' . $border, 0, 'C', $fill);
		$this->Cell( 5, 4, '',                                         '',            0, 'L', 0);
		$this->Cell(37, 4, 'Total',                                    'L' . $border, 0, 'R', $fill);
		$this->Cell(24, 4, $sum[$this->params['year_month']],          '' . $border,  0, 'C', $fill);
		$this->Cell(84, 4, '',                                         'R' . $border, 0, 'C', $fill);
		$this->Cell( 5, 4, '',                                         '',            0, 'L', 0);
		$this->Cell(37, 4, 'Total',                                    'L' . $border, 0, 'R', $fill);
		$this->Cell(24, 4, $sum[$this->params['next_year_month']],     'R' . $border, 0, 'C', $fill);
		$this->Ln(10);
	}

	protected function doBody()
	{
		$fill = 1;

		$this->SetFont('Arial', '', 8);
		$this->AddPage();

		// Do summary section
		$this->doSummary();
		
		// Data
		$data = app_domain_ReportReader::getReport1MainData($this->params['year_month'], $this->params['user_id'], $this->params['nbm_exclusions'], $this->params['client_id']);
//		echo '<pre>';
//		print_r($data);
//		echo '</pre>';
		
		// Initialise totals
		$totals = array();
		$totals['prev_meetings_set_imperative']    = 0;
		$totals['prev_meetings_set']               = 0;
		$totals['prev_meetings_attended']          = 0;
		$totals['planned_days']                    = 0;
		$totals['effectives_target']               = 0;
		$totals['cumulative_meet_set_target']      = 0;
		$totals['meetings_set_target']             = 0;
		$totals['meetings_set_imperative']         = 0;
		$totals['meetings_set']                    = 0;
		$totals['cumulative_meet_attended_target'] = 0;
		$totals['meetings_attended_target']        = 0;
		$totals['meetings_attended']               = 0;
		$totals['diary_meets']                     = 0;
		$totals['next_effective_target']           = 0;
		$totals['next_meeting_target']             = 0;
		$totals['next_meeting_imperative']         = 0;
		
		// Initialise cumulative totals
		$cumulative_totals = array();
		$cumulative_totals['prev_meetings_set_imperative']    = 0;
		$cumulative_totals['prev_meetings_set']               = 0;
		$cumulative_totals['prev_meetings_attended']          = 0;
		$cumulative_totals['planned_days']                    = 0;
		$cumulative_totals['effectives_target']               = 0;
		$cumulative_totals['cumulative_meet_set_target']      = 0;
		$cumulative_totals['meetings_set_target']             = 0;
		$cumulative_totals['meetings_set_imperative']         = 0;
		$cumulative_totals['meetings_set']                    = 0;
		$cumulative_totals['cumulative_meet_attended_target'] = 0;
		$cumulative_totals['meetings_attended_target']        = 0;
		$cumulative_totals['meetings_attended']               = 0;
		$cumulative_totals['diary_meets']                     = 0;
		$cumulative_totals['next_effective_target']           = 0;
		$cumulative_totals['next_meeting_target']             = 0;
		$cumulative_totals['next_meeting_imperative']         = 0;
		
		$i = 0;
		$current_nbm = null;
		
		$previous = null;
		$current  = null;
		for ($i = 0; $i < count($data); $i++)
		{
//			if ($i == 0)
//			{
//				echo '<pre>';
//				print_r($data);
//				echo '</pre>';
//			}
			$rows = $data[$i];
			$current = $rows['user'];
			
			// Ensure the number of characters is limited so cell text doesn't overflow into next column
			$rows['client'] = substr($rows['client'], 0, 23);
			
//			if ($i != 0)
//			{
//				$this->Ln();
//			}
			
			if ($current != $previous)
			{
				$this->SetFont('Arial', 'B', 7);
				if ($i != 0)
				{
					$this->doNbmTotals($totals);
					$this->Ln(5);
				}
				
//				$y = $this->GetY();
				$this->SetFont('Arial', 'B', 8);
				$this->Cell( 66, 5, $rows['user'], 0, 0, 'L', $fill);
				$this->Cell(150, 5, $rows['user'], 0, 0, 'L', $fill);
				$this->Cell( 61, 5, $rows['user'], 0, 0, 'L', $fill);
				$this->Ln();
				
				$this->outputHeader();
//				$totals['prev_imperative'] = 0;
//				$totals['prev_effectives'] = 0;
//				$totals['prev_attended'] = 0;
//				
//				$totals['planned_days'] = 0;
//				$totals['effectives'] = 0;
//				$totals['cumulative_meet_set_target'] = 0;
//				$totals['meetings_set'] = 0;
//				$totals['meetings_set_imperative'] = 0;
//				$totals['meetings_attended'] = 0;
//				$totals['meetings_attended_actual'] = 0;
//				$totals['diary_meets'] = 0;
//				
//				$totals['next_effectives'] = 0;
//				$totals['next_target'] = 0;
				
				$totals['prev_meetings_set_imperative']    = 0;
				$totals['prev_meetings_set']               = 0;
				$totals['prev_meetings_attended']          = 0;
				$totals['planned_days']                    = 0;
				$totals['effectives_target']               = 0;
				$totals['cumulative_meet_set_target']      = 0;
				$totals['meetings_set_target']             = 0;
				$totals['meetings_set_imperative']         = 0;
				$totals['meetings_set']                    = 0;
				$totals['cumulative_meet_attended_target'] = 0;
				$totals['meetings_attended_target']        = 0;
				$totals['meetings_attended']               = 0;
				$totals['diary_meets']                     = 0;
				$totals['next_effective_target']           = 0;
				$totals['next_meeting_target']             = 0;
				$totals['next_meeting_imperative']         = 0;
			}	
			
			
			if ($this->params['include_zero_targets'] || (!$this->params['include_zero_targets'] && ($rows['meetings_set_target'] != 0 || $rows['effectives_target'] != 0)))
			{
				if ($this->params['include_zero_targets'] && $rows['meetings_set_target'] == 0 && $rows['effectives_target'] == 0)
				{
					// Make grey text - this is reset at bottom of loop
					$this->SetTextColor(153, 153, 153);
				}
				
				// Maintain totals
				$totals['prev_meetings_set_imperative']    += $rows['prev_meetings_set_imperative'];
				$totals['prev_meetings_set']               += $rows['prev_meetings_set'];
				$totals['prev_meetings_attended']          += $rows['prev_meetings_attended'];
				$totals['planned_days']                    += $rows['planned_days'];
				$totals['effectives_target']               += $rows['effectives_target'];
				$totals['cumulative_meet_set_target']      += $rows['cumulative_meet_set_target'];
				$totals['meetings_set_target']             += $rows['meetings_set_target'];
				$totals['meetings_set_imperative']         += $rows['meetings_set_imperative'];
				$totals['meetings_set']                    += $rows['meetings_set'];
				$totals['cumulative_meet_attended_target'] += $rows['cumulative_meet_attended_target'];
				$totals['meetings_attended_target']        += $rows['meetings_attended_target'];
				$totals['meetings_attended']               += $rows['meetings_attended'];
				$totals['diary_meets']                     += $rows['diary_meets'];
				$totals['next_effective_target']           += $rows['next_effective_target'];
				$totals['next_meeting_target']             += $rows['next_meeting_target'];
				$totals['next_meeting_imperative']         += $rows['next_meeting_imperative'];
	
//				$totals['prev_imperative']   += $rows['meetings_set_imperative'];
//				$totals['prev_effectives'] += $rows['effectives'];
//				$totals['prev_attended']        += 0;
//				
//				$totals['planned_days']             += $rows['planned_days'];
//				$totals['effectives']               += $rows['effectives'];
//				$totals['cumulative_meet_set_target'] += $rows['cumulative_meet_set_target'];
//				$totals['meetings_set']             += $rows['meetings_set'];
//				$totals['meetings_set_imperative']  += $rows['meetings_set_imperative'];
//				$totals['meetings_attended']        += $rows['meetings_attended'];
//				$totals['meetings_attended_actual'] += $rows['meetings_attended_actual'];
//				$totals['diary_meets']              += $rows['diary_meets'];
//				
//				$totals['next_effectives']          += $rows['next_effectives'];
//				$totals['next_target']              += $rows['next_target'];
				
				// Maintain cumulative totals
				$cumulative_totals['prev_meetings_set_imperative']    += $rows['prev_meetings_set_imperative'];
				$cumulative_totals['prev_meetings_set']               += $rows['prev_meetings_set'];
				$cumulative_totals['prev_meetings_attended']          += $rows['prev_meetings_attended'];
				$cumulative_totals['planned_days']                    += $rows['planned_days'];
				$cumulative_totals['effectives_target']               += $rows['effectives_target'];
				$cumulative_totals['cumulative_meet_set_target']      += $rows['cumulative_meet_set_target'];
				$cumulative_totals['meetings_set_target']             += $rows['meetings_set_target'];
				$cumulative_totals['meetings_set_imperative']         += $rows['meetings_set_imperative'];
				$cumulative_totals['meetings_set']                    += $rows['meetings_set'];
				$cumulative_totals['cumulative_meet_attended_target'] += $rows['cumulative_meet_attended_target'];
				$cumulative_totals['meetings_attended_target']        += $rows['meetings_attended_target'];
				$cumulative_totals['meetings_attended']               += $rows['meetings_attended'];
				$cumulative_totals['diary_meets']                     += $rows['diary_meets'];
				$cumulative_totals['next_effective_target']           += $rows['next_effective_target'];
				$cumulative_totals['next_meeting_target']             += $rows['next_meeting_target'];
				$cumulative_totals['next_meeting_imperative']         += $rows['next_meeting_imperative'];
				
				// Output previous month
				$this->Cell(25, 5, $rows['client'], 1, 0, 'L', $fill);
				$this->Cell(12, 5, $rows['prev_meetings_set_imperative'], 1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['prev_meetings_set'],            1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['prev_meetings_attended'],       1, 0, 'C', $fill);
	
				$this->Cell(5, 5, '', 0, 0, 'C', 0);
	
				// Output current month
				$this->Cell(25, 5, $rows['client'],                          1, 0, 'L', $fill);
				$this->Cell(12, 5, $rows['planned_days'],                    1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['effectives_target'],               1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['cumulative_meet_set_target'],      1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['meetings_set_target'],             1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['meetings_set_imperative'],         1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['meetings_set'],                    1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['cumulative_meet_attended_target'], 1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['meetings_attended_target'],        1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['meetings_attended'],               1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['diary_meets'],                     1, 0, 'C', $fill);
	
				$this->Cell(5, 5, '', 0, 0, 'C', 0);
		
				// Output next month
				$this->Cell(25, 5, $rows['client'],          1, 0, 'L', $fill);
//				$this->Cell(12, 5, $rows['call_effective_target_to_date'] . '-' . $rows['call_effective_count_to_date'] . ' = ' . $rows['next_effective_target'], 1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['next_effective_target'], 1, 0, 'C', $fill);
//				$this->Cell(12, 5, $rows['next_campaign_meeting_set_target_to_date'] . '-' . $rows['next_campaign_meeting_set_count_to_date'] . ' = ' . $rows['next_meeting_target'],     1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['next_meeting_target'],     1, 0, 'C', $fill);
//				$this->Cell(12, 5, $rows['next_campaign_meeting_set_imperative_to_date'] . '-' . $rows['next_campaign_meeting_set_count_to_date'] . ' = ' . $rows['next_meeting_imperative'],     1, 0, 'C', $fill);
				$this->Cell(12, 5, $rows['next_meeting_imperative'],     1, 0, 'C', $fill);

				// Next line
				$this->Ln();
//				$previous = $current;
				
				// Ensure text color is set to black for next iteration
				$this->SetTextColor(0, 0, 0);
			}
			$previous = $current;
		}
		
		// Do final NBM totals
		$this->Ln();
		$this->doNbmTotals($totals);
		$this->Ln(5);
		$this->doCumulativeTotals($cumulative_totals);
		$this->Ln(10);
	}

	/**
	 * Output the header for each NBM section
	 */
	protected function outputHeader()
	{
		$fill = 1;
		
		// Previous month
		$this->SetFont('Arial', 'B', 7);
		$this->Cell(25, 5, '',           0, 0, 'C', $fill);
		$this->Cell(12, 5, 'Impv',       1, 0, 'C', $fill);  // Imperative
		$this->Cell(12, 5, 'Actual',     1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Atd',        1, 0, 'C', $fill);

		$this->Cell(5, 5, '', 0, 0, 'C', 0);
		
		// Current month
//		$this->SetFont('Arial', '', 7);
		$this->Cell(25, 5, '',         0, 0, 'C', $fill);
		$this->Cell(12, 5, 'Days',     1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Effect',   1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Campaign', 1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Target',   1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Impv',     1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Set act',  1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Camp atd', 1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Atd tgt',  1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Atd act',  1, 0, 'C', $fill);
		$this->Cell(12, 5, 'Diary',    1, 0, 'C', $fill);

		$this->Cell(5, 5, '', 0, 0, 'C', 0);

		$this->Cell(25, 5, '', 0, 0, 'C', $fill);
		$this->Cell(12, 5, 'Effect',     1, 0, 'C', $fill);
//		$this->SetFont('Arial', 'B', 7);
		$this->Cell(12, 5, 'Target',     1, 0, 'C', $fill);
//		$this->SetFont('Arial', '', 7);
		$this->Cell(12, 5, 'Impv',       1, 0, 'C', $fill);
		
		$this->SetFont('Arial', '', 7);
		$this->Ln();
	}

	/**
	 * Output NBM totals
	 */
	protected function doNbmTotals($totals)
	{
//		echo '<pre>';
//		print_r($totals);
//		echo '</pre>';
		$fill = 1;
		$this->SetFont('Arial', 'B', 7);
		
		// Output totals


		// Previous month
		$this->Cell(25, 5, 'Total', 1, 0, 'C', $fill);
		$this->Cell(12, 5, $totals['prev_meetings_set_imperative'], 1, 0, 'C', $fill);
		$this->Cell(12, 5, $totals['prev_meetings_set'],            1, 0, 'C', $fill);
		$this->Cell(12, 5, $totals['prev_meetings_attended'],       1, 0, 'C', $fill);

		$this->Cell(5, 5, '', 0, 0, 'C', 0);

		// Current month
		$this->Cell(25, 5, 'Total', 1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['planned_days']),                    1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['effectives_target']),               1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['cumulative_meet_set_target']),      1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_set_target']),             1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_set_imperative']),         1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_set']),                    1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['cumulative_meet_attended_target']), 1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_attended_target']),        1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_attended']),               1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['diary_meets']),                     1, 0, 'C', $fill);
		
		$this->Cell(5, 5, '', 0, 0, 'C', 0);

		// Next month
		$this->Cell(25, 5, 'Total', 1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['next_effective_target']),   1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['next_meeting_target']),     1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['next_meeting_imperative']), 1, 0, 'C', $fill);

		$this->Ln();
		
		// Ensure the line is proper width

		// Previous month
		$this->Cell( 61, 1, '', 'T');
		$this->Cell(  5, 1, '', '');
		$this->Cell(145, 1, '', 'T');
		$this->Cell(  5, 1, '', '');
		$this->Cell( 61, 1, '', 'T');
	}

	/**
	 * Output cumulative totals
	 * @param array $totals
	 */
	protected function doCumulativeTotals($totals)
	{
		$fill = 1;
		
		// Do cumulative totals
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(14, 5, 'Cumulative Total', 0, 1, 'L', $fill);
		
		$this->outputHeader();
		
		$this->SetFont('Arial', 'B', 7);
		$this->Cell(25, 5, 'Cumulative Total', 1, 0, 'C', $fill);
		$this->Cell(12, 5, $totals['prev_meetings_set_imperative'], 1, 0, 'C', $fill);
		$this->Cell(12, 5, $totals['prev_meetings_set'],            1, 0, 'C', $fill);
		$this->Cell(12, 5, $totals['prev_meetings_attended'],       1, 0, 'C', $fill);

		$this->Cell(5, 5, '', 0, 0, 'C', 0);

		$this->Cell(25, 5, 'Cumulative Total',                                        1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['planned_days']),                    1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['effectives_target']),               1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['cumulative_meet_set_target']),      1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_set_target']),             1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_set_imperative']),         1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_set']),                    1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['cumulative_meet_attended_target']), 1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_attended_target']),        1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['meetings_attended']),               1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['diary_meets']),                     1, 0, 'C', $fill);

		$this->Cell(5, 5, '', 0, 0, 'C', 0);

		$this->Cell(25, 5, 'Cumulative Total', 1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['next_effective_target']), 1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['next_meeting_target']), 1, 0, 'C', $fill);
		$this->Cell(12, 5, number_format($totals['next_meeting_imperative']), 1, 0, 'C', $fill);
	}

	/**
	 * 
	 */
	private function getNextMonthsData()
	{
		$db = $this->getDb();
		$sql = "SELECT tar.*, cli.name AS client, u.name AS user, " .
				"ds.meeting_in_diary_this_month_count AS diary_meets, ds.meeting_attended_count AS meetings_attended_actual " .
				"FROM tbl_campaign_nbm_targets AS tar " .
				"INNER JOIN tbl_campaigns AS cam ON tar.campaign_id = cam.id " .
				"INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id " .
				"INNER JOIN tbl_rbac_users AS u ON tar.user_id = u.id " .
				"INNER JOIN tbl_data_statistics AS ds ON tar.user_id = ds.user_id AND tar.campaign_id = ds.campaign_id AND tar.`year_month` = ds.`year_month` " .
				"WHERE tar.`year_month` = '200709' " .
				"AND u.is_active = 1 " .
				"ORDER BY u.name, cli.name LIMIT 45";
//		echo "<P>$sql</p>";
		return $db->getResults($sql);
	}

}

?>