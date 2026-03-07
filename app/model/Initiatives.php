<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Class Initiatives
 */
class app_model_Initiatives extends Model
{
    /**
     * @var string
     */
    protected $table = 'tbl_initiatives';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * One Initiative belongs to one campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo('app_model_Campaign', 'campaign_id');
    }
    
    public function posts()
    {
        return $this->belongsToMany('app_model_Post', 'tbl_post_initiatives', 'initiative_id', 'post_id');
    }
    
    public function postInitiatives()
    {
        return $this->hasMany('app_model_PostInitiative', 'initiative_id');
    }
}