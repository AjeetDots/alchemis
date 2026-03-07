<?php

class app_model_CharacteristicElement extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_characteristic_elements';
  public $timestamps = false;

  public function characteristic()
  {
    return $this->belongsTo('app_model_Characteristic', 'characteristic_id');
  }

}