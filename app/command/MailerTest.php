<?php

use app_controller_Response as Response;

ini_set('display_errors', 'On');
error_reporting(E_ERROR);

class app_command_MailerTest extends app_command_BaseCommand
{
    public function test()
    {
        // ./command run MailerTest/test env:aws
        
        require_once 'include/campaignmonitor/csrest_clients.php';
        $wrap = new CS_REST_Clients(
            app_base_ApplicationRegistry::getItem('campaign_monitor_client_id'),
            app_base_ApplicationRegistry::getItem('campaign_monitor_api_key')
        );
        
        $result = $wrap->get_campaigns();
        var_dump($result->was_successful());
        return Response::dump('test');
    }
    
}