<?php

require_once ('Spreadsheet/Writer.php');

class app_command_FilterExport extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{

		if ($request->getProperty('id') != '')
		{
			$request->getProperty('format') == '' ? $export_format = 'standard' : $export_format = $request->getProperty('format');

			$filter_id = $request->getProperty('id');
			$filter = app_domain_Filter::find($filter_id);

			$xls = new Spreadsheet_Excel_Writer();

			$xls =& $this->exportXLS($filter_id, $export_format);
			$xls->send($filter->getName() . '.xls');
			$xls->close();
		}
		exit();
	}

	function exportXLS($filter_id, $export_format)
    {
    	$xls = new Spreadsheet_Excel_Writer();
		$filter = app_domain_Filter::find($filter_id);

		$header_format =& $xls->addFormat(array('Size' => 10,
                                      'Align' => 'left',
                                      'Bold'  => 1));

 		$sheet_data =& $xls->addWorksheet('Data');


		switch ($filter->getResultsFormat())
		{

			case 'Company':
			case 'Site':
				$this->writeCompanyHeader($sheet_data, 0, $header_format);
				$this->insertBlankRow($sheet_data, 1, 0);
				break;
			case 'Company and posts':
			case 'Site and posts':
				$this->writeCompanyHeader($sheet_data, 0, $header_format);
				$this->writePostHeader($sheet_data, 12, $header_format);
				$this->insertBlankRow($sheet_data, 1, 0);
				break;
			case 'Client initiative':
			case 'Client initiative with last note':
			case 'Mailer':
				$this->writeCompanyHeader($sheet_data, 0, $header_format);
				$this->writePostHeader($sheet_data, 12, $header_format);
				$this->writePostInitiativeHeader($sheet_data, 24, $header_format);
				$this->insertBlankRow($sheet_data, 1, 0);
				break;
			case 'Meeting':
				$this->writeMeetingHeader($sheet_data, 0, $header_format);
                $this->writeCompanyHeader($sheet_data, 16, $header_format);
                $this->writePostHeader($sheet_data, 28, $header_format);
                $this->writePostInitiativeHeader($sheet_data, 40, $header_format);
                $this->insertBlankRow($sheet_data, 1, 0);
                break;
		}

		if ($items = app_domain_FilterBuilder::getFilterExport($filter_id, $filter->getResultsFormat()))
		{
			$row = 2;
			switch ($filter->getResultsFormat())
			{

				case 'Company':
				case 'Site':
					foreach ($items as $item)
					{
						$this->writeCompanyData($sheet_data, $row, 0, $item);
						$row++;
					}
					break;
				case 'Company and posts':
				case 'Site and posts':
					foreach ($items as $item)
					{
						$this->writeCompanyData($sheet_data, $row, 0, $item);
						$this->writePostData($sheet_data, $row, 12, $item);
						$row++;
					}
					break;
				case 'Client initiative':
				case 'Client initiative with last note':
				case 'Mailer':
					foreach ($items as $item)
					{
						$this->writeCompanyData($sheet_data, $row, 0, $item);
						$this->writePostData($sheet_data, $row, 12, $item);
						$this->writePostInitiativeData($sheet_data, $row, 24, $item);
						$row++;
					}
					break;
				case 'Meeting':
                    foreach ($items as $item)
                    {
                    	$this->writeMeetingData($sheet_data, $row, 0, $item);
                        $this->writeCompanyData($sheet_data, $row, 16, $item);
                        $this->writePostData($sheet_data, $row, 28, $item);
                        $this->writePostInitiativeData($sheet_data, $row, 40, $item);
                        $row++;
                    }
                    break;
				default:
					throw new Exception('No results format variable ($results_format) supplied.');
					break;
			}
		}
		return $xls;
    }

    function insertBlankRow($sheet_data, $start_row, $start_col, $format = null)
    {
    	$sheet_data->writeString($start_row, $start_col, '', $format);
    }

    function writeCompanyHeader($sheet_data, $start_col, $format = null)
    {
    	// company
    	$sheet_data->writeString(0, $start_col, 'Company ID', $format);
		$sheet_data->writeString(0, ++$start_col, 'Company name', $format);
		$sheet_data->writeString(0, ++$start_col, 'Website', $format);
		if ($this->session_user->hasPermission('permission_admin_users')) {
			$sheet_data->writeString(0, ++$start_col, 'Company telephone', $format);
		} else {
			++$start_col;
		}


		// site
		$sheet_data->writeString(0, ++$start_col, 'Site ID', $format);
		if ($this->session_user->hasPermission('permission_admin_users')) {
			$sheet_data->writeString(0, ++$start_col, 'Address 1', $format);
		} else {
			++$start_col;
		}
		$sheet_data->writeString(0, ++$start_col, 'Address 2', $format);
		$sheet_data->writeString(0, ++$start_col, 'Town', $format);
		$sheet_data->writeString(0, ++$start_col, 'City', $format);
		$sheet_data->writeString(0, ++$start_col, 'Postcode', $format);
		$sheet_data->writeString(0, ++$start_col, 'County', $format);
		$sheet_data->writeString(0, ++$start_col, 'Country', $format);

    }

    function writePostHeader($sheet_data, $start_col, $format = null)
    {
   		// post
		$sheet_data->writeString(0, $start_col, 'Post ID', $format);
		$sheet_data->writeString(0, ++$start_col, 'Job_title', $format);
		$sheet_data->writeString(0, ++$start_col, 'Post telephone 1', $format);
		$sheet_data->writeString(0, ++$start_col, 'Post telephone 2', $format);
		$sheet_data->writeString(0, ++$start_col, 'Post telephone switchboard', $format);
		$sheet_data->writeString(0, ++$start_col, 'Post telephone fax', $format);

		// contact
		$sheet_data->writeString(0, ++$start_col, 'Contact id', $format);
		$sheet_data->writeString(0, ++$start_col, 'Title', $format);
		$sheet_data->writeString(0, ++$start_col, 'First name', $format);
		$sheet_data->writeString(0, ++$start_col, 'Surname', $format);
		$sheet_data->writeString(0, ++$start_col, 'Email', $format);
		$sheet_data->writeString(0, ++$start_col, 'Contact telephone', $format);
    }

	function writePostInitiativeHeader($sheet_data, $start_col, $format = null)
    {
   		// post initiative
		$sheet_data->writeString(0, $start_col, 'Post initiative id', $format);
		$sheet_data->writeString(0, ++$start_col, 'Client name', $format);
		$sheet_data->writeString(0, ++$start_col, 'Initiative name', $format);
		$sheet_data->writeString(0, ++$start_col, 'Status', $format);
		$sheet_data->writeString(0, ++$start_col, 'Comment', $format);
		$sheet_data->writeString(0, ++$start_col, 'Last communication date', $format);
		$sheet_data->writeString(0, ++$start_col, 'Last communication note', $format);
    }

    function writeMeetingHeader($sheet_data, $start_col, $format = null)
    {
        // new biz meeting report
        $sheet_data->writeString(0, $start_col, 'Company name', $format);
        $sheet_data->writeString(0, ++$start_col, 'Title', $format);
        $sheet_data->writeString(0, ++$start_col, 'First name', $format);
        $sheet_data->writeString(0, ++$start_col, 'Surname', $format);
        $sheet_data->writeString(0, ++$start_col, 'Status', $format);
        $sheet_data->writeString(0, ++$start_col, 'Lead source', $format);
        $sheet_data->writeString(0, ++$start_col, 'Meeting set by', $format);
        $sheet_data->writeString(0, ++$start_col, 'Meeting last modified by', $format);
        $sheet_data->writeString(0, ++$start_col, 'Meeting set date', $format);
        $sheet_data->writeString(0, ++$start_col, 'Meeting date', $format);
        $sheet_data->writeString(0, ++$start_col, 'Meeting attended date', $format);
        $sheet_data->writeString(0, ++$start_col, '', $format);
        $sheet_data->writeString(0, ++$start_col, 'Comment', $format);
        $sheet_data->writeString(0, ++$start_col, 'Last communication date', $format);
        $sheet_data->writeString(0, ++$start_col, 'Last communication note', $format);
    }

    function writeCompanyData($sheet_data, $row, $start_col, $item)
    {
    	// company
		$sheet_data->writeString($row, $start_col, $item['company_id']);
		$sheet_data->writeString($row, ++$start_col, $item['company_name']);
		$sheet_data->writeString($row, ++$start_col, $item['website']);
		if ($this->session_user->hasPermission('permission_admin_users')) {
			$sheet_data->writeString($row, ++$start_col, $item['company_telephone']);
		} else {
			++$start_col;
		}

		// site
		$sheet_data->writeString($row, ++$start_col, $item['site_id']);
		if ($this->session_user->hasPermission('permission_admin_users')) {
			$sheet_data->writeString($row, ++$start_col, $item['address_1']);
		} else {
			++$start_col;
		}

		$sheet_data->writeString($row, ++$start_col, $item['address_2']);
		$sheet_data->writeString($row, ++$start_col, $item['town']);
		$sheet_data->writeString($row, ++$start_col, $item['city']);
		$sheet_data->writeString($row, ++$start_col, $item['postcode']);
		$sheet_data->writeString($row, ++$start_col, $item['county']);
		$sheet_data->writeString($row, ++$start_col, $item['country']);
    }

    function writePostData($sheet_data, $row, $start_col, $item)
    {
    	// post
		$sheet_data->writeString($row, $start_col, $item['post_id']);
		$sheet_data->writeString($row, ++$start_col, $item['job_title']);
		$sheet_data->writeString($row, ++$start_col, $item['post_telephone_1']);
		$sheet_data->writeString($row, ++$start_col, $item['post_telephone_2']);
		$sheet_data->writeString($row, ++$start_col, $item['post_telephone_switchboard']);
		$sheet_data->writeString($row, ++$start_col, $item['post_telephone_fax']);

		// contact
		$sheet_data->writeString($row, ++$start_col, $item['contact_id']);
		$sheet_data->writeString($row, ++$start_col, $item['title']);
		$sheet_data->writeString($row, ++$start_col, $item['first_name']);
		$sheet_data->writeString($row, ++$start_col, $item['surname']);
		$sheet_data->writeString($row, ++$start_col, $item['email']);
		$sheet_data->writeString($row, ++$start_col, $item['contact_telephone_mobile']);
    }

    function writePostInitiativeData($sheet_data, $row, $start_col, $item)
    {
    	// post initiative
		$sheet_data->writeString($row, $start_col, $item['post_initiative_id']);
		$sheet_data->writeString($row, ++$start_col, $item['client_name']);
		$sheet_data->writeString($row, ++$start_col, $item['initiative_name']);
		$sheet_data->writeString($row, ++$start_col, $item['status']);
		$sheet_data->writeString($row, ++$start_col, $item['comment']);
		$sheet_data->writeString($row, ++$start_col, $item['last_communication_date']);
		$sheet_data->writeString($row, ++$start_col, $item['last_communication_note']);
    }

    function writeMeetingData($sheet_data, $row, $start_col, $item, $format = null)
    {
        // new biz meeting report data
        $sheet_data->writeString($row, $start_col, $item['company_name'], $format);
        $sheet_data->writeString($row, ++$start_col, $item['title']);
        $sheet_data->writeString($row, ++$start_col, $item['first_name']);
        $sheet_data->writeString($row, ++$start_col, $item['surname']);
        $sheet_data->writeString($row, ++$start_col, $item['status']);
        $sheet_data->writeString($row, ++$start_col, $item['lead_source']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_created_by']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_modified_by']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_set_date']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_date']);
        $sheet_data->writeString($row, ++$start_col, $item['meeting_attended_date']);
        $sheet_data->writeString($row, ++$start_col, $item['']);
        $sheet_data->writeString($row, ++$start_col, $item['comment']);
        $sheet_data->writeString($row, ++$start_col, $item['last_communication_date']);
        $sheet_data->writeString($row, ++$start_col, $item['last_communication_note']);
    }


}

?>