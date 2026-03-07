<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateDedupeParentCompaniesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_dedupe_parent_companies', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('parent');
      $table->text('company_ids');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_dedupe_parent_companies');
  }

}
