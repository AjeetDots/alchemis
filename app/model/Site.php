<?php

class app_model_Site extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_sites';
  public $timestamps = false;
  public $incrementing = false;
  
  public static function boot()
  {
    parent::boot();

    self::creating(function ($site) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $site->id = $db->nextID('tbl_sites');
    });
  }

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
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