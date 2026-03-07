<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateCompanyTokensTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_company_tokens', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('company_id');
      $table->string('tokens');
      $table->string('now')->nullable();
      $table->string('previous')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_company_tokens');
  }

}
