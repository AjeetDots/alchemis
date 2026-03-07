<?php

/**
 * Defines the app_report_Report7 class.
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
class app_report_Report8 extends FPDF_TABLE
{

	/**
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 */
	public function __construct($start, $end, $client_id, $filter_id, $display_front_page_statuses, $display_front_page_figures, $summary_figures)
	{

		// variables to hold values between function calls
		$this->effectives_per_meeting_set = 0;
		$this->report_parameter_description = '';
		// variable to hold various x/y coords
		$this->xy_coords[] = null;


		is_null($filter_id) || $filter_id = '' ? $filter_id = 0 : $filter_id;

		// if we have specified a filter then need to regenerate the results so that tbl_filter_results is updated.
		// This means we can then join to tbl_filter_results in the stored procedure we call later
		if ($filter_id > 0) {
			$filter_builder = new app_domain_FilterBuilder();
			$filter = app_domain_Filter::find($filter_id);

			$this->report_parameter_description = $filter->getReportParameterDescription();

			// regenerate filter results and insert into tbl_filter_results
			$filter_lines_include = app_domain_Filter::findFilterLinesByFilterIdAndDirection($filter_id, 'include');
			$filter_lines_exclude = app_domain_Filter::findFilterLinesByFilterIdAndDirection($filter_id, 'exclude');
			$filter_builder->makeSQLData($filter_id, $filter_lines_include, 'include');
			$filter_builder->makeSQLData($filter_id, $filter_lines_exclude, 'exclude');
			$t = null;
			$t = $filter_builder->makeMainSQL($filter_id, false);

			// To debug (print query results to screen), set debug = true AND set debug = true on first line in app_domain_FilterBuilder:makeMainSQL
			$debug = false;
			if ($debug) {
				echo ($t['query']);
				exit();
			}
		}

		$this->params['start']                          = $start . ' 00:00:00';
		$this->params['end']                            = $end . ' 23:59:59';
		$this->params['client_id']                      = $client_id;
		$this->params['filter_id']                      = $filter_id;
		$this->params['display_front_page_statuses']    = $display_front_page_statuses;
		$this->params['display_front_page_figures']     = $display_front_page_figures;
		$this->params['summary_figures']                = $summary_figures;

		//        print_r($this->params);
		//
		//        exit();


		parent::__construct('L');
		$this->AliasNbPages();

		$this->coverPage();

		$this->keyToTerms();
		if ($summary_figures) {
			$this->topLineSummarySection();
			$this->sectorPenetrationSummary();
		}

		$this->sectorPeriodResults();
	}

	public function Header()
	{
		//		$this->SetFont('Arial', '', 8);
		//		$this->Cell(50, 5, 'Report ID 8', 0, 0, 'L', 0);
		//		$this->SetFont('Arial', 'B', 10);
		//		$this->Cell(180, 5, 'Line Listing Report', 0, 0, 'C', 0);
		//		$this->SetFont('Arial', '', 8);
		//		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'R', 0);
		//		$this->Ln();
		//		$this->SetFont('Arial', '', 8);
		//		$this->Cell(50, 5, 'Page ' . $this->PageNo().' of {nb}', 0, 0, 'L', 0);
		//		$this->Cell(180, 5, 'For period ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end'])), 0, 0, 'C', 0);
		//		$this->Cell(50, 5, '', 0, 0, 'R', 0);
		//		$this->Ln(10);

	}

	public function Footer()
	{
		//Go to 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, date('d/m/Y'), 0, 0, 'L', 0);
		//		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$this->Cell(0, 5, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'R', 0);
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
		$this->SetFont('Arial', '', 8);

		// Layout
		$this->SetFont('Arial', '', 8);
	}



	public function coverPage()
	{
		$this->AddPage();

		// Page Title

		$this->Ln(70);

		$this->SetFont('Arial', 'B', 10);
		$this->Cell(0, 5, 'Alchemis Activity Report Summary', 0, 0, 'C', 0);
		$this->Ln(10);

		$client = app_domain_Client::find($this->params['client_id']);

		$this->Cell(0, 5, 'Prepared for ' . $client->getName(), 0, 0, 'C', 0);
		$this->Ln(10);

		$this->SetFont('Arial', '', 10);
		$this->Cell(0, 5, 'for the period', 0, 0, 'C', 0);
		$this->Ln(10);

		$this->SetFont('Arial', 'B', 10);
		$this->Cell(0, 5, date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end'])), 0, 0, 'C', 0);
		$this->Ln(10);

		if ($this->report_parameter_description != '') {
			$this->SetFont('Arial', '', 10);
			//        	$this->MultiCell(0, 5, 'This report uses has had the following criteria applied:', 0, 'C', 0);
			$this->Cell(0, 5, 'This report has been built using the following criteria:', 0, 1, 'C', 0);
			$this->Ln(5);
			$this->MultiCell(0, 5, $this->report_parameter_description, 0, 'C', 0);
			//        	$this->Cell(0, 5, $this->report_parameter_description, 0, 0, 'C', 0);
		}
		$this->Ln();
	}

	public function keyToTerms()
	{
		$this->AddPage();

		// Page Title
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(190, 5, 'Key to terms within this report', 0, 0, 'L', 0);
		$this->Ln(5);

		if ($this->params['display_front_page_figures'] == 1) {
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(190, 5, 'Call Statuses', 0, 0, 'L', 0);
			$this->Ln();


			$this->SetFont('Arial', '', 8);

			$this->Cell(190, 5, 'Effective = An effective conversation with a key decision maker for a marketing service and/or discipline', 0, 0, 'L', 0);
			$this->Ln();

			$this->Cell(190, 5, 'Non Effective (N/E) = An attempt to reach a key decision maker or key influencer', 0, 0, 'L', 0);
		}

		$this->Ln(10);

		$height = 3.8;

		$this->SetFont('Arial', 'B', 8);

		$this->Cell(75, $height, 'Status', 0, 0, 'L', 0);

		if ($this->params['display_front_page_figures'] == 1) {
			$this->Cell(15, $height, 'Effective', 0, 0, 'C', 0);
			$this->Cell(15, $height, 'N/E', 0, 0, 'C', 0);
		}

		$this->Cell(0, $height, 'Description', 0, 0, 'L', 0);
		$this->Ln();

		$this->SetFont('Arial', '', 8);


		switch ($this->params['display_front_page_statuses']) {
			case '1': //Display All
				$data = app_domain_ReportReader::getReport8KeyToTermsAll($this->params['start'], $this->params['end'], $this->params['client_id'], $this->params['filter_id']);
				break;
			case '2': //Display Only Those With a Communication
				$data = app_domain_ReportReader::getReport8KeyToTermsOnlyCommunications($this->params['start'], $this->params['end'], $this->params['client_id'], $this->params['filter_id']);
				break;
		}

		if ($this->params['display_front_page_figures'] == 1) {
			foreach ($data as $d) {
				$this->Cell(75, $height, $d['description'], 0, 0, 'L', 0);
				$this->Cell(15, $height, $d['effective_count'], 0, 0, 'C', 0);
				$this->Cell(15, $height, $d['non_effective_count'], 0, 0, 'C', 0);
				$this->Cell(0, $height, $d['report_description'], 0, 0, 'L', 0);
				$this->Ln();
				if ($d['report_break_after_line'] == 1) {
					$this->Ln();
				}
			}
		} else {
			foreach ($data as $d) {
				$this->Cell(75, $height, $d['description'], 0, 0, 'L', 0);
				$this->Cell(0, $height, $d['report_description'], 0, 0, 'L', 0);
				$this->Ln();
				if ($d['report_break_after_line'] == 1) {
					$this->Ln();
				}
			}
		}

		$this->Ln();
	}

	public function topLineSummarySection()
	{
		$this->AddPage();

		// Page Title
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(190, 5, 'Top Line Summary', 0, 0, 'L', 0);
		$this->Ln(8);

		// Reset font to normal rather than bold
		$this->SetFont('Arial', '', 8);

		// Get the campaig start year month
		$campaign = app_domain_ReportReader::getReport7ClientCampaignSummary($this->params['start'], $this->params['end'], $this->params['client_id']);

		$this->params['campaign_start_year_month'] = $campaign[0]['start_year_month'];
		$this->params['campaign_start'] = $campaign[0]['campaign_start_date'];

		// Grab x/y location of 'Summary of Campaign' line so we can line up the top of the graph title
		$this->xy_coords['summary_of_campaign_x'] = $this->GetX();
		$this->xy_coords['summary_of_campaign_y'] = $this->GetY();

		// Results vs Objectives
		$this->Cell(190, 6, 'Summary of Campaign: ' . $this->params['campaign_start_year_month'] . ' to ' . date('F Y'), 0, 0, 'L', 0);
		$this->Ln(5);

		$table_default_header_type = array(
			'WIDTH' => 6,
			'T_COLOR' => array(0, 0, 0),
			'T_SIZE' => 9,
			'T_FONT' => 'Arial',
			'T_ALIGN' => 'L',
			'V_ALIGN' => 'T',
			'T_TYPE' => 'B',
			'LN_SIZE' => 4,
			'BG_COLOR' => array(255, 255, 255),
			'BRD_COLOR' => array(0, 0, 0),
			'BRD_SIZE' => '0.1',
			'BRD_TYPE' => '0',
			'BRD_TYPE_NEW_PAGE' => '',
			'TEXT' => '',
		);
		$table_default_data_type = array(
			'T_COLOR' => array(0, 0, 0),
			'T_SIZE' => 8,
			'T_FONT' => 'Arial',
			'T_ALIGN' => 'N',
			'V_ALIGN' => 'T',
			'T_TYPE' => '',
			'LN_SIZE' => 4,
			'BG_COLOR' => array(255, 255, 255),
			'BRD_COLOR' => array(255, 255, 255),
			'BRD_SIZE' => '0',
			'BRD_TYPE' => '0',
			'BRD_TYPE_NEW_PAGE' => '',
		);
		$table_default_table_type = array(
			'TB_ALIGN' => 'L',
			'L_MARGIN' => 1,
			'T_MARGIN' => 1,
			'R_MARGIN' => 1,
			'B_MARGIN' => 1,
			'BRD_COLOR' => array(0, 0, 0),
			'BRD_SIZE' => '0.1',
		);

		// set number of columns in table
		$columns = 3; //five columns

		//we initialize the table class
		$this->Table_Init($columns, true, true);

		$table_subtype = $table_default_table_type;
		$this->Set_Table_Type($table_subtype);

		$this->Set_Table_SplitMode(false);

		//TABLE HEADER SETTINGS
		$header_subtype = $table_default_header_type;
		for ($i = 0; $i < $columns; $i++) $header_type[$i] = $table_default_header_type;


		$header_type[0]['WIDTH'] = 40;
		$header_type[1]['WIDTH'] = 20;
		$header_type[2]['WIDTH'] = 20;

		$header_type[0]['TEXT'] = "";
		$header_type[1]['TEXT'] = "Target";
		$header_type[2]['TEXT'] = "Actual";

		//set the header type
		$this->Set_Header_Type($header_type);

		$this->Draw_Manual_Header();

		//TABLE DATA SETTINGS
		$data_subtype = $table_default_data_type;

		$data_type = array(); //reset the array
		for ($i = 0; $i < $columns; $i++) $data_type[$i] = $data_subtype;

		$this->Set_Data_Type($data_type);

		$data = app_domain_ReportReader::getReport8CampaignSummary($this->params['campaign_start'], $this->params['end'], $this->params['client_id']);
		//        print_r($data);

		$output = array();
		$output[0]['TEXT'] = 'Meetings set';
		$output[1]['TEXT'] = $data[0]['meets_set_target_to_date'];
		$output[2]['TEXT'] = $data[0]['meets_set_to_date'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings attended';
		$output[1]['TEXT'] = $data[0]['meets_attended_target_to_date'];
		$output[2]['TEXT'] = $data[0]['meets_attended_to_date'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings awaiting feedback';
		$output[1]['TEXT'] = '';
		$output[2]['TEXT'] = $data[0]['meets_potential_attended'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings in diary';
		$output[1]['TEXT'] = '';
		$output[2]['TEXT'] = $data[0]['meets_in_diary'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings to be rearranged';
		$output[1]['TEXT'] = '';
		$output[2]['TEXT'] = $data[0]['meets_lapsed_tbr'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings cancelled';
		$output[1]['TEXT'] = '';
		$output[2]['TEXT'] = $data[0]['meets_lapsed_cancelled'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Strong call backs: pipeline';
		$output[1]['TEXT'] = '';
		$output[2]['TEXT'] = $data[0]['strong_callbacks_pipeline'];
		$this->Draw_Data($output);

		$this->Draw_Table_Border();

		// ------End of campaign summary
		$this->Ln(5);


		// Results vs Objectives
		$this->Cell(190, 6, 'Summary Figures For Reporting Period: ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end'])), 0, 0, 'L', 0);
		$this->Ln(5);


		$this->Table_Init($columns, true, true);

		$table_subtype = $table_default_table_type;
		$this->Set_Table_Type($table_subtype);

		$this->Set_Table_SplitMode(false);

		//TABLE HEADER SETTINGS
		$header_subtype = $table_default_header_type;
		for ($i = 0; $i < $columns; $i++) $header_type[$i] = $table_default_header_type;


		$header_type[0]['WIDTH'] = 40;
		$header_type[1]['WIDTH'] = 20;

		$header_type[0]['TEXT'] = "";
		$header_type[1]['TEXT'] = "Actual";

		//set the header type
		$this->Set_Header_Type($header_type);

		$this->Draw_Manual_Header();

		//TABLE DATA SETTINGS
		$data_subtype = $table_default_data_type;

		$data_type = array(); //reset the array
		for ($i = 0; $i < $columns; $i++) $data_type[$i] = $data_subtype;

		$this->Set_Data_Type($data_type);

		$data = app_domain_ReportReader::getReport8PeriodSummary($this->params['start'], $this->params['end'], $this->params['client_id']);
		//		print_r($data);

		$output = array();
		$output[0]['TEXT'] = 'Meetings set';
		$output[1]['TEXT'] = $data[0]['meets_set_to_date'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings attended';
		$output[1]['TEXT'] = $data[0]['meets_attended_to_date'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings awaiting feedback';
		$output[1]['TEXT'] = $data[0]['meets_potential_attended'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings in diary';
		$output[1]['TEXT'] = $data[0]['meets_in_diary'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings to be rearranged';
		$output[1]['TEXT'] = $data[0]['meets_lapsed_tbr'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Meetings cancelled';
		$output[1]['TEXT'] = $data[0]['meets_lapsed_cancelled'];
		$this->Draw_Data($output);

		$output[0]['TEXT'] = 'Strong call backs: generated';
		$output[1]['TEXT'] = $data[0]['strong_callbacks_generated'];
		$this->Draw_Data($output);

		$this->Draw_Table_Border();

		$this->Ln(5);
	}

	public function sectorPenetrationSummary()
	{
		// Page Title
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(190, 5, 'Sector Penetration', 0, 0, 'L', 0);
		$this->Ln(5);

		//grab x/y coo-ords so we can position the second table next to this one
		$curr_x = $this->GetX();
		$curr_y = $this->GetY();

		// Reset font to normal rather than bold
		$this->SetFont('Arial', '', 8);

		$this->Cell(190, 5, 'For Reporting Period: ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end'])), 0, 0, 'L', 0);
		$this->Ln(5);

		$table_default_header_type = array(
			'WIDTH' => 6,
			'T_COLOR' => array(0, 0, 0),
			'T_SIZE' => 8,
			'T_FONT' => 'Arial',
			'T_ALIGN' => 'C',
			'V_ALIGN' => 'T',
			'T_TYPE' => 'B',
			'LN_SIZE' => 4,
			'BG_COLOR' => array(255, 255, 255),
			'BRD_COLOR' => array(0, 0, 0),
			'BRD_SIZE' => '0.1',
			'BRD_TYPE' => '1',
			'BRD_TYPE_NEW_PAGE' => '',
			'TEXT' => '',
		);
		$table_default_data_type = array(
			'T_COLOR' => array(0, 0, 0),
			'T_SIZE' => 8,
			'T_FONT' => 'Arial',
			'T_ALIGN' => 'C',
			'V_ALIGN' => 'T',
			'T_TYPE' => '',
			'LN_SIZE' => 4,
			'BG_COLOR' => array(255, 255, 255),
			'BRD_COLOR' => array(0, 0, 0),
			'BRD_SIZE' => '0.1',
			'BRD_TYPE' => 'R',
			'BRD_TYPE_NEW_PAGE' => '',
		);
		$table_default_table_type = array(
			'TB_ALIGN' => 'XY',
			'L_MARGIN' => 1,
			'T_MARGIN' => 1,
			'R_MARGIN' => 1,
			'B_MARGIN' => 1,
			'BRD_COLOR' => array(0, 0, 0),
			'BRD_SIZE' => '0.1',
		);

		// set number of columns in table
		$columns = 5; //five columns

		//we initialize the table class
		$this->Table_Init($columns, true, true);

		$table_subtype = $table_default_table_type;
		$this->Set_Table_Type($table_subtype);

		$this->Set_Table_SplitMode(false);

		//TABLE HEADER SETTINGS
		$header_subtype = $table_default_header_type;
		for ($i = 0; $i < $columns; $i++) $header_type[$i] = $table_default_header_type;

		$header_type[0]['WIDTH'] = 35;
		$header_type[1]['WIDTH'] = 20;
		$header_type[2]['WIDTH'] = 25;
		$header_type[3]['WIDTH'] = 20;
		$header_type[4]['WIDTH'] = 20;

		$header_type[0]['TEXT'] = "Sector";
		$header_type[1]['TEXT'] = "All Calls (%)";
		$header_type[2]['TEXT'] = "Access to Decision Maker (%)";
		$header_type[3]['TEXT'] = "Meetings Set";
		$header_type[4]['TEXT'] = "Conversion (%)";

		$header_type[0]['T_ALIGN'] = "L";

		//set the header type
		$this->Set_Header_Type($header_type);

		$this->Draw_Manual_Header();

		//TABLE DATA SETTINGS
		$data_subtype = $table_default_data_type;

		$data_type = array(); //reset the array
		for ($i = 0; $i < $columns; $i++) $data_type[$i] = $data_subtype;

		$this->Set_Data_Type($data_type);

		$this->Draw_Table_Border();

		$data = app_domain_ReportReader::getReport8SectorPenetrationSummary($this->params['start'], $this->params['end'], $this->params['client_id']);



		$output = array();
		foreach ($data as $row) {
			if (is_null($row['sector'])) {
				$sector = 'Other';
			} else {
				$sector = $row['sector'];
			}

			$output[0]['TEXT'] = $sector;
			$output[0]['T_ALIGN'] = 'L';

			$output[1]['TEXT'] = ($row['call_percentage'] != '') ? $row['call_percentage'] . ' %' : '';
			$output[2]['TEXT'] =  ($row['access'] != '') ? $row['access'] . ' %' : '';
			$output[3]['TEXT'] = $row['meetings_set'];
			$output[4]['TEXT'] = ($row['conversion'] != '') ? $row['conversion'] . ' %' : '';

			$this->Draw_Data($output);
		}

		$this->Draw_Table_Border();

		// -------End of first sector penetration


		$this->SetXY($curr_x + 150, $curr_y);

		//        $this->SetFont('Arial', 'B', 10);
		$this->Cell(190, 5, 'For Campaign: ' . $this->params['campaign_start_year_month'] . ' to ' . date('F Y'), 0, 0, 'L', 0);
		$this->Ln(5);

		$this->SetXY($curr_x + 150, $curr_y + 5);

		//we initialize the table class
		$this->Table_Init($columns, true, true);

		$table_subtype = $table_default_table_type;
		$this->Set_Table_Type($table_subtype);

		$this->Set_Table_SplitMode(false);

		//TABLE HEADER SETTINGS
		$header_subtype = $table_default_header_type;
		for ($i = 0; $i < $columns; $i++) $header_type[$i] = $table_default_header_type;

		$header_type[0]['WIDTH'] = 35;
		$header_type[1]['WIDTH'] = 20;
		$header_type[2]['WIDTH'] = 25;
		$header_type[3]['WIDTH'] = 20;
		$header_type[4]['WIDTH'] = 20;

		$header_type[0]['TEXT'] = "Sector";
		$header_type[1]['TEXT'] = "All Calls (%)";
		$header_type[2]['TEXT'] = "Access to Decision Maker (%)";
		$header_type[3]['TEXT'] = "Meetings Set";
		$header_type[4]['TEXT'] = "Conversion (%)";

		$header_type[0]['T_ALIGN'] = "L";

		//set the header type
		$this->Set_Header_Type($header_type);

		$this->Draw_Manual_Header();

		//TABLE DATA SETTINGS
		$data_subtype = $table_default_data_type;

		$data_type = array(); //reset the array
		for ($i = 0; $i < $columns; $i++) $data_type[$i] = $data_subtype;

		$this->Set_Data_Type($data_type);

		$this->Draw_Table_Border();

		$data = app_domain_ReportReader::getReport8SectorPenetrationSummary($this->params['campaign_start'], $this->params['end'], $this->params['client_id']);
		$data1 = app_domain_ReportReader::getReport8SectorPenetrationSummary($this->params['campaign_start'], $this->params['end'], $this->params['client_id']);



		foreach ($data1 as $row) {
			if (is_null($row['sector'])) {
				$sector = 'Other';
			} else {
				$sector = $row['sector'];
			}
			$output[0]['TEXT'] = $sector;
			$output[0]['T_ALIGN'] = 'L';

			$output[1]['TEXT'] = ($row['call_percentage'] != '') ? $row['call_percentage'] . ' %' : '';
			$output[2]['TEXT'] =  ($row['access'] != '') ? $row['access'] . ' %' : '';
			$output[3]['TEXT'] = $row['meetings_set'];
			$output[4]['TEXT'] = ($row['conversion'] != '') ? $row['conversion'] . ' %' : '';

			$this->Draw_Data($output);
		}

		$this->Draw_Table_Border();

		// Write graph title
		$this->setXY($this->xy_coords['summary_of_campaign_x'] + 150, $this->xy_coords['summary_of_campaign_y']);
		$this->Cell(190, 5, 'Summary of campaign build up: Target vs Actual Meetings set and attended', 0, 0, 'L', 0);
		$this->Ln(5);

		$path = 'app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;

		$filename = 'ReportGraph8_1_' . mt_rand() . '.png';
		$dest = 'index.php?cmd=ReportGraph8_1&media=print&file=' . $filename
			. '&start=' . urlencode($this->params['campaign_start'])
			. '&end=' . urlencode($this->params['end'])
			. '&client_id=' . urlencode($this->params['client_id']);
		if ($_GET['test']){
			print_r($this->params);
			die($dest);
		}
		$this->writeImage($dest);
		$img = $path . $filename;

		// check if file exists
		while (!file_exists($img)) {
			// do nothing
			// $img = $path . 'test.png';
			throw new Exception('No graph file found! Looking for: ' . $img);
		}

		$this->Image($img, $this->GetX() + 150, $this->getY(), 120, 70);
		unlink($path . $filename);
	}

	/**
	 * NB Function was failing due to Undefined index: auth_session in Session.php::getSessionUser.
	 * Writes the graph image to a file so that it can be imported by FPDF.
	 * NB Function was failing due to Undefined index: auth_session in Session.php::getSessionUser.
	 * @param string $dest
	 */
	private function writeImage($dest)
	{
		// Need to use localhost rather than server name ($_SERVER['SERVER_NAME'])
		if ($_SERVER['SERVER_PORT'] == 443) {
			$url = 'http://localhost' . app_base_ApplicationRegistry::getUrl();
		} else {
			$url = 'http://localhost' . app_base_ApplicationRegistry::getUrl();
		}


		$full_url = $url . $dest;

		$ch = curl_init($full_url);
		if (!$ch) {
			echo "Here - in die";
			die('Cannot allocate a new PHP-CURL handle');
		}
		curl_setopt($ch, CURLOPT_FAILONERROR,    1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$data = curl_exec($ch);

		curl_close($ch);
	}

	public function sectorPeriodResults()
	{
		$this->AddPage();

		$table_default_header_type = array(
			'WIDTH' => 6,
			'T_COLOR' => array(0, 0, 0),
			'T_SIZE' => 9,
			'T_FONT' => 'Arial',
			'T_ALIGN' => 'L',
			'V_ALIGN' => 'T',
			'T_TYPE' => 'UB',
			'LN_SIZE' => 4,
			'BG_COLOR' => array(255, 255, 255),
			'BRD_COLOR' => array(0, 0, 0),
			'BRD_SIZE' => 0,
			'BRD_TYPE' => '0',
			'BRD_TYPE_NEW_PAGE' => '',
			'TEXT' => '',
		);
		$table_default_data_type = array(
			'T_COLOR' => array(0, 0, 0),
			'T_SIZE' => 8,
			'T_FONT' => 'Arial',
			'T_ALIGN' => 'N',
			'V_ALIGN' => 'T',
			'T_TYPE' => '',
			'LN_SIZE' => 4,
			'BG_COLOR' => array(255, 255, 255),
			'BRD_COLOR' => array(255, 255, 255),
			'BRD_SIZE' => 0,
			'BRD_TYPE' => '0',
			'BRD_TYPE_NEW_PAGE' => '',
		);
		$table_default_table_type = array(
			'TB_ALIGN' => 'L',
			'L_MARGIN' => 0,
			'T_MARGIN' => 0,
			'R_MARGIN' => 0,
			'B_MARGIN' => 0,
			'BRD_COLOR' => array(255, 255, 255),
			'BRD_SIZE' => '1',
		);

		//        // Page Title
		$this->SetFont('Arial', 'B', 10);
		//        $this->Cell(190, 5, 'Period Results', 0, 0, 'C', 0);
		//        $this->Ln(5);

		$this->Cell(190, 5, 'Reporting Period: ' . date('d/m/y', strtotime($this->params['start'])) . ' to ' . date('d/m/y', strtotime($this->params['end'])), 0, 0, 'L', 0);
		$this->Ln(8);
		$this->SetFont('Arial', 'B', 8);

		// set number of columns in table
		$columns = 5; //five columns

		//we initialize the table class
		$this->Table_Init($columns, true, false);

		$table_subtype = $table_default_table_type;
		$this->Set_Table_Type($table_subtype);

		$this->Set_Table_SplitMode(false);

		//TABLE HEADER SETTINGS
		$header_subtype = $table_default_header_type;
		for ($i = 0; $i < $columns; $i++) $header_type[$i] = $table_default_header_type;


		$header_type[0]['WIDTH'] = 75;
		$header_type[1]['WIDTH'] = 65;
		$header_type[2]['WIDTH'] = 70;
		$header_type[3]['WIDTH'] = 40;
		$header_type[4]['WIDTH'] = 30;

		$header_type[0]['TEXT'] = "Company Name";
		$header_type[1]['TEXT'] = "Prospect";
		$header_type[2]['TEXT'] = "Status and Comments";
		$header_type[3]['TEXT'] = "Date of Meetings";
		$header_type[3]['T_ALIGN'] = 'C

		';
		$header_type[4]['TEXT'] = "Action";

		//set the header type
		$this->Set_Header_Type($header_type);

		$this->Draw_Manual_Header();

		//TABLE DATA SETTINGS
		$data_subtype = $table_default_data_type;

		$data_type = array(); //reset the array
		for ($i = 0; $i < $columns; $i++) $data_type[$i] = $data_subtype;

		$data_type[3]['T_ALIGN'] = 'C';
		//		echo ('<pre>');
		//		print_r($data_type);
		//		echo ('</pre>');
		$this->Set_Data_Type($data_type);

		$fsize = 5;
		$colspan = 1;
		$rr = 255;

		$data = app_domain_ReportReader::getReport8PeriodResults($this->params['start'], $this->params['end'], $this->params['client_id'], $this->params['filter_id']);
		$data2 = app_domain_ReportReader::getReport8PeriodResults($this->params['start'], $this->params['end'], $this->params['client_id'], $this->params['filter_id']);
		//
			// 	print_r($data2);
		    //    exit();

		$data2 = self::array_utf8_to_iso88591($data2);

		$this->SetStyle("style", "arial", "I", 8, "0, 0, 0");

		$current_status = '';
		foreach ($data2 as $row) {
			// do grouping check
			if ($current_status != $row['status']) {
				$current_status = $row['status'];

				$header_type = array();
				$header_type[0] = $table_default_header_type;
				$header_type[0]['WIDTH'] = 75;
				$header_type[0]['TEXT'] = "\n" . $row['status'];
				$header_type[0]['T_TYPE'] = 'B';

				//set the header type
				$this->Set_Header_Type($header_type);
				$this->Draw_Manual_Header();

				$header_type = array();
				for ($i = 0; $i < $columns; $i++) $header_type[$i] = $table_default_header_type;

				$header_type[0]['WIDTH'] = 75;
				$header_type[1]['WIDTH'] = 65;
				$header_type[2]['WIDTH'] = 70;
				$header_type[3]['WIDTH'] = 40;
				//				$header_type[3]['T_ALIGN'] = 'L';
				$header_type[4]['WIDTH'] = 30;

				$header_type[0]['TEXT'] = "Company Name";
				$header_type[1]['TEXT'] = "Prospect";
				$header_type[2]['TEXT'] = "Status and Comments";
				$header_type[3]['TEXT'] = "Date of Meetings";
				$header_type[4]['TEXT'] = "Action";

				//set the header type
				$this->Set_Header_Type($header_type);
			} else {
				$header_type = array();
				for ($i = 0; $i < $columns; $i++) $header_type[$i] = $table_default_header_type;

				$header_type[0]['WIDTH'] = 75;
				$header_type[1]['WIDTH'] = 65;
				$header_type[2]['WIDTH'] = 70;
				$header_type[3]['WIDTH'] = 40;
				//				$header_type[3]['T_ALIGN'] = 'L';
				$header_type[4]['WIDTH'] = 30;

				$header_type[0]['TEXT'] = "Company Name";
				$header_type[1]['TEXT'] = "Prospect";
				$header_type[2]['TEXT'] = "Status and Comments";
				$header_type[3]['TEXT'] = "Date of Meetings";
				$header_type[4]['TEXT'] = "Action";

				//set the header type
				$this->Set_Header_Type($header_type);
			}

			$output = array();
			// set column 1 text -
			// NOTES: 	we need to use double quotes so that the \n characters are processed correctly
			// 			and we insert a \n character at the start of each column to provide spacing between lines.
			//			(have to do it this way rather than adding a new line with page width colspan) otherwise the page cut-off check
			//			doesn't work property (because it checks each line, and there's no way of grouping the two lines together
			//			for the purposes of the cut-off check).
			$col_text = "\n" . $row['company_name'];
			$output[0]['TEXT'] = $col_text;

			// set column 2 text - NOTE: we need to use double quotes so that the \n characters are processed correctly
			$col_text = "\n" . $row['job_title'] . "\n<style>" . $row['prospect_name'] . '</style>';
			$output[1]['TEXT'] = $col_text;

			// set column 3 text - NOTE: we need to use double quotes so that the \n characters are processed correctly
			$col_text = "\n" . $row['status'] . "\n<style>" . $row['comment'] . '</style>';
			$output[2]['TEXT'] = $col_text;

			// set column 3 text - NOTE: we need to use double quotes so that the \n characters are processed correctly
			$col_text = "\n" . $row['meeting_date'];
			$output[3]['TEXT'] = $col_text;


			// set column 3 text - NOTE: we need to use double quotes so that the \n characters are processed correctly
			$col_text = "\n" . $row['next_action_by'] . "\n<style>" . $row['next_communication_date'] . '</style>';
			$output[4]['TEXT'] = $col_text;



			$this->Draw_Data($output, false);
		}

		$this->Draw_Table_Border();
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
		if (is_array($x)) {
			$sum = 0.0;
			$count = 0;
			foreach ($x as $item) {
				$sum += $item[$y];
				$count++;
			}
			if ($count > 1) {
				$sum = $sum / $count;
			}
			return number_format($sum, $decimals);
		} else {
			if ($y > 0) {
				return number_format($x / $y, $decimals);
			} else {
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
		foreach ($array as $item) {
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
		foreach ($array as $item) {
			if ($item['nbm_id'] == $nbm_id) {
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
		foreach ($array as $item) {
			if ($item['nbm_id'] == $nbm_id) {
				$meetings += $item['meeting_set_count'];
			}
		}

		foreach ($array as $item) {
			if ($item['nbm_id'] == $nbm_id) {
				$effectives += $item['call_effective_count'];
				//                $count++;
			}
		}

		if ($effectives > 0 && $meetings > 0) {
			$sum = ($effectives / $meetings) * 100;
		} else {
			$sum = 0;
		}


		//        if ($count > 1)
		//        {
		//            $sum = ($meetings / $effectives) * 100;
		//        }
		return number_format($sum, $decimals);
	}




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
		foreach ($array as $item) {
			$meetings += $item['meeting_set_count'];
		}

		foreach ($array as $item) {

			$effectives += $item['call_effective_count'];
		}


		if ($effectives > 0 && $meetings > 0) {
			$sum = ($effectives / $meetings) * 100;
		} else {
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
		foreach ($array as $item) {
			if ($item['nbm_id'] == $nbm_id) {
				$effectives += $item['call_effective_count'];
			}
		}

		foreach ($array as $item) {
			if ($item['nbm_id'] == $nbm_id) {
				$calls += $item['call_count'];
			}
		}


		if ($effectives > 0 && $calls > 0) {
			$sum = ($effectives / $calls) * 100;
		} else {
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
		foreach ($array as $item) {
			$effectives += $item['call_effective_count'];
		}

		foreach ($array as $item) {
			$calls += $item['call_count'];
		}



		if ($effectives > 0 && $calls > 0) {
			$sum = ($effectives / $calls) * 100;
		} else {
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
		while ($start_year_month != $end_year_month) {
			$x++;
			$year = substr($start_year_month, 0, 4);
			$month = substr($start_year_month, 5, 2);

			//			echo $month . ' : ' . $year;

			if ($month >= 12) {
				$month = 1;
				$year++;
			} else {
				$month++;
			}

			//			if ($month <10)
			//			{
			$month = str_pad($month, 2, '0', STR_PAD_LEFT);
			//			}

			$start_year_month = $year . '-' . $month;
		}
		return $x;
	}

	private function get_timestamp($date)
	{
		list($y, $m, $d) = explode('-', $date);
		return mktime(0, 0, 0, $m, $d, $y);
	}

	//	private function addGraphs($image_x, $image_y, $image_width, $image_height)
	//	{
	//		$path = 'app' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
	//
	//		// Image 1
	//		$filename = 'ReportGraph4_1_' . mt_rand() . '.png';
	//		$dest = 'index.php?cmd=ReportGraph8_1&media=print&file=' . $filename . '&start=' . $this->params['start'] . '&end=' . $this->params['end']
	//				. 'client_id=' . $this->params['client_id'];;
	//		$this->writeImage($dest);
	//		$img = $path . $filename;
	//		$this->Image($img, $image_x, $image_y, $image_width, $image_height);
	//		unlink($path . $filename);
	//	}

	protected static function array_utf8_to_iso88591($in)
	{
		if (is_array($in)) {
			foreach ($in as $key => $value) {
				$out[self::array_utf8_to_iso88591($key)] = self::array_utf8_to_iso88591($value);
			}
		} elseif (is_string($in)) {
			return iconv("UTF-8", "windows-1252//TRANSLIT", $in);
		} else {
			return $in;
		}
		return $out;
	}
}
