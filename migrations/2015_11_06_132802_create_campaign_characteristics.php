<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateCampaignCharacteristics extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::schema()->create('tbl_campaign_characteristics', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('campaign_id');
      $table->integer('characteristic_id');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::schema()->drop('tbl_campaign_characteristics');
  }

}
