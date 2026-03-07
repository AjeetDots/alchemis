<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class PopulateObjectTieredCharacteristics extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    $rows = DB::table('tbl_object_tiered_characteristics')
      ->join('tbl_companies', 'tbl_object_tiered_characteristics.company_id', '=', 'tbl_companies.id')
      ->get();

    foreach($rows as $row){
    
      // only if company has a parent_company_id
      if ($row['parent_company_id']) {
        // insert new row - get parent_company_id from tbl_companies where id = $row['company_id']
        // only insert if not already there
        $count = DB::table('tbl_object_tiered_characteristics')
          ->where('tiered_characteristic_id', $row['tiered_characteristic_id'])
          ->where('tier', $row['tier'])
          ->where('parent_company_id', $row['parent_company_id'])
          ->count();
          
        if(!$count){
          DB::table('tbl_object_tiered_characteristics')->insert([
            'tiered_characteristic_id' => $row['tiered_characteristic_id'],
            'tier' => $row['tier'],
            'parent_company_id' => $row['parent_company_id']
          ]);
        }
      }
    }

  
  }
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::table('tbl_object_tiered_characteristics')->whereNotNull('parent_company_id')->delete();
  }

}
