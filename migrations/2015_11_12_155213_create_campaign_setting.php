<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateCampaignSetting extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::schema()->create('tbl_campaign_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setting');
            $table->integer('campaign_id');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::schema()->drop('tbl_campaign_setting');
    }

}
