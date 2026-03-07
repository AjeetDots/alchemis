<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class AddParentCompanyIdToObjectTieredCharacteristics extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->table('tbl_object_tiered_characteristics', function(Blueprint $table)
    {
      $table->integer('parent_company_id')->nullable();
    });
    
    DB::statement('ALTER TABLE tbl_object_tiered_characteristics MODIFY company_id INTEGER NULL');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->table('tbl_object_tiered_characteristics', function(Blueprint $table)
    {
      $table->dropColumn('parent_company_id');
    });
    
    DB::statement('ALTER TABLE tbl_object_tiered_characteristics MODIFY company_id INTEGER NOT NULL');
  }

}
