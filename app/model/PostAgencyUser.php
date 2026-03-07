<?php

class app_model_PostAgencyUser extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_post_agency_users';
  public $timestamps = false;
  public $incrementing = false;
  
  public static function boot()
  {
    parent::boot();

    self::creating(function ($site) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $site->id = $db->nextID('tbl_post_agency_users');
    });
  }

  public function communication()
  {
    return $this->belongsTo('app_model_Communication', 'communication_id');
  }

}