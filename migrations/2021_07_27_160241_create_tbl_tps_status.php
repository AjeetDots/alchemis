<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateTblTpsStatus extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_tps_status', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('telephoneIndex');
      $table->integer('telephone');
      $table->string('tps_status')->nullable();
      $table->timestamps();
      $table->integer('updated_by');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_tps_status');
  }

}
