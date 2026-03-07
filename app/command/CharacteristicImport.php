<?php

use app_controller_Response as Response;
use Carbon\Carbon;
use League\Csv\Reader;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

ini_set('display_errors', 'On');
error_reporting(E_ERROR);

class app_command_CharacteristicImport extends app_command_BaseCommand
{
  
  public function prepare()
  {
    $request = $this->request;
    $input = $request->input;

    if(!$request->action){
      $request->action = 'import';
    }
  }
  
  public function import()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();

    $analytics = app_model_Characteristic::where('name', 'Google Analytics')->first();
    $remarketing = app_model_Characteristic::where('name', 'Google Remarketing')->first();
    $cms = app_model_Characteristic::where('name', 'CMS')->first();
    $php = app_model_Characteristic::where('name', 'PHP User')->first();

    $files = [
      APP_DIRECTORY . 'data/characteristic_import/cb2b0226-ba44-4a7a-8032-2f25de510b0c_matrix-dave-no.1.csv',
      APP_DIRECTORY . 'data/characteristic_import/0da11e5d-02f3-473f-aa4a-72ea2f44ea00_matrix-Dave-2.csv',
      APP_DIRECTORY . 'data/characteristic_import/bd69c482-8a90-4650-a581-c0ecd596f288_matrix-Dave-3.csv'
    ];
    
    foreach ($files as $file) {
      $reader = Reader::createFromPath($file);
      $reader = $reader->setOffset(2)->fetchAll();
      foreach ($reader as $index => $row) {
        $url = $row[0];
        $re = $this->value($row[1]);
        $ana = $this->value($row[2]);
        $php_user = $this->value($row[5]);
        $cms_values = [
          'Drupal' => $this->value($row[3]),
          'Wordpress' => $this->value($row[4]),
          'Magento' => $this->value($row[6]),
          'Magento Enterprise' => $this->value($row[7]),
          'WooCommerce' => $this->value($row[8])
        ];
        
        // find companies by url
        $companies = app_model_Company::select('id')->where('website', 'LIKE', "%$url%")->get();
        
        foreach ($companies as $company) {
          
          // analytics
          $this->addBoolean($analytics->id, $company->id, $ana);
          
          // remarketing
          $this->addBoolean($remarketing->id, $company->id, $re);
          
          // cms
          $char = $this->addCharacteristic($cms->id, $company->id);
          foreach ($cms_values as $name => $value) {
            $this->addElement($char->id, $cms->id, $name, $value);
          }
          
          // php
          $value = in_array(true, $cms_values);
          if (!$value) $value = $php_user;
          $this->addBoolean($php->id, $company->id, $value);
          
        }
        
      }
    }
    
    return Response::json('done');
  }
  
  public function value($value)
  {
    $value = trim($value);
    return $value == 'x';
  }
  
  public function addCharacteristic($characteristic_id, $company_id)
  {
    return app_model_ObjectCharacteristics::updateOrCreate([
      'characteristic_id' => $characteristic_id,
      'company_id' => $company_id
    ], [
      'characteristic_id' => $characteristic_id,
      'company_id' => $company_id
    ]);
  }
  
  public function addBoolean($characteristic_id, $company_id, $value)
  {
    $this->addCharacteristic($characteristic_id, $company_id);
    
    return app_model_BooleanCharacteristic::updateOrCreate([
      'characteristic_id' => $characteristic_id,
      'company_id' => $company_id
    ], [
      'characteristic_id' => $characteristic_id,
      'company_id' => $company_id,
      'value' => $value
    ]);
  }
  
  public function addElement($object_characteristic_id, $characteristic_id, $name, $value)
  {
    
    $element = app_model_CharacteristicElement::where('characteristic_id', $characteristic_id)
      ->where('name', $name)
      ->first();

    return app_model_ElementBooleanCharacteristic::updateOrCreate([
      'object_characteristic_id' => $object_characteristic_id,
      'characteristic_element_id' => $element->id
    ], [
      'object_characteristic_id' => $object_characteristic_id,
      'characteristic_element_id' => $element->id,
      'value' => $value
    ]);
  }
  
}