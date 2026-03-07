<?php

class app_model_DateCharacteristic extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_object_characteristics_date';
  public $timestamps = false;

  public function characteristic()
  {
    return $this->belongsTo('app_model_Characteristic', 'characteristic_id');
  }

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
  }

}