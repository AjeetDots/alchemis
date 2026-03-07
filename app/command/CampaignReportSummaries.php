<?php

require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

class app_command_CampaignReportSummaries extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get client
		$campaign_id = $request->getProperty('campaign_id');
        $campaign = app_model_Campaign::find($campaign_id);
		$report_summaries = app_domain_CampaignReportSummary::findByCampaignId($campaign->id);
		$request->setObject('report_summaries', $report_summaries);
	}	
}	
?>
