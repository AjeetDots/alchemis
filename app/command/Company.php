<?php

use app_controller_Response as Response;
use League\Csv\Reader;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

ini_set('display_errors', 'On');
error_reporting(E_ERROR);

class app_command_Company extends app_command_ResourceCommand
{

  public function autocomplete()
  {
    $query = $this->request->input->get('query');
    $companies = app_model_Company::select([
      'id',
      'name'
    ])->where('name', 'LIKE', $query.'%')
    ->withoutDeleted()
    ->limit(50)
    ->get();
    return Response::json($companies);
  }
  
  public function addParentCompany()
  {
    $input = $this->request->input;
    $data = $input->all();
    
    // validation
    $validator = new Validator($data, [
      'id' => 'required',
      'parent_company_id' => 'required'
    ]);

    if($validator->fails()){
      return Response::redirect(['url' => $this->request->referrer]);
    }

    $company = app_model_Company::find($data['id']);
    $company->parent_company_id = $data['parent_company_id'];
    $company->save();

    return Response::redirect(['url' => $this->request->referrer]);
  }
  
  public function import()
  {
    // ./command run Company/import file:ALCHEMIS03040X.csv
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    
    $file = APP_DIRECTORY . $this->request->input->get('file');
    $tagName = $this->request->input->get('tag');
    $tag = app_model_Tag::create([
      'value' => 'Bowen Craggs New York',
      'category_id' => 2
    ]);
    $postTag = app_model_Tag::create([
        'value' => 'BCNewYork',
        'category_id' => 2
    ]);
    
    $reader = Reader::createFromPath($file);
    $reader = $reader->setOffset(1)->fetchAll();
    $companies = [];
    foreach ($reader as $index => $row) {
        $r = [
            'name' => trim($row[0]),
            'addr1' => trim($row[1]),
            'addr2' => null,
            'addr3' => null,
            'addr4' => null,
            'town' => trim($row[2]),
            'postcode' => trim($row[3]),
            'phone' => null,
            'website' => null,
            'email' => null,
            'post_name' => trim($row[7]),
            'post_title' => trim($row[8]),
            'post_phone' => trim($row[5]),
            'post_email' => trim($row[9])
        ];
        if(empty($r['name'])) continue;
        if (!isset($companies[$r['name']])) $companies[$r['name']] = new Collection;
        $companies[$r['name']]->push($r);
    }
    
    foreach ($companies as $name => $sites) {
      $sites = $sites->groupBy('postcode')->toArray();
      $companies[$name] = $sites;
      $company = app_model_ParentCompany::create(['name' => $name]);
      $createdSites = [];
      foreach ($sites as $postcode => $posts) {
        if (!isset($createdSites[$postcode])) {
          // create site
          $site = app_model_Company::create([
            'parent_company_id' => $company->id,
            'name' => $name,
          ]);
          // address
          app_model_Site::create([
            'company_id' => $site->id,
            'address_1' => $posts[0]['addr1'],
            'postcode' => $posts[0]['postcode'],
            'town' => $posts[0]['town']
          ]);
          // tag
          app_model_CompanyTag::create([
            'company_id' => $site->id,
            'tag_id' => $tag->id
          ]);
          $createdSites[$postcode] = $site;
        }
        $site = $createdSites[$postcode];
        foreach ($posts as $post) {
            // create post & contact
            $p = app_model_Post::create([
              'company_id' => $site->id,
              'job_title' => $post['post_title'],
              'telephone_1' => $post['post_phone']
            ]);
            $names = preg_split('/\s+/', $post['post_name']);
            app_model_Contact::create([
              'post_id' => $p->id,
              'email' => $post['post_email'],
              'first_name' => $names[0],
              'surname' => $names[1],
              'full_name' => $names[0] . ' ' . $names[1]
            ]);
            app_model_PostTag::create([
                'post_id' => $p->id,
                'tag_id' => $postTag->id
            ]);
          }
      }
    }
    
    return Response::dump('done');
  }

}