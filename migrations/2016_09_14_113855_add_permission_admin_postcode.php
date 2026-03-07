<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class AddPermissionAdminPostcode extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->table('tbl_rbac_users', function(Blueprint $table)
    {
      $table->boolean('permission_admin_postcode')->default(false);
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
      $table->dropColumn('permission_admin_postcode');
    });
  }

}
