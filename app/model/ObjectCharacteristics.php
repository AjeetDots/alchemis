<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Class app_model_ObjectCharacteristics
 */
class app_model_ObjectCharacteristics extends Model
{

    /**
     * @var string
     */
    protected $table = 'tbl_object_characteristics';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;
    public $incrementing = false;
    
    public static function boot()
    {
      parent::boot();

      self::creating(function ($obj) {
        // set id
        $db = app_controller_ApplicationHelper::instance()->DB();
        $obj->id = $db->nextID('tbl_object_characteristics');
      });
    }

    /**
     * One object characteristic belongs to one characteristic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function characteristic()
    {
        return $this->belongsTo('app_model_Characteristic', 'characteristic_id');
    }

    /**
     * One object characteristic belongs to one company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('app_model_Company', 'company_id');
    }
}