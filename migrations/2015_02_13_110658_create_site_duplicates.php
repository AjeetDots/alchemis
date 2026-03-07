<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateSiteDuplicates extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_company_duplicates', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('company_id');
      $table->integer('merge_into');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_company_duplicates');
  }

}
