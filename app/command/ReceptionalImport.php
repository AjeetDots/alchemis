<?php

use app_controller_Response as Response;
use League\Csv\Reader;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;
use Dedupe\Tokenizer;
use Dedupe\Matcher;
use webd\language\StringDistance;

ini_set('display_errors', 'On');
error_reporting(E_ERROR);

class app_command_ReceptionalImport extends app_command_BaseCommand
{
  
  public function import()
  {
    // ./command run ReceptionalImport/import env:aws
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $time_start = microtime();
    $file = APP_DIRECTORY . 'data/imports/candy_banners_import.csv';
    $reader = Reader::createFromPath($file);
    $reader = $reader->setOffset(1)->fetchAll();
    
    $createTags = [
      'Candy Banners Boston import jun17',
    ];
    
    $createdCount = 0;
    $totalCount = 0;
    
    $tags = [];
    foreach ($createTags as $t) {
      $tag = app_model_Tag::where('value', $t)->where('category_id', 2)->first();
      if (!$tag) {
        $tag = app_model_Tag::create([
          'value' => $t,
          'category_id' => 2
        ]);
      }
      $tags[] = $tag;
    }
    
    $postTagValue = 'Candy Banners Boston import jun17';
    $postTag = app_model_Tag::where('value', $postTagValue)->where('category_id', 2)->first();
    if (!$postTag) {
      $postTag = app_model_Tag::create([
        'value' => $postTagValue,
        'category_id' => 2
      ]);
    }
    
    foreach ($reader as $index => $row) {
    //   var_dump($row);
    //   die;
    //   if ($index == 0) continue;
      $r = (object) [
        'company' => trim($row[0]),
        // 'address1' => trim($row[2]),
        // 'address2' => trim($row[3]),
        // 'address3' => trim($row[4]),
        // 'address4' => '',
        // 'postcode' => trim($row[7]),
        // 'town' => trim($row[5]),
        // 'county' => trim($row[6]),
        // 'area' => '',
        'phone' => trim($row[2]),
        'fax' => '',
        'email' => '',
        'website' => trim($row[1]),
        // 'num_employees' => trim($row[21]),
        // 'tps' => trim($row[9]),
        // 'ctps' => trim($row[10]),
        
        // 'title' => trim($row[11]),
        'forename' => trim($row[3]),
        'surname' => trim($row[4]),
        'job_title' => trim($row[5]),
        'post_email' => trim($row[6]),
        'post_phone' => trim($row[2])
      ];
      
      // try match to existing site
      $site = $this->findSite($r->company);
      
      if (!$site) {
        // if site not exist create and create company
        $company = app_model_ParentCompany::create(['name' => $r->company]);
        $site = app_model_Company::create([
          'parent_company_id' => $company->id,
          'name' => $r->company,
          'telephone' => $r->phone,
          'website' => $r->website
        ]);
        
        // create addresses
        // $county_id = null;
        // if ($r->county == 'London') {
        //   $county_id = 190; // Hardcode for London
        // } else {
        //   $county = app_model_County::where('name', $r->county)->first();
        //   if ($county) $county_id = $county->id;
        // }
        // // figure out which address lines to use
        // $lines = array_filter([$r->address1, $r->address2, $r->address3, $r->address4]);
        // $l = [];
        // switch (count($lines)) {
        //   case 4:
        //     // e.g. charity, building, street, town
        //     $l[] = $lines[1]; // building
        //     $l[] = $lines[2]; // street
        //     break;
        //   default:
        //     // e.g. building, street, town
        //     $l[] = isset($lines[0]) ? $lines[0] : ''; // building
        //     $l[] = isset($lines[1]) ? $lines[1] : ''; // street
        //     break;
        // }
        // $address = [
        //   'address_1' => isset($l[0]) ? $l[0] : '',
        //   'address_2' => isset($l[1]) ? $l[1] : '',
        //   'town' => $r->town,
        //   'postcode' => $r->postcode,
        //   'telephone' => $r->phone,
        //   'county_id' => $county_id,
        //   'country_id' => 9 // UK
        // ];
        // app_model_ParentCompanyAddress::create($address + ['parent_company_id' => $company->id]);
        // app_model_CompanyAddress::create($address + ['company_id' => $site->id]);
        
        $createdCount++;
      }
      $totalCount++;
      
      // add phone, website only if missing on current site
    //   if (empty($site->telephone)) $site->telephone = $r->phone;
    //   if (empty($site->website)) $site->website = $r->website;
      // 
    //   // tps/ctps
    //   if ($r->tps == 'Y' || $r->ctps == 'Y') {
    //     $site->telephone_tps = 1;
    //   }
    //   $site->save();
      
      // add tags to site
    //   $siteTags = $tags;
    //   if (!empty($r->employees)) {
    //     $t = 'Receptional Data Total Employees ' . $r->employees;
    //     $tag = app_model_Tag::where('value', $t)->where('category_id', 2)->first();
    //     if (!$tag) {
    //       $tag = app_model_Tag::create([
    //         'value' => $t,
    //         'category_id' => 2
    //       ]);
    //     }
    //     $siteTags[] = $tag;
    //   }
      

      // add brands to site
      // foreach ($r->brands as $brand) {
      //   if (!empty($brand)) {
      //     $brand = trim($brand);
      //     $tag = app_model_Tag::where('value', $brand)->where('category_id', 1)->first();
      //     if (!$tag) {
      //       $tag = app_model_Tag::create([
      //         'value' => $brand,
      //         'category_id' => 1
      //       ]);
      //     }
      //     $siteTags[] = $tag;
      //   }
      // }
      
      // add tags to site
      foreach ($tags as $tag) {
        try {
          $hasTag = app_model_CompanyTag::where('company_id', $site->id)->where('tag_id', $tag->id)->first();
          if (!$hasTag) {
            app_model_CompanyTag::create([
              'company_id' => $site->id,
              'tag_id' => $tag->id
            ]);
          }
        } catch (Exception $e) {
          var_dump($e->getMessage());
        }
      }
      
      // add categories to site
    //   $categories = [[
    //     'tiered_characteristic_id' => 535, // Charities
    //     'tier' => 0,
    //     'company_id' => $site->id
    //   ], [
    //     'tiered_characteristic_id' => 595, // Misc Charities
    //     'tier' => 1,
    //     'company_id' => $site->id
    //   ]];
    //   foreach ($categories as $c) {
    //     try {
    //       $has = app_model_ObjectTieredCharacteristic::where('tiered_characteristic_id', $c->tiered_characteristic_id)
    //       ->where('tier', $c->tier)
    //       ->where('company_id', $site->id)
    //       ->first();
    //       if (!$has) {
    //         app_model_ObjectTieredCharacteristic::create($c);
    //       }
    //     } catch (Exception $e) {
    //       // oh well
    //     }
    //     
    //   }
        
        // num employees
        // if ($r->num_employees && is_numeric($r->num_employees)) {
        //     $characteristic_element_id = null;
        //     if ($r->num_employees <= 5) {
        //         $characteristic_element_id = 51;
        //     } else if ($r->num_employees <= 10) {
        //         $characteristic_element_id = 50;
        //     } else if ($r->num_employees <= 25) {
        //         $characteristic_element_id = 55;
        //     } else if ($r->num_employees <= 50) {
        //         $characteristic_element_id = 56;
        //     } else if ($r->num_employees <= 100) {
        //         $characteristic_element_id = 54;
        //     } else if ($r->num_employees <= 150) {
        //         $characteristic_element_id = 52;
        //     } else if ($r->num_employees <= 200) {
        //         $characteristic_element_id = 57;
        //     } else if ($r->num_employees > 200) {
        //         $characteristic_element_id = 53;
        //     }
        // }
        
        // if ($characteristic_element_id) {
        //     try {
        //         // insert into tbl_object_characteristics characteristic_id = 4, company_id = $site->id
        //         $object = app_model_ObjectCharacteristics::create([
        //             'characteristic_id' => 4,
        //             'company_id' => $site->id
        //         ]);
        //         
        //         // insert into tbl_object_characteristic_elements_boolean object_characteristic_id = (id above) characteristic_element_id = (matched id)
        //         app_model_ObjectCharacteristicElementsBoolean::create([
        //             'object_characteristic_id' => $object->id,
        //             'characteristic_element_id' => $characteristic_element_id,
        //             'value' => 1
        //         ]);
        //     } catch (Exception $e) {
        //         var_dump($e->getMessage());
        //     }
        //     
        // }
        
      
      // find post/contact within site - based on name
      $posts = DB::select("
        select p.id, c.id as contact_id, c.first_name, c.surname
        from tbl_contacts c
        join tbl_posts p on p.id = c.post_id 
        where p.company_id = :company_id
        and lower(c.first_name) = :first_name
        and lower(c.surname) = :surname
        and p.deleted = 0
      ", [
        'company_id' => $site->id,
        'first_name' => strtolower(trim($r->forename)),
        'surname' => strtolower(trim($r->surname))
      ]);
      if (!count($posts)) {
        $post = app_model_Post::create([
          'company_id' => $site->id,
          'job_title' => $r->job_title ? $r->job_title : 'Unknown',
          'notes' => '',
          'telephone_1' => $r->post_phone
        ]);
        
        $contact = app_model_Contact::create([
          'post_id' => $post->id,
          'title' => $r->title,
          'first_name' => $r->forename,
          'surname' => $r->surname,
          'email' => $r->post_email,
          'full_name' => implode(' ', [$r->title, $r->forename, $r->surname])
        ]);
      } else {
        $post = app_model_Post::find($posts[0]['id']);
        $contact = app_model_Contact::find($posts[0]['contact_id']);
        if (empty($post->telephone_1)) {
          $post->telephone_1 = $r->post_phone;
          $post->save();
        }
        if (empty($contact->email)) {
          $contact->email = $r->post_email;
          $contact->save();
        }
      }
      
      // add post tag
      $hasTag = app_model_PostTag::where('post_id', $post->id)->where('tag_id', $postTag->id)->first();
      if (!$hasTag) {
        app_model_PostTag::create([
          'post_id' => $post->id,
          'tag_id' => $postTag->id
        ]);
      }

    }
    
    $time_end = microtime(true);
    
    return Response::dump('done in ' . $time_end - $time_start . ' seconds. Created ' . $createdCount . ' out of ' . $totalCount);
  }
  
  public function findSite($name)
  {
    $site = app_model_Company::where('name', $name)->first();
    if ($site) return $site;
    
    $commonphrases = [];
    $commonwords = include APP_DIRECTORY . 'app/lib/Dedupe/commonwords.php';
    
    $results = Tokenizer::run($name, [], $commonwords);
    
    $sites = app_model_Company::select('id', 'name')
      ->where('deleted', 0)
      ->where('name', 'LIKE', $results['tokens'][0] . '%')
      ->get();
      
    if (!$sites) return null;
    $name = implode(' ', $results['tokens']);
    
    // try exact match
    foreach ($sites as $site) {
      $tokens = Tokenizer::run($site->name, [], $commonwords);
      $tokenName = implode(' ', $tokens['tokens']);
      if (strtolower($name) == strtolower($site->tokenName)) {
        return app_model_Company::find($site->id);
      }
      $site->tokens = $tokens['tokens'];
      $site->tokenName = $tokenName;
    }
    
    // if no exact match try fuzzy match
    $matcher = new Matcher;
    foreach ($sites as $site) {
      $site->score = $matcher->similarText($name, $site->tokenName);
    }
    $site = $sites->sortByDesc('score')->first();
    
    if ($site->score >= 0.94) {
      return app_model_Company::find($site->id);
    }
    
    return null;
  }
}
