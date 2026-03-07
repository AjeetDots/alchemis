<?php

/**
 * Defines the app_report_Report14 class.
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
class app_report_Report14 extends FPDF
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

//        // Work out previous and next month
//        $year  = substr($year_month, 0, 4);
//        $month = substr($year_month, 4, 2);

        parent::__construct('P');
        $this->AliasNbPages();


        $this->makeReport();
//        $this->quarterSummaryByNBMClient($year);


	}

	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 14', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(90, 5, 'NBM Bonus Detail Report', 0, 0, 'C', 0);
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

	public function makeReport()
	{
		// Changed 16/04/2011: if user has permission 'permission_admin_reports') then assume no exclusions, otherwise,
        // limit report only currently logged on user
		// Get user information from the session
        $session = Auth_Session::singleton();
        $session_user = $session->getSessionUser();
        $user = app_domain_RbacUser::find($session_user['id']);
        $this->session_user = $user;

//        echo $session_user['id'];

		if ($this->session_user->hasPermission('permission_admin_reports')) {
			$rows = app_domain_ReportReader::getReport14NBMList($this->params['nbm_exclusions'], $this->params['client_id']);
		} else {

			$rows = app_domain_ReportReader::getReport14SingleNBMList($session_user['id']);
		}


        foreach ($rows as $row)
        {
        	$this->quarterSummaryByNBM($this->params['year'], $row['id']);
        	$this->quarterFinancialSummaryByNBM($this->params['year'], $row['id']);
        	$this->quarterSummaryByNBMClient($this->params['year'], $row['id']);
        }
	}

    public function quarterSummaryByNBM($year, $userId)
    {
        $quarter = '';
        $x = 0;
        $currentQuarter = null;
        $this->AddPage();

//        echo $year . '-01-01';
        $rows = app_domain_ReportReader::getReport14NBMSummary($year . '-01-01', $userId);

        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);

        if (count($rows) > 0) {
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
    }

    public function quarterFinancialSummaryByNBM($year, $userId)
    {
        $quarter = '';
        $x = 0;
        $currentQuarter = null;
        $runningAttended = 0;
        $runningPotential = 0;
        $runningWasted = 0;
        $diary = 0;
        $this->Ln();

        $rows = app_domain_ReportReader::getReport14NBMSummary($year . '-01-01', $userId);

        // Change font to normal from bold
        $this->SetFont('Arial', 'B', 10);

        if (count($rows) > 0) {
	        // Loop over data rows
	        foreach ($rows as $row)
	        {
	                $x==0 ? $currentQuarter = $row['period_year'] . $row['period_quarter'] : null;

	                // Table header row
	                if ($x == 0) {
	                    $this->Cell(84, 5, '', 1, 0, 'L', 0);
	                    $this->Cell(22, 5, 'Total Att', 1, 0, 'L', 0);
	                    $this->Cell(40, 5, 'Potental Total',      1, 0, 'C', 0);
	                    $this->Cell(22, 5, 'Wasted', 1, 0, 'C', 0);
	                    $this->Cell(22, 5, 'Att Rate',  1, 0, 'C', 0);
	                    $this->Ln();
	                }

	                $this->SetFont('Arial', '', 10);

	                $total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'];

	            if ($x > 0) {
	                    $this->Cell(84, 5, 'Q' . $row['period_quarter'],         1, 0, 'L', 0);
	                    $this->Cell(22, 5, (int)$row['attended'] * 100,         1, 0, 'L', 0);
	                    $runningAttended += (int)$row['attended'] * 100;
	                    $this->Cell(40, 5, (int)$total * 100,            1, 0, 'C', 0);
	                    $runningPotential += (int)$total * 100;
	                    $this->Cell(22, 5, ((int)$total * 100) - ((int)$row['attended'] * 100),       1, 0, 'C', 0);
	                    $runningWasted += ((int)$total * 100) - ((int)$row['attended'] * 100);
	                    $runningTotal += $total*100;
	                    $this->Cell(22, 5, number_format((int)$row['attended']*100/(int)$total) . '%',        1, 0, 'C', 0);
	                $this->Ln();
	            }

	            $x++;
	        }

	        $this->Cell(84, 5, 'Totals',         1, 0, 'L', 0);
	        $this->Cell(22, 5, $runningAttended,         1, 0, 'L', 0);
	        $this->Cell(40, 5, $runningPotential,            1, 0, 'C', 0);
	        $this->Cell(22, 5, $runningWasted,       1, 0, 'C', 0);
	        $this->Cell(22, 5, number_format($runningAttended/$runningTotal*100). '%',        1, 0, 'C', 0);

	        $this->Ln();
	        $this->Ln();
	        $x=0;
	        // Loop over data rows
	        foreach ($rows as $row)
	        {
	                $x==0 ? $currentQuarter = $row['period_year'] . $row['period_quarter'] : null;
	                // Table header row
	                if ($x == 0) {
	                	$this->SetFont('Arial', 'B', 10);
	                    $this->Cell(84, 5, '', 1, 0, 'L', 0);
	                    $this->Cell(22, 5, 'Actual', 1, 0, 'L', 0);
	                    $this->Cell(40, 5, 'Diary',      1, 0, 'C', 0);
	                    $this->Cell(22, 5, 'Max', 1, 0, 'C', 0);
	                    $this->Cell(22, 5, 'Att Rate',  1, 0, 'C', 0);
	                    $this->Ln();
	                }

	                $this->SetFont('Arial', '', 10);
	                $total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'];

	            if ($x == 0) {
	                    $this->Cell(84, 5, 'Q' . $row['period_quarter'],         1, 0, 'L', 0);
	                    $this->Cell(22, 5, (int)$row['attended'] * 100,         1, 0, 'L', 0);
	                    $runningAttended += (int)$row['attended'] * 100;
	                    $this->Cell(40, 5, (int)$row['diary'] * 100,            1, 0, 'C', 0);
	                    $runningPotential += ((int)$row['diary'] * 100) + ((int)$row['attended'] * 100);
	                    $diary = (int)$row['diary'] * 100;
	                    $this->Cell(22, 5, ((int)$row['diary'] * 100) + ((int)$row['attended'] * 100),       1, 0, 'C', 0);
	//                    $runningWasted += ((int)$total * 100) - ((int)$row['attended'] * 100);
	//                    $runningTotal += $total*100;
	                    $this->Cell(22, 5, number_format((int)$row['attended']*100/(int)$total) . '%',        1, 0, 'C', 0);
	                $this->Ln();
	            }

	            $x++;
	        }


	        $this->Ln();
	        $this->SetFont('Arial', 'B', 10);
	        $this->Cell(190, 5, 'Year to date', 1, 0, 'L', 0);
	        $this->Ln();
	        $this->Cell(84, 5, '', 1, 0, 'L', 0);
	        $this->Cell(22, 5, 'Total Att', 1, 0, 'L', 0);
	        $this->Cell(40, 5, 'Potental Total',      1, 0, 'C', 0);
	        $this->Cell(22, 5, 'Wasted', 1, 0, 'C', 0);
	        $this->Cell(22, 5, 'Att Rate',  1, 0, 'C', 0);
	        $this->SetFont('Arial', '', 10);
	        $this->Ln();

	        $this->Cell(84, 5, 'Actual',         1, 0, 'L', 0);
	        $this->Cell(22, 5, $runningAttended,         1, 0, 'L', 0);
	        $this->Cell(40, 5, $runningPotential,            1, 0, 'C', 0);
	        $this->Cell(22, 5, $runningWasted,       1, 0, 'C', 0);
	        $this->Cell(22, 5, number_format((int)$runningAttended*100/(int)$runningPotential) . '%',        1, 0, 'C', 0);
	        $this->Ln();

	        $this->Cell(84, 5, 'Max',         1, 0, 'L', 0);
	        $this->Cell(22, 5, $runningAttended + $diary,         1, 0, 'L', 0);
	        $this->Cell(40, 5, $runningPotential,            1, 0, 'C', 0);
	        $this->Cell(22, 5, $runningWasted - $diary,       1, 0, 'C', 0);
	        $this->Cell(22, 5, number_format(($runningAttended + $diary)*100/$runningPotential) . '%',        1, 0, 'C', 0);
         }

         $this->Ln();


    }


    public function quarterSummaryByNBMClient($year, $userId)
    {
        $quarter = '';
        $x = 0;
        $currentQuarter = null;
        $currentNbm = '';

        $this->Ln();

        $rows = app_domain_ReportReader::getReport14QuarterClientSummary($year, $userId);

        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);

        $clientYearToDateRunningTotals = array();

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

                $quarter = $row['period_year'] . $row['period_quarter'];
                $x++;


            }

            if ($currentQuarter == $quarter) {
                $total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'] + (int)$row['diary'];
            } else {
                $total = (int)$row['attended'] + (int)$row['tbr'] + (int)$row['cancelled'] + (int)$row['unknown'];
            }

            // Add records to running totals by client
            // Does the current client_id exist in $clientYearToDateRunningTotals?
            if (array_key_exists($row['client'],$clientYearToDateRunningTotals)) {
                $clientYearToDateRunningTotals[$row['client']]['attended'] = $clientYearToDateRunningTotals[$row['client']]['attended'] + (int)$row['attended'];
                $clientYearToDateRunningTotals[$row['client']]['tbr'] = $clientYearToDateRunningTotals[$row['client']]['tbr'] + (int)$row['tbr'];
                $clientYearToDateRunningTotals[$row['client']]['cancelled'] = $clientYearToDateRunningTotals[$row['client']]['cancelled']+ (int)$row['cancelled'];
                $clientYearToDateRunningTotals[$row['client']]['unknown'] = $clientYearToDateRunningTotals[$row['client']]['unknown'] + (int)$row['unknown'];
                $clientYearToDateRunningTotals[$row['client']]['diary'] = $clientYearToDateRunningTotals[$row['client']]['diary'] + (int)$row['diary'];
                $clientYearToDateRunningTotals[$row['client']]['total'] = $clientYearToDateRunningTotals[$row['client']]['total'] + $total;
            } else {
                $clientYearToDateRunningTotals[$row['client']]['attended'] = (int)$row['attended'];
                $clientYearToDateRunningTotals[$row['client']]['tbr'] = (int)$row['tbr'];
                $clientYearToDateRunningTotals[$row['client']]['cancelled'] = (int)$row['cancelled'];
                $clientYearToDateRunningTotals[$row['client']]['unknown'] = (int)$row['unknown'];
                $clientYearToDateRunningTotals[$row['client']]['total'] = $total;
            }

            $this->SetFont('Arial', '', 10);

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


        // now write out YTD totals
        $this->SetFont('Arial', 'B', 10);
        $this->Ln();
        $this->Cell(80, 5, 'Period: ', 1, 0, 'L', 0);
        $this->Cell(110, 5, 'Year to date', 1, 0, 'L', 0);
        $this->Ln();

        // Table header row
        $this->Cell(48, 5, 'Client', 1, 0, 'L', 0);
        $this->Cell(22, 5, 'Attended', 1, 0, 'L', 0);
        $this->Cell(18, 5, 'TBR',      1, 0, 'C', 0);
        $this->Cell(22, 5, 'Cancelled', 1, 0, 'C', 0);
        $this->Cell(22, 5, 'Unknown',  1, 0, 'C', 0);
        $this->Cell(18, 5, 'Diary',  1, 0, 'C', 0);
        $this->Cell(18, 5, 'Total',     1, 0, 'C', 0);
        $this->Cell(22, 5, 'Attended %', 1, 0, 'C', 0);
        $this->Ln();

        // set up array to get YTD totals
        $ytdTotals = array('attended'   => 0,
                            'tbr'       => 0,
                            'cancelled' => 0,
                            'unknown'   => 0,
                            'diary'     => 0,
                            'total'     => 0,
                     );

        foreach ($clientYearToDateRunningTotals as $key => $item) {
        	$this->Cell(48, 5, $key,         1, 0, 'L', 0);
            $this->Cell(22, 5, $item['attended'],         1, 0, 'L', 0);
            $this->Cell(18, 5, $item['tbr'],            1, 0, 'C', 0);
            $this->Cell(22, 5, $item['cancelled'],       1, 0, 'C', 0);
            $this->Cell(22, 5, $item['unknown'],        1, 0, 'C', 0);
            $this->Cell(18, 5, $item['diary'],        1, 0, 'C', 0);
            $this->Cell(18, 5, $item['total'], 1, 0, 'C', 0);
            $this->Cell(22, 5, number_format((((int)$item['attended']/$item['total']) * 100)) . '%', 1, 0, 'C', 0);
	        $this->Ln();

	        $ytdTotals['attended'] += $item['attended'];
	        $ytdTotals['tbr'] += $item['tbr'];
	        $ytdTotals['cancelled'] += $item['cancelled'];
	        $ytdTotals['unknown'] += $item['unknown'];
	        $ytdTotals['diary'] += $item['diary'];
	        $ytdTotals['total'] += $item['total'];
        }

        // now write out YTD totals
        $this->Cell(48, 5, 'YTD Total',         1, 0, 'L', 0);
        $this->Cell(22, 5, $ytdTotals['attended'],         1, 0, 'L', 0);
        $this->Cell(18, 5, $ytdTotals['tbr'],            1, 0, 'C', 0);
        $this->Cell(22, 5, $ytdTotals['cancelled'],       1, 0, 'C', 0);
        $this->Cell(22, 5, $ytdTotals['unknown'],        1, 0, 'C', 0);
        $this->Cell(18, 5, $ytdTotals['diary'],        1, 0, 'C', 0);
        $this->Cell(18, 5, $ytdTotals['total'], 1, 0, 'C', 0);
        $this->Cell(22, 5, number_format((((int)$ytdTotals['attended']/$ytdTotals['total']) * 100)) . '%', 1, 0, 'C', 0);
        $this->Ln();
    }
}
?>