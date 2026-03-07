<?php

use app_controller_Response as Response;
use Carbon\Carbon;

ini_set('display_errors', 'On');
error_reporting(E_ERROR);

class app_command_Dedupe extends app_command_BaseCommand
{

  public function prepare()
  {
    $request = $this->request;
    $input = $request->input;

    if(!$request->action){
      $request->action = 'index';
    }
  }

  public function index()
  {
    return Response::view('dedupe.index', '');
  }

  public function prepareData()
  {
    $this->status('prepareData', 'started');
    $time_start = microtime(true);
    $data = Dedupe::prepareData();
    $time_end = microtime(true);
    $mem = System::memory();
    $this->status('prepareData', 'complete', $time_end - $time_start);
    return Response::json([
      'time' => $time_end - $time_start,
      'mem' => $mem,
      'data' => $data
    ]);
  }

  public function siteDuplicates()
  {
    // $companies = include APP_DIRECTORY . 'app/lib/dedupe/test/companies.php';
    $this->status('siteDuplicates', 'started');
    $time_start = microtime(true);
    $data = Dedupe::siteDuplicates();
    $time_end = microtime(true);
    $mem = System::memory();
    $this->status('siteDuplicates', 'complete', $time_end - $time_start);
    return Response::json([
      'time' => $time_end - $time_start,
      'mem' => $mem,
      'data' => $data
    ]);
  }

  public function siteMatchesCSV()
  {
    $matches = \app_model_DedupeMatch::with([
      'company',
      'company.address'
    ])
    ->orderBy('group')
    ->get();

    $data = [];
    $prevGroup = 1;
    foreach($matches as $m){
      if($m->group > $prevGroup) $data[] = [];
      $prevGroup = $m->group;
      $data[] = [
        'num' => count($data) + 1,
        'group' => $m->group,
        'id' => $m->company->id,
        'name' => $m->company->name,
        'addr1' => $m->company->address->address_1,
        'postcode' => $m->company->address->postcode
      ];
    }

    return Response::csv($data, 'site-duplicates.csv');
  }

  public function siteMismatchesCSV()
  {
    $mismatches = \app_model_DedupeMismatch::with([
      'company',
      'company.address'
    ])->get();

    $data = [];
    foreach($mismatches as $m){
      $data[] = [
        'num' => count($data) + 1,
        'type' => $m->type,
        'id' => $m->company->id,
        'name' => $m->company->name,
        'addr1' => $m->company->address->address_1,
        'postcode' => $m->company->address->postcode
      ];
    }

    return Response::csv($data, 'site-unmatched.csv');
  }

  public function matchesJson()
  {
    $matches = \app_model_DedupeMatch::with([
      'company',
      'company.address'
    ])
    ->orderBy('group')
    ->get();

    $data = [];
    foreach($matches as $m){
      $prevGroup = $m->group;
      $data[] = [
        'num' => count($data) + 1,
        'group' => $m->group,
        'id' => $m->company->id,
        'name' => $m->company->name,
        'addr1' => $m->company->address->address_1,
        'postcode' => $m->company->address->postcode
      ];
    }

    return Response::json($data);
  }

  public function mismatchesJson()
  {
    $input = $this->request->input;
    $page = $input->get('page');
    $search = $input->get('search');

    if(!$page) $page = 1;
    $limit = 100;
    $skip = ($page - 1) * $limit;

    $query = \app_model_DedupeMismatch::with([
      'company',
      'company.address'
    ]);

    if($search){
      $query->whereHas('company', function ($q) use ($search) {
        $q->where('name', 'LIKE', '%'.$search.'%');
      });
    }

    $mismatches = $query->skip($skip)->take($limit)->get();

    $data = [];
    foreach($mismatches as $m){
      $data[] = [
        'id' => $m->id,
        'type' => $m->type,
        'company_id' => $m->company->id,
        'name' => $m->company->name,
        'addr1' => $m->company->address->address_1,
        'postcode' => $m->company->address->postcode
      ];
    }

    return Response::json($data);
  }

  public function getMismatch()
  {
    $id = $this->request->input->get('id');

    $mismatch = \app_model_DedupeMismatch::with([
        'company',
        'company.address'
      ])
      ->find($id);

    $mismatch->matched_companies = $mismatch->matchedCompanies()
      ->with([
        'address'
      ])
      ->get();

    return Response::json($mismatch);
  }

  public function removeMismatch()
  {
    $id = $this->request->input->get('id');

    $mismatch = \app_model_DedupeMismatch::find($id);
    $mismatch->delete();

    return new Response;
  }

  public function saveAdditions()
  {
    $input = $this->request->input;
    $additions = json_decode($input->get('additions'));

    $group = \app_model_DedupeAddition::max('group');
    if(!$group) $group = 0;
    $group++;

    foreach($additions as $company_id){
      \app_model_DedupeAddition::create([
        'company_id' => $company_id,
        'group' => $group
      ]);
    }

    // delete mismatches
    \app_model_DedupeMismatch::whereIn('company_id', $additions)->delete();

    return Response::json();
  }

  public function siteMergePrepare()
  {
    $this->status('siteMergePrepare', 'started');
    $time_start = microtime(true);
    $data = Dedupe::siteMergePrepare();
    $time_end = microtime(true);
    $mem = System::memory();
    $this->status('siteMergePrepare', 'complete', $time_end - $time_start);
    return Response::csv($data);
  }

  public function mergeSites()
  {
    $this->status('mergeSites', 'started');
    $time_start = microtime(true);
    $data = Dedupe::mergeSites();
    $time_end = microtime(true);
    $mem = System::memory();
    $this->status('mergeSites', 'complete', $time_end - $time_start);
    return Response::json([
      'time' => $time_end - $time_start,
      'mem' => $mem,
      'data' => $data
    ]);
  }

  public function groupCompanies()
  {
    $this->status('groupCompanies', 'started');
    $companies = include APP_DIRECTORY . 'app/lib/dedupe/test/companies.php';
    $time_start = microtime(true);
    $data = Dedupe::groupCompanies($companies);
    $time_end = microtime(true);
    $mem = System::memory();
    $this->status('groupCompanies', 'complete', $time_end - $time_start);
    // return Response::json([
    //   'execution_time' => $time_end - $time_start,
    //   'memory' => $mem['used'],
    //   'peak_memory' => $mem['peak'],
    //   'data' => $data
    // ]);
    return Response::csv($data);
  }

  public function createCompanies()
  {
    $this->status('createCompanies', 'started');
    $time_start = microtime(true);
    $data = Dedupe::createCompanies();
    $time_end = microtime(true);
    $mem = System::memory();
    $this->status('createCompanies', 'complete', $time_end - $time_start);
    return Response::json([
      'time' => $time_end - $time_start,
      'mem' => $mem,
      'data' => $data
    ]);
  }

  public function postDuplicates()
  {
    $this->status('postDuplicates', 'started');
    $time_start = microtime(true);
    $data = Dedupe::postDuplicates();
    $time_end = microtime(true);
    $mem = System::memory();
    $this->status('postDuplicates', 'complete', $time_end - $time_start);
    //return Response::json([
    //  'time' => $time_end - $time_start,
    //  'mem' => $mem,
    //  'data' => $data
    //]);
    return Response::csv($data);
  }

  public function mergePosts()
  {
    $data = Dedupe::mergePosts();
    return Response::table($data);
  }

  public function status($name, $status, $runtime)
  {
    $dupeStatus = \app_model_DedupeStatus::where('name', $name)->first();
    if(!$dupeStatus){
      $dupeStatus = new \app_model_DedupeStatus;
    }
    $dupeStatus->name = $name;
    $dupeStatus->status = $status;
    $dupeStatus->date = Carbon::now();
    if($runtime){
      // convert to minutes
      $dupeStatus->runtime = round($runtime / 60, 2);
    }
    $dupeStatus->save();
  }

  public function statusJson()
  {
    $status = \app_model_DedupeStatus::all()->keyBy('name');
    if($status->has('siteDuplicates')){
      $status['siteDuplicates']['match_count'] = \app_model_DedupeMatch::count();
      $status['siteDuplicates']['mismatch_count'] = \app_model_DedupeMismatch::count();
      $status['siteDuplicates']['addition_count'] = \app_model_DedupeAddition::where('complete', false)->count();
    }
    return Response::json($status);
  }

  public function runCommand()
  {
    $input = $this->request->input;
    $command = $input->get('command');

    Command::run('Dedupe/' . $command);
    return new Response;
  }

  public function test()
  {
    $result = Command::runSync('Dedupe/siteMatchesCSV');
    return Response::json($result);
  }

}