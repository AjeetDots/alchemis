<?php

use Illuminate\Database\Eloquent\Model;

class app_model_Whitelist extends Model {

  protected $guarded = [];
  public $table = 'tbl_whitelist';

  public static function boot()
  {
    parent::boot();

    static::saving(function ($whitelist) {
      if (!empty($whitelist->ip)) {
        $whitelist->ip_int = ip2long($whitelist->ip);
      }
    });
  }

  public function logins()
  {
    // Link whitelist entries to login log rows by IP address
    return $this->hasMany('app_model_LoginLog', 'ip', 'ip');
  }

}