<?php

class app_model_PostDecisionMaker extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_post_decision_makers';
  public $timestamps = false;
  public $incrementing = false;
  
  public static function boot()
  {
    parent::boot();

    self::creating(function ($site) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $site->id = $db->nextID('tbl_post_decision_makers');
    });
  }

  public function communication()
  {
    return $this->belongsTo('app_model_Communication', 'communication_id');
  }

}