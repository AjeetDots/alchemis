<?php

/**
 * Defines the app_mapper_ScoreboardMapper class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package Alchemis
 */
class app_mapper_ScoreboardMapper extends app_mapper_Mapper implements app_domain_ScoreboardFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Scoreboard();
		$obj->setCommunicationCount($array['communication_count']);
		$obj->setEffectiveCount($array['effective_count']);
		$obj->setNonEffectiveCount($array['non_effective_count']);
		$obj->setMeetingSetCount($array['meeting_set_count']);
		$obj->setInformationRequestCount($array['information_request_count']);
		$obj->setCallBackCount($array['callback_count']);
		$obj->setPriorityCallBackCount($array['priority_callback_count']);
		$obj->markClean();
		return $obj;
	}

//Info reqs
//Meets set
//Meets atd
//Effectives

	/**
	 * @TODO docs
	 * Returns the target class name, i.e. 
	 * @return string
	 */
	protected function targetClass()
	{
		return 'app_domain_Scoreboard';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId(){}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object){}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object){}
	
	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
//		$values = array($id);
//		// factor this out
//		
//		// Returns an MDB2_Result object 
//		$result = $this->doStatement($this->selectStmt, $values);
//		
//		// Extract and return an associative array from the MDB2_Result object
//		return $this->load($result);
	}

	/**
	 * Find all contacts.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_FilterBuilderCollection($result, $this);
	}
	
	/**
	 * 
	 * @param integer $user_id ID of the user who the scoreboard is for
	 * @param string $start_date start date of the scoreboard being generated
	 * @param string $end_date end date of the scoreboard being generated
	 * @return app_domain_DomainObject 
	 */
	public function findByUserIdStartDateEndDate($user_id, $start_date, $end_date)
	{
		$sql = array();
		
		$types = array ('user_id' 		=> 'integer',
						'start_date' 	=> 'date',
						'end_date'		=> 'date');
									
						
		$values = array('user_id' 		=> $user_id,
						'start_date' 	=> $start_date,
						'end_date'		=> $end_date);
		
		
		$sql[] = 	'create temporary table t_communications ' .
					'select count(comm.id) as communication_count, IFNULL(sum(comm.is_effective),0) AS effective_count ' .
					'from tbl_communications comm ' .
					'where user_id = :user_id ' .
					'and communication_date >= :start_date ' . 
					'and communication_date <= :end_date';

		$sql[] = 	'create temporary table t_meetings_set ' .
					'select count(distinct ms.id) as meeting_set_count ' .
					'from tbl_meetings_shadow ms ' .
					'where shadow_updated_by = :user_id ' .
					'and shadow_timestamp >= :start_date ' . 
					'and shadow_timestamp <= :end_date ' .
					'and shadow_type = \'i\'';

		$sql[] = 	'create temporary table t_information_requests ' .
					'select count(distinct id) as information_request_count ' .
					'from tbl_actions AS a ' .
					'where a.user_id = :user_id ' .
					'and a.created_at >= :start_date ' . 
					'and a.created_at <= :end_date ' .
					'and type_id = 2';
					
		$sql[] = 	'create temporary table t_callbacks ' .
					'select count(distinct pi.id) as callback_count ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN tbl_initiatives AS i ON i.id = pi.initiative_id ' .
					'INNER JOIN tbl_campaigns AS cam ON cam.id = i.campaign_id ' .
					'INNER JOIN tbl_clients AS cl ON cl.id = cam.client_id ' .
					'INNER JOIN tbl_campaign_nbms AS cn_user_access ON i.campaign_id = cn_user_access.campaign_id ' .
					'LEFT JOIN tbl_communications AS com ON com.id = pi.last_communication_id ' .
					'WHERE pi.next_communication_date >= :start_date ' .
					'AND pi.next_communication_date <= :end_date ' .
					'AND com.user_id = :user_id ' .
					'AND cn_user_access.user_id = :user_id ' .
					'AND cn_user_access.deactivated_date = \'0000-00-00\''; 
		
		$sql[] = 	'create temporary table t_priority_callbacks ' .
					'select count(distinct pi.id) as priority_callback_count ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN tbl_initiatives AS i ON i.id = pi.initiative_id ' .
					'INNER JOIN tbl_campaigns AS cam ON cam.id = i.campaign_id ' .
					'INNER JOIN tbl_clients AS cl ON cl.id = cam.client_id ' .
					'INNER JOIN tbl_campaign_nbms AS cn_user_access ON i.campaign_id = cn_user_access.campaign_id ' .
					'LEFT JOIN tbl_communications AS com ON com.id = pi.last_communication_id ' .
					'WHERE pi.next_communication_date >= :start_date ' .
					'AND pi.next_communication_date <= :end_date ' .
					'AND com.user_id = :user_id ' .
					'AND pi.priority_callback = 1 ' .
					'AND cn_user_access.user_id = :user_id ' .
					'AND cn_user_access.deactivated_date = \'0000-00-00\''; 
		
		//execute each of the above sql strings using standard types and values
		foreach ($sql as $query)
		{
// 			echo $query . '<br />';
			$stmt = self::$DB->prepare($query);
			
			$this->doStatement($stmt, $values);
		}

		//NOTE: MUST SUPPLY AN ID TO THE DOMAIN OBJECT OR ELSE THE MAPPER BASE OBJECT RETURNS NULL!!
// 		$query = 	'select 1 as id, tc.communication_count, tc.effective_count, (tc.communication_count - tc.effective_count) as non_effective_count, ' .
// 					'ms.meeting_set_count, ir.information_request_count ' .
// 					'from t_communications tc, t_meetings_set ms, t_information_requests ir';		
		
		$query = 	'select 1 as id, tc.communication_count, tc.effective_count, (tc.communication_count - tc.effective_count) as non_effective_count, ' .
							'ms.meeting_set_count, ir.information_request_count, cb.callback_count as callback_count, pcb.priority_callback_count as priority_callback_count ' .
							'from t_communications tc, t_meetings_set ms, t_information_requests ir, t_callbacks cb, t_priority_callbacks pcb';		
		
		$stmt4 = self::$DB->prepare($query);
		$values = array();
		$result = $this->doStatement($stmt4, $values);
		
// 		print_r($result);
		$sql = array();
		$sql[] = 'drop table t_communications';
		$sql[] = 'drop table t_meetings_set';
		$sql[] = 'drop table t_information_requests';
		$sql[] = 'drop table t_callbacks';
		$sql[] = 'drop table t_priority_callbacks';
			
		foreach ($sql as $query)
		{
			$stmt = self::$DB->prepare($query);
			$values = array();
			$this->doStatement($stmt, $values);
		}
		
		$t =  $this->load($result);
		
// 		print_r($t);
		return $t;
	}

	/**
	 * Find effectives count by given user in a given date range grouped by initiative.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findEffectiveCountGroupedByInitiative($user_id, $start_datetime, $end_datetime)
	{
		$query = 'select count(comm.id) as effective_count, vw_cli.initiative_name, vw_cli.client_name ' .
				'from tbl_communications AS comm ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = comm.post_initiative_id ' .
				'JOIN vw_client_initiatives AS vw_cli ON pi.initiative_id = vw_cli.initiative_id ' .
				'WHERE comm.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND comm.communication_date >= ' . self::$DB->quote($start_datetime, 'timestamp') . ' ' .
				'AND comm.communication_date <= ' . self::$DB->quote($end_datetime, 'timestamp') . ' ' .
				'AND comm.is_effective = 1 ' .
				'GROUP BY pi.initiative_id ' .
				'ORDER BY vw_cli.initiative_name';
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Find effectives by given user in a given date range grouped by initiative.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findEffectivesGroupedByInitiative($user_id, $start_datetime, $end_datetime)
	{
		$query = 	'CREATE TEMPORARY TABLE t_effectives (' .
				'post_initiative_id int(11), ' .
				'comm_count int(11), ' .
				'key `ix_t_effectives_post_initiative_id` (post_initiative_id)' .
				')';
//		echo $query . '<br />';
		self::$DB->query($query);
				
		$query = 'INSERT INTO t_effectives (post_initiative_id, comm_count) ' .
				'SELECT pi.id, count(comm.id) as comm_count ' .
				'from tbl_communications AS comm ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = comm.post_initiative_id ' .
				'WHERE comm.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND comm.communication_date >= ' . self::$DB->quote($start_datetime, 'timestamp') . ' ' .
				'AND comm.communication_date <= ' . self::$DB->quote($end_datetime, 'timestamp') . ' ' .
				'AND comm.is_effective = 1 ' .
				'GROUP BY pi.id;';
//		echo $query . '<br />';
		self::$DB->query($query);
		
		$query = 'select t.comm_count, comp.id as company_id, comp.name as company_name, vw_pc.id as post_id, vw_pc.job_title, vw_pc.full_name, ' .
				'vw_cli.initiative_id, vw_cli.initiative_name, vw_cli.client_name, ' .
				'lkp_cs.description AS status ' .
				'from t_effectives AS t ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = t.post_initiative_id ' .
				'JOIN vw_client_initiatives AS vw_cli ON pi.initiative_id = vw_cli.initiative_id ' .
				'JOIN vw_posts_contacts AS vw_pc ON pi.post_id = vw_pc.id ' .
				'JOIN tbl_companies AS comp ON vw_pc.company_id = comp.id ' .
				'JOIN tbl_lkp_communication_status AS lkp_cs ON lkp_cs.id = pi.status_id ' .
				'ORDER BY vw_cli.client_name, company_name;';
//		echo $query . '<br />';
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		
		
		$query = 'DROP TEMPORARY TABLE t_effectives;';
//		echo $query . '<br />';
		self::$DB->query($query);
		
		return $result;
	}

	/**
	 * Find non-effectives by given user in a given date range grouped by initiative.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findNonEffectiveCountGroupedByInitiative($user_id, $start_datetime, $end_datetime)
	{
		$query = 'select count(comm.id) as effective_count, vw_cli.initiative_name, vw_cli.client_name ' .
				'from tbl_communications AS comm ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = comm.post_initiative_id ' .
				'JOIN vw_client_initiatives AS vw_cli ON pi.initiative_id = vw_cli.initiative_id ' .
				'WHERE comm.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND comm.communication_date >= ' . self::$DB->quote($start_datetime, 'timestamp') . ' ' .
				'AND comm.communication_date <= ' . self::$DB->quote($end_datetime, 'timestamp') . ' ' .
				'AND comm.is_effective = 0 ' .
				'GROUP BY pi.initiative_id ' .
				'ORDER BY vw_cli.initiative_name';
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Find meetings set by given user in a given date range grouped by initiative.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findMeetingsSetGroupedByInitiative($user_id, $start_datetime, $end_datetime)
	{
		$query = 	'CREATE TEMPORARY TABLE t_meetings_set (' .
				'post_initiative_id int(11), ' .
				'comm_count int(11), ' .
				'key `ix_t_meetings_set_post_initiative_id` (post_initiative_id)' .
				')';
		self::$DB->query($query);
				
		$query = 'INSERT INTO t_meetings_set (post_initiative_id, comm_count) ' .
				'SELECT pi.id, count(*) as comm_count ' .
				'from tbl_meetings_shadow AS ms ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = ms.post_initiative_id ' .
				'JOIN vw_client_initiatives AS vw_cli ON pi.initiative_id = vw_cli.initiative_id ' .
				'WHERE shadow_updated_by = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND ms.shadow_timestamp >= ' . self::$DB->quote($start_datetime, 'timestamp') . ' ' .
				'AND ms.shadow_timestamp <= ' . self::$DB->quote($end_datetime, 'timestamp') . ' ' .
				'AND ms.shadow_type = \'i\' ' . 
				'GROUP BY pi.id;';
		self::$DB->query($query);
		
		$query = 'select comp.id as company_id, comp.name as company_name, vw_pc.id as post_id, vw_pc.job_title, vw_pc.full_name, ' .
				'vw_cli.initiative_id, vw_cli.initiative_name, vw_cli.client_name, ' .
				'lkp_cs.description AS status ' .
				'from t_meetings_set AS t ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = t.post_initiative_id ' .
				'JOIN vw_client_initiatives AS vw_cli ON pi.initiative_id = vw_cli.initiative_id ' .
				'JOIN vw_posts_contacts AS vw_pc ON pi.post_id = vw_pc.id ' .
				'JOIN tbl_companies AS comp ON vw_pc.company_id = comp.id ' .
				'JOIN tbl_lkp_communication_status AS lkp_cs ON lkp_cs.id = pi.status_id ' .
				'ORDER BY vw_cli.client_name, company_name;';
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		
		$query = 'DROP TEMPORARY TABLE t_meetings_set;';
		self::$DB->query($query);
		
		return $result;
	}
	
	/**
	 * Find information requests by given user in a given date range grouped by initiative.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findInformationRequestGroupedByInitiative($user_id, $start_datetime, $end_datetime)
	{
		$query = 	'CREATE TEMPORARY TABLE t_information_requests (' .
				'post_initiative_id int(11), ' .
				'key `ix_t_information_requests_set_post_initiative_id` (post_initiative_id)' .
				')';
		self::$DB->query($query);
				
		$query = 'INSERT INTO t_information_requests (post_initiative_id) ' .
				'SELECT pi.id ' .
				'from tbl_actions AS a ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = a.post_initiative_id ' .
				'JOIN vw_client_initiatives AS vw_cli ON pi.initiative_id = vw_cli.initiative_id ' .
				'WHERE a.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND a.created_at >= ' . self::$DB->quote($start_datetime, 'timestamp') . ' ' .
				'AND a.created_at <= ' . self::$DB->quote($end_datetime, 'timestamp') . ' ' .
				'AND a.type_id = 2 ' .
				'GROUP BY pi.id;';
		self::$DB->query($query);
		
		$query = 'select comp.id as company_id, comp.name as company_name, vw_pc.id as post_id, vw_pc.job_title, vw_pc.full_name, ' .
				'vw_cli.initiative_id, vw_cli.initiative_name, vw_cli.client_name, ' .
				'lkp_cs.description AS status ' .
				'from t_information_requests AS t ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = t.post_initiative_id ' .
				'JOIN vw_client_initiatives AS vw_cli ON pi.initiative_id = vw_cli.initiative_id ' .
				'JOIN vw_posts_contacts AS vw_pc ON pi.post_id = vw_pc.id ' .
				'JOIN tbl_companies AS comp ON vw_pc.company_id = comp.id ' .
				'JOIN tbl_lkp_communication_status AS lkp_cs ON lkp_cs.id = pi.status_id ' .
				'ORDER BY vw_cli.client_name, company_name;';
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		
		$query = 'DROP TEMPORARY TABLE t_information_requests;';
		self::$DB->query($query);
		
		return $result;
	}

}

?>