<?php

class app_model_PostNote extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_post_notes';
  public $timestamps = false;
  public $incrementing = false;
  
  public static function boot()
  {
    parent::boot();

    self::creating(function ($site) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $site->id = $db->nextID('tbl_post_notes');
    });
  }

  public function post()
  {
    return $this->belongsTo('app_model_Post', 'post_id');
  }

}