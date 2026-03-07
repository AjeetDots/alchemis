<?php

class app_model_ParentCompanyCategory extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_object_tiered_characteristics';
  public $timestamps = false;

  public $incrementing = false;

  public static function boot()
  {
    parent::boot();

    self::creating(function ($category) {
      // set id
      // $db = app_controller_ApplicationHelper::instance()->DB();
      // $category->id = $db->nextID('tbl_object_tiered_characteristics');
    });
  }


}