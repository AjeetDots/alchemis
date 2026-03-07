<?php

class app_model_CompanyDuplicate extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_company_duplicates';
  public $timestamps = false;

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
  }

  public function mergeCompany()
  {
    return $this->belongsTo('app_model_Company', 'merge_into');
  }

}