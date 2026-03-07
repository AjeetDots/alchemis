<?php 

/**
 * Defines the app_view_CampaignSumaryReports class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
 
/**
 * @package Alchemis
 */
class app_view_CampaignReportSummaries extends app_view_View
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('campaign_id', $this->request->getProperty('campaign_id'));
		$this->smarty->assign('report_summaries', $this->request->getObject('report_summaries'));

		$this->smarty->display('CampaignReportSummaries.tpl');
	}
}
?>