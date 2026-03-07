<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Class app_model_CampaignSetting
 */
class app_model_CampaignSetting extends Model
{
    /**
     * Campaign Setting type for the default view
     */
    const CAMPAIGN_SETTING_DEFAULT_VIEW = 1;
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'tbl_campaign_setting';
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Save a campaign setting, create if it doesn't exist
     *
     * @param int $setting
     * @param int $campaignId
     * @param mixed $value
     */
    public static function saveSetting($setting, $campaignId, $value)
    {
        $campaignSetting = self::CampaignSetting($campaignId, $setting)->first();
        if (empty($campaignSetting)) {
            $campaignSetting = new app_model_CampaignSetting();
            $campaignSetting->campaign_id = $campaignId;
            $campaignSetting->setting = $setting;
        }
        $campaignSetting->value = $value;
        $campaignSetting->save();
    }

    /**
     * One Campaign Setting belongs to one campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaign()
    {
        return $this->belongsTo('app_model_Campaign', 'campaign_id');
    }

    public function scopeCampaignSetting(\Illuminate\Database\Eloquent\Builder $q, $campaignId, $setting)
    {
        return $q->where('campaign_id', '=', $campaignId)->where('setting', '=', $setting);
    }
}