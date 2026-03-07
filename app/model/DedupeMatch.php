<?php

class app_model_DedupeMatch extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_dedupe_match';
  public $timestamps = false;

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
  }

}