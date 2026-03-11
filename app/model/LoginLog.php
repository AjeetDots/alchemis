<?php

use Illuminate\Database\Eloquent\Model;

class app_model_LoginLog extends Model {

  protected $guarded = [];
  public $table = 'tbl_login_log';

  public function user()
  {
    return $this->belongsTo('app_model_User', 'user_id');
  }

}