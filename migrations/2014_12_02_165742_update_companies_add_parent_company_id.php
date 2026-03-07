<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class UpdateCompaniesAddParentCompanyId extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->table('tbl_companies', function(Blueprint $table)
    {
      $table->integer('parent_company_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->table('tbl_companies', function(Blueprint $table)
    {
      $table->dropColumn('parent_company_id');
    });
  }

}
