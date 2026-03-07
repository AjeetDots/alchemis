<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Class app_model_CampaignCharacteristic
 */
class app_model_CampaignCharacteristic extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var string
     */
    protected $table = 'tbl_campaign_characteristics';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * One Campaign characteristic belongs to one characteristic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function characteristic()
    {
        return $this->belongsTo('app_model_Characteristic', 'characteristic_id');
    }

    /**
     * One Campaign characteristic belongs to one campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo('app_model_Campaign', 'campaign_id');
    }

    /**
     * Get the display name for this campaign char
     *
     * @return string
     */
    public function displayText()
    {

        return $this->campaign->client->name;
    }
}