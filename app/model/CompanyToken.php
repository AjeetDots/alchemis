<?php

class app_model_CompanyToken extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_company_tokens';
  public $timestamps = false;

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
  }

}