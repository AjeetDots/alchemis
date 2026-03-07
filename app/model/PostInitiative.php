<?php

class app_model_PostInitiative extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_post_initiatives';
  public $timestamps = false;
  
  public static function boot()
  {
    parent::boot();

    self::creating(function ($model) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $model->id = $db->nextID('tbl_post_initiatives');
    });
  }

  public function communications()
  {
    return $this->hasMany('app_model_Communication', 'post_initiative_id');
  }

  public function post()
  {
      return $this->belongsTo('app_model_Post', 'post_id');
  }

  public function tags()
  {
      return $this->belongsToMany('app_model_Tag', 'tbl_post_initiative_tags', 'post_initiative_id', 'tag_id');
  }

}