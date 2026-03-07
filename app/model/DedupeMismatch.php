<?php

class app_model_DedupeMismatch extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_dedupe_mismatch';
  public $timestamps = false;

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
  }

  public function matchedCompanies()
  {
    return app_model_Company::whereIn('id', explode(',', $this->companies));
  }

}