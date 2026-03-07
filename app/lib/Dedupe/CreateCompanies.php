<?php namespace Dedupe;

use Illuminate\Support\Collection;

class CreateCompanies implements Deduper {

  public function run()
  {
    $companies = \app_model_DedupeParentCompany::all();
    
    foreach($companies as $c){
      // check to see if sites already have a parent company
      $site_ids = explode(',', $c->company_ids);
      $sites = \app_model_Company::whereIn('id', $site_ids)
        ->whereNull('parent_company_id')
        ->where('deleted', 0)
        ->get();
      
      if(!$sites->count()) continue;
      
      // create parent company - if atleast 1 site
      $company = \app_model_ParentCompany::create([
        'name' => $c->parent
      ]);
      
      // update sites with the parent id
      foreach($sites as $s){
        $s->parent_company_id = $company->id;
        $s->save();
      }
      
    }

  }

}