<?php

require_once('../include/EasySql/EasySql.class.php');

define('DB_HOST',     'localhost');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis');
define('DB_PASSWORD', 'rYT4maP7');

$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$db->debug_all = false;
//$db->query('SET FOREIGN_KEY_CHECKS = 0');

$tables = array(
'tbl_action_resources',
'tbl_actions',
'tbl_bank_holidays',
'tbl_campaign_companies_do_not_call',
'tbl_campaign_disciplines',
'tbl_campaign_nbm_targets',
'tbl_campaign_nbms',
'tbl_campaign_regions',
'tbl_campaign_sectors',
'tbl_campaign_targets',
'tbl_campaigns',
'tbl_characteristic_elements',
'tbl_characteristics',
'tbl_client_contacts',
'tbl_clients',
//'tbl_communications',
'tbl_companies',
'tbl_company_notes',
'tbl_company_tags',
'tbl_company_tiered_characteristics',
'tbl_configuration',
'tbl_contacts',
'tbl_data_statistics',
'tbl_data_statistics_daily',
'tbl_data_statistics_run',
'tbl_documents',
'tbl_events',
'tbl_exclude',
'tbl_filter_lines',
'tbl_filter_results',
'tbl_filters',
'tbl_include',
'tbl_information_requests',
'tbl_initiatives',
'tbl_lkp_action_communication_types',
'tbl_lkp_action_resource_types',
'tbl_lkp_action_types',
'tbl_lkp_agency_user_types',
'tbl_lkp_campaign_billing_terms',
'tbl_lkp_campaign_payment_methods',
'tbl_lkp_campaign_payment_terms',
'tbl_lkp_campaign_types',
'tbl_lkp_communication_receptiveness',
'tbl_lkp_communication_status',
'tbl_lkp_communication_status_rules',
'tbl_lkp_communication_targeting',
'tbl_lkp_communication_types',
'tbl_lkp_counties',
'tbl_lkp_countries',
'tbl_lkp_decision_maker_types',
'tbl_lkp_event_types',
'tbl_lkp_filter_types',
'tbl_lkp_information_request_comm_types',
'tbl_lkp_information_request_status',
'tbl_lkp_information_request_types',
'tbl_lkp_lead_source',
'tbl_lkp_mailer_response_groups',
'tbl_lkp_mailer_responses',
'tbl_lkp_mailer_types',
'tbl_lkp_meeting_locations',
'tbl_lkp_meeting_status',
'tbl_lkp_meeting_types',
'tbl_lkp_next_communication_reasons',
'tbl_lkp_post_no_access_types',
'tbl_lkp_postcodes',
'tbl_lkp_region_postcodes',
'tbl_lkp_regions',
'tbl_lkp_reports',
'tbl_mailer_item_responses',
'tbl_mailer_items',
'tbl_mailers',
'tbl_meetings',
'tbl_messages',
'tbl_mime_types',
'tbl_nbm_campaign_targets',
'tbl_object_characteristic_elements_boolean',
'tbl_object_characteristic_elements_date',
'tbl_object_characteristic_elements_text',
'tbl_object_characteristics',
'tbl_object_characteristics_boolean',
'tbl_object_characteristics_date',
'tbl_object_characteristics_seq',
'tbl_object_characteristics_shadow',
'tbl_object_characteristics_text',
'tbl_object_tiered_characteristics',
'tbl_post_agency_review_dates',
'tbl_post_agency_users',
'tbl_post_decision_makers',
'tbl_post_discipline_review_dates',
'tbl_post_incumbent_agencies',
//'tbl_post_initiative_notes',
'tbl_post_initiative_tags',
'tbl_post_initiatives',
'tbl_post_notes',
'tbl_post_site',
'tbl_post_tags',
'tbl_posts',
'tbl_rbac_commands',
'tbl_rbac_permissions',
'tbl_rbac_role_permissions',
'tbl_rbac_roles',
'tbl_rbac_sessions',
'tbl_rbac_user_roles',
'tbl_rbac_users',
'tbl_sites',
'tbl_sites_shadow',
'tbl_tag_categories',
'tbl_tags',
'tbl_team_nbms',
'tbl_teams',
'tbl_tiered_characteristic_categories',
'tbl_tiered_characteristics',
'tbl_user_client_access');




foreach ($tables as $table)
{
	
//	$table = 'tbl_' . $table;
	$item_count = $db->getVariable('select max(id) from ' . $table);
	
	if (!is_null($item_count))
	{
		echo '<p>$item_count: '.$item_count . '</p>';
		
		$db->query('ALTER TABLE ' . $table . ' AUTO_INCREMENT = '. $item_count);
		
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