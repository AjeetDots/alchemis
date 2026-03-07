<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class AddCompaniesToDedupeMismatch extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->table('tbl_dedupe_mismatch', function(Blueprint $table)
    {
      $table->text('companies')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->table('tbl_dedupe_mismatch', function(Blueprint $table)
    {
      $table->dropColumn('companies');
    });
  }

}
