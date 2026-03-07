<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateCompanyParentTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_parent_company', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name');
      $table->integer('parent_company_id')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_parent_company');
  }

}
