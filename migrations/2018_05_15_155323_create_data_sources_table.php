<?php

require_once('include/Utils/Utils.class.php');

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateDataSourcesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::schema()->create('tbl_lkp_data_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('sort_order');
            $table->boolean('client_specific')->default(false);
            $table->boolean('global')->default(false);
        });

        $sources = [
            ['description' => "We've spoken before", 'sort_order' => 1, 'client_specific' => true],
            ['description' => 'Your website', 'sort_order' => 2, 'client_specific' => true, 'global' => true],
            ['description' => 'Linked-in', 'sort_order' => 3, 'client_specific' => true, 'global' => true],
            ['description' => 'Network', 'sort_order' => 4, 'client_specific' => true],
            ['description' => 'Media', 'sort_order' => 5, 'client_specific' => true, 'global' => true],
            ['description' => 'Colleague referred', 'sort_order' => 6, 'client_specific' => true],
            ['description' => 'Colleague suggested', 'sort_order' => 7, 'client_specific' => true, 'global' => true],
        ];

        $spoken = DB::table('tbl_lkp_data_sources')->insertGetId($sources[0]);
        $website = DB::table('tbl_lkp_data_sources')->insertGetId($sources[1]);
        $linkedin = DB::table('tbl_lkp_data_sources')->insertGetId($sources[2]);
        $network = DB::table('tbl_lkp_data_sources')->insertGetId($sources[3]);
        $media = DB::table('tbl_lkp_data_sources')->insertGetId($sources[4]);
        $referred = DB::table('tbl_lkp_data_sources')->insertGetId($sources[5]);
        $suggested = DB::table('tbl_lkp_data_sources')->insertGetId($sources[6]);

        DB::schema()->table('tbl_post_initiatives', function (Blueprint $table) use ($suggested) {
            $table->integer('data_source_id')->default($suggested);
            $table->dateTime('data_source_updated')->default(Utils::getTimestamp());
        });

        DB::schema()->table('tbl_posts', function (Blueprint $table) use ($suggested) {
            $table->integer('data_source_id')->default($suggested);
            $table->dateTime('data_source_updated')->default(Utils::getTimestamp());
        });

        DB::schema()->table('tbl_post_initiative_tags', function (Blueprint $table) use ($suggested) {
            $table->boolean('data_source')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::schema()->drop('tbl_lkp_data_sources');

        DB::schema()->table('tbl_post_initiatives', function (Blueprint $table) {
            $table->dropColumn('data_source_id');
            $table->dropColumn('data_source_updated');
        });

        DB::schema()->table('tbl_posts', function (Blueprint $table) {
            $table->dropColumn('data_source_id');
            $table->dropColumn('data_source_updated');
        });

        DB::schema()->table('tbl_post_initiative_tags', function (Blueprint $table) {
            $table->dropColumn('data_source');
        });
    }

}
