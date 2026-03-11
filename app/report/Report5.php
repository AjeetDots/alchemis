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
class app_report_Report5 extends FPDF
{

	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @param string $start date in the format 'YYYY-MM-DD'
	 * @param string $end date in the format 'YYYY-MM-DD'
	 * @param integer $client_id
	 * @param boolean $summary_figures whether to include summary figures on front page
	 * @param boolean $all_statuses whether to include all statuses on the front page
	 * @param boolean $full_history whether to include full note history
	 */
	public function __construct($start, $end, $client_id, $project_ref, $effectives, $summary_figures, $all_statuses, $full_history)
	{
		parent::__construct('L');
		
		// Start date
		if (Utils::isValidDate($start))
		{
			$this->params['start'] = $start;
		}
		else
		{
			throw new Exception('Invalid start date');
		}
		
		// End date
		if (Utils::isValidDate($end))
		{
			$this->params['end'] = $end;
		}
		else
		{
			throw new Exception('Invalid end date');
		}
		
		// Client
		$this->params['client_id']    = $client_id;
		$this->params['client_name'] = app_domain_Client::lookupClientNameById($client_id);

		// Other params
		$this->params['project_ref'] 		= $project_ref;
		$this->params['effectives'] 		= $effectives;
		$this->params['summary_figures'] 	= (bool)$summary_figures;
		$this->params['all_statuses']    	= (bool)$all_statuses;
		$this->params['full_history']    	= (bool)$full_history;
		
		$this->AliasNbPages();
		$this->SetFillColor(255, 255, 255);
		$this->doFrontPage();
		$this->doBody();
	}

	/**
	 * Override the footer.
	 * NB - deliberately doesn't work on landscape front page  
	 */
	public function Footer()
	{
		if ($this->PageNo() > 1)
		{
		$this->setX(0);
		$this->setY(285);
		$this->SetFont('Arial', '', 5);
		$this->Cell(35, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
		$this->Cell(100, 5, 'Alchemis Activity Report of Conversation Notes', 0, 0, 'C', 0);
		$this->Cell(35, 5, '', 0, 0, 'L', 0);
		$this->Ln();
	}
	}

	/**
	 * Output front summary page.
	 */
	public function doFrontPage()
	{
		$this->AddPage();

		// Title
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(66, 5, '', 0, 0, 'L', 0);
		$this->Cell(150, 5, 'Alchemis Activity Report of Conversation Notes', 0, 0, 'C', 0);
		$this->Cell(61, 5, '', 0, 0, 'R', 0);
		$this->Ln(10);
		
		$this->SetFont('Arial', '', 9);
		$this->Cell(277, 5, 'Prepared for ' . $this->params['client_name'], 0, 0, 'C', 0);
		$this->Ln(5);
		
		$this->SetFont('Arial', '', 9);
		$this->Cell(277, 5, 'for the period', 0, 0, 'C', 0);
		$this->Ln(5);
		
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(277, 5, date('d/m/Y', strtotime($this->params['start'])) . ' to ' . date('d/m/Y', strtotime($this->params['end'])), 0, 0, 'C', 0);
		$this->Ln(15);
		
		// Legend
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(277, 5, 'Key To Terms Within Report', 0, 0, 'L', 0);
		$this->Ln(7);
		
		$this->Cell(25, 5, 'Effective', 0, 0, 'L', 0);
		$this->Cell(252, 5, 'A conversation with a key decision maker for a marketing service and/or discipline', 0, 0, 'L', 0);
		$this->Ln(5);
		
		$this->Cell(25, 5, 'Non-Effective', 0, 0, 'L', 0);
		$this->Cell(252, 5, 'An attempt to reach a key decision maker or key influencer', 0, 0, 'L', 0);
		$this->Ln(10);

		// Get data
		$data = app_domain_ReportReader::getReport5SummaryData($this->params['start'], $this->params['end'], $this->params['client_id'], $this->params['project_ref'], $this->params['all_statuses']);
//		echo '<pre>';
//		print_r($data);
//		echo '</pre>';
		$this->SetFont('Arial', '', 6);

		// Output if data exists
		if (count($data) > 0)
		{
			// Determine sensible line height to fit number of rows
			$line_height = 110 / count($data);
			if ($line_height > 6)
			{
				$line_height = 6;
			}
			if ($line_height < 2.5)
			{
				$line_height = 2.5;
			}

			// Set up date columns
			$this->SetFont('Arial', 'B', 6);
			$this->Cell( 40, 4, '',              0, 0, 'L', 0);
			$this->Cell( 50, 4, 'Status',        0, 0, 'L', 0);

			if ($this->params['summary_figures'])
			{
				$this->Cell( 15, 4, 'No. of Prospects',     0, 0, 'C', 0);
				$this->Cell( 20, 4, 'Non-Effective', 0, 0, 'C', 0);
				$final_col_width = 147;
			}
			else
			{
				$final_col_width = 182;
			}
			
			$this->Cell(  5, 4, '',              0, 0, 'L', 0);
			$this->Cell(147, 4, 'Description',   0, 0, 'L', 0);
			$this->Ln();
			
			// Record running total
			$total_effectives     = 0;
			$total_non_effectives = 0;

			$this->SetFont('Arial', '', 6);
			foreach ($data as &$row)
			{
				// Replace client name in full description
				$row['full_description'] = str_replace('[Client]', $this->params['client_name'], $row['full_description']);
				
				$this->Cell( 40, $line_height, '',                       0, 0, 'L', 0);
				$this->Cell( 50, $line_height, $row['description'],      0, 0, 'L', 0);
				
				if ($this->params['summary_figures'])
				{
					$this->Cell( 15, $line_height, $row['effectives'],       0, 0, 'C', 0);
					$this->Cell( 20, $line_height, $row['non_effectives'],   0, 0, 'C', 0);
				}
				
				$this->Cell(               5,            4, '',                       0, 0, 'L', 0);
				$this->Cell($final_col_width, $line_height, $row['full_description'], 0, 0, 'L', 0);
				$this->Ln();
				
				$total_effectives     += $row['effectives'];
				$total_non_effectives += $row['non_effectives'];
			}
			
			$this->SetFont('Arial', 'B', 6);
			$this->Cell( 90, $line_height, '',                    0, 0, 'L', 0);
			if ($this->params['summary_figures'])
			{
				$this->Cell( 15, $line_height, $total_effectives,     0, 0, 'C', 0);
				$this->Cell( 20, $line_height, $total_non_effectives, 0, 0, 'C', 0);
			}
			$this->Ln();
		}
		else
		{
			$this->SetFont('Arial', 'B', 9);
			$this->Cell(277, 20, 'No call records found', 0, 0, 'C', 0);
		}
	}

	/**
	 * Output all the effective notes.
	 */
	public function doBody()
	{
		$this->SetFont('Arial', '', 6);
//		$this->SetMargins(10, '10cm', 2.5);
//$this->SetMargins(25, 20, 25);
		$this->SetMargins(20, 20, 20);
		
		// Get the note data
		$data = app_domain_ReportReader::getReport5DetailData(
			$this->params['start'],
			$this->params['end'],
			$this->params['client_id'],
			$this->params['project_ref'],
			$this->params['effectives'],
			$this->params['full_history']
		);
		$data = self::array_utf8_to_iso88591($data);

		// If no data returned, skip body generation gracefully
		if (!is_array($data) || count($data) === 0) {
			return;
		}
		
		if ($this->params['full_history'])
		{
			// TODO
		}
		$i = 0;
		$current_status_id = 0;
		foreach ($data as $row)
		{
			if ($current_status_id != $row['status_id']) 
			{
				// Output a full-width line when the status changes (ignores the first one) 
				if ($i != 0)
				{
					$this->Line($this->getX(), $this->getY(), $this->getX()+170, $this->getY());
				}
				// Push onto a new page
				$this->AddPage('P');
			}
			else
			{
				// Output a half-width line to seperate notes of the same status (ignores the first one)
				if ($i != 0)
				{
					$this->Line($this->getX()+50, $this->getY(), $this->getX()+120, $this->getY());
					$this->Ln(10);
				}
			}
			
			// Output the conversation note
			$this->outputConversationNote($row);
			$current_status_id = $row['status_id'];
			$i++;
		}
		
		// Output a full-width line under the last record
		if (count($data) > 0)
		{
			$this->Line($this->getX(), $this->getY(), $this->getX()+170, $this->getY());
		}
	}

	/**
	 * Output the conversation note
	 * @param array $note
	 */
	public function outputConversationNote($note)
	{
		// Portrait width = 190

		if ($this->getY() > 230)
		{
			$this->AddPage('P');
		}
		
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(190, 5, $note['status'], 0, 0, 'L', 0);
		$this->Ln(10);

		$this->SetFont('Arial', 'B', 7);
		$this->Cell( 30, 4, 'Company',   0, 0, 'L', 0);
		$this->SetFont('Arial', '', 7);
		$this->Cell(160, 4, $note['company'], 0, 0, 'L', 0);
		$this->Ln();
		$this->Cell( 30, 4, '',    0, 0, 'L', 0);
		$this->Cell(160, 4, $note['address'], 0, 0, 'L', 0);
		$this->Ln(6);
		
		$this->SetFont('Arial', 'B', 7);
		$this->Cell(30,  4, 'Prospect', 0, 0, 'L', 0);
		$this->SetFont('Arial', '', 7);
		
		// Possible for name to be empty (if no record in tbl_contacts)
		if ($note['full_name'])
		{
			$this->Cell(160, 4, trim($note['full_name']) . ' (' . trim($note['job_title']) . ')', 0, 0, 'L', 0);
		}
		else
		{
			$this->Cell(160, 4, trim($note['job_title']), 0, 0, 'L', 0);
		}
//		$this->Ln();
		
//		$this->Cell( 30, 4, '',    0, 0, 'L', 0);
//		$this->Cell(160, 4, $note['calls'] . ' calls, ' . $note['effectives'] . ' effectives', 0, 0, 'L', 0);
		$this->Ln(6);

		$this->SetFont('Arial', 'B', 7);
		$this->Cell(190,  4, 'Effective Notes:', 0, 0, 'L', 0);
		$this->Ln(6);
		
//		// Write note date
//		$this->Write(4, date('d/m/Y H:i', strtotime($note['date'])) . '    ' . $note['status']);
//		$this->Ln();
		
//		// Format and output the note
//		$this->SetFont('Arial', '', 7);
//		$note['note'] = trim($note['note']);
//		$note['note'] = preg_replace('/\r\n\r\n */i', "\n", $note['note']);
//		$this->Write(4, $note['note']);
//		$this->Ln(10);
		
		
		if (isset($note['note_history']))
		{
			foreach ($note['note_history'] as $history)
			{
				// Write note date
				$this->SetFont('Arial', 'B', 7);
				$this->Write(4, date('d/m/Y H:i', strtotime($history['date'])) . '    ' . $history['status']);
				$this->Ln();
				
				// Format and output the note
				$this->SetFont('Arial', '', 7);
				$history['note'] = trim($history['note']);
				$history['note'] = preg_replace('/\r\n\r\n */i', "\n", $history['note']);
				
				// Filter out odd characters
				$history['note'] = $this->formatForCharSet($history['note']);
				
				$this->Write(4, $history['note']);
				$this->Ln(10);
			}
        }
		
		$this->Ln(5);
	}

	/**
	 * Format a given string to filter out odd characters.
	 * @param string $str
	 * @return string
	 */
	public function formatForCharSet($str)
	{
		// &amp;
		$str = preg_replace('/&amp;/i', "&", $str);

		// Rather than just using the pattern \W (non-word character) which is equivalent to [^0-9A-Za-z]
		// I use [^0-9A-Za-z]  to catch when hypens are used, e.g.
		//     "... a similar story to Ewan McTaggart - they are coming to ..."
	
		// 'd
		$str = preg_replace('/[^0-9A-Za-z\-]{3,}d/', "'d", $str);

		// 's
		$str = preg_replace('/[^0-9A-Za-z\-]{3,}s/', "'s", $str);
		
		// 't
		$str = preg_replace('/[^0-9A-Za-z\-]{3,}t/', "'t", $str);
				
		// 've				
		$str = preg_replace('/[^0-9A-Za-z\-]{3,}ve/', "'ve", $str);
		
		return $str;
	}
	

	protected static function array_utf8_to_iso88591($in) 
	{
		$out = array();

		if (is_array($in)) 
		{
			foreach ($in as $key => $value) 
			{
				$out[self::array_utf8_to_iso88591($key)] = self::array_utf8_to_iso88591($value);
			}
			return $out;
		} 

		if (is_string($in)) 
		{
			return iconv("UTF-8", "windows-1252//TRANSLIT", $in);
		} 

		return $in;
	}
}

?>