<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class HandleDataSegregation extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::schema()->table('tbl_posts', function (Blueprint $table) {
            $table->integer('data_owner_id')->nullable();
            $table->integer('original_post_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::schema()->table('tbl_posts', function (Blueprint $table) {
            $table->dropColumn('data_owner_id');
            $table->dropColumn('original_post_id');
        });
    }

}
