<?php

require_once ('Spreadsheet/Writer.php');
require_once('app/mapper/MailerMapper.php');
require_once('app/domain/Mailer.php');


class app_command_MailerExport extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{

		if ($request->getProperty('id') != '')
		{
			$mailer_id = $request->getProperty('id');
			$mailer = app_domain_Mailer::find($mailer_id);

			$xls = new Spreadsheet_Excel_Writer();

			$xls =& $this->exportXLS($mailer_id);
			$xls->send($mailer->getName() . '.xls');
			$xls->close();
		}

//		return self::statuses('CMD_OK');
		exit();
	}


	function exportXLS($mailer_id)
    {
		$xls = new Spreadsheet_Excel_Writer();
		$mailer = app_domain_Mailer::find($mailer_id);

		$sheetDetails =& $xls->addWorksheet('Details');
 		$sheetInProgress =& $xls->addWorksheet('In Progress');
 		$sheetDispatched =& $xls->addWorksheet('Despatched');

		$titleFormat =& $xls->addFormat();
		$titleFormat->setFontFamily('Verdana');
		$titleFormat->setBold();
		$titleFormat->setSize('10');
		$titleFormat->setColor('navy');
		$titleFormat->setBottom(1);
		$titleFormat->setBottomColor('navy');

		$sheetDetails->writeString(0, 0, "Name", $titleFormat);
		$sheetDetails->writeString(0, 1, $mailer->getName());
		$sheetDetails->writeString(1, 0, "Description", $titleFormat);
		$sheetDetails->writeString(1, 1, $mailer->getDescription());
		$sheetDetails->writeString(2, 0, "Mailer Type", $titleFormat);
		$sheetDetails->writeString(2, 1, $mailer->getResponseGroupName());
		$sheetDetails->writeString(3, 0, "Delivery Method", $titleFormat);
		$sheetDetails->writeString(3, 1, $mailer->getTypeName());
		$sheetDetails->writeString(4, 0, "Created By", $titleFormat);
		$sheetDetails->writeString(4, 1, $mailer->getCreatedByName());
		$sheetDetails->writeString(5, 0, "DateCreated", $titleFormat);
		$sheetDetails->writeString(5, 1, $mailer->getCreatedAt());

		$sheetInProgress->writeString(0, 0, "CompanyID", $titleFormat);
		$sheetInProgress->writeString(0, 1, "Company", $titleFormat);
//		$sheetInProgress->writeString(0, 2, "Employees", $titleFormat);
//		$sheetInProgress->writeString(0, 3, "BusinessType", $titleFormat);
//		$sheetInProgress->writeString(0, 4, "CompanyCleaned", $titleFormat);
		$sheetInProgress->writeString(0, 2, "ContactID", $titleFormat);
		$sheetInProgress->writeString(0, 3, "ContactTitle", $titleFormat);
		$sheetInProgress->writeString(0, 4, "ContactFirstName", $titleFormat);
		$sheetInProgress->writeString(0, 5, "ContactSurname", $titleFormat);
		$sheetInProgress->writeString(0, 6, "PostID", $titleFormat);
		$sheetInProgress->writeString(0, 7, "Post", $titleFormat);
//		$sheetInProgress->writeString(0, 8, "PostCleaned", $titleFormat);

		switch ($mailer->getTypeName())
		{
			case 'E-mail';
				$sheetInProgress->writeString(0, 8, "Email Address", $titleFormat);
				break;

			case 'Fax';
				$sheetInProgress->writeString(0, 8, "Fax Number", $titleFormat);
				break;

			case 'Postal';
				$sheetInProgress->writeString(0, 8, "Address 1", $titleFormat);
				$sheetInProgress->writeString(0, 9, "Address 2", $titleFormat);
				$sheetInProgress->writeString(0, 10, "Town", $titleFormat);
				$sheetInProgress->writeString(0, 11, "City", $titleFormat);
				$sheetInProgress->writeString(0, 12, "Postcode", $titleFormat);
				$sheetInProgress->writeString(0, 13, "County", $titleFormat);
				$sheetInProgress->writeString(0, 14, "Country", $titleFormat);
				break;
		}

		$sheetDispatched->writeString(0, 0, "CompanyID", $titleFormat);
		$sheetDispatched->writeString(0, 1, "Company", $titleFormat);
//		$sheetDispatched->writeString(0, 2, "Employees", $titleFormat);
//		$sheetDispatched->writeString(0, 3, "BusinessType", $titleFormat);
//		$sheetDispatched->writeString(0, 4, "CompanyCleaned", $titleFormat);
		$sheetDispatched->writeString(0, 2, "ContactID", $titleFormat);
		$sheetDispatched->writeString(0, 3, "ContactTitle", $titleFormat);
		$sheetDispatched->writeString(0, 4, "ContactFirstName", $titleFormat);
		$sheetDispatched->writeString(0, 5, "ContactSurname", $titleFormat);
		$sheetDispatched->writeString(0, 6, "PostID", $titleFormat);
		$sheetDispatched->writeString(0, 7, "Post", $titleFormat);
//		$sheetDispatched->writeString(0, 8, "PostCleaned", $titleFormat);
		$sheetDispatched->writeString(0, 8, "Despatched Date", $titleFormat);


		switch ($mailer->getTypeName())
		{
			case 'E-mail';
				$sheetDispatched->writeString(0, 9, "Email Address", $titleFormat);
				break;

			case 'Fax';
				$sheetDispatched->writeString(0, 9, "Fax Number", $titleFormat);
				break;

			case 'Postal';
				$sheetDispatched->writeString(0, 9, "Address 1", $titleFormat);
				$sheetDispatched->writeString(0, 10, "Address 2", $titleFormat);
				$sheetDispatched->writeString(0, 11, "Town", $titleFormat);
				$sheetDispatched->writeString(0, 12, "City", $titleFormat);
				$sheetDispatched->writeString(0, 13, "Postcode", $titleFormat);
				$sheetDispatched->writeString(0, 14, "County", $titleFormat);
				$sheetDispatched->writeString(0, 15, "Country", $titleFormat);
				break;
		}

		if ($not_despatched = app_domain_MailerItem::findNotDespatchedByMailerIdForExport($mailer_id))
		{
			$row = 1;

//			echo '<pre>';
//			print_r($not_despatched);
//			echo '</pre>';
			foreach ($not_despatched as $item)
			{
				$sheetInProgress->writeString($row, 0, $item['company_id']);
				$sheetInProgress->writeString($row, 1, $item['company_name']);
//				$sheetInProgress->writeString($row, 2, $item['Employees);
//				$sheetInProgress->writeString($row, 3, $item->BusinessType);
//				$sheetInProgress->writeString($row, 4, $item->CompanyCleanedAt);
				$sheetInProgress->writeString($row, 2, $item['contact_id']);
				$sheetInProgress->writeString($row, 3, $item['title']);
				$sheetInProgress->writeString($row, 4, $item['first_name']);
				$sheetInProgress->writeString($row, 5, $item['surname']);
				$sheetInProgress->writeString($row, 6, $item['post_id']);
				$sheetInProgress->writeString($row, 7, $item['job_title']);
//				$sheetInProgress->writeString($row, 11, $item->PostCleanedAt);
				switch ($mailer->getTypeName())
				{
					case 'E-mail';
						$sheetInProgress->writeString($row, 8, $item['email']);
						break;

					case 'Fax';
						$sheetInProgress->writeString($row, 8, $item['telephone_fax']);
						break;

					case 'Postal';
//						$sheetInProgress->writeString($row, 8, $item['Department);
						$sheetInProgress->writeString($row, 8, $item['address_1']);
						$sheetInProgress->writeString($row, 9, $item['address_2']);
						$sheetInProgress->writeString($row, 10, $item['town']);
						$sheetInProgress->writeString($row, 11, $item['city']);
						$sheetInProgress->writeString($row, 12, $item['postcode']);
						$sheetInProgress->writeString($row, 13, $item['county']);
						$sheetInProgress->writeString($row, 14, $item['country']);
						break;
				}
				$row++;
			}
		}

		if ($despatched = app_domain_MailerItem::findDespatchedByMailerIdForExport($mailer_id))
		{
			$row = 1;
//			echo '<pre>';
//			print_r($despatched);
//			echo '</pre>';
			foreach ($despatched as $item)
			{

//				echo '$item->company_id = ' . $item['company_id'];
				$sheetDispatched->writeString($row, 0, $item['company_id']);
				$sheetDispatched->writeString($row, 1, $item['company_name']);
//				$sheetInProgress->writeString($row, 2, $item->Employees);
//				$sheetInProgress->writeString($row, 3, $item->BusinessType);
//				$sheetInProgress->writeString($row, 4, $item->CompanyCleanedAt);
				$sheetDispatched->writeString($row, 2, $item['contact_id']);
				$sheetDispatched->writeString($row, 3, $item['title']);
				$sheetDispatched->writeString($row, 4, $item['first_name']);
				$sheetDispatched->writeString($row, 5, $item['surname']);
				$sheetDispatched->writeString($row, 6, $item['post_id']);
				$sheetDispatched->writeString($row, 7, $item['job_title']);
				$sheetDispatched->writeString($row, 8, $item['despatched_date']);
//				$sheetInProgress->writeString($row, 11, $item->PostCleanedAt);
				switch ($mailer->getTypeName())
				{
					case 'E-mail';
						$sheetDispatched->writeString($row, 9, $item['email']);
						break;

					case 'Fax';
						$sheetDispatched->writeString($row, 9, $item['telephone_fax']);
						break;

					case 'Postal';
//						$sheetDispatched->writeString($row, 8, $item->Department);
						$sheetDispatched->writeString($row, 9, $item['address_1']);
						$sheetDispatched->writeString($row, 10, $item['address_2']);
						$sheetDispatched->writeString($row, 11, $item['town']);
						$sheetDispatched->writeString($row, 12, $item['city']);
						$sheetDispatched->writeString($row, 13, $item['postcode']);
						$sheetDispatched->writeString($row, 14, $item['county']);
						$sheetDispatched->writeString($row, 15, $item['country']);
						break;
				}
				$row++;
			}
		}

		return $xls;
    }

}

?>