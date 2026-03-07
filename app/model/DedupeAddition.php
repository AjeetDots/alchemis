<?php

class app_model_DedupeAddition extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_dedupe_additions';
  public $timestamps = false;

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
  }

}