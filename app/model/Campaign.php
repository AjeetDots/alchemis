<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Class app_model_Campaign
 */
class app_model_Campaign extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var string
     */
    protected $table = 'tbl_campaigns';

    /**
     * One Campaign belongs to one client
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo("app_model_Clients", "client_id");
    }

    /**
     * One Campaign can have many CampaignSettings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaignSetting()
    {
        return $this->hasMany('app_model_CampaignSetting', 'campaign_id');
    }

    /**
     * Get a campaign setting based on setting identifier
     *
     * @param int $setting | The identifier for the setting
     * @return null|mixed
     */
    public function getCampaignSetting($setting)
    {
        $setting = app_model_CampaignSetting::CampaignSetting($this->id, $setting)->first();
        return !empty($setting) ? $setting->value : null;
    }

    /**
     * One campaign (CAN?) have many initiatives
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function initiative()
    {
        return $this->hasOne('app_model_Initiatives', 'campaign_id');
    }
}