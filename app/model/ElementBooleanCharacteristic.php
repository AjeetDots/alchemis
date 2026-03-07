<?php

class app_model_ElementBooleanCharacteristic extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_object_characteristic_elements_boolean';
  public $timestamps = false;

  public function object_characteristic()
  {
    return $this->belongsTo('app_model_ObjectCharacteristics', 'object_characteristic_id');
  }

}