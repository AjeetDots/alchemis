<?php

// Ensure the maximum execution time is at least 300 seconds
if (ini_get('max_execution_time') < 300)
{
    set_time_limit(300);
}

require_once '../index.php';
require_once '../include/campaignmonitor/csrest_clients.php';

$wrap = new CS_REST_Clients(
        app_base_ApplicationRegistry::getItem('campaign_monitor_client_id'),
        app_base_ApplicationRegistry::getItem('campaign_monitor_api_key'));

$result          = $wrap->get_campaigns();
$importStartDate = strtotime('-1 months');

if ($result->was_successful())
{
    foreach( $result->response as $campaignResult )
    {
        if (strtotime($campaignResult->SentDate) < $importStartDate) continue;

        $campaign = app_domain_CmCampaign::findByCmId($campaignResult->CampaignID);
        if ( null === $campaign )
        {
            $campaign = new app_domain_CmCampaign();
            $campaign->setCmId( $campaignResult->CampaignID );
            $campaign->setCmName( $campaignResult->Name );
            $campaign->setTotalRecipients($campaignResult->TotalRecipients);
            $campaign->setCreated(date('Y-m-d H:i:s'));
            $campaign->setProcessed(0);
            $campaign->commit();
        }
        $campaign->updateStats();
    }
}
else
{
    echo 'Failed with code ' . $result->http_status_code . "\n<br /><pre>";
}

function __autoload($classname)
{
	$path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
	if (file_exists($path . '.php'))
	{
		require_once($path . '.php');
	}
}