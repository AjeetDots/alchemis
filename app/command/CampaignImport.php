<?php

/**
 * Defines the app_command_WorkspacePost class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_CampaignImport extends app_command_Command
{
    public function doExecute(app_controller_Request $request)
    {
        $time_start = microtime(true);
        $session = Auth_Session::singleton();
        $session->login('python.user', 'password', session_id());

        ini_set('memory_limit','1600M');

        require_once 'include/campaignmonitor/csrest_clients.php';
        $wrap = new CS_REST_Clients(
            app_base_ApplicationRegistry::getItem('campaign_monitor_client_id'),
            app_base_ApplicationRegistry::getItem('campaign_monitor_api_key')
        );

        $result = $wrap->get_campaigns();
        // import last month
        $importStartDate = strtotime('-1 months');
        if ($result->was_successful())
        {
            //Loop through and create campaign if it doesn't exist
            foreach( $result->response as $index => $campaignResult )
            {
                //skip if older than start date
                if (strtotime($campaignResult->SentDate) < $importStartDate) continue;

                $campaign = app_domain_CmCampaign::findByCmId($campaignResult->CampaignID);
                echo "<br><br><p>CampaignResult: $index</p><br><br>";
                var_dump($campaignResult);
                echo "<br><br><p>Campaign: $index</p><br><br>";
                var_dump($campaign);
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
                //get the stats on that campaign
                $campaign->updateStats();
            }
        }
        else
        {
            $body = print_r($result, true);
            echo 'successful? ' . $result->was_successful() . "\n\n";
            echo $body;
        }
        $session->logout();
        $time_end = microtime(true);
        echo date('Y-m-d H:i:s');
        var_dump('finished in ' . round(($time_end - $time_start) / 60, 2) . ' minutes');
        die;
    }
}