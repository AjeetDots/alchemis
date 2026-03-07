<?php namespace Dedupe;

use Illuminate\Support\Collection;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\QueryException;

class MergeSites implements Deduper {

  public function run()
  {
    $duplicates = \app_model_CompanyDuplicate::all();

    foreach($duplicates as $d){

      // merge data into master record
      // merge company fields where missing
      $company = \app_model_Company::find($d->company_id);
      $master = \app_model_Company::find($d->merge_into);

      if(empty($master->website) && !empty($company->website)){
        $master->website = $company->website;
      }

      if(empty($master->telephone) && !empty($company->telephone)){
        $master->telephone = $company->telephone;
      }

      $master->save();

      // tables with company_id field
      $tables = [
        'tbl_posts',
        'tbl_campaign_companies_do_not_call',
        'tbl_company_notes',
        'tbl_company_tags',
        'tbl_company_tiered_characteristics',
        'tbl_company_tokens',
        'tbl_exclude',
        'tbl_filter_results',
        'tbl_include',
        'tbl_object_characteristics',
        'tbl_object_characteristics_boolean',
        'tbl_object_characteristics_date',
        'tbl_object_characteristics_text',
        'tbl_object_tiered_characteristics'
      ];

      foreach($tables as $t){
        try {
          DB::update("UPDATE $t
            SET company_id = :merge_into
            WHERE company_id = :company_id", [
            'company_id' => $d->company_id,
            'merge_into' => $d->merge_into
          ]);
        } catch (QueryException $e) {
          // echo $e;
        }
      }

      // soft delete company
      \app_model_Company::where('id', $d->company_id)
        ->update(['deleted' => 1]);

      \app_model_CompanyDuplicate::destroy($d->id);

      // update additions table to complete
      \app_model_DedupeAddition::where('company_id', $d->company_id)
        ->orWhere('company_id', $d->merge_into)
        ->update(['complete' => true]);
    }



    return $duplicates;
  }

  
}