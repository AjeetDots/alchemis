<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteFiltersWhereIdLt10000AndCampaignNotInList extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement("
      UPDATE tbl_filters set deleted = 1
      WHERE id < 10000
      AND campaign_id NOT IN (
        SELECT ca.id
        FROM tbl_campaigns ca
        INNER jOIN tbl_clients AS c ON ca.client_id=c.id
        WHERE c.name NOT IN
        ('Alchemis',
        '2CV',
        'Acacia Avenue',
        'AMS',
        'Artisan',
        'Atlantic',
        'Berkeley',
        'EC Group',
        'Hurricane',
        'Mediaworks',
        'SMP',
        'The Nursery')
      )
    ");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement("
      UPDATE tbl_filters set deleted = 0
      WHERE id < 10000
      AND campaign_id NOT IN (
        SELECT ca.id
        FROM tbl_campaigns ca
        INNER jOIN tbl_clients AS c ON ca.client_id=c.id
        WHERE c.name NOT IN
        ('Alchemis',
        '2CV',
        'Acacia Avenue',
        'AMS',
        'Artisan',
        'Atlantic',
        'Berkeley',
        'EC Group',
        'Hurricane',
        'Mediaworks',
        'SMP',
        'The Nursery')
      )
    ");
  }

}
