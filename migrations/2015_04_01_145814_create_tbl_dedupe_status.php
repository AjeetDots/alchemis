<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateTblDedupeStatus extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_dedupe_status', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name')->nullable();
      $table->string('status')->nullable();
      $table->dateTime('date')->nullable();
      $table->float('runtime')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_dedupe_status');
  }

}
