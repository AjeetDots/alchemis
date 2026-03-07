<?php


class app_command_CampaignSettings extends app_command_Command
{

    /**
     * TODO - declare public?
     * @param app_controller_Request $request
     * @return app_controller_Response
     */
    function doExecute(app_controller_Request $request)
    {
        $campaign_id = $request->getProperty('campaign_id');
        $campaign = app_model_Campaign::find($campaign_id);

        if (!empty($_POST)) {
            $campaignViewDefault = filter_input(INPUT_POST, 'campaign_view_default');
            if (!empty($campaignViewDefault)) {
                app_model_CampaignSetting::saveSetting(app_model_CampaignSetting::CAMPAIGN_SETTING_DEFAULT_VIEW, $campaign_id, $campaignViewDefault);
            }
        }

        $campaignDefaultView = $campaign->getCampaignSetting(app_model_CampaignSetting::CAMPAIGN_SETTING_DEFAULT_VIEW);


        return app_controller_Response::view('CampaignSettings', compact('campaign', 'campaignDefaultView'));
    }
}
