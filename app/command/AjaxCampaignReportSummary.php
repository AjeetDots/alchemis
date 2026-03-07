<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_CampaignReportSummary objects.
 * @package alchemis
 */
class app_command_AjaxCampaignReportSummary extends app_command_AjaxCommand
{
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'delete_report_summary':
				$campaign_report_summary = app_domain_CampaignReportSummary::find($this->request->item_id);
				$campaign_report_summary->markDeleted();
				$campaign_report_summary->commit();
				break;
			default:
				break;
		}
		
		// Return result data
		array_push($this->response->data, $this->request);
		
	}
	
}

?>