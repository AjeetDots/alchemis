<?php

use app_controller_Response as Response;

ini_set('display_errors', 'On');
error_reporting(E_ERROR);

class app_command_ParentCompany extends app_command_ResourceCommand
{

  public function create()
  {

    return Response::view('parentcompany.create', [
      'counties' => ['0' => '-- select if required --'] + app_model_County::lists('name', 'id'),
      'countries' => ['0' => '-- select if required --'] + app_model_Country::lists('name', 'id'),
      'input' => [
        'country_id' => 9
      ]
    ]);
  }

  public function store()
  {
    $input = $this->request->input;

    // ajax methods...
    if($input->has('ajaxRequest')){
      return $this->handleAjaxMethod();
    }

    $data = $input->all();

    // validation
    $validator = new Validator($data, [
      'name' => 'required',
      'postcode' => 'required_with:add_address',
      'site_name' => 'required_with:add_site',
      'category_id' => 'required',
      'subcategory_id' => 'required_with:add_site'
    ]);

    if($validator->fails()){
      $messages = $validator->messages();
      return Response::view('parentcompany.create', [
        'success' => false,
        'errors' => $messages->toArray(),
        'feedback' => implode('</li><li>', $messages->all()),
        'input' => $data,
        'counties' => ['0' => '-- select if required --'] + app_model_County::lists('name', 'id'),
        'countries' => ['0' => '-- select if required --'] + app_model_Country::lists('name', 'id')
      ]);
    }

    $company = app_model_ParentCompany::create([
      'name' => $data['name'],
      'parent_company_id' => $data['parent_company_id'] ? $data['parent_company_id'] : null
    ]);


    if($input->has('add_site')){

      $site = app_model_Company::create([
        'parent_company_id' => $company->id,
        'name' => $data['site_name'],
        'telephone' => $data['site_telephone'],
        'website' => $data['site_website']
      ]);

      // duplicate address as site-address
      if($input->has('add_address')){
        app_model_Site::create([
          'company_id' => $site->id,
          'address_1' => $data['address_1'],
          'address_2' => $data['address_2'],
          'town' => $data['town'],
          'postcode' => $data['postcode'],
          'telephone' => $data['telephone'],
          'county_id' => $data['county_id'] ? $data['county_id'] : null,
          'country_id' => $data['country_id']
        ]);
      }

      if($data['category_id']){
        app_model_ParentCompanyCategory::create([
          'company_id' => $site->id,
          'tiered_characteristic_id' => $data['category_id'],
          'tier' => $data['tier']
        ]);
      }
      if($data['subcategory_id']){
        app_model_ParentCompanyCategory::create([
          'company_id' => $site->id,
          'tiered_characteristic_id' => $data['subcategory_id'],
          'tier' => $data['tier']
        ]);
      }
    }


    if($data['category_id']){
      app_model_ParentCompanyCategory::create([
        'parent_company_id' => $company->id,
        'tiered_characteristic_id' => $data['category_id'],
        'tier' => $data['tier']
      ]);
    }
    if($data['subcategory_id']){
      app_model_ParentCompanyCategory::create([
        'parent_company_id' => $company->id,
        'tiered_characteristic_id' => $data['subcategory_id'],
        'tier' => $data['tier']
      ]);
    }


    return Response::view('parentcompany.create', [
      'success' => true,
      'company' => $company
    ]);
  }

  public function handleAjaxMethod()
  {
    $input = $this->request->input;
    $data = json_decode($input->get('ajaxRequest'));
    return $this->{$data->cmd_action}();
  }

  public function updateName()
  {
    $input = $this->request->input;
    $data = json_decode($input->get('ajaxRequest'));

    $company = app_model_ParentCompany::find($data->item_id);
    $company->name = $data->name;
    $company->save();

    $response = Response::ajaxResponse();
    $response->data = [
      'item_id' => $company->id,
      'name' => $company->name,
      'cmd_action' => 'updateName'
    ];

    return Response::json($response);
  }

  public function searchByNameStartsWith()
  {
    $query = $this->request->input->get('query');
    $companies = app_model_ParentCompany::where('name', 'LIKE', $query.'%')->has('companies')->get();

    $session = Auth_Session::singleton();
    $user = $session->getSessionUser();

    // sort sites
    foreach($companies as $company){
      $company->sites = $company->child_companies();
      $company->sites->sortByDesc(function ($c) use ($user) {
        if (!empty($user['client_id'])) {
            return $c->posts()->where('data_owner_id', $user['client_id'])->count();
        } else {
            return $c->posts()->whereNull('data_owner_id')->count();
        }
      });
    }

    return Response::view('parentcompany.search', [
      'parent_companies' => $companies
    ]);
  }

  public function searchByName()
  {
    $query = $this->request->input->get('query');
    $companies = app_model_ParentCompany::where('name', $query)->has('companies')->get();

    $session = Auth_Session::singleton();
    $user = $session->getSessionUser();

    // sort sites
    foreach($companies as $company){
      $company->sites = $company->child_companies();
      $company->sites->sortByDesc(function ($c) use ($user) {
        if (!empty($user['client_id'])) {
            return $c->posts()->where('data_owner_id', $user['client_id'])->count();
        } else {
            return $c->posts()->whereNull('data_owner_id')->count();
        }
      });
    }

    return Response::view('parentcompany.search', [
      'parent_companies' => $companies
    ]);
  }

  public function autocomplete()
  {
    $query = $this->request->input->get('query');
    $companies = app_model_ParentCompany::select([
      'id',
      'name'
    ])->where('name', 'LIKE', $query.'%')
    ->limit(50)
    ->get();
    return Response::json($companies);
  }

  public function getMainCategory()
  {
    $id = $this->request->input->get('id');
    $company = app_model_ParentCompany::find($id);
    $category = $company->categories()->orderBy('tbl_object_tiered_characteristics.id')->first();
    return Response::json($category);
  }

  public function addParentCompany()
  {
    $input = $this->request->input;
    $data = $input->all();
    echo '<pre>';print_r($data);echo '</pre>';die;
    // validation
    $validator = new Validator($data, [
      'id' => 'required',
      'parent_company_id' => 'required'
    ]);

    if($validator->fails()){
      return Response::redirect(['url' => $this->request->referrer]);
    }

    $company = app_model_ParentCompany::find($data['id']);

    if(!$company->hasCircularDependency($data['parent_company_id'])){
      $company->parent_company_id = $data['parent_company_id'];
      $company->save();
    }

    return Response::redirect(['url' => $this->request->referrer]);
  }

  public function removeParentCompany()
  {
    $input = $this->request->input;
    $data = $input->all();

    $company = app_model_ParentCompany::find($data['id']);
    $company->parent_company_id = null;
    $company->save();
    return Response::redirect(['url' => $this->request->referrer]);
  }
}