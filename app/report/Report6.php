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
class app_report_Report6 extends FPDF
{
	const ORDER_BY_CLIENT_NAME             = 0;
	const ORDER_BY_STATUS                  = 1;
	const ORDER_BY_CAMPAIGN_OWNER          = 2;
	const ORDER_BY_CAMPAIGN_MONTH          = 3;
	const ORDER_BY_CAMPAIGN_MEETS_SET      = 5;
	const ORDER_BY_CAMPAIGN_MEETS_ATTENDED = 6;
	
	protected $totals = array();
	
	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param integer $order_by
	 * @param boolean $include_imperative_target
	 */
	public function __construct($start, $end, $order_by = 5, $include_imperative_target = true, $client_id = null)
	{
		parent::__construct('P', 'mm', 'A3');

		// Date params
		$this->params['start'] = $start . ' 00:00:00';
		$this->params['end']   = $end . ' 23:59:59';
		
		// Client id
		$this->params['client_id']   = $client_id;
		
		
		// Order by
		$this->params['order_by'] = $order_by;
		
		// Whether to include the imperative target for period column
		$this->params['include_imperative_target_for_period'] = $include_imperative_target;
		
		// Default cell width
		if ($include_imperative_target)
		{
			$this->params['cell_width'] = 12.5;
		}
		else
		{
			$this->params['cell_width'] = 12.9;
		}
		
		$this->AliasNbPages();
		
		$this->AddPage('Landscape');

		// First page title
		$this->SetFont('Arial', '', 5);
		$this->Cell(50, 5, 'Report ID 6', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(300, 5, 'Client Services Report for the period ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end'])), 0, 0, 'C', 0);
		$this->SetFont('Arial', '', 5);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'R', 0);
		$this->Ln(7);
		
		// Output report body
		$this->Body();
	}

	/**
	 * Override the footer.
	 * NB - deliberately doesn't work on landscape front page  
	 */
	public function Footer()
	{
		$this->setX(0);
		$this->setY(290);
		$this->SetFont('Arial', 'B', 5);
		$this->Cell(400, 5, 'Internal Use Only', 0, 0, 'C', 0);
	}

	/**
	 * Output report body
	 */
	public function Body()
	{
		$cell_width = $this->params['cell_width'];
		
		// Output column headers
		$this->OutputColumnHeaders();
		$this->SetFont('Arial','', 8);

		// Get the data
//		echo '1 - ' . $this->params['client_id'] . '<br />';
		$data = app_domain_ReportReader::getReport6Data($this->params['start'], $this->params['end'], $this->params['order_by'], $this->params['client_id']);
		
		// Add other 'worked out' date (e.g. status string)
		$data = self::preProcess($data);
		
		// Sort results
		// Most sorted can be done using the SQL query (as called above). Sort 
		// by status needs to be done here however.
		if ($this->params['order_by'] == self::ORDER_BY_STATUS)
		{
			$data = Utils::msort($data, 'status', true);
		}

		// Work out font size and line heights based on number of clients
		$line_height = 239 / (count($data) + 1);
		if ($line_height < 2.5) $line_height = 2.5;
		if ($line_height > 7) $line_height = 7;
		
		$font_size = 5;
		
		// Output record lines
		foreach ($data as $key => $row)
		{
			$this->OutputRecordLine($key, $row, $font_size, $cell_width, $line_height);
		}
		
		// Output totals line
		$this->OutputTotalsLine(count($data), $data, $font_size, $cell_width, $line_height);
	}

	/**
	 * Output the column headers row
	 */
	public function OutputColumnHeaders()
	{
		$cell_width = $this->params['cell_width'];
		$this->SetFont('Arial', 'B', 7);
		$this->SetFillColor(166, 166, 166);
		
		$this->Cell(10 + $cell_width, 4, '',                           '',     0, 'C', 0);
		$this->Cell(6 * $cell_width,  4, 'CAMPAIGN STATISTICS',        'TRBL', 0, 'C', 1);
		$this->Cell(2,                4, '',                           '',     0, 'C', 0);
		
		// If outputting the include imperative target for period column, need to insert spacer
		if ($this->params['include_imperative_target_for_period'])
		{
			$this->Cell($cell_width, 4, '', '',   0, 'C', 0);
		}
		
		$this->Cell(23 * $cell_width, 4, 'SELECTED PERIOD STATISTICS', 'TRBL', 0, 'C', 1);
		$this->Ln(5);

		$this->SetFont('Arial', '', 5);
		
		// Construct array of column headers
		$column_headers_1 = array(	"\nClient\n \n ",
									"\nStatus\n \n ",
									"\nCampaign Owner\n ",
									"\nCampaign Month\n ",
									"\nNotice\nMonth\n ",
									"\nCampaign Meets Set +/-\n ",
									"\nCampaign Meets Att +/-\n ",
									'');
		
		
		if ($this->params['include_imperative_target_for_period'])
		{
			$column_headers_1[] = "Imperative\nTarget\nfor\nPeriod";
		}
		
		$column_headers_2 = array(	"\nTotal\nCalls\n ",
									"\nNon-tgt Sector Calls\n ",
									"\nTotal Effectives\n ",
									"\nONTE\n \n ",
									"\nOFFTE\n \n ",
									"Non-tgt Sector Effectives\n ",
									"\nOverall Access\n ",
									"\nOn Target Access\n ",
									"\nTotal Meets Set\n ",
									"\nNew Meets Set\n ",
									"\n2nd Meets Set\n ",
									"\nMeets Rearranged\n ",
									"\nTotal Conversion\n ",
									"\nOTE\nConversion\n ",
									"\nMeets Due\nto be Att\n ",
									"\nActual\nMeets Att\n ",
									"\nMeets\nLapsed\n ",
									"Meets\nto be\nRearranged\n ",
									"Meets in Diary for Period\n ",
									"\nFuture\nMeets\n ",
									"Receptive Call Backs in Next 0-4 Weeks",
									"Receptive Call Backs in Next 4-8 Weeks",
									"\nInfo Request Conversion\n ");
		
		$column_headers = array_merge($column_headers_1, $column_headers_2);
		
		$i = 0;
		foreach ($column_headers as $header)
		{
			$x = $this->GetX();
			$y1 = $this->GetY();

			if ($header == '')
			{
				$_width = 2;
			}
			elseif ($i == 0)
			{
				$_width = 10 + $cell_width;
			}
			else
			{
				$_width = $cell_width;
			}
			
			if ($header != '')
			{
				if ($i == $this->params['order_by'])
				{
					$this->SetFillColor(166);
					$this->MultiCell($_width, 3, $header, 'TRBL', 'C', true);
					$this->SetFillColor(255);
				}
				else
				{
					$this->MultiCell($_width, 3, $header, 'TRBL', 'C', false);
				}
			}
			else
			{
				$this->Cell($_width, 12, '', 'TRL',   0, 'C', 1);
			}
			
			$y2 = $this->GetY();
			$yH = $y2 - $y1;
			$this->SetXY($x + $_width, $this->GetY() - $yH);
			$i++;
		}
		$this->Ln(12);
	}

	/**
	 * Outputs a record line.
	 * @param integer $record_number the current record number
	 * @param array $data the data array
	 * @param float $font_size the font size
	 * @param float $width the cell width
	 * @param float $height the cell height
	 */
	public function OutputRecordLine($record_number, $data, $font_size, $width, $height)
	{
		// Formatting
		$this->SetFont('Arial', '', $font_size);
		
		// Alternating row colours
		if ($record_number % 2 == 0)
		{
			$color = 230;
		}
		else
		{
			$color = 255;
		}
		$this->SetFillColor($color);
		$fill = 1;
		
		// Campaign Statistics
		$this->Cell($width + 10, $height, $data['client'],                                         'TRBL', 0, 'L', $fill);  // Client
		$this->Cell($width,      $height, $data['status'],                                         'TRB',  0, 'C', $fill);  // Status
		$this->Cell($width,      $height, $data['campaign_owner'],                                 'TRB',  0, 'L', $fill);  // Campaign Owner
		$this->Cell($width,      $height, $data['campaign_month'],                                 'TRB',  0, 'C', $fill);  // Campaign Month
		$this->Cell($width,      $height, $data['notice_month'],                                   'TRB',  0, 'C', $fill);  // Notice Month
		$this->Cell($width,      $height, number_format($data['campaign_meets_set_compare']),      'TRB',  0, 'C', $fill);  // Campaign Meets Set +/-
		$this->Cell($width,      $height, number_format($data['campaign_meets_attended_compare']), 'TRB',  0, 'C', $fill);  // Campaign Meets Att +/-
			
		// Output seperator
		$this->SetFillColor(166);
		$this->Cell(2, $height, '', 'RL',  0, 'C', 1);
		$this->SetFillColor($color);
		
		// Imperative Target for Period
		if ($this->params['include_imperative_target_for_period'])
		{
			$this->Cell($width, $height, number_format($data['imperative_target_for_period']), 'TRBL', 0, 'C', $fill);  // Imperative Target for Period
		}
		
		// Selected Period Statistics
		$this->Cell($width, $height, number_format($data['totals_calls']),                  'TRB', 0, 'C', $fill);  // Total Calls
		$this->Cell($width, $height, number_format($data['non_target_sector_calls']),       'TRB', 0, 'C', $fill);  // Non-tgt Sector Calls
		$this->Cell($width, $height, number_format($data['total_effectives']),              'TRB', 0, 'C', $fill);  // Total Effectives
		$this->Cell($width, $height, number_format($data['on_target_effectives']),          'TRB', 0, 'C', $fill);  // ONTE
		$this->Cell($width, $height, number_format($data['off_target_effectives']),         'TRB', 0, 'C', $fill);  // OFFTE
		$this->Cell($width, $height, number_format($data['non_target_sector_effectives']),  'TRB', 0, 'C', $fill);  // Non Target Sector Effectives
		$this->Cell($width, $height, number_format($data['overall_access']).'%',            'TRB', 0, 'C', $fill);  // Overall Access
		$this->Cell($width, $height, number_format($data['on_target_access']).'%',          'TRB', 0, 'C', $fill);  // On Target Access
		$this->Cell($width, $height, number_format($data['total_meets_set']),               'TRB', 0, 'C', $fill);  // Total Meets Set
		$this->Cell($width, $height, number_format($data['new_meets_set']),                 'TRB', 0, 'C', $fill);  // New Meets Set
		$this->Cell($width, $height, number_format($data['second_meets_set']),              'TRB', 0, 'C', $fill);  // 2nd Meets Set
		$this->Cell($width, $height, number_format($data['meets_rearranged']),              'TRB', 0, 'C', $fill);  // Meets Rearranged
		$this->Cell($width, $height, number_format($data['total_conversion']) . '%',        'TRB', 0, 'C', $fill);  // Total Conversion
		$this->Cell($width, $height, number_format($data['ote_conversion']) . '%',          'TRB', 0, 'C', $fill);  // OTE Conversion
		$this->Cell($width, $height, number_format($data['meets_due_to_be_attended']),      'TRB', 0, 'C', $fill);  // Meets due to be att
		$this->Cell($width, $height, number_format($data['actual_meets_attended']),         'TRB', 0, 'C', $fill);  // Actual Meets Att
		$this->Cell($width, $height, number_format($data['meets_lapsed']),                  'TRB', 0, 'C', $fill);  // Meets Lapsed
		$this->Cell($width, $height, number_format($data['meets_to_be_rearranged']),        'TRB', 0, 'C', $fill);  // Meets to be Rearranged
		$this->Cell($width, $height, number_format($data['meets_in_diary_for_period']),     'TRB', 0, 'C', $fill);  // Meets in Diary for Period
		$this->Cell($width, $height, number_format($data['future_meets']),                  'TRB', 0, 'C', $fill);  // Future Meets
		$this->Cell($width, $height, number_format($data['receptive_call_backs_0_4']),      'TRB', 0, 'C', $fill);  // Receptive Call Backs in Next 0-4 weeks
		$this->Cell($width, $height, number_format($data['receptive_call_backs_4_8']),      'TRB', 0, 'C', $fill);  // Receptive Call Backs in Next 4-8 weeks
		$this->Cell($width, $height, number_format($data['info_request_conversion']) . '%', 'TRB', 0, 'C', $fill);  // Info Request Conversion
		$this->Ln();
	}

	/**
	 * Outputs a totals line.
	 * @param integer $record_number the current record number
	 * @param array $data the data array
	 * @param float $font_size the font size
	 * @param float $width the cell width
	 * @param float $height the cell height
	 */
	public function OutputTotalsLine($record_number, $data, $font_size, $width, $height)
	{
		// Formatting
		$this->SetFont('Arial', 'B', $font_size);
		
		// Alternate row colour
		if ($record_number % 2 == 0)
		{
			$color = 230;
		}
		else
		{
			$color = 255;
		}
		$this->SetFillColor($color);
		$fill = 1;
		
		// Add separator
		$this->Cell(($width + 10) + (6 * $width), 0.5, '', 'TRBL', 0, 'L', $fill);
		$this->SetFillColor(166);
		$this->Cell(2, 0.5, '', 'RL',  0, 'C', 1);
		$this->SetFillColor($color);
		if ($this->params['include_imperative_target_for_period'])
		{
			$separator_width = (24 * $width);
		}
		else
		{
			$separator_width = (23 * $width);
		}
		$this->Cell($separator_width, 0.5, '', 'TRBL', 0, 'L', $fill);
		$this->Ln(0.5);
		
		// Campaign Statistics
		$this->Cell($width + 10, $height, 'Grand Totals',                                                  'TRBL', 0, 'L', $fill);  // Client
		$this->Cell($width,      $height, '',                                                              'TRB',  0, 'C', $fill);  // Status
		$this->Cell($width,      $height, '',                                                              'TRB',  0, 'C', $fill);  // Campaign Owner
		$this->Cell($width,      $height, '',                                                              'TRB',  0, 'C', $fill);  // Campaign Month
		$this->Cell($width,      $height, '',                                                              'TRB',  0, 'C', $fill);  // Notice Month
		$this->Cell($width,      $height, self::array_field_sum($data, 'campaign_meets_set_compare'),      'TRB',  0, 'C', $fill);  // Campaign Meets Set +/-
		$this->Cell($width,      $height, self::array_field_sum($data, 'campaign_meets_attended_compare'), 'TRB',  0, 'C', $fill);  // Campaign Meets Att +/-
		
		// Output seperator
		$this->SetFillColor(166);
		$this->Cell(2, $height, '', 'RBL',  0, 'C', 1);
		$this->SetFillColor($color);
		
		// Imperative Target for Period
		if ($this->params['include_imperative_target_for_period'])
		{
			$this->Cell($width, $height, self::array_field_sum($data, 'imperative_target_for_period'), 'TRBL',   0, 'C', $fill);  // Imperative Target For Period
		}
		
		// Selected Period Statistics
		$this->Cell($width, $height, self::array_field_sum($data, 'totals_calls'),                      'TRB', 0, 'C', $fill);  // Total Calls
		$this->Cell($width, $height, self::array_field_sum($data, 'non_target_sector_calls'),           'TRB', 0, 'C', $fill);  // Non-tgt Sector Calls
		$this->Cell($width, $height, self::array_field_sum($data, 'total_effectives'),                  'TRB', 0, 'C', $fill);  // Total Effectives
		$this->Cell($width, $height, self::array_field_sum($data, 'on_target_effectives'),              'TRB', 0, 'C', $fill);  // ONTE
		$this->Cell($width, $height, self::array_field_sum($data, 'off_target_effectives'),             'TRB', 0, 'C', $fill);  // OFFTE
		$this->Cell($width, $height, self::array_field_sum($data, 'non_target_sector_effectives'),      'TRB', 0, 'C', $fill);  // Non Target Sector Effectives
		$this->Cell($width, $height, self::array_field_average($data, 'overall_access').'%',            'TRB', 0, 'C', $fill);  // Overall Access
		$this->Cell($width, $height, self::array_field_average($data, 'on_target_access').'%',          'TRB', 0, 'C', $fill);  // On Target Access
		$this->Cell($width, $height, self::array_field_sum($data, 'total_meets_set'),                   'TRB', 0, 'C', $fill);  // Total Meets Set
		$this->Cell($width, $height, self::array_field_sum($data, 'new_meets_set'),                     'TRB', 0, 'C', $fill);  // New Meets Set
		$this->Cell($width, $height, self::array_field_sum($data, 'second_meets_set'),                  'TRB', 0, 'C', $fill);  // 2nd Meets Set
		$this->Cell($width, $height, self::array_field_sum($data, 'meets_rearranged'),                  'TRB', 0, 'C', $fill);  // Meets Rearranged
		$this->Cell($width, $height, self::array_field_average($data, 'total_conversion') . '%',        'TRB', 0, 'C', $fill);  // Total Conversion
		$this->Cell($width, $height, self::array_field_average($data, 'ote_conversion') . '%',          'TRB', 0, 'C', $fill);  // OTE Conversion
		$this->Cell($width, $height, self::array_field_sum($data, 'meets_due_to_be_attended'),          'TRB', 0, 'C', $fill);  // Meets due to be att
		$this->Cell($width, $height, self::array_field_sum($data, 'actual_meets_attended'),             'TRB', 0, 'C', $fill);  // Actual Meets Att
		$this->Cell($width, $height, self::array_field_sum($data, 'meets_lapsed'),                      'TRB', 0, 'C', $fill);  // Meets Lapsed
		$this->Cell($width, $height, self::array_field_sum($data, 'meets_to_be_rearranged'),            'TRB', 0, 'C', $fill);  // Meets to be Rearranged
		$this->Cell($width, $height, self::array_field_sum($data, 'meets_in_diary_for_period'),         'TRB', 0, 'C', $fill);  // Meets in Diary for Period
		$this->Cell($width, $height, self::array_field_sum($data, 'future_meets'),                      'TRB', 0, 'C', $fill);  // Future Meets
		$this->Cell($width, $height, self::array_field_sum($data, 'receptive_call_backs_0_4'),          'TRB', 0, 'C', $fill);  // Receptive Call Backs in Next 0-4 weeks
		$this->Cell($width, $height, self::array_field_sum($data, 'receptive_call_backs_4_8'),          'TRB', 0, 'C', $fill);  // Receptive Call Backs in Next 4-8 weeks
		$this->Cell($width, $height, self::array_field_average($data, 'info_request_conversion') . '%', 'TRB', 0, 'C', $fill);  // Info Request Conversion
	}

	/**
	 * Add additional information used in report to the data array and return it.
	 * @param array $data
	 * @return array
	 */
	protected static function preProcess($data)
	{
		foreach ($data as &$item)
		{
			// Work out status text
			if ($item['campaign_notice_month'])
			{
				$year  = substr($item['campaign_notice_month'], 0, 4);
				$month = substr($item['campaign_notice_month'], 4, 2);
				$notice_month = date("M 'y", mktime(0, 0, 0, $month, 1, $year));
				
				
//				if ($item['campaign_notice_month'] < date('Ym'))
				if ($item['campaign_notice_month'] < '200802')
				{
					$status = 'Lapsed';
				}
//				elseif ($item['campaign_notice_month'] == date('Ym'))
				elseif ($item['campaign_notice_month'] == '200802')
				{
					$status = 'Notice';
				}
				else
				{
					$status = 'Current';
				}
			}
			else
			{
				$notice_month = '';
				$status = 'Current';
			}
			
			$item['notice_month'] = $notice_month;
			$item['status']       = $status;
		}
		return $data;
	}
	
	/**
	 * Sums the values of a given field.
	 * @param array $array
	 * @param string $field
	 * @return string formatted number
	 */
	protected static function array_field_sum($array, $field)
	{
		$sum = 0;
		foreach ($array as $row)
		{
			$sum += $row[$field];
		}
		return number_format($sum);
	}

	/**
	 * Averages the values of a given field.
	 * @param array $array
	 * @param string $field
	 * @return string formatted number
	 */
	protected static function array_field_average($array, $field)
	{
		$sum = self::array_field_sum($array, $field);
		return number_format($sum / count($array));
	}

}

?>