<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateTblDedupeAdditions extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // mirrors tbl_dedupe_match
    DB::schema()->create('tbl_dedupe_additions', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('company_id')->nullable();
      $table->integer('group')->nullable();
      $table->boolean('complete')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_dedupe_additions');
  }

}
