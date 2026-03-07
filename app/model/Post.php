<?php

class app_model_Post extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_posts';
  public $timestamps = false;
  public $incrementing = false;
  
  public static function boot()
  {
    parent::boot();

    self::creating(function ($site) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $site->id = $db->nextID('tbl_posts');
    });
  }

  public function company()
  {
    return $this->belongsTo('app_model_Company', 'company_id');
  }
  
  public function contact()
  {
    return $this->hasOne('app_model_Contact', 'post_id');
  }

  public function initiatives()
  {
    return $this->hasMany('app_model_PostInitiative', 'post_id');
  }
  
  public function tags()
  {
    return $this->belongsToMany('app_model_Tag', 'tbl_post_tags', 'post_id', 'tag_id');
  }

  public function notesList()
  {
    return $this->hasMany('app_model_PostNote', 'post_id');
  }

  public function postSites()
  {
    return $this->hasMany('app_model_PostSite', 'post_id');
  }

  public function decisionMakers()
  {
    return $this->hasMany('app_model_PostDecisionMaker', 'post_id');
  }

  public function agencyUsers()
  {
    return $this->hasMany('app_model_PostAgencyUser', 'post_id');
  }

}