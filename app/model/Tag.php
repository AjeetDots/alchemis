<?php

class app_model_Tag extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_tags';
  public $timestamps = false;
  public $incrementing = false;
  
  public static function boot()
  {
    parent::boot();

    self::creating(function ($site) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $site->id = $db->nextID('tbl_tags');
    });
  }

  public function posts()
  {
    return $this->belongsToMany('app_model_Post', 'tbl_post_tags', 'tag_id', 'post_id');
  }
  
  public function companies()
  {
    return $this->belongsToMany('app_model_Company', 'tbl_company_tags', 'tag_id', 'company_id');
  }

}