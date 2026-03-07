<?php

require_once('../include/EasySql/EasySql.class.php');

define('DB_HOST',     'localhost');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis');
define('DB_PASSWORD', 'rYT4maP7');

$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$db->debug_all = false;
//$db->query('SET FOREIGN_KEY_CHECKS = 0');

$tables = array('companies',
				'sites',
				'posts',
				'post_site',
				'contacts',
				'clients',
				'client_contacts',
				'campaigns',
				'campaign_details',
				'initiatives',
				'rbac_users',
				'lkp_decision_maker_types',
				'communications',
				'post_initiative_notes',
				'post_initiatives',
				'meetings',
				'information_requests',
				'user_client_aliases',
				'characteristics',
				'characteristic_elements',
				'object_characteristics',
				'object_characteristic_elements_text',
				'object_characteristic_elements_boolean',
				'lkp_mailer_types',
				'lkp_mailer_response_groups',
				'lkp_mailer_responses',
				'mailers',
				'mailer_items',
				'mailer_item_responses');

foreach ($tables as $table)
{
	
	$table = 'tbl_' . $table;
	$item_count = $db->getVariable('select max(id) from ' . $table);
	
	if (!is_null($item_count))
	{
		echo '<p>$item_count: '.$item_count . '</p>';
			
		$table .= '_seq';
		
		$db->query('DELETE FROM ' . $table);
		$db->query('insert into ' . $table . ' (sequence) values (' . $item_count . ')');
		$db->query('ALTER TABLE ' . $table . ' AUTO_INCREMENT = '. $item_count);
		echo "<p>Updated table: $table</p>";
	}
	else
	{
		echo "<p>No data found for: $table</p>";
	}


}


?>