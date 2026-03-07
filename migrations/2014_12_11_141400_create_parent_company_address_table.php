<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateParentCompanyAddressTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_parent_company_address', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('parent_company_id')->nullable();
      $table->string('address_1')->nullable();
      $table->string('address_2')->nullable();
      $table->string('town')->nullable();
      $table->string('postcode')->nullable();
      $table->string('telephone')->nullable();
      $table->integer('county_id')->nullable();
      $table->integer('country_id')->nullable();
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
    DB::schema()->drop('tbl_parent_company_address');
  }

}
