<?php

/**
 * Defines the app_report_Report13 class.
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
class app_report_Report13 extends FPDF
{

	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 */
    public function __construct($year, $nbm_exclusions, $client_id = null)
    {
        if (is_null($year))
        {
            $year = date('Y');
        }
        $this->params['year'] = $year;
        $this->params['nbm_exclusions'] = $nbm_exclusions;
        $this->params['client_id'] = $client_id;

        parent::__construct('P');
        $this->AliasNbPages();

        $this->quarterSummaryByNBM();
        $this->quarterSummaryByNBMClient();


	}

	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 13', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(90, 5, 'NBM Bonus Report', 0, 0, 'C', 0);
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'R', 0);
		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
		$this->Cell(90, 5, 'For year ' . $this->params['year'], 0, 0, 'C', 0);
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

    public function quarterSummaryByNBM()
    {
        $quarter = '';
        $x = 0;
        $currentQuarter = null;
        $this->AddPage();

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Quarter Summary by NBM', 0, 0, 'C', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport13QuarterSummary($this->params['year'], $this->params['nbm_exclusions'], $this->params['client_id']);

        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);

        // Loop over data rows
        foreach ($rows as $row)
        {
            if ($quarter != $row['period_year'] . $row['period_quarter']) {
            	$x==0 ? $currentQuarter = $row['period_year'] . $row['period_quarter'] : null;

                $this->SetFont('Arial', 'B', 10);
                $this->Ln();
                $this->Cell(80, 5, 'Period: ', 1, 0, 'L', 0);
                $this->Cell(110, 5, 'Q' . $row['period_quarter'] . ' (' . $row['period_year'] . ')', 1, 0, 'L', 0);
                $this->Ln();

                // Table header row
                if ($x == 0) {
	                $this->Cell(48, 5, 'NBM', 1, 0, 'L', 0);
	                $this->Cell(22, 5, 'Attended', 1, 0, 'L', 0);
	                $this->Cell(18, 5, 'TBR',      1, 0, 'C', 0);
	                $this->Cell(22, 5, 'Cancelled', 1, 0, 'C', 0);
	                $this->Cell(22, 5, 'Unknown',  1, 0, 'C', 0);
	                $this->Cell(18, 5, 'Diary',  1, 0, 'C', 0);
	                $this->Cell(18, 5, 'Total',     1, 0, 'C', 0);
	                $this->Cell(22, 5, 'Attended %', 1, 0, 'C', 0);
                } else {
                	$this->Cell(58, 5, 'NBM', 1, 0, 'L', 0);
                    $this->Cell(22, 5, 'Attended', 1, 0, 'L', 0);
                    $this->Cell(22, 5, 'TBR',      1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Cancelled', 1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Unknown',  1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Total',     1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Attended %', 1, 0, 'C', 0);
                }
                $this->Ln();



                $this->SetFont('Arial', '', 10);

                $quarter = $row['period_year'] . $row['period_quarter'];
                $x++;
            }

            if ($currentQuarter == $quarter) {
                $total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'] + (int)$row['diary'];
            } else {
            	$total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'];
            }

            if ($total > 0) {
            	if ($currentQuarter == $quarter) {
		            $this->Cell(48, 5, $row['nbm'],         1, 0, 'L', 0);
		            $this->Cell(22, 5, $row['attended'],         1, 0, 'L', 0);
		            $this->Cell(18, 5, $row['tbr'],            1, 0, 'C', 0);
		            $this->Cell(22, 5, $row['cancelled'],       1, 0, 'C', 0);
		            $this->Cell(22, 5, $row['unknown'],        1, 0, 'C', 0);
		            $this->Cell(18, 5, $row['diary'],        1, 0, 'C', 0);
		            $this->Cell(18, 5, $total, 1, 0, 'C', 0);
            	} else {
            		$this->Cell(58, 5, $row['nbm'],         1, 0, 'L', 0);
	                $this->Cell(22, 5, $row['attended'],         1, 0, 'L', 0);
	                $this->Cell(22, 5, $row['tbr'],            1, 0, 'C', 0);
	                $this->Cell(22, 5, $row['cancelled'],       1, 0, 'C', 0);
	                $this->Cell(22, 5, $row['unknown'],        1, 0, 'C', 0);
	                $this->Cell(22, 5, $total, 1, 0, 'C', 0);
            	}
	            $this->Cell(22, 5, number_format((((int)$row['attended']/$total) * 100)) . '%', 1, 0, 'C', 0);
	            $this->Ln();
            }
        }
    }

    public function quarterSummaryByNBMClient()
    {
        $quarter = '';
        $x = 0;
        $currentQuarter = null;
        $currentNbm = '';

        $rows = app_domain_ReportReader::getReport13QuarterClientSummary($this->params['year'], $this->params['nbm_exclusions'], $this->params['client_id']);

        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);

        // Loop over data rows
        foreach ($rows as $row)
        {
            if ($quarter != $row['period_year'] . $row['period_quarter']) {

            	$this->AddPage();

            	if ($x==0) {
            		$this->SetFont('Arial', 'B', 10);
            		$this->Cell(190, 5, 'Quarter Summary by NBM and Client', 0, 0, 'C', 0);
            		$this->Ln();
            		$currentQuarter = $row['period_year'] . $row['period_quarter'];
            	}

                $this->SetFont('Arial', 'B', 10);
                $this->Ln();
                $this->Cell(80, 5, 'Period: ', 1, 0, 'L', 0);
                $this->Cell(110, 5, 'Q' . $row['period_quarter'] . ' (' . $row['period_year'] . ')', 1, 0, 'L', 0);
                $this->Ln();

                // Table header row
                if ($x == 0) {
                    $this->Cell(48, 5, 'NBM', 1, 0, 'L', 0);
                    $this->Cell(22, 5, 'Attended', 1, 0, 'L', 0);
                    $this->Cell(18, 5, 'TBR',      1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Cancelled', 1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Unknown',  1, 0, 'C', 0);
                    $this->Cell(18, 5, 'Diary',  1, 0, 'C', 0);
                    $this->Cell(18, 5, 'Total',     1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Attended %', 1, 0, 'C', 0);
                } else {
                    $this->Cell(58, 5, 'NBM', 1, 0, 'L', 0);
                    $this->Cell(22, 5, 'Attended', 1, 0, 'L', 0);
                    $this->Cell(22, 5, 'TBR',      1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Cancelled', 1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Unknown',  1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Total',     1, 0, 'C', 0);
                    $this->Cell(22, 5, 'Attended %', 1, 0, 'C', 0);
                }
                $this->Ln();

                $quarter = $row['period_year'] . $row['period_quarter'];
                $x++;


            }


            if ($currentQuarter == $quarter) {
                $total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'] + (int)$row['diary'];
            } else {
                $total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'];
            }

            if ($total > 0) {

	            if ($currentNbm != $row['nbm']) {
	            	    $this->Ln();
	            	    $this->SetFont('Arial', 'B', 10);
	                    $this->Cell(190, 5, $row['nbm'],         1, 0, 'L', 0);
	                    $currentNbm = $row['nbm'];
	                    $this->Ln();
	                    $this->SetFont('Arial', '', 10);
	            }



                if ($total > 0) {
	                if ($currentQuarter == $quarter) {
	                	$this->Cell(48, 5, $row['client'],         1, 0, 'L', 0);
                        $this->Cell(22, 5, $row['attended'],         1, 0, 'L', 0);
                        $this->Cell(18, 5, $row['tbr'],            1, 0, 'C', 0);
                        $this->Cell(22, 5, $row['cancelled'],       1, 0, 'C', 0);
                        $this->Cell(22, 5, $row['unknown'],        1, 0, 'C', 0);
                        $this->Cell(18, 5, $row['diary'],        1, 0, 'C', 0);
                        $this->Cell(18, 5, $total, 1, 0, 'C', 0);

	                } else {
	                    $this->Cell(58, 5, $row['client'],         1, 0, 'L', 0);
		                $this->Cell(22, 5, $row['attended'],         1, 0, 'L', 0);
		                $this->Cell(22, 5, $row['tbr'],            1, 0, 'C', 0);
		                $this->Cell(22, 5, $row['cancelled'],       1, 0, 'C', 0);
		                $this->Cell(22, 5, $row['unknown'],        1, 0, 'C', 0);
		                $this->Cell(22, 5, $total, 1, 0, 'C', 0);
	                }
	                $this->Cell(22, 5, number_format((((int)$row['attended']/$total) * 100)) . '%', 1, 0, 'C', 0);
	                $this->Ln();
	            }



            }
        }
    }

}
?>