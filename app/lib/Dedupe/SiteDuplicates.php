<?php namespace Dedupe;

use Illuminate\Support\Collection;
use Illuminate\Database\Capsule\Manager as DB;

class SiteDuplicates implements Deduper {

  private $company_ids;

  public function __construct($company_ids = null)
  {
    if($company_ids) $this->company_ids = array_unique($company_ids);
  }

  public function run()
  {
    \app_model_DedupeMismatch::truncate();
    \app_model_DedupeMatch::truncate();
    // 1. Find possible duplicates based on
    // 1st line address && postcode matching
    // similar name
    // if e.g. 1st line matches & postcode doesn't create log to check over

    $groupNum = 1;

    // get postcodes that appear multiple times
    $postcodes = $this->getPostcodes();
    foreach($postcodes as $postcode){

      $query = \app_model_Site::select('company_id', 'address_1')
        ->where('postcode', $postcode)
        ->where('deleted', 0);
      if($this->company_ids){
        $query->whereIn('company_id', $this->company_ids);
      }
      $sites = $query->get();

      // fuzzy match on addresses
      $groups = Matcher::findSimilar($sites, 'address_1', 'company_id', 0.75, true);

      foreach($groups as $m){
        // if count of 1 log
        $ids = array_diff($sites->lists('company_id'), [$m[0]['company_id']]);
        if(count($m) == 1){
          \app_model_DedupeMismatch::create([
            'company_id' => $m[0]['company_id'],
            'type' => 'addr1',
            'companies' => implode(',', $ids)
          ]);
          continue;
        }

        // if multiple then get companies
        // check to see if names are similar
        $company_ids = $m->lists('company_id');
        $companies = \app_model_Company::whereIn('id', $company_ids)
          ->where('deleted', 0)
          ->with('token')
          ->with('address')
          ->get()
          ->map(function ($c) {
            $c->tokens = $c->token->tokens;
            return $c;
          });

        $matchedCompanies = [];
        $groups = Matcher::findSimilar($companies, 'tokens', 'id');

        foreach($groups as $group){
          // group contains matching companies
          // log to DB
          foreach($group as $company){
            $matchedCompanies[] = $company;
            \app_model_DedupeMatch::create([
              'company_id' => $company->id,
              'group' => $groupNum
            ]);
          }
          $groupNum++;
        }
        // compare matchedCompanies against companies
        // where missing log.
        $unmatched = $companies->diff($matchedCompanies);
        foreach($unmatched as $c){
          $ids = array_diff($companies->lists('id'), [$c->id]);
          \app_model_DedupeMismatch::create([
            'company_id' => $c->id,
            'type' => 'name',
            'companies' => implode(',', $ids)
          ]);
        }

      }

    }

    return true;
  }

  public function getPostcodes()
  {
    $query = "SELECT s.postcode
      FROM tbl_sites s
      WHERE s.postcode != ''
      AND s.postcode != '.'
      AND s.postcode != '*'
      AND s.postcode != '***'
      AND s.postcode != '?'
      AND s.postcode != '??'
      AND s.postcode != '-'
      AND s.postcode != '..'
      AND s.postcode != 'delete'
      AND s.postcode != 'DELETE'
      AND s.postcode != ','
      AND s.postcode != 'Test'
      AND s.postcode != 'test'
      AND s.postcode != 'TBC'
      AND s.postcode != 'tba'";
    if($this->company_ids){
      $query .= " AND company_id IN (".implode(',', $this->company_ids).")";
    }
    $query .= " GROUP BY s.postcode HAVING count(*) > 1";

    $sites = new Collection(DB::select($query));

    // remove any numeric postcodes
    $sites = $sites->filter(function ($s) {
      return !is_numeric($s->postcode);
    });

    return $sites->lists('postcode');
  }
  
}