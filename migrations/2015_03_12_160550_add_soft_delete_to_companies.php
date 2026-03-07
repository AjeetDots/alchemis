<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class AddSoftDeleteToCompanies extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->table('tbl_companies', function(Blueprint $table)
    {
      $table->boolean('soft_delete')->default(false);
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
      $table->dropColumn('soft_delete');
    });
  }

}
