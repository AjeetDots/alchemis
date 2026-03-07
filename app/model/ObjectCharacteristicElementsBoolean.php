<?php

use Illuminate\Database\Eloquent\Model;

class app_model_ObjectCharacteristicElementsBoolean extends Model
{
    protected $table = 'tbl_object_characteristic_elements_boolean';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public static function boot()
    {
      parent::boot();

      self::creating(function ($obj) {
        // set id
        $db = app_controller_ApplicationHelper::instance()->DB();
        $obj->id = $db->nextID('tbl_object_characteristic_elements_boolean');
      });
    }
}