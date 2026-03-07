<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class app_model_ParentCompanyAddress extends Illuminate\Database\Eloquent\Model {

  use SoftDeletingTrait;

  protected $guarded = [];
  public $table = 'tbl_parent_company_address';

  public function company()
  {
    return $this->belongsTo('app_model_ParentCompany', 'parent_company_id');
  }

  public function county()
  {
    return $this->belongsTo('app_model_County', 'county_id');
  }

  public function country()
  {
    return $this->belongsTo('app_model_Country', 'country_id');
  }

}