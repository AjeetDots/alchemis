<?php

/**
 * Defines the app_report_Report15 class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');

require_once('app/domain/Filter.php');
require_once('app/domain/FilterBuilder.php');

require_once('include/fpdf/fpdf_table.php');
//require_once('include/fpdf/.php');
require_once('include/EasySql/EasySql.class.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_report_Report15 extends FPDF_TABLE
{

	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 */
    public function __construct($clientId)
    {

//        $this->params['client_id']                      = $client_id;


        parent::__construct('L');
        $this->AliasNbPages();

      	$this->clientExceptionBase($clientId);

	}

	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 15', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(180, 5, 'Client Exception Report', 0, 0, 'C', 0);
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'R', 0);
		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
//		$this->Cell(180, 5, 'For ), 0, 0, 'C', 0);
		$this->Cell(50, 5, '', 0, 0, 'R', 0);
		$this->Ln(10);

	}

	public function Footer()
	{
		//Go to 1.5 cm from bottom
    	$this->SetY(-15);
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'L', 0);
//		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$this->Cell(0, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'R', 0);
//		$this->Ln(10);

	}


	/**
	 * Output report body
	 */
	public function Body()
	{
		$this->AddPage();
		$this->SetFont('Arial', 'B', 8);
//		$this->OutputHeader();
//		$this->OutputFooter();
		$this->SetFont('Arial','', 8);

		// Layout
		$this->SetFont('Arial', '', 8);
	}

    public function clientExceptionBase($clientId)
    {
        $quarter = '';
        $x = 0;
        $currentQuarter = null;
        $this->AddPage();

//        echo $year . '-01-01';
        $rows = app_domain_ReportReader::getReport15ClientExceptionBase($clientId);

//        print_r($rows);
        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);

        $this->Cell(190, 5, $rows[0]['client_name'],         0, 0, 'L', 0);
        $this->Ln(10);

        $this->Cell(50, 5, 'Lead NBM',         1, 0, 'L', 0);
        $this->Cell(140, 5, $rows[0]['nbm'],         1, 0, 'L', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport15ClientMaxTargetDate($clientId);
        $this->Cell(50, 5, 'Maximum targets year/month',         1, 0, 'L', 0);
        $this->Cell(140, 5, $rows[0]['max_target_date'],         1, 0, 'L', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport15ClientSectorCount($clientId);
        $this->Cell(50, 5, 'Number of target sectors',         1, 0, 'L', 0);
        $this->Cell(140, 5, $rows[0]['sector_count'],         1, 0, 'L', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport15ClientDisciplineCount($clientId);
        $this->Cell(50, 5, 'Number of target disciplines',         1, 0, 'L', 0);
        $this->Cell(140, 5, $rows[0]['discipline_count'],         1, 0, 'L', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport15ClientFreshLeads($clientId);
        $this->Cell(50, 5, 'Number of fresh leads',         1, 0, 'L', 0);
        $this->Cell(140, 5, $rows[0]['fresh_lead_count'],         1, 0, 'L', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport15ClientMeetings($clientId);
        $this->Cell(50, 5, 'Meetings TBRs',         1, 0, 'L', 0);
        $this->Cell(140, 5, $rows[0]['tbr'],         1, 0, 'L', 0);
        $this->Ln();
        $this->Cell(50, 5, 'Meetings unknown status',         1, 0, 'L', 0);
        $this->Cell(140, 5, $rows[0]['unknown'],         1, 0, 'L', 0);
        $this->Ln();
    }
}

?>