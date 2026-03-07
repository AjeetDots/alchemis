<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

class app_model_Company extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_companies';
  public $timestamps = false;
  public $incrementing = false;

  public static function boot()
  {
    parent::boot();

    self::creating(function ($company) {
      // set id
      $db = app_controller_ApplicationHelper::instance()->DB();
      $company->id = $db->nextID('tbl_companies');
    });
  }

  public function scopeWithoutDeleted($query)
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

  public function parent_company()
  {
    return $this->belongsTo('app_model_ParentCompany', 'parent_company_id');
  }

  public function address()
  {
    return $this->hasOne('app_model_Site', 'company_id');
  }

  public function posts()
  {
    $session = Auth_Session::singleton();
    $user = $session->getSessionUser();

    return $this->hasMany('app_model_Post', 'company_id')->where(function ($query) use ($user) {
      if (!empty($user['client_id'])) {
        $query->where('data_owner_id', $user['client_id']);
      } else {
        $query->whereNull('data_owner_id');
      }
    });
  }

  public function token()
  {
    return $this->hasOne('app_model_CompanyToken', 'company_id');
  }
  
  public function tags()
  {
    return $this->belongsToMany('app_model_Tag', 'tbl_company_tags', 'company_id', 'tag_id');
  }

  public function characteristics()
  {
    return $this->belongsToMany('app_model_Characteristic', 'tbl_object_characteristics', 'company_id', 'characteristic_id')
      ->leftJoin('tbl_object_characteristics_date', function ($join) {
        $join->on('tbl_object_characteristics.characteristic_id', '=', 'tbl_object_characteristics_date.characteristic_id')
          ->on('tbl_object_characteristics.company_id', '=', 'tbl_object_characteristics_date.company_id');
      })
      ->select([
        'tbl_object_characteristics_date.id as date_id',
        'name',
        'data_type',
        'value'
      ]);
  }

  public function dateCharacteristics()
  {
    return $this->hasMany('app_model_DateCharacteristic', 'company_id');
  }

  public function parents()
  {
    // array of all parents starting with oldest
    $parents = new Collection;
    $parent = $this->parent_company()->first();
    if(!$parent) return $parents;

    $parents->push($parent);
    $parents = $parents->merge($parent->parents());

    return $parents->reverse();
  }

}