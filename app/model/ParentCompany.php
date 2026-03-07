<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Collection;

class app_model_ParentCompany extends Illuminate\Database\Eloquent\Model {

  use SoftDeletingTrait;
  
  protected $guarded = [];
  public $table = 'tbl_parent_company';

  public function address()
  {
    return $this->hasOne('app_model_ParentCompanyAddress', 'parent_company_id');
  }

  public function parent_companies()
  {
    return $this->hasMany('app_model_ParentCompany', 'parent_company_id');
  }

  public function parent()
  {
    return $this->belongsTo('app_model_ParentCompany', 'parent_company_id');
  }

  public function companies()
  {
    return $this->hasMany('app_model_Company', 'parent_company_id');
  }
  
  public function categories()
  {
    return $this->belongsToMany('app_model_Category', 'tbl_object_tiered_characteristics', 'parent_company_id', 'tiered_characteristic_id')
      ->where('tier', 0);
  }
  
  public function subcategories()
  {
    return $this->belongsToMany('app_model_Category', 'tbl_object_tiered_characteristics', 'parent_company_id', 'tiered_characteristic_id')
      ->where('tier', '!=', 0);
  }

  public function parents()
  {
    // Collection of all parents starting with oldest
    $parents = new Collection;

    $parent = $this->parent()->first();
    if(!$parent) return $parents;

    $parents->push($parent);
    // recursion
    $parents = $parents->merge($parent->parents());

    return $parents;
  }

  public function child_parent_companies()
  {
    // Collection of all child companies
    $children = $this->parent_companies()->get();
    if(!$children) return [];

    $allChildren = $children; //collection

    foreach($children as $child){
      $allChildren = $allChildren->merge($child->child_parent_companies());
    }

    return $allChildren;
  }

  public function child_companies()
  {
    $child_companies = $this->companies()->withoutDeleted()->get();
    $companies = $this->child_parent_companies();
    foreach($companies as $c){
      $child_companies = $child_companies->merge($c->companies()->withoutDeleted()->get());
    }
    return $child_companies;
  }

  public function hasCircularDependency($id)
  {
    // check if adding the company will give a circular dependency
    $company = self::find($id);

    // check for circular dependency
    $companies = [$this];
    if($company) $companies[] = $company;
    $child_companies = $this->child_parent_companies();
    $parent_companies = $company->parents();
    $companies = array_merge($companies, $parent_companies->all(), $child_companies->all());
    
    $dupes = [];
    foreach($companies as $c){
      if(++$dupes[$c->id] > 1) return true;
    }

    return false;
  }

}