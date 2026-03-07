<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class UpdateFiltersRenameToSite extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement("update tbl_filters set results_format = 'Site' where results_format = 'Company'");
    DB::statement("update tbl_filters set results_format = 'Site and posts' where results_format = 'Company and posts'");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement("update tbl_filters set results_format = 'Company' where results_format = 'Site'");
DB::statement("update tbl_filters set results_format = 'Company and posts' where results_format = 'Site and posts'");
  }

}
