<?php

/**
 * Defines the app_report_Report10 class.
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
class app_report_Report10 extends FPDF
{

	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 */
    public function __construct($start, $end)
    {
        $this->params['start']                         = $start . ' 00:00:00';
        $this->params['end']                           = $end . ' 23:59:59';
//        $this->params['client_id']                     = $client_id;

        // variables to hold values between function calls
        $this->effectives_per_meeting_set = 0;

        parent::__construct('P');
        $this->AliasNbPages();

        $this->globalSectorEffectivenessSection();
        $this->nbmSectorEffectivenessSection();

	}

	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 10', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(90, 5, 'Industry Sector Analysis Report', 0, 0, 'C', 0);
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

	}

    public function globalSectorEffectivenessSection()
    {
        $this->AddPage();

        $this->SetFont('Arial', 'B', 10);

        $rows = app_domain_ReportReader::getReport10GlobalSectorAnalysis($this->params['start'], $this->params['end']);

        // Table header row
        $this->Cell(80, 5, 'Sector', 1, 0, 'L', 0);
        $this->Cell(22, 5, 'Calls',      1, 0, 'C', 0);
        $this->Cell(22, 5, 'Effectives', 1, 0, 'C', 0);
        $this->Cell(22, 5, 'Meets Set',  1, 0, 'C', 0);
        $this->Cell(22, 5, 'Access',     1, 0, 'C', 0);
        $this->Cell(22, 5, 'Conversion', 1, 0, 'C', 0);
        $this->Ln();

         $this->SetFont('Arial', '', 10);

        // Loop over data rows
        foreach ($rows as $row)
        {
        	$this->Cell(80, 5, $row['sector'],         1, 0, 'L', 0);
        	$this->Cell(22, 5, $row['calls'],            1, 0, 'C', 0);
        	$this->Cell(22, 5, $row['effectives'],       1, 0, 'C', 0);
        	$this->Cell(22, 5, $row['meets_set'],        1, 0, 'C', 0);
        	$this->Cell(22, 5, number_format($row['access'],2) . '%',     1, 0, 'C', 0);
        	$this->Cell(22, 5, number_format($row['conversion'],2) . '%', 1, 0, 'C', 0);
        	$this->Ln();
        }
    }

    public function nbmSectorEffectivenessSection()
    {
    	$currentNbm = '';

        $this->AddPage();

        $rows = app_domain_ReportReader::getReport10NbmSectorAnalysis($this->params['start'], $this->params['end']);

        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);

        // Loop over data rows
        foreach ($rows as $row)
        {
        	if ($currentNbm != $row['nbm']) {
        		$this->SetFont('Arial', 'B', 10);
        		$this->Ln();
        		$this->Cell(80, 5, 'NBM: ', 1, 0, 'L', 0);
        		$this->Cell(110, 5, $row['nbm'], 1, 0, 'L', 0);
        		$this->Ln();

		        // Table header row
		        $this->Cell(80, 5, 'Sector', 1, 0, 'L', 0);
		        $this->Cell(22, 5, 'Calls',      1, 0, 'C', 0);
		        $this->Cell(22, 5, 'Effectives', 1, 0, 'C', 0);
		        $this->Cell(22, 5, 'Meets Set',  1, 0, 'C', 0);
		        $this->Cell(22, 5, 'Access',     1, 0, 'C', 0);
		        $this->Cell(22, 5, 'Conversion', 1, 0, 'C', 0);
		        $this->Ln();

		        $currentNbm = $row['nbm'];
		        $this->SetFont('Arial', '', 10);
        	}

            $this->Cell(80, 5, $row['sector'],         1, 0, 'L', 0);
            $this->Cell(22, 5, $row['calls'],            1, 0, 'C', 0);
            $this->Cell(22, 5, $row['effectives'],       1, 0, 'C', 0);
            $this->Cell(22, 5, $row['meets_set'],        1, 0, 'C', 0);
            $this->Cell(22, 5, number_format($row['access'],2) . '%',     1, 0, 'C', 0);
            $this->Cell(22, 5, number_format($row['conversion'],2) . '%', 1, 0, 'C', 0);
            $this->Ln();
        }
    }

}
?>