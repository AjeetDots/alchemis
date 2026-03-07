<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

class app_model_TpsStatus extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_tps_status';
  public $timestamps = true;

  /* public function scopeWithoutDeleted($query)
  {
    return $query->where(function ($query) {
      return $query->whereNull('deleted')
        ->orWhere('deleted', 0);
    });
  }

  public function scopeOnlyDeleted($query)
  {
    return $query->where('deleted', 1);
  }
 */

//   public function posts()
//   {
//     $session = Auth_Session::singleton();
//     $user = $session->getSessionUser();
//     //  $user['client_id']
//   }


}