<?php namespace Dedupe;

use Illuminate\Support\Collection;
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

class SiteMergePrepare implements Deduper {

  public function run()
  {
    \app_model_CompanyDuplicate::truncate();

    $companies = [];
    $companies = array_merge($companies, $this->runMatches());
    $companies = array_merge($companies, $this->runAdditions());

    return $companies;
  }

  public function runMatches()
  {
    $maxGroup = \app_model_DedupeMatch::max('group');
    $all_companies = [];

    $nondupes = include APP_DIRECTORY . 'app/lib/Dedupe/nondupes.php';

    for ($i=1; $i <= $maxGroup; $i++) {
      
      $company_ids = \app_model_DedupeMatch::where('group', $i)
        ->get()
        ->lists('company_id');

      $companies = \app_model_Company::whereIn('id', $company_ids)
        ->with([
          'address',
          'characteristics'
        ])
        ->get();

      foreach($companies as $company){
        $company = $this->getCompanyData($company);
      }

      // sort - master should be first record
      $companies = $this->sortCompanies($companies)->reverse()->values();

      // output data
      $output = [];

      // merge anything after first record into master
      $master_id = $companies[0]->id;
      foreach($companies as $key => $company){
        $output[] = [
          'master' => $key == 0 ? 'master' : 'dupe',
          'id' => $company->id,
          'name' => $company->name,
          'meeting' => $company->data['meeting'] ? $company->data['meeting']->toDateTimeString() : null,
          'cleaned_at' => $company->data['cleaned'] ? $company->data['cleaned']->toDateTimeString() : null,
          'effective_call' => $company->data['effective_call'] ? $company->data['effective_call']->toDateTimeString() : null,
          'call_frequency' => $company->data['call_frequency'],
          'last_communication' => $company->data['last_communication'] ? $company->data['last_communication']->toDateTimeString() : null,
          'address_id' => $company->data['address_id'],
          'post_count' => $company->data['post_count']
        ];

        // ignore master
        if($key == 0) continue;

        // if master -> company is in nondupes ignore
        if(array_key_exists($master_id, $nondupes)){
          if(array_search($company->id, $nondupes[$master_id]) !== false){
            // remove from output
            array_pop($output);
            continue;
          }
        }

        // if company id already exists then don't add.
        $c = \app_model_CompanyDuplicate::where('company_id', $company->id)->first();
        if($c) continue;
        \app_model_CompanyDuplicate::create([
          'company_id' => $company->id,
          'merge_into' => $master_id
        ]);
      }

      if(count($output) > 1){
        $all_companies = array_merge($all_companies, $output);
        $all_companies[] = [];
      }
    }

    return $all_companies;
  }

  public function runAdditions()
  {
    $maxGroup = \app_model_DedupeAddition::where('complete', false)->max('group');
    $minGroup = \app_model_DedupeAddition::where('complete', false)->min('group');
    $all_companies = [];

    $nondupes = include APP_DIRECTORY . 'app/lib/Dedupe/nondupes.php';

    for ($i=$minGroup; $i <= $maxGroup; $i++) {
      
      $company_ids = \app_model_DedupeAddition::where('group', $i)
        ->get()
        ->lists('company_id');

      $companies = \app_model_Company::whereIn('id', $company_ids)
        ->with([
          'address',
          'characteristics'
        ])
        ->get();

      foreach($companies as $company){
        $company = $this->getCompanyData($company);
      }

      // sort - master should be first record
      $companies = $this->sortCompanies($companies)->reverse()->values();

      // output data
      $output = [];

      // merge anything after first record into master
      $master_id = $companies[0]->id;
      foreach($companies as $key => $company){
        $output[] = [
          'master' => $key == 0 ? 'master' : 'dupe',
          'id' => $company->id,
          'name' => $company->name,
          'meeting' => $company->data['meeting'] ? $company->data['meeting']->toDateTimeString() : null,
          'cleaned_at' => $company->data['cleaned'] ? $company->data['cleaned']->toDateTimeString() : null,
          'effective_call' => $company->data['effective_call'] ? $company->data['effective_call']->toDateTimeString() : null,
          'call_frequency' => $company->data['call_frequency'],
          'last_communication' => $company->data['last_communication'] ? $company->data['last_communication']->toDateTimeString() : null,
          'address_id' => $company->data['address_id'],
          'post_count' => $company->data['post_count']
        ];

        // ignore master
        if($key == 0) continue;

        // if master -> company is in nondupes ignore
        if(array_key_exists($master_id, $nondupes)){
          if(array_search($company->id, $nondupes[$master_id]) !== false){
            // remove from output
            array_pop($output);
            continue;
          }
        }

        // if company id already exists then don't add.
        $c = \app_model_CompanyDuplicate::where('company_id', $company->id)->first();
        if($c) continue;
        \app_model_CompanyDuplicate::create([
          'company_id' => $company->id,
          'merge_into' => $master_id
        ]);
      }

      if(count($output) > 1){
        $all_companies = array_merge($all_companies, $output);
        $all_companies[] = [];
      }
    }

    return $all_companies;
  }

  public function getCompanyData($company)
  {
    $data = [];

    // 1. 1 Company Cleaned At
    $cleaned = $company->characteristics->filter(function ($c) {
      return $c->name == '1 Company Cleaned At';
    })->first()->value;
    $data['cleaned'] = $cleaned ? new Carbon($cleaned) : null;

    // 2. Most recent effective call
    $effective_call = DB::selectOne(
      'select MAX(co.communication_date) as date
      from tbl_communications co
      join tbl_post_initiatives pi
      on co.post_initiative_id = pi.id
      join tbl_posts p
      on p.id = pi.post_id
      where p.company_id = :company_id
      and co.is_effective = 1
      group by p.company_id', [
        'company_id' => $company->id
      ]
    )['date'];
    $data['effective_call'] = $effective_call ? new Carbon($effective_call) : null;

    // 3. Call frequency?
    $data['call_frequency'] = (int) DB::selectOne(
      'select COUNT(co.id) as count
      from tbl_communications co
      join tbl_post_initiatives pi
      on co.post_initiative_id = pi.id
      join tbl_posts p
      on p.id = pi.post_id
      where p.company_id = :company_id
      group by p.company_id', [
        'company_id' => $company->id
      ]
    )['count'];

    // 4. Last communication date
    $last_communication = DB::selectOne(
      'select MAX(co.communication_date) as date
      from tbl_communications co
      join tbl_post_initiatives pi
      on co.post_initiative_id = pi.id
      join tbl_posts p
      on p.id = pi.post_id
      where p.company_id = :company_id
      group by p.company_id', [
        'company_id' => $company->id
      ]
    )['date'];
    $data['last_communication'] = $last_communication ? new Carbon($last_communication) : null;

    // 5. Address id
    $data['address_id'] = (int) $company->address->id;

    // 6. Site with most posts
    $data['post_count'] = $company->posts()->count();

    // most recent meeting set
    $meeting = DB::selectOne(
      "select MAX(co.communication_date) as date
      from tbl_communications co
      join tbl_post_initiatives pi
      on co.post_initiative_id = pi.id
      join tbl_posts p
      on p.id = pi.post_id
      where p.company_id = :company_id
      and co.status_id IN (12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27)
      and co.communication_date >= '2013-01-01'
      group by p.company_id", [
        'company_id' => $company->id
      ]
    )['date'];
    $data['meeting'] = $meeting ? new Carbon($meeting) : null;

    $company->data = $data;

    return $company;
  }

  public function sortCompanies($companies)
  {
    return $companies->sort(function ($a, $b) {

      // 1. Company Cleaned At
      if($a->data['cleaned'] && $b->data['cleaned']){
        return ($a->data['cleaned']->gt($b->data['cleaned'])) ? 1 : -1;
      }else if($a->data['cleaned']){
        return 1;
      }else if($b->data['cleaned']){
        return -1;
      }

      // 2. Most recent meeting set
      if($a->data['meeting'] && $b->data['meeting']){
        return ($a->data['meeting']->gt($b->data['meeting'])) ? 1 : -1;
      }else if($a->data['meeting']){
        return 1;
      }else if($b->data['meeting']){
        return -1;
      }

      // 3. Site with most posts
      if($a->data['post_count'] > $b->data['post_count']){
        return 1;
      }else if($a->data['post_count'] < $b->data['post_count']){
        return -1;
      }

      // 4. Most recent effective call
      if($a->data['effective_call'] && $b->data['effective_call']){
        return ($a->data['effective_call']->gt($b->data['effective_call'])) ? 1 : -1;
      }else if($a->data['effective_call']){
        return 1;
      }else if($b->data['effective_call']){
        return -1;
      }

      // 5. Call frequency
      if($a->data['call_frequency'] > $b->data['call_frequency']){
        return 1;
      }else if($a->data['call_frequency'] < $b->data['call_frequency']){
        return -1;
      }

      // 6. Last communication date
      if($a->data['last_communication'] && $b->data['last_communication']){
        return ($a->data['last_communication']->gt($b->data['last_communication'])) ? 1 : -1;
      }else if($a->data['last_communication']){
        return 1;
      }else if($b->data['last_communication']){
        return -1;
      }

      // 7. Address id - earliest first
      if($a->data['address_id'] < $b->data['address_id']){
        return 1;
      }else if($a->data['address_id'] > $b->data['address_id']){
        return -1;
      }

      return 0;
    });
  }
}