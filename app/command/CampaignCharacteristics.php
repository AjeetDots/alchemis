<?php


use Illuminate\Database\Eloquent\Builder;

class app_command_CampaignCharacteristics extends app_command_Command
{

    /**
     * TODO - declare public?
     * @param app_controller_Request $request
     * @return app_controller_Response
     */
    function doExecute(app_controller_Request $request)
    {
        $initId = $request->getProperty('initId');
        $initiative = app_model_Initiatives::with('campaign')->find($initId);
        $campaignId = $initiative->campaign->id;
        $parentId = $request->getProperty('parentId');
        $campaignCharacteristics = app_model_CampaignCharacteristic::with('characteristic')
            ->where('campaign_id', '=', $campaignId)
            ->whereHas('characteristic', function (Builder $q) use($parentId) {
                $q->whereDoesntHave('objectCharacteristic', function (Builder $q2) use($parentId) {
                    $q2->where('company_id', '=', $parentId);
                });
            })
            ->get();

        $campChars = $campaignCharacteristics->count() > 0 ? $campaignCharacteristics->toArray() : null;
        return app_controller_Response::json($campChars);
    }
}