<?php

/**
 * Defines the app_command_Report class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/birt/BirtHelper.php');

/**
 * @package Alchemis
 */
class app_command_Report extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
//		$format = $request->getProperty('format');
		$format = 'html';
		
		$report_id = $request->getProperty('report_id');
		$report    = app_domain_ReportReader::find($report_id);
		
		if (!$report)
		{
			exit('No report specified');
		}

		$report = $report['design_file'] . '.rptdesign';
		
		// Use Smarty to pull in the report template
		$smarty = app_birt_BirtHelper::getSmarty();
		if (!$smarty->template_exists($report))
		{
			throw new app_base_BirtException('Report not found');
		}
		
		// Create temporary compiled report file
		$filestring = tempnam(app_birt_BirtHelper::getTemporaryLocation(), session_id() . '-');
		$rpt = $smarty->fetch($report);
		$handle = fopen($filestring, 'w');
		fwrite($handle, $rpt);
		fclose($handle);
//		chown($filestring, 'ian');
//		chgrp($filestring, 'www');
		chmod($filestring, '0100');
		
		// TODO
		//  - need to ensure this file is only temporary, or implement some 
		//    other solution to ensure data access is only possible for 
		//    authenticated users
		
		// Redirect browser to the temporary compiled report file
		if ($format == 'html')
		{
			$destination = app_birt_BirtHelper::getBirtViewerLocation() . '/frameset?__report=';
			$destination .= urlencode(realpath($filestring));
			header('Location: ' . $destination);

//			$destination = app_birt_BirtHelper::getBirtViewerLocation() . '/frameset?__report=';
//			$destination .= urlencode($filestring);
//			$destination .= '&__format=' . urlencode('html');
//
//			$ch = curl_init($destination);
//			if (!$ch)
//			{
//				die('Cannot allocate a new PHP-CURL handle');
//			}
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
//			$data = curl_exec($ch);
//			curl_close($ch);
//			header('Content-type: text/html');
//			print($data);
		}
//		elseif ($format == 'pdf')
//		{
//			$destination = app_birt_BirtHelper::getBirtViewerLocation() . '/frameset?__report=';
////			$destination = app_birt_BirtHelper::getBirtViewerLocation() . '/run?__report=';
//			$destination .= urlencode($filestring);
//			$destination .= '&__format=' . urlencode('pdf');
////			echo "$destination";
//			$ch = curl_init($destination);
//			if (!$ch)
//			{
//				die('Cannot allocate a new PHP-CURL handle');
//			}
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
//			
//			$data = curl_exec($ch);
//			curl_close($ch);
//			header("Content-type: application/pdf");
//			print($data);
//		}

		// Exit to prevent controller continuing on to show a view
		exit(); 
	}

}

?>