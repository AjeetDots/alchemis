<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class AddClientIdToTblRbacUsers extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->table('tbl_rbac_users', function(Blueprint $table)
    {
      $table->integer('client_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->table('tbl_rbac_users', function(Blueprint $table)
    {
      $table->dropColumn('client_id');
    });
  }

}
