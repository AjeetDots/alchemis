<?php

class app_model_User extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_rbac_users';
  public $timestamps = false;

  public static function boot()
  {
    parent::boot();

    self::creating(function ($obj) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $obj->id = $db->nextID($this->table);
    });
  }

  public function client()
  {
    return $this->belongsTo('app_model_Clients', 'client_id');
  }

}