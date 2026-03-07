<?php

/**
 * Defines the app_report_Report7 class.
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
class app_report_Report7 extends FPDF
{

	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 */
    public function __construct($start, $end, $client_id,
                                $client_fact_summary = false,
                                $campaign_statistics = false,
                                $nbm_statistics = false,
                                $meetings_set_summary = false,
                                $cancellation_clinic = false,
                                $opportunities_and_wins_clinic = false,
                                $targeting_clinic = false,
                                $database_analysis = false,
                                $effectives_analysis = false,
                                $nbm_discipline_effectiveness = false,
                                $nbm_industry_effectiveness = false,
                                $pipeline_report = false,
                                $effective_notes = false)
    {
        $this->params['start']                         = $start . ' 00:00:00';
        $this->params['end']                           = $end . ' 23:59:59';
        $this->params['client_id']                     = $client_id;

        // variables to hold values between function calls
        $this->effectives_per_meeting_set = 0;

        parent::__construct('P');
        $this->AliasNbPages();
//		$this->Body();
//		$this->Legend();

//        if ($client_fact_summary)           $this->clientFactSummarySection();
        $this->clientFactSummarySection();
        if ($campaign_statistics)           $this->campaignStatisticsSection();
        if ($nbm_statistics)                $this->nbmStatisticsSection();
        if ($meetings_set_summary)          $this->meetingsSetSummarySection();
        if ($cancellation_clinic)           $this->cancellationClinicSection();
        //if ($opportunities_and_wins_clinic) $this->opportunitiesAndWinsClinicSection();
        //if ($targeting_clinic)              $this->targetingClinicSection();
        if ($database_analysis)             $this->databaseAnalysisSection();
        if ($effectives_analysis)           $this->effectivesAnalysisSection();
        if ($nbm_discipline_effectiveness)  $this->nbmDisciplineEffectivenessSection();
        if ($nbm_industry_effectiveness)    $this->nbmSectorEffectivenessSection();
        if ($pipeline_report)               $this->pipelineReportSection();
        //if ($effective_notes)               $this->effectiveNotesSection();
	}

	public function Header()
	{
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, 'Report ID 7', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(90, 5, 'Client Clinic Report', 0, 0, 'C', 0);
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
		$data = app_domain_ReportReader::getReport2Data($this->params['start'], $this->params['end'], $this->params['nbm_exclusions']);
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


    public function clientFactSummarySection()
    {
        $this->AddPage();

        // Page Title


        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Client Fact Summary', 0, 0, 'C', 0);
        $this->Ln(10);

        $campaign_data = app_domain_ReportReader::getReport7ClientCampaignSummary($this->params['start'], $this->params['end'], $this->params['client_id']);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Client Summary - ' . $campaign_data[0]['name'], 0, 0, 'C', 0);
        $this->Ln(10);

        $height = 6;
        $this->SetFont('Arial', '', 10);
        $this->Cell(50,  $height, 'Campaign Start Date:', 0, 0, 'L', 0);
        $this->Cell(140, $height, $campaign_data[0]['start_year_month'], 0, 0, 'L', 0);
        $this->Ln();

        $this->params['campaign_start'] = $campaign_data[0]['campaign_start_date'];

        $this->Cell(50,  $height, 'Campaign Month:', 0, 0, 'L', 0);
        $this->Cell(140, $height, $campaign_data[0]['campaign_month'], 0, 0, 'L', 0);
        $this->Ln();
        $this->Ln();

        $data = app_domain_ReportReader::getReport7ClientCampaignDisciplines($this->params['client_id']);
        $this->Cell(50,  $height, 'Client Discipline(s):', 0, 0, 'L', 0);
        $x = 0;
        foreach ($data as $d)
        {
        	if ($x == 0) // first time through loop write the dicipline on the same line
        	{
        		// do nothing
        	}
        	else // by now we will have dropped a line so need to write in a blank left column of the correct width
        	{
        		$this->Cell(50,  $height, '', 0, 0, 'L', 0);
        	}

            $this->Cell(140, $height, $d['discipline'], 0, 0, 'L', 0);
            $this->Ln();
            $x++;
        }

        $this->Ln();

//        $this->Cell(50,  $height, 'Client Size:', 0, 0, 'L', 0);
//        $this->Cell(140, $height, '', 0, 0, 'L', 0);
//        $this->Ln();

        $data = app_domain_ReportReader::getReport7ClientCampaignLeadNbm($this->params['client_id']);
        $this->Cell(50,  $height, 'Current Lead NBM:', 0, 0, 'L', 0);
        if ($campaign_data[0]['is_current'] == 1)
        {
        	$lead_nbm = $data[0]['name'];
        }
        else
        {
        	$lead_nbm = 'Not applicable - client is not current';
        }
        $this->Cell(140, $height, $lead_nbm, 0, 0, 'L', 0);
        $this->Ln();

        $data = app_domain_ReportReader::getReport7ClientCampaignNonLeadNbm($this->params['client_id']);
        $this->Cell(50,  $height, 'Other NBMs:', 0, 0, 'L', 0);
        $x = 0;
        foreach ($data as $d)
        {
        	if ($x == 0) // first time through loop write the dicipline on the same line
        	{
        		// do nothing
        	}
        	else // by now we will have dropped a line so need to write in a blank left column of the correct width
        	{
        		$this->Cell(50,  $height, '', 0, 0, 'L', 0);
        	}

        	if ($d['deactivated_date'] != '0000-00-00') // nbm is currently assigned to this client
        	{
        		$nbm = $d['name'] . ' (not current)';
        	}
        	else //nbm is no longer assigned to this client
        	{
        		$nbm = $d['name'];
        	}
            $this->Cell(140, $height, $nbm, 0, 0, 'L', 0);
            $this->Ln();
            $x++;
        }

        $this->Cell(140, $heights, '', 0, 0, 'L', 0);
        $this->Ln();
    }

    public function campaignStatisticsSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Campaign Statistics', 0, 0, 'C', 0);
        $this->Ln(15);

        // Reset font to normal rather than bold
        $this->SetFont('Arial', '', 10);

        // Results vs Objectives
        $this->Cell(190, 6, 'Results vs Objectives:', 0, 0, 'L', 0);
        $this->Ln(10);

        // Table header row
        $this->Cell(47.5, 6, 'Meetings set target',      1, 0, 'C', 0);
        $this->Cell(47.5, 6, 'Meetings set actual',      1, 0, 'C', 0);
        $this->Cell(47.5, 6, 'Meetings attended target', 1, 0, 'C', 0);
        $this->Cell(47.5, 6, 'Meetings attended actual', 1, 0, 'C', 0);
        $this->Ln();


        // Call Statistics
 		$data_targets = app_domain_ReportReader::getReport7MeetingsTargets($this->params['start'], $this->params['end'], $this->params['client_id']);

        $data = app_domain_ReportReader::getReport7DatabaseAnalysisProspect($this->params['start'], $this->params['end'], $this->params['client_id']);
        $data = $this->preProcess_CallStatistics($data, $data_targets, $this->params['start'], $this->params['end']);



        // Data row
        $this->Cell(47.5, 6, $data_targets[0]['meetings_set_target'], 1, 0, 'C', 0);

        if ($data[0]['meeting_set_count'] < $data_targets[0]['meetings_set_target']) $this->SetTextColor(255, 0, 0);
        $this->Cell(47.5, 6, $data[0]['meeting_set_count'], 1, 0, 'C', 0);
        $this->SetTextColor(0, 0, 0);

        $this->Cell(47.5, 6, $data_targets[0]['meetings_attended_target'], 1, 0, 'C', 0);

        if ($data[0]['meeting_category_attended_count'] < $data_targets[0]['meetings_attended_target']) $this->SetTextColor(255, 0, 0);
        $this->Cell(47.5, 6, $data[0]['meeting_category_attended_count'], 1, 0, 'C', 0);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(15);

        // Call Statistics
//        $data = app_domain_ReportReader::getReport7DatabaseAnalysisProspect($this->params['start'], $this->params['end'], $this->params['client_id']);
//        $data = $this->preProcess_CallStatistics($data, $this->params['start'], $this->params['end']);


//        print_r($data);

        $this->Cell(190, 6, 'Call Statistics:', 0, 0, 'L', 0);
        $this->Ln(10);

        $this->Cell(95, 6, 'Calls', 1, 0, 'L', 0);
        $this->Cell(95, 6, $data[0]['call_count'], 1, 1, 'L', 0);

        $this->Cell(95, 6, 'Effectives', 1, 0, 'L', 0);
        $this->Cell(95, 6, $data[0]['call_effective_count'], 1, 1, 'L', 0);

        $this->Cell(95, 6, 'Meetings Set', 1, 0, 'L', 0);
        $this->Cell(95, 6, $data[0]['meeting_set_count'], 1, 1, 'L', 0);

        $this->Cell(95, 6, 'Access Rate', 1, 0, 'L', 0);
        if ($data[0]['access_rate'] < 13) $this->SetTextColor(255, 0, 0);
        $this->Cell(95, 6, number_format($data[0]['access_rate'],2) . '%', 1, 0, 'L', 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(1)', 0, 1, 'L', 0);
        $this->SetFont('Arial', '', 10);

        $this->Cell(95, 6, 'Conversion Rate', 1, 0, 'L', 0);
        if ($data[0]['conversion_rate'] < 8) $this->SetTextColor(255, 0, 0);
        $this->Cell(95, 6, number_format($data[0]['conversion_rate'],2) . '%', 1, 0, 'L', 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(2)', 0, 1, 'L', 0);
        $this->SetFont('Arial', '', 10);

        $this->Cell(95, 6, 'Average Calls per Month', 1, 0, 'L', 0);
        if ($data[0]['average_calls_per_month'] < 210) $this->SetTextColor(255, 0, 0);
        $this->Cell(95, 6, number_format($data[0]['average_calls_per_month'],2), 1, 0, 'L', 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(3)', 0, 1, 'L', 0);
        $this->SetFont('Arial', '', 10);

        $this->Cell(95, 6, 'Average Effectives per Month', 1, 0, 'L', 0);
        if ($data[0]['average_effectives_per_month'] < 25) $this->SetTextColor(255, 0, 0);
        $this->Cell(95, 6, number_format($data[0]['average_effectives_per_month'],2), 1, 0, 'L', 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(4)', 0, 1, 'L', 0);
        $this->SetFont('Arial', '', 10);

        $this->Cell(95, 6, 'Average Days / Month spent', 1, 0, 'L', 0);
        $this->Cell(95, 6, number_format($data[0]['average_days_month_spent'],2), 1, 1, 'L', 0);

        $this->Cell(95, 6, 'Effectives per meeting set', 1, 0, 'L', 0);
        //grab effectives per meeting set as we refer to this figure in the Effectives analysis
        $this->effectives_per_meeting_set = number_format($data[0]['effectives_per_meeting_set'],2);

        $this->Cell(95, 6, number_format($this->effectives_per_meeting_set,2), 1, 0, 'L', 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(6)', 0, 1, 'L', 0);
        $this->SetFont('Arial', '', 10);

        $this->Cell(95, 6, 'Effectives required to deliver monthly target', 1, 0, 'L', 0);
        $this->Cell(95, 6, number_format($data[0]['effs_reqd_to_deliver_ave_monthly_target'],2), 1, 1, 'L', 0);

        $this->Cell(95, 6, 'Calls per effective', 1, 0, 'L', 0);
        $this->Cell(95, 6, number_format($data[0]['calls_per_effective'],2), 1, 1, 'L', 0);

        $this->Cell(95, 6, 'Calls to deliver monthly target', 1, 0, 'L', 0);
        $this->Cell(95, 6, number_format($data[0]['calls_reqd_to_deliver_ave_monthly_target'],2), 1, 1, 'L', 0);

        $this->Cell(95, 6, 'Days required to deliver to client', 1, 0, 'L', 0);
        if ($data[0]['days_required_to_deliver'] > 4) $this->SetTextColor(255, 0, 0);
        $this->Cell(95, 6, number_format($data[0]['days_required_to_deliver'],2), 1, 0, 'L', 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(8)', 0, 1, 'L', 0);
        $this->SetFont('Arial', '', 10);

        $this->Cell(95, 6, 'Cancellation rate', 1, 0, 'L', 0);
        if ($data[0]['meeting_category_cancelled_rate'] > 15) $this->SetTextColor(255, 0, 0);
        $this->Cell(95, 6, number_format($data[0]['meeting_category_cancelled_rate'],2) . '%', 1, 0, 'L', 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(9)', 0, 1, 'L', 0);
        $this->SetFont('Arial', '', 10);


        $this->Cell(95, 6, 'On target effectives', 1, 0, 'L', 0);
        if ($data[0]['call_ote_rate'] < 90) $this->SetTextColor(255, 0, 0);
        $this->Cell(95, 6, number_format($data[0]['call_ote_rate'],2) . '%', 1, 0, 'L', 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(5, 6, '(10)', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 10);

        $this->Ln(10);

        // Expanation
        // Reset font to normal rather than bold
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(255, 0, 0);

        $this->Cell(190, 5, '(1) Access Rate shown in red if below 13%', 0, 1, 'L', 0);
        $this->Cell(190, 5, '(2) Conversion Rate shown in red if below 8%', 0, 1, 'L', 0);
        $this->Cell(190, 5, '(3) Average Calls per Month shown in red if below 210 (based on 70 calls per day or 3 work days)', 0, 1, 'L', 0);

        $this->Write(5, '(4) Average Effectives per Month show in red if below 25 – Agreed with Jim on 08/10/08 that report will run on a day-date range, but where months are referred to for some calculations then we will use the number of whole months. Eg for this row, we will use the number of months that occur in the date range (eg if 31/07/2008 - 01/10/2008 then number of months = 4) ');
        $this->Ln();
		$this->Cell(190, 5, '(5) Average days/month spent – based on a 70 calls per day average', 0, 1, 'L', 0);
		$this->Cell(190, 5, '(6) Effectives per meeting set – calculated using dividing overall effectives by meetings set', 0, 1, 'L', 0);
		$this->Cell(190, 5, '(7) Effective and calls required to hit monthly target - the target to be calculated using the total annual target divided by 12.', 0, 1, 'L', 0);
		$this->Cell(190, 5, '(8) Days required to deliver to the client, based on dividing calls required to deliver monthly target by 70. Shows in Red if above 4.', 0, 1, 'L', 0);
		$this->Cell(190, 5, '(9) Cancellation rate shown in red if above 15%.', 0, 1, 'L', 0);
        $this->Cell(190, 5, '(10) On target effectives highlight the proportion of effectives that are logged as on target. Shown in red if below 90%.', 0, 1, 'L', 0);
        $this->SetTextColor(0, 0, 0);
    }

    public function nbmStatisticsSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'NBM Statistics', 0, 0, 'C', 0);
        $this->Ln(40);

        // Result font face to normal from bold
        $this->SetFont('Arial', '', 10);

        $data_targets = app_domain_ReportReader::getReport7MeetingsTargets($this->params['start'], $this->params['end'], $this->params['client_id']);

        $data = app_domain_ReportReader::getReport7DatabaseAnalysisProspectByNbm($this->params['start'], $this->params['end'], $this->params['client_id']);

        $data = $this->preProcess_NbmCallStatistics($data, $data_targets, $this->params['start'], $this->params['end'], $this->params['client_id']);

//        print_r($data);
        $width = 10;
//        $width = 115 / count($data);

        // NBM names
        $this->Cell(75, 6, '', 0, 0, 'C', 0);
        $nameBaselineY = $this->getY();
        $nameBaselineX = $this->getX();
        foreach ($data as $d)
        {
        	$nameBaselineX+=3.5;
        	$this->SetXY($nameBaselineX, $nameBaselineY);

            $this->Rotate(90);
            $this->Cell($width, 6, $d['nbm'], 0, 0, 'L', 0);
            $this->Rotate(0);
            $nameBaselineX+=6.5;
        }

        $this->SetFont('Arial', '', 8);
        $this->Ln();

        // Calls
        $this->Cell(75, 6, 'Calls', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
	        $this->Cell($width, 6, $d['call_count'], 1, 0, 'C', 0);
        }
        $this->Ln();

        // Effectives
        $this->Cell(75, 6, 'Effectives', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            $this->Cell($width, 6, $d['call_effective_count'], 1, 0, 'C', 0);
        }
        $this->Ln();

        // Meetings Set
        $this->Cell(75, 6, 'Meetings Set', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            $this->Cell($width, 6, $d['meeting_set_count'], 1, 0, 'C', 0);
        }
        $this->Ln();

        // Access Rate
        $this->Cell(75, 6, 'Access Rate', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            if ($d['access_rate'] < 13) $this->SetTextColor(255, 0, 0);
        	$this->Cell($width, 6, number_format($d['access_rate'], 0) . '%', 1, 0, 'C', 0);
        	$this->SetTextColor(0, 0, 0);
        }
        $this->Ln();

        // Conversion Rate
        $this->Cell(75, 6, 'Conversion Rate', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
        	if ($d['conversion_rate'] < 8) $this->SetTextColor(255, 0, 0);
            $this->Cell($width, 6, number_format($d['conversion_rate'],0) . '%', 1, 0, 'C', 0);
            $this->SetTextColor(0, 0, 0);
        }
        $this->Ln();

        // Average Calls per Month
        $this->Cell(75, 6, 'Average Calls per Month', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
        	if ($d['average_calls_per_month'] < 210) $this->SetTextColor(255, 0, 0);
            $this->Cell($width, 6, number_format($d['average_calls_per_month'],2), 1, 0, 'C', 0);
            $this->SetTextColor(0, 0, 0);
        }
        $this->Ln();

        // Average Effectives per Month
        $this->Cell(75, 6, 'Average Effectives per Month', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
        	if ($d['average_effectives_per_month'] < 25) $this->SetTextColor(255, 0, 0);
            $this->Cell($width, 6, number_format($d['average_effectives_per_month'],2), 1, 0, 'C', 0);
            $this->SetTextColor(0, 0, 0);
        }
        $this->Ln();

        // Average Days per Month Spent
        $this->Cell(75, 6, 'Average Days per Month Spent', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            $this->Cell($width, 6, number_format($d['average_days_month_spent'],2), 1, 0, 'C', 0);
        }
        $this->Ln();

        // Effectives per Meeting Set
        $this->Cell(75, 6, 'Effectives per Meeting Set', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            $this->Cell($width, 6, number_format($d['effectives_per_meeting_set'],2), 1, 0, 'C', 0);
        }
        $this->Ln();

        // Effectives Required to Deliver Monthly Target
        $this->Cell(75, 6, 'Effectives Required to Deliver Monthly Target', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
//            $this->Cell($width, 6, number_format($d['effectives_target'],2), 1, 0, 'C', 0);
			$this->Cell($width, 6, number_format($d['effs_reqd_to_deliver_ave_monthly_target'],2), 1, 0, 'C', 0);

        }
        $this->Ln();

        // Calls per Effective
        $this->Cell(75, 6, 'Calls per Effective', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            $this->Cell($width, 6, number_format($d['calls_per_effective'],2), 1, 0, 'C', 0);
        }
        $this->Ln();

        // Calls to Deliver Monthly Target
        $this->Cell(75, 6, 'Calls to Deliver Monthly Target', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            $this->Cell($width, 6, number_format($d['calls_reqd_to_deliver_ave_monthly_target']), 1, 0, 'C', 0);
        }
        $this->Ln();

        // Days Required to Deliver to Client
        $this->Cell(75, 6, 'Days Required to Deliver to Client', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            $this->Cell($width, 6, number_format($d['days_required_to_deliver']), 1, 0, 'C', 0);
        }
        $this->Ln();

        // Cancellation Rate
        $this->Cell(75, 6, 'Cancellation Rate', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
            if ($d['meeting_category_cancelled_rate'] > 15) $this->SetTextColor(255, 0, 0);
        	$this->Cell($width, 6, number_format($d['meeting_category_cancelled_rate'],0) . '%', 1, 0, 'C', 0);
        	$this->SetTextColor(0, 0, 0);
        }
        $this->Ln();

        // On Target Effectives
        $this->Cell(75, 6, 'On Target Effectives', 1, 0, 'L', 0);
        foreach ($data as $d)
        {
        	if ($d['call_ote_rate'] < 90) $this->SetTextColor(255, 0, 0);
            $this->Cell($width, 6, number_format($d['call_ote_rate'],0) . '%', 1, 0, 'C', 0);
            $this->SetTextColor(0, 0, 0);
        }
        $this->Ln(15);

        $rows = app_domain_ReportReader::getReport7DatabaseAnalysisProspectByNbmByMonth($this->params['start'], $this->params['end'], $this->params['client_id']);
//        echo '<pre>';
//        print_r($rows);
//        echo '</pre>';


        // Header row
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, '',           'TL', 0, 'L');
        $this->Cell(20, 5, 'Calls',      'T',  0, 'C');
        $this->Cell(20, 5, 'Non-Effs',   'T',  0, 'C');
        $this->Cell(20, 5, 'Effectives', 'T',  0, 'C');
        $this->Cell(20, 5, 'ONTE',       'T',  0, 'C');
        $this->Cell(20, 5, 'OFFTE',      'T',  0, 'C');
        $this->Cell(20, 5, 'Access',     'T',  0, 'C');
        $this->Cell(20, 5, 'Conv',       'T',  0, 'C');
        $this->Cell(20, 5, 'Meets',      'TR', 0, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Ln();

        $current_nbm_id = null;
        foreach ($rows as $row)
        {
            if ($current_nbm_id != $row['nbm_id'])
            {
                if (!is_null($current_nbm_id))
                {
            	    $this->SetFont('Arial', 'B', 10);
                    $this->Cell(30, 5, 'Total',                                                          'L', 0, 'L', 0);
                    $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_count'),            '',  0, 'C', 0);
                    $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_non_effective_count'),        '',  0, 'C', 0);
                    $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_effective_count'),            '',  0, 'C', 0);
                    $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_ote_count'),  '',  0, 'C', 0);
                    $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_ofte_count'), '',  0, 'C', 0);
                    $this->Cell(20, 5, number_format(self::accessRateForNbm($rows, $current_nbm_id),2),            '',  0, 'C', 0);
                    $this->Cell(20, 5, number_format(self::conversionRateForNbm($rows, $current_nbm_id),2),        '',  0, 'C', 0);
                    $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'meeting_set_count'),                 'R', 0, 'C', 0);
                    $this->SetFont('Arial', '', 10);
                    $this->Ln();
                    $this->Cell(190, 5, '', 'LR');
                    $this->Ln();
                }

                // NBM name on single row
            	$this->SetFont('Arial', 'B', 10);
                $this->Cell(190, 5, $row['nbm'], 'LR');
                $this->SetFont('Arial', '', 10);
                $this->Ln();

                // Update current NBM
                $current_nbm_id = $row['nbm_id'];
        	}

        	// Data rows
            $this->Cell(30, 5, self::monthYearToMonthString($row['year_month']), 'L', 0, 'L', 0);
            $this->Cell(20, 5, $row['call_count'],                               '',  0, 'C', 0);
            $this->Cell(20, 5, $row['call_non_effective_count'],                           '',  0, 'C', 0);
            $this->Cell(20, 5, $row['call_effective_count'],                               '',  0, 'C', 0);
            $this->Cell(20, 5, $row['call_ote_count'],                     '',  0, 'C', 0);
            $this->Cell(20, 5, $row['call_ofte_count'],                    '',  0, 'C', 0);
            $this->Cell(20, 5, number_format($row['access_rate'],2),                                   '',  0, 'C', 0);
            $this->Cell(20, 5, number_format($row['conversion_rate'],2),                               '',  0, 'C', 0);
            $this->Cell(20, 5, $row['meeting_set_count'],                                    'R', 0, 'C', 0);
            $this->Ln();
        }

        // Final NBM totals
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Total',                                                          'L', 0, 'L', 0);
        $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_count'),                 '',  0, 'C', 0);
        $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_non_effective_count'),        '',  0, 'C', 0);
        $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_effective_count'),            '',  0, 'C', 0);
        $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_ote_count'),  '',  0, 'C', 0);
        $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'call_ofte_count'), '',  0, 'C', 0);
        $this->Cell(20, 5, number_format(self::accessRateForNbm($rows, $current_nbm_id),2),            '',  0, 'C', 0);
        $this->Cell(20, 5, number_format(self::conversionRateForNbm($rows, $current_nbm_id),2),        '',  0, 'C', 0);
        $this->Cell(20, 5, self::sumForNbm($rows, $current_nbm_id, 'meeting_set_count'),                 'R', 0, 'C', 0);
        $this->SetFont('Arial', '', 10);
        $this->Ln();
        $this->Cell(190, 5, '', 'LR');
        $this->Ln();

        // Grand total
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Grand Total',                                                    'LB', 0, 'L', 0);
        $this->Cell(20, 5, self::sum($rows, 'call_count'),                 'B',  0, 'C', 0);
        $this->Cell(20, 5, self::sum($rows, 'call_non_effective_count'),        'B',  0, 'C', 0);
        $this->Cell(20, 5, self::sum($rows, 'call_effective_count'),            'B',  0, 'C', 0);
        $this->Cell(20, 5, self::sum($rows, 'call_ote_count'),  'B',  0, 'C', 0);
        $this->Cell(20, 5, self::sum($rows, 'call_ofte_count'), 'B',  0, 'C', 0);
        $this->Cell(20, 5, number_format(self::accessRateTotal($rows),2),         'B',  0, 'C', 0);
        $this->Cell(20, 5, number_format(self::conversionRateTotal($rows),2),     'B',  0, 'C', 0);
        $this->Cell(20, 5, self::sum($rows, 'meeting_set_count'),                 'RB', 0, 'C', 0);
    }

    public function meetingsSetSummarySection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Meetings Set Summary', 0, 0, 'C', 0);
        $this->Ln(15);

        $this->Cell(190, 5, 'New Meetings Set', 0, 0, 'L', 0);
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 8);

        $this->Cell(60, 5, 'Company Name',          'LT', 0, 'L', 0);
        $this->Cell(60, 5, 'Job Title',             'LT', 0, 'L', 0);
        $this->Cell(25, 5, 'Date Set',			    'LT', 0, 'L', 0);
        $this->Cell(50, 5, 'Meeting Status',        'LTR', 0, 'L', 0);
        $this->Ln();
        $this->Cell(60, 5, '',          'LB', 0, 'L', 0);
        $this->Cell(60, 5, '',             'LB', 0, 'L', 0);
        $this->SetFont('Arial', 'BI', 8);
        $this->Cell(25, 5, '(Meeting Date)',      'LB', 0, 'L', 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 5, '',        'LBR', 0, 'L', 0);
        $this->Ln(5);

        $this->SetFont('Arial', '', 8);

        $data = app_domain_ReportReader::getReport7MeetingsSetSummaryNewMeetings($this->params['start'], $this->params['end'], $this->params['client_id']);
//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';

        foreach($data as $row)
        {
        	$this->Cell(60, 5, $row['name'],        'LT', 0, 'L', 0);
        	$this->Cell(60, 5, $row['job_title'],        'LT', 0, 'L', 0);
        	$this->Cell(25, 5, date('j M Y', strtotime($row['created_at'])),      	'LT', 0, 'L', 0);
        	$this->Cell(50, 5, $row['description'],        'LTR', 0, 'L', 0);
        	$this->Ln();
        	$this->Cell(60, 5, '',        'LB', 0, 'L', 0);
        	$this->Cell(60, 5, '',        'LB', 0, 'L', 0);
        	$this->SetFont('Arial','I', 8);
			$this->Cell(25, 5, '(' . date('j M Y', strtotime($row['date'])) . ')',      	'LB', 0, 'L', 0);
			$this->SetFont('Arial', '', 8);
        	$this->Cell(50, 5, '',        'LBR', 0, 'L', 0);
        	$this->Ln();
        }


        $this->Ln(15);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Rearranged Meetings', 0, 0, 'L', 0);
        $this->Ln(10);

        $data = app_domain_ReportReader::getReport7MeetingsSetSummaryRearrangedMeetings($this->params['start'], $this->params['end'], $this->params['client_id']);
//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';
        if (count($data) > 0 )
        {

	        $this->SetFont('Arial', 'B', 8);

	        $this->Cell(60, 5, 'Company Name',          'LT', 0, 'L', 0);
	        $this->Cell(60, 5, 'Job Title',             'LT', 0, 'L', 0);
	        $this->Cell(25, 5, 'Date Set',			    'LT', 0, 'L', 0);
	        $this->Cell(50, 5, 'Meeting Status',        'LTR', 0, 'L', 0);
	        $this->Ln();
	        $this->Cell(60, 5, '',          'LB', 0, 'L', 0);
	        $this->Cell(60, 5, '',             'LB', 0, 'L', 0);
	        $this->SetFont('Arial', 'BI', 8);
	        $this->Cell(25, 5, '(Meeting Date)',      'LB', 0, 'L', 0);
	        $this->SetFont('Arial', 'B', 8);
	        $this->Cell(50, 5, '',        'LBR', 0, 'L', 0);
	        $this->Ln(5);

	        $this->SetFont('Arial', '', 8);
	        foreach($data as $row)
	        {
	        	$this->Cell(60, 5, $row['name'],        'LT', 0, 'L', 0);
	        	$this->Cell(60, 5, $row['job_title'],        'LT', 0, 'L', 0);
	        	$this->Cell(25, 5, date('j M Y', strtotime($row['created_at'])),      	'LT', 0, 'L', 0);
	        	$this->Cell(50, 5, $row['description'],        'LTR', 0, 'L', 0);
	        	$this->Ln();
	        	$this->Cell(60, 5, '',        'LB', 0, 'L', 0);
	        	$this->Cell(60, 5, '',        'LB', 0, 'L', 0);
	        	$this->SetFont('Arial','I', 8);
				$this->Cell(25, 5, '(' . date('j M Y', strtotime($row['date'])) . ')',      	'LB', 0, 'L', 0);
				$this->SetFont('Arial', '', 8);
	        	$this->Cell(50, 5, '',        'LBR', 0, 'L', 0);
	        	$this->Ln();
	        }
        }
        else
        {
        	$this->SetFont('Arial', '', 10);
        	$this->Cell(190, 5, 'None found', 0, 0, 'L', 0);
        }
//        $this->SetFont('Arial', '', 10);
//
//        $this->Cell(38, 5, 'Company Name',          1, 0, 'C', 0);
//        $this->Cell(38, 5, 'Job Title',             1, 0, 'C', 0);
//        $this->Cell(38, 5, 'Meeting Set Date',      1, 0, 'C', 0);
//        $this->Cell(38, 5, 'Meeting Status',        1, 0, 'C', 0);
//        $this->Cell(38, 5, 'Client Feedback Score', 1, 0, 'C', 0);
    }

    public function cancellationClinicSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Cancellation Clinic', 0, 0, 'C', 0);
        $this->Ln(15);

        // Overall Campaign Statistics
        $data = app_domain_ReportReader::getReport7DatabaseAnalysisProspect($this->params['campaign_start'], $this->params['end'], $this->params['client_id']);
        $data = $this->preProcess_CancellationClinicPeriod($data);
//        echo $this->params['campaign_start'];
        $lead_times = app_domain_ReportReader::getReport7CampaignCancellationsMeetingLeadTimes($this->params['campaign_start'], $this->params['end'], $this->params['client_id']);

//       	$lead_times_campaign = app_domain_ReportReader::getReport7PeriodCancellationsMeetingLeadTimes($this->params['campaign_start'], $this->params['end'], $this->params['client_id']);


        $this->Cell(190, 5, 'Overall Campaign Statistics', 0, 0, 'L', 0);
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 5, 'Meetings set', 0, 0, 'L', 0);
        $this->Cell( 90, 5, $data[0]['meeting_set_count'], 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Meetings cancelled', 0, 0, 'L', 0);
        $this->Cell( 90, 5, $data[0]['meeting_category_cancelled_count'], 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Meetings TBR', 0, 0, 'L', 0);
        $this->Cell( 90, 5, $data[0]['meeting_category_tbr_count'], 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Cancellation Rate (Cancelled and TBR)', 0, 0, 'L', 0);
        $this->Cell( 90, 5, number_format($data[0]['meeting_category_cancelled_rate'],2) . '%', 0, 1, 'L', 0);
//        $this->Cell(100, 5, '% meetings cancelled by client', 0, 0, 'L', 0);
//        $this->Cell( 90, 5, '%', 0, 1, 'L', 0);
//        $this->Cell(100, 5, '% meetings cancelled by prospect', 0, 0, 'L', 0);
//        $this->Cell( 90, 5, '%', 0, 1, 'L', 0);
//        $this->Cell(100, 5, 'Number of meetings cancelled on requalification', 0, 0, 'L', 0);
//        $this->Cell( 90, 5, '%', 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Average meeting lead time', 0, 0, 'L', 0);
       if ($lead_times[0]['day_count'] == null)
        {
        	$lead_time = 0;
        }
        else
        {
        	$lead_time = number_format($lead_times[0]['day_count'],2);
        }

        $this->Cell( 90, 5, $lead_time . ' days (' . $lead_times[0]['meeting_count'] . ' meetings)', 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Average cancelled meeting lead time', 0, 0, 'L', 0);
        if ($lead_times[1]['day_count'] == null)
        {
        	$lead_time = 0;
        }
        else
        {
        	$lead_time = number_format($lead_times[1]['day_count'],2);
        }
        $this->Cell( 90, 5,  $lead_time . ' days (' . $lead_times[1]['meeting_count'] . ' meetings)', 0, 0, 'L', 0);
        $this->Ln(15);

        // Statistics for Period
        $data = app_domain_ReportReader::getReport7DatabaseAnalysisProspect($this->params['start'], $this->params['end'], $this->params['client_id']);
        $data = $this->preProcess_CancellationClinicPeriod($data);

        $lead_times = app_domain_ReportReader::getReport7PeriodCancellationsMeetingLeadTimes($this->params['start'], $this->params['end'], $this->params['client_id']);
//        echo $this->params['start'];
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Statistics for Period', 0, 0, 'L', 0);
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 5, 'Number of meetings set in period', 0, 0, 'L', 0);
        $this->Cell( 90, 5, $data[0]['meeting_set_count'], 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Number of meetings cancelled in period', 0, 0, 'L', 0);
        $this->Cell( 90, 5, $data[0]['meeting_category_cancelled_count'], 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Meetings TBR', 0, 0, 'L', 0);
        $this->Cell( 90, 5, $data[0]['meeting_category_tbr_count'], 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Cancellation Rate (Cancelled and TBR)', 0, 0, 'L', 0);
//        $this->Cell(100, 5, 'Cancellation Rate', 0, 0, 'L', 0);
        $this->Cell( 90, 5, number_format($data[0]['meeting_category_cancelled_rate'],2) . '%', 0, 1, 'L', 0);
//        $this->Cell(100, 5, '% meetings cancelled by client', 0, 0, 'L', 0);
//        $this->Cell( 90, 5, '%', 0, 1, 'L', 0);
//        $this->Cell(100, 5, '% meetings cancelled by prospect', 0, 0, 'L', 0);
//        $this->Cell( 90, 5, '%', 0, 1, 'L', 0);
//        $this->Cell(100, 5, 'Number of meetings cancelled on requalification', 0, 0, 'L', 0);
//        $this->Cell( 90, 5, '%', 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Average meeting lead time', 0, 0, 'L', 0);
    	if ($lead_times[0]['day_count'] == null)
        {
        	$lead_time = 0;
        }
        else
        {
        	$lead_time = number_format($lead_times[0]['day_count'],2);
        }

        $this->Cell( 90, 5, $lead_time . ' days (' . $lead_times[0]['meeting_count'] . ' meetings)', 0, 1, 'L', 0);
        $this->Cell(100, 5, 'Average cancelled meeting lead time', 0, 0, 'L', 0);
        if ($lead_times[1]['day_count'] == null)
        {
        	$lead_time = 0;
        }
        else
        {
        	$lead_time = number_format($lead_times[1]['day_count'],2);
        }
        $this->Cell( 90, 5,  $lead_time . ' days (' . $lead_times[1]['meeting_count'] . ' meetings)', 0, 0, 'L', 0);
        $this->Ln(15);


        $rows = app_domain_ReportReader::getReport7CancellationsMeetingLeadTimesByCompany($this->params['start'], $this->params['end'], $this->params['client_id']);

        // Meeting Lead Time for Cancelled Meetings For Period
//        $rows = array(array('company' => 'Piper Trading',      'days_lead_time' => 77),
//                      array('company' => 'Newman Enterprises', 'days_lead_time' => 56));
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Meeting Lead Time for Cancelled Meetings For Period', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 10);
        $this->Ln(10);

        if (count($rows) > 0)
        {
	        foreach ($rows as $row)
	        {
	               $this->Cell(100, 5, $row['company_name'], 0, 0, 'L', 0);
	               $this->Cell( 90, 5, $row['day_count'] . ' days (' . $row['meeting_status'] . ')', 0, 0, 'L', 0);
	               $this->Ln();
	        }
	        $this->Ln(10);
        }
        else
        {
        	$this->Cell(200, 5, 'N/A - no meetings cancelled in period', 0, 0, 'L', 0);
        }

//        // Meeting Set Scores of Cancelled Meets
//        $this->SetFont('Arial', 'B', 10);
//        $this->Cell(190, 5, 'Meeting Set Scores of Cancelled Meets', 0, 0, 'L', 0);
//        $this->SetFont('Arial', '', 10);
//        $this->Ln(15);
//
//
//        // Rearrangement Timescales
//        $this->SetFont('Arial', 'B', 10);
//        $this->Cell(190, 5, 'Rearrangement Timescales', 0, 0, 'L', 0);
//        $this->SetFont('Arial', '', 10);
//        $this->Ln(10);
    }

    public function opportunitiesAndWinsClinicSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Opportunities And Wins Clinic', 0, 0, 'C', 0);
        $this->Ln(15);

        // Meeting Outcomes
        $this->Cell(190, 5, 'Meeting Outcomes', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 10);
        $this->Ln(10);

        // TODO
        // Assume data is sorted by outcome
        $rows = array(array('company' => 'ACME PR 1', 'outcome' => 'Short Term'),
                      array('company' => 'ACME PR 2', 'outcome' => 'Short Term'),
                      array('company' => 'ACME PR 3', 'outcome' => 'Long Term'),
                      array('company' => 'ACME PR 4', 'outcome' => 'Long Term'),
                      array('company' => 'ACME PR 5', 'outcome' => 'Go Nowhere'));

        $current_outcome = null;
        foreach ($rows as $row)
        {
            if ($current_outcome != $row['outcome'])
            {
                if (!is_null($current_outcome))
                {
                    // Apply to all, but first item of array
                    $this->Ln(5);
                }
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(190, 5, $row['outcome'], 0, 0, 'L', 0);
                $this->SetFont('Arial', '', 10);
                $this->Ln();
            }
            $this->Cell(190, 5, $row['company'], 0, 0, 'L', 0);
            $this->Ln();

            $current_outcome = $row['outcome'];
        }
    }

    public function targetingClinicSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Targeting Clinic', 0, 0, 'C', 0);
        $this->Ln();
    }

    public function databaseAnalysisSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Database Analysis', 0, 0, 'C', 0);
        $this->Ln(15);

        //
        // Prospect Level
        //
        $this->Cell(190, 5, 'Prospect Level', 0, 0, 'L', 0);
        $this->Ln(10);

        // Header row
        $this->Cell(70, 5, 'Status',          0, 0, 'L', 0);
        $this->Cell(40, 5, 'Total',           0, 0, 'C', 0);
        $this->Cell(40, 5, 'Callback Past',   0, 0, 'C', 0);
        $this->Cell(40, 5, 'Callback Future', 0, 0, 'C', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport7DatabaseAnalysis($this->params['start'], $this->params['end'], $this->params['client_id']);

        $this->SetFont('Arial', '', 10);
        foreach ($rows as $row)
        {
            $this->Cell(70, 5, $row['status'],          0, 0, 'L', 0);
            $this->Cell(40, 5, $row['total'],           0, 0, 'C', 0);
            $this->Cell(40, 5, $row['callback_past'],   0, 0, 'C', 0);
            $this->Cell(40, 5, $row['callback_future'], 0, 0, 'C', 0);
            $this->Ln();
        }
        $this->Ln(10);

        //
        // Company Level
        //
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Company Level', 0, 0, 'L', 0);
        $this->Ln(10);

        // Header row
        $this->Cell(70, 5, 'Status', 0, 0, 'L', 0);
        $this->Cell(40, 5, 'Total',  0, 0, 'C', 0);
        $this->Ln();

        $rows = app_domain_ReportReader::getReport7DatabaseAnalysisByCompany($this->params['start'], $this->params['end'], $this->params['client_id']);

        $this->SetFont('Arial', '', 10);
        foreach ($rows as $row)
        {
            $this->Cell(70, 5, $row['status'], 0, 0, 'L', 0);
            $this->Cell(40, 5, $row['company_count'],  0, 0, 'C', 0);
            $this->Ln();
        }
        $this->Ln(10);

//        // TODO - Replace sample data
//        $prospects_not_yet_attempted = 486;
//        $companies_not_yet_attempted = 208;

        $prospects_not_yet_attempted = app_domain_ReportReader::getReport7DatabaseAnalysisProspectsNotYetAttempted($this->params['start'], $this->params['end'], $this->params['client_id']);
        $companies_not_yet_attempted = app_domain_ReportReader::getReport7DatabaseAnalysisCompaniesNotYetAttempted($this->params['start'], $this->params['end'], $this->params['client_id']);


        $this->Cell(70, 5, 'Prospects in target audience not yet attempted');
        $this->Cell(40, 5, $prospects_not_yet_attempted, 0, 0, 'C');
        $this->Ln();
        $this->Cell(70, 5, 'Companies in target audience not yet attempted');
        $this->Cell(40, 5, $companies_not_yet_attempted, 0, 0, 'C');
        $this->Ln();
    }

    public function effectivesAnalysisSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Effectives Analysis - based on all effectives made for this client during the campaign', 0, 0, 'C', 0);
        $this->Ln(15);

        $rows = app_domain_ReportReader::getReport7LeadNBMEffectiveAnalysis($this->params['start'], $this->params['end'], $this->params['client_id']);


        // Table header row
        $this->Cell(62, 5, '', 0, 0, 'L', 0);
        $this->Cell(32, 5, 'Total',      'TRL', 0, 'C', 0);
        $this->Cell(32, 5, 'Total', 'TRL', 0, 'C', 0);
        $this->Cell(32, 5, 'Total',  'TRL', 0, 'C', 0);
        $this->Cell(32, 5, 'Ave No Effectives',     'TRL', 0, 'C', 0);
        $this->Ln();
        $this->Cell(62, 5, '', 0, 0, 'L', 0);
        $this->Cell(32, 5, "Effectives",      'RBL', 0, 'C', 0);
        $this->Cell(32, 5, 'Prospects', 'RBL', 0, 'C', 0);
        $this->Cell(32, 5, 'Companies',  'RBL', 0, 'C', 0);
        $this->Cell(32, 5, 'Per Prospect',     'RBL', 0, 'C', 0);
        $this->Ln();

        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);
        $temp = 0;
        // Loop over data rows
        foreach ($rows as $row)
        {
            $this->Cell(62, 5, $row['status'],                          1, 0, 'L', 0);
            $this->Cell(32, 5, $row['total_effectives'],                1, 0, 'C', 0);
            $this->Cell(32, 5, $row['total_prospects'],                 1, 0, 'C', 0);
            $this->Cell(32, 5, $row['total_companies'],                 1, 0, 'C', 0);
            $this->Cell(32, 5, number_format($row['average_effectives_per_prospect'],2), 1, 0, 'C', 0);
            $temp += number_format($row['average_effectives_per_prospect'],2);
            $this->Ln();
        }

        // Totals row
        $this->Cell(62, 5, 'Total',                                             1, 0, 'L', 0);
        $this->Cell(32, 5, self::sum($rows, 'total_effectives'),                1, 0, 'C', 0);
        $this->Cell(32, 5, self::sum($rows, 'total_prospects'),                 1, 0, 'C', 0);
        $this->Cell(32, 5, self::sum($rows, 'total_companies'),                 1, 0, 'C', 0);
//        $this->Cell(32, 5, self::sum($rows, 'average_effectives_per_prospect'), 1, 0, 'C', 0);
        $this->Ln(10);

        // Average number
//        $effectives_per_meeting = 3.55;
//        $effectives_per_meeting = $temp/count($rows);
//        $this->Cell(190, 5, 'Average number of effectives per meeting set ' . number_format($effectives_per_meeting, 2));
        $this->Cell(190, 5, 'Average number of effectives per meeting set ' . $this->effectives_per_meeting_set);


    }

    public function nbmDisciplineEffectivenessSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Lead NBM Discipline Effectiveness - across all clients for this NBM', 0, 0, 'C', 0);
        $this->Ln(10);

        $rows = app_domain_ReportReader::getReport7LeadNBMDisciplineAnalysis($this->params['start'], $this->params['end'], $this->params['client_id']);

        // Table header row
        $this->Cell(80, 5, 'Discipline', 1, 0, 'L', 0);
        $this->Cell(22, 5, 'Calls',      1, 0, 'C', 0);
        $this->Cell(22, 5, 'Effectives', 1, 0, 'C', 0);
        $this->Cell(22, 5, 'Meets Set',  1, 0, 'C', 0);
        $this->Cell(22, 5, 'Access',     1, 0, 'C', 0);
        $this->Cell(22, 5, 'Conversion', 1, 0, 'C', 0);
        $this->Ln();

        // Change font to normal from bold
        $this->SetFont('Arial', '', 10);

        // Loop over data rows
        foreach ($rows as $row)
        {
            $this->Cell(80, 5, $row['discipline'],       1, 0, 'L', 0);
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
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Lead NBM Sector Effectiveness - across all clients for this NBM', 0, 0, 'C', 0);
        $this->Ln(10);

        $rows = app_domain_ReportReader::getReport7LeadNBMSectorAnalysis($this->params['start'], $this->params['end'], $this->params['client_id']);

        // Table header row
        $this->Cell(80, 5, 'Sector', 1, 0, 'L', 0);
        $this->Cell(22, 5, 'Calls',      1, 0, 'C', 0);
        $this->Cell(22, 5, 'Effectives', 1, 0, 'C', 0);
        $this->Cell(22, 5, 'Meets Set',  1, 0, 'C', 0);
        $this->Cell(22, 5, 'Access',     1, 0, 'C', 0);
        $this->Cell(22, 5, 'Conversion', 1, 0, 'C', 0);
        $this->Ln();

        // Change font to normal from bold
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

    public function pipelineReportSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Pipeline Report Section', 0, 0, 'C', 0);
        $this->SetFont('Arial', '', 10);
        $this->Ln(10);

        $rows = app_domain_ReportReader::getReport7Pipeline($this->params['start'], $this->params['end'], $this->params['client_id']);

        // Loop over data
        $current_year_month = null;
        foreach ($rows as $row)
        {
            if ($current_year_month != $row['year_month'])
            {
                if (!is_null($current_year_month))
                {
                    $this->Ln(5);
                }
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(40, 5, self::monthYearToMonthString($row['year_month']), 0, 1);
                $this->SetFont('Arial', '', 10);
            }
            $this->Cell(90, 5, $row['status'],           0, 0, 'L', 0);
            $this->Cell(30, 5, $row['total'],            0, 1, 'C', 0);
//            $this->Cell(30, 5, 'Cult 1',               0, 0, 'L', 0);
//            $this->Cell(30, 5, $row['cult_1'],         0, 1, 'C', 0);
//            $this->Cell(30, 5, 'Cult 2',               0, 0, 'L', 0);
//            $this->Cell(30, 5, $row['cult_2'],         0, 1, 'C', 0);
//            $this->Cell(30, 5, 'Cult 3',               0, 0, 'L', 0);
//            $this->Cell(30, 5, $row['cult_3'],         0, 1, 'C', 0);
//            $this->Cell(30, 5, 'TBR',                  0, 0, 'L', 0);
//            $this->Cell(30, 5, $row['tbr'],            0, 1, 'C', 0);
//            $this->Cell(30, 5, 'Meet follow up',       0, 0, 'L', 0);
//            $this->Cell(30, 5, $row['meet_follow_up'], 0, 1, 'C', 0);
            $current_year_month = $row['year_month'];
        }
    }

    public function effectiveNotesSection()
    {
        $this->AddPage();

        // Page Title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 5, 'Effective Notes', 0, 0, 'C', 0);
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
    protected function average($x, $y, $decimals = 0)
    {
        if (is_array($x))
        {
            $sum = 0.0;
            $count = 0;
            foreach ($x as $item)
            {
                $sum += $item[$y];
                $count++;
            }
            if ($count > 1)
            {
                $sum = $sum / $count;
            }
            return number_format($sum, $decimals);
        }
        else
        {
            if ($y > 0)
            {
                return number_format($x / $y, $decimals);
            }
            else
            {
                return 0;
            }
        }
    }

    /**
     * Returns the sum of items in a given array index
     * @param array $array
     * @param mixed $index
     * @return integer
     */
	protected function sum($array, $index)
    {
    	$sum = 0.0;
    	foreach ($array as $item)
    	{
    		$sum += $item[$index];
    	}
    	return $sum;
    }

    /**
     * Returns the sum of items for a given NBM in a given array index
     * @param array   $array
     * @param integer $index
     * @param mixed   $index
     * @return integer
     */
    protected function sumForNbm($array, $nbm_id, $index)
    {
		$sum = 0.0;
		foreach ($array as $item)
		{
			if ($item['nbm_id'] == $nbm_id)
			{
				$sum += $item[$index];
			}
		}
		return $sum;
	}

    /**
     * Returns the conversion rate a given NBM in a given array index
     * @param array   $array
     * @param integer $index
     * @param mixed   $index
     * @param integer $decimals
     * @return integer
     */
    protected function conversionRateForNbm($array, $nbm_id, $decimals = 2)
    {
        $meetings   	= 0.0;
        $effectives   	= 0.0;
        $sum = 0.0;
        $count = 0;
        foreach ($array as $item)
        {
            if ($item['nbm_id'] == $nbm_id)
            {
                $meetings += $item['meeting_set_count'];
            }
        }

     	foreach ($array as $item)
        {
            if ($item['nbm_id'] == $nbm_id)
            {
                $effectives += $item['call_effective_count'];
//                $count++;
            }
        }

    	if ($effectives >0 && $meetings > 0)
        {
            $sum = ($effectives / $meetings) * 100;
        }
        else
        {
        	$sum = 0;
        }


//        if ($count > 1)
//        {
//            $sum = ($meetings / $effectives) * 100;
//        }
        return number_format($sum, $decimals);
    }



//    /**
//     * Returns the sum of items for a given NBM in a given array index
//     * @param array   $array
//     * @param integer $index
//     * @param mixed   $index
//     * @param integer $decimals
//     * @return integer
//     */
//    protected function averageForNbm($array, $nbm_id, $index, $decimals = 1)
//    {
//        $sum   = 0.0;
//        $count = 0;
//        foreach ($array as $item)
//        {
//            if ($item['nbm_id'] == $nbm_id)
//            {
//                $sum += $item[$index];
//                $count++;
//            }
//        }
//        if ($count > 1)
//        {
//            $sum = $sum / $count;
//        }
//        return number_format($sum, $decimals);
//    }

    /**
     * Returns the conversion rate for all NBMs in a given array index
     * @param array   $array
     * @param integer $index
     * @param mixed   $index
     * @param integer $decimals
     * @return integer
     */
    protected function conversionRateTotal($array, $decimals = 2)
    {
        $meetings   	= 0.0;
        $effectives   	= 0.0;
        $sum = 0.0;
        $count = 0;
        foreach ($array as $item)
        {
         	$meetings += $item['meeting_set_count'];
        }

     	foreach ($array as $item)
        {

			$effectives += $item['call_effective_count'];
        }


        if ($effectives >0 && $meetings > 0)
        {
            $sum = ($effectives / $meetings) * 100;
        }
        else
        {
        	$sum = 0;
        }

//        if ($count > 1)
//        {
//            $sum = ($meetings / $effectives) * 100;
//        }
        return number_format($sum, $decimals);
    }


    /**
     * Returns the sum of items for a given NBM in a given array index
     * @param array   $array
     * @param integer $nbm
     * @param integer $decimals
     * @return integer
     */
    protected function accessRateForNbm($array, $nbm_id, $decimals = 2)
    {

        $effectives   	= 0.0;
        $calls   		= 0.0;
        $sum = 0.0;
        $count = 0;
        foreach ($array as $item)
        {
            if ($item['nbm_id'] == $nbm_id)
            {
                $effectives += $item['call_effective_count'];
            }
        }

     	foreach ($array as $item)
        {
            if ($item['nbm_id'] == $nbm_id)
            {
                $calls += $item['call_count'];
            }
        }


        if ($effectives >0 && $calls > 0)
        {
            $sum = ($effectives / $calls) * 100;
        }
        else
        {
        	$sum = 0;
        }
        return number_format($sum, $decimals);
    }

	/**
     * Returns the access rate for all NBMs in a given array index
     * @param array   $array
     * @param integer $nbm
     * @param integer $decimals
     * @return integer
     */
    protected function accessRateTotal($array, $decimals = 2)
    {

        $effectives   	= 0.0;
        $calls   		= 0.0;
        $sum = 0.0;
        $count = 0;
        foreach ($array as $item)
        {
                $effectives += $item['call_effective_count'];
        }

     	foreach ($array as $item)
        {
                $calls += $item['call_count'];
        }



        if ($effectives >0 && $calls > 0)
        {
            $sum = ($effectives / $calls) * 100;
        }
        else
        {
        	$sum = 0;
        }

//        if ($count > 1)
//        {
//            $sum = ($effectives / $calls) * 100;
//        }
        return number_format($sum, $decimals);
    }

    /**
     * Convert a string of the format 'YYYYMM' to human readable form.
     * E.g. '200810' becomes October '08
     * @param unknown_type $month_year
     * @return unknown
     */
    protected function monthYearToMonthString($month_year)
    {
        $year =  substr($month_year, 0, 4);
        $month = substr($month_year, 4, 2);
        return date("F 'y", mktime(0, 0, 0, $month, 1, $year));
    }

	/**
	 * Add additional information used in report to the data array and return it.
	 * @param array $data
	 * @return array
	 */
	protected static function preProcess_CallStatistics($data, $targets, $start, $end)
	{
//		print_r($targets);
		$return = $data;
		$numberOfMonths = self::numberOfMonths($start, $end);

		if ($return[0]['call_count'] != null)
		{
			$return[0]['average_calls_per_month'] = $return[0]['call_count']/$numberOfMonths;
		}
		else
		{
			$return[0]['average_calls_per_month'] = 0;
		}

		if ($return[0]['call_effective_count'] != null)
		{
			$return[0]['average_effectives_per_month'] = $return[0]['call_effective_count']/$numberOfMonths;
		}
		else
		{
			$return[0]['average_effectives_per_month'] = 0;
		}


		if ($return[0]['call_count'] != null)
		{
			$return[0]['average_days_month_spent'] = $return[0]['call_count']/70/$numberOfMonths;
		}
		else
		{
			$return[0]['average_days_month_spent'] = 0;
		}

		if ($return[0]['call_effective_count'] == null || $return[0]['call_effective_count'] == 0 || $return[0]['meeting_set_count'] == null || $return[0]['meeting_set_count'] == 0)
		{
			$return[0]['effectives_per_meeting_set'] = 0;
		}
		else
		{
			$return[0]['effectives_per_meeting_set'] = $return[0]['call_effective_count']/$return[0]['meeting_set_count'];
		}


//		echo "$targets[0][ave_meetings_set_target] = " . $targets[0]['ave_meetings_set_target'];
		// Effectives required to deliver monthly meeting set target
		if ($return[0]['effectives_per_meeting_set'] == null || $return[0]['effectives_per_meeting_set'] == 0 ||
			$targets[0]['ave_meetings_set_target'] == null || $targets[0]['ave_meetings_set_target'] == 0)
		{
			$return[0]['effs_reqd_to_deliver_ave_monthly_target'] = 0;
		}
		else
		{
			$return[0]['effs_reqd_to_deliver_ave_monthly_target'] = $targets[0]['ave_meetings_set_target']*$return[0]['effectives_per_meeting_set'];
		}
		///




		if ($return[0]['call_effective_count'] == null || $return[0]['call_count'] == null)
		{
			$return[0]['calls_per_effective'] = 0;
		}
		else
		{
			$return[0]['calls_per_effective'] = $return[0]['call_count']/$return[0]['call_effective_count'];
		}


		// Calls required to deliver monthly meetings set target
		if ($return[0]['effs_reqd_to_deliver_ave_monthly_target'] == null || $return[0]['effs_reqd_to_deliver_ave_monthly_target'] == 0 ||
			$return[0]['calls_per_effective'] == null || $return[0]['calls_per_effective'] == 0)
		{
			$return[0]['calls_reqd_to_deliver_ave_monthly_target'] = 0;
		}
		else
		{
			$return[0]['calls_reqd_to_deliver_ave_monthly_target'] = $return[0]['effs_reqd_to_deliver_ave_monthly_target'] * $return[0]['calls_per_effective'] * $numberOfMonths;
		}

		if ($return[0]['call_effective_count'] == null || $return[0]['call_ote_count'] == null)
		{
			$return[0]['call_ote_rate'] = 0;
		}
		else
		{
			$return[0]['call_ote_rate'] = $return[0]['call_ote_count']/$return[0]['call_effective_count']*100;
		}

		if ($return[0]['calls_reqd_to_deliver_ave_monthly_target'] == null)
		{
			$return[0]['days_required_to_deliver'] = 0;
		}
		else
		{
			$return[0]['days_required_to_deliver'] = $return[0]['calls_reqd_to_deliver_ave_monthly_target']/70/$numberOfMonths;
		}

		if ($return[0]['meeting_category_cancelled_count'] == null || $return[0]['meeting_category_cancelled_count'] == 0 || $return[0]['meeting_set_count'] == null || $return[0]['meeting_set_count'] == 0)
		{
			$return[0]['meeting_category_cancelled_rate'] = 0;
		}
		else
		{
			$return[0]['meeting_category_cancelled_rate'] = $return[0]['meeting_category_cancelled_count']/$return[0]['meeting_set_count']*100;
		}

		return $return;
	}


/**
	 * Add additional information used in report to the data array and return it.
	 * @param array $data
	 * @return array
	 */
	protected static function preProcess_NbmCallStatistics($data, $targets, $start, $end, $client_id)
	{

		$return = $data;
		$numberOfMonths = self::numberOfMonths($start, $end);

		foreach($return as &$item)
		{

		$data_targets = app_domain_ReportReader::getReport7MeetingsTargetsByNbm($start, $end, $client_id, $item['user_id']);
//			print_r($data_targets);

			if ($data_targets[0]['effectives_target'] != null)
			{
				$item['effectives_target'] = $data_targets[0]['effectives_target'];
			}
			else
			{
				$item['effectives_target'] = 0;
			}


			if ($item['call_count'] != null)
			{
				$item['average_calls_per_month'] = $item['call_count']/$numberOfMonths;
			}
			else
			{
				$item['average_calls_per_month'] = 0;
			}

			if ($item['call_effective_count'] != null)
			{
				$item['average_effectives_per_month'] = $item['call_effective_count']/$numberOfMonths;
			}
			else
			{
				$item['average_effectives_per_month'] = 0;
			}


			if ($item['call_count'] != null)
			{
				$item['average_days_month_spent'] = $item['call_count']/70/$numberOfMonths;
			}
			else
			{
				$item['average_days_month_spent'] = 0;
			}

			if ($item['call_effective_count'] == null || $item['call_effective_count'] == 0 || $item['meeting_set_count'] == null || $item['meeting_set_count'] == 0)
			{
				$item['effectives_per_meeting_set'] = 0;
			}
			else
			{
				$item['effectives_per_meeting_set'] = $item['call_effective_count']/$item['meeting_set_count'];
			}

			// Effectives required to deliver monthly meeting set target
			if ($item['effectives_per_meeting_set'] == null || $item['effectives_per_meeting_set'] == 0 ||
				$targets[0]['ave_meetings_set_target'] == null || $targets[0]['ave_meetings_set_target'] == 0)
			{
				$item['effs_reqd_to_deliver_ave_monthly_target'] = 0;
			}
			else
			{
				$item['effs_reqd_to_deliver_ave_monthly_target'] = $targets[0]['ave_meetings_set_target']*$item['effectives_per_meeting_set'];
			}

			if ($item['call_effective_count'] == null || $item['call_effective_count'] == 0 || $item['call_count'] == null || $item['call_count'] == 0)
			{
				$item['calls_per_effective'] = 0;
			}
			else
			{
				$item['calls_per_effective'] = $item['call_count']/$item['call_effective_count'];
			}

			if ($item['call_effective_count'] == null || $item['call_effective_count'] == 0 || $item['call_ote_count'] == null || $item['call_ote_count'] == 0)
			{
				$item['call_ote_rate'] = 0;
			}
			else
			{
				$item['call_ote_rate'] = $item['call_ote_count']/$item['call_effective_count']*100;
			}

			// Calls required to deliver monthly meetings set target
			if ($item['effs_reqd_to_deliver_ave_monthly_target'] == null || $item['effs_reqd_to_deliver_ave_monthly_target'] == 0 ||
				$item['calls_per_effective'] == null || $item['calls_per_effective'] == 0)
			{
				$item['calls_reqd_to_deliver_ave_monthly_target'] = 0;
			}
			else
			{
				$item['calls_reqd_to_deliver_ave_monthly_target'] = $item['effs_reqd_to_deliver_ave_monthly_target'] * $item['calls_per_effective'] * $numberOfMonths;
			}

			if ($item['calls_reqd_to_deliver_ave_monthly_target'] == null)
			{
				$item['days_required_to_deliver'] = 0;
			}
			else
			{
				$item['days_required_to_deliver'] = $item['calls_reqd_to_deliver_ave_monthly_target']/70/$numberOfMonths;
			}

			if ($item['meeting_category_cancelled_count'] == null || $item['meeting_category_cancelled_count'] == 0 || $item['meeting_set_count'] == null || $item['meeting_set_count'] == 0)
			{
				$item['meeting_category_cancelled_rate'] = 0;
			}
			else
			{
				$item['meeting_category_cancelled_rate'] = $item['meeting_category_cancelled_count']/$item['meeting_set_count']*100;
			}

		}

//		echo '-------------\n';
//		echo '<pre>';
//		print_r($item);
//		echo '</pre>';
//		echo '-------------\n';
		return $return;
	}


	/**
	 * Add additional information used in report to the data array and return it.
	 * @param array $data
	 * @return array
	 */
	protected static function preProcess_CancellationClinicPeriod($data)
	{

		$return = $data;
		if ($return[0]['meeting_category_cancelled_count'] == null)
		{
			$return[0]['meeting_category_cancelled_rate'] = 0;
		}
		else
		{
			$return[0]['meeting_category_cancelled_rate'] = $return[0]['meeting_category_cancelled_count'];
		}


		if ($return[0]['meeting_category_tbr_count'] != null)
		{
			$return[0]['meeting_category_cancelled_rate'] += $return[0]['meeting_category_tbr_count'];
		}

		if ($return[0]['meeting_category_cancelled_rate'] == 0 || $return[0]['meeting_set_count'] == null || $return[0]['meeting_set_count'] == 0)
		{
			$return[0]['meeting_category_cancelled_rate'] = 0;
		}
		else
		{
			$return[0]['meeting_category_cancelled_rate'] = $return[0]['meeting_category_cancelled_rate']/$return[0]['meeting_set_count']*100;
		}
		return $return;
	}

 	/**
 	 *  Calculate the number of months that occur in the date range (eg if 31/07/2008 - 01/10/2008 then number of months = 4)
	 * @param string $start in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end in the format 'YYYY-MM-DD HH:MM:SS'
	 * @return integer
	 */
	protected static function numberOfMonths($start, $end)
	{
		$start_year_month = substr($start, 0, 7);
		$end_year_month = substr($end, 0, 7);
		$x = 1;

//		echo '$start_year_month = ' . $start_year_month;
//		echo '$end_year_month = ' . $end_year_month;
//		exit();
		while($start_year_month != $end_year_month)
		{
			$x++;
			$year = substr($start_year_month, 0, 4);
			$month = substr($start_year_month, 5, 2);

//			echo $month . ' : ' . $year;

			if ($month >= 12)
			{
				$month = 1;
				$year++;
			}
			else
			{
				$month++;
			}

//			if ($month <10)
//			{
				$month = str_pad($month,2,'0', STR_PAD_LEFT);
//			}

			$start_year_month = $year . '-'. $month;
		}
		return $x;
	}

	private function get_timestamp($date)
	{
    	list($y, $m, $d) = explode('-', $date);
		return mktime(0, 0, 0, $m, $d, $y);
	}


}

?>