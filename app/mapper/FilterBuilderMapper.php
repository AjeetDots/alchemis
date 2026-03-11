<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_FilterBuilderMapper extends app_mapper_Mapper implements app_domain_FilterBuilderFinder
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
	protected function doLoad($array){}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e.
	 * @return string
	 */
	protected function targetClass()
	{
		return 'app_domain_FilterBuilder';
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
		$values = array('id' => $id);
		// Returns an MDB2_Result object
		$result = $this->doStatement($this->selectStmt, $values);
		return $this->load($result);
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
	 * Responsible for looping through incoming array, constructing and running any queries that are within the array.
	 * @param integer $sql - an array of SQL queries needed to construct a filter
	 * @return boolean - true if all queries executed
	 */
	public function doExecuteSqlArray($sql)
	{
		ksort($sql);
		foreach ($sql as $query)
		{
			$stmt = self::$DB->prepare($query);
			$data = array();
			$this->doStatement($stmt, $data);
		}
		return true;
	}

	/**
	 * Constructs main filter builder results.
	 * @return raw array of app_mapper_FilterBuilderCollection -
	 */
	public function getFilterBuilderResults($filter_id, $results_format)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();

		switch ($results_format)
		{
			case 'Company':
			case 'Site':
				$query = 	'SELECT c.id, pc.name as parent_company, c.name, c.website, c.telephone, c.telephone_tps, s.town, s.postcode, fr.post_count ' .
							'FROM tbl_filter_results fr ' .
							'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
							'LEFT OUTER JOIN tbl_parent_company pc on pc.id = c.parent_company_id ' .
							'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
							'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            'GROUP BY c.id, c.name, pc.id, pc.name, c.website, c.telephone, c.telephone_tps, s.town, s.postcode, fr.post_count ' .
							'order by c.name';
                break;
			case 'Company and posts':
			case 'Site and posts':
				$query = 	'SELECT c.id, pc.name as parent_company, c.name, c.website, c.telephone, c.telephone_tps, s.town, s.postcode, p.id as post_id, p.job_title, ' .
							'con.title, con.first_name, con.surname, p.propensity, p.telephone_1, fr.post_count ' .
							'FROM tbl_filter_results fr ' .
							'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
							'LEFT OUTER JOIN tbl_parent_company pc on pc.id = c.parent_company_id ' .
							'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
							'LEFT JOIN tbl_posts p on fr.post_id = p.id ' .
							'LEFT JOIN vw_contacts con on p.id = con.post_id ' .
                            'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            (!empty($user['client_id']) 
                                ? 'AND p.data_owner_id = ' . self::$DB->quote($user['client_id'], 'integer') . ' '
                                : 'AND p.data_owner_id IS NULL ') .
							'order by c.name, propensity_max desc, propensity_avg desc, propensity_min desc, propensity_sum desc, p.propensity desc, ' .
							'p.job_title, con.surname, con.first_name';
				break;
			case 'Client initiative':
			case 'Client initiative with last note':
				$query = 	'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps, s.town, s.postcode, p.id as post_id, p.job_title, ' .
							'con.title, con.first_name, con.surname, p.propensity, p.telephone_1, ' .
							'pi.id as post_initiative_id, cl.name as client_name, i.name as initiative_name, pi.initiative_id, lkp_cs.description as status, ' .
							'com.communication_date, com.comments, pin.note, ' .
							'fr.post_count ' .
							'FROM tbl_filter_results fr ' .
							'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
							'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
							'LEFT JOIN tbl_posts p on fr.post_id = p.id ' .
							'LEFT JOIN vw_contacts con on p.id = con.post_id ' .
							'LEFT JOIN tbl_post_initiatives pi on fr.post_initiative_id = pi.id '.
							'LEFT JOIN tbl_initiatives i on pi.initiative_id = i.id ' .
							'LEFT JOIN tbl_campaigns camp on camp.id = i.campaign_id ' .
							'LEFT JOIN tbl_clients cl on camp.client_id = cl.id ' .
							'LEFT JOIN tbl_lkp_communication_status lkp_cs ON pi.status_id = lkp_cs.id ' .
							'LEFT JOIN tbl_communications com on pi.last_effective_communication_id = com.id ' .
							'LEFT JOIN tbl_post_initiative_notes pin ON pin.id = com.note_id ' .
                            'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            (!empty($user['client_id']) 
                                ? 'AND p.data_owner_id = ' . self::$DB->quote($user['client_id'], 'integer') . ' '
                                : 'AND p.data_owner_id IS NULL ') .
							'order by c.name, propensity_max desc, propensity_avg desc, propensity_min desc, propensity_sum desc, p.propensity desc, ' .
							'p.job_title, con.surname, con.first_name';
				break;
			case 'Mailer':
				$query = 	'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps, s.address_1, s.address_2, ' .
							's.town, s.city, s.postcode, p.id as post_id, p.job_title, ' .
							'con.title, con.first_name, con.surname, p.propensity, p.telephone_1, fr.post_count, ' .
							'ocd.value as company_cleaned_date, ocd_1.value as post_cleaned_date ' .
							'FROM tbl_filter_results fr ' .
							'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
							'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
							'LEFT JOIN tbl_posts p on fr.post_id = p.id ' .
							'LEFT JOIN vw_contacts con on p.id = con.post_id ' .
							'LEFT JOIN tbl_object_characteristics_date ocd on c.id = ocd.company_id ' .
							'LEFT JOIN tbl_object_characteristics_date ocd_1 on p.id = ocd_1.post_id ' .
                            'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            (!empty($user['client_id']) 
                                ? 'AND p.data_owner_id = ' . self::$DB->quote($user['client_id'], 'integer') . ' '
                                : 'AND p.data_owner_id IS NULL ') .
							'order by c.name, propensity_max desc, propensity_avg desc, propensity_min desc, propensity_sum desc, p.propensity desc, ' .
							'p.job_title, con.surname, con.first_name';
				break;
			case 'Meeting':
                $query =    'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps, s.town, s.postcode, p.id as post_id, p.job_title, ' .
                            'con.title, con.first_name, con.surname, p.propensity, p.telephone_1, ' .
                            'pi.id as post_initiative_id, cl.name as client_name, i.name as initiative_name, pi.initiative_id, lkp_cs.description as status, ' .
                            'com.communication_date, com.comments, ' .
                            'u.name as `meeting_created_by`, u1.name as `meeting_modified_by`, ' .
                            'm.created_at as meeting_set_date, m.date as meeting_date, m.attended_date as meeting_attended_date, ' .
                            'fr.post_count ' .
                            'FROM tbl_filter_results fr ' .
                            'LEFT JOIN tbl_meetings AS m ON m.id = fr.meeting_id ' .
                            'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
                            'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
                            'LEFT JOIN tbl_posts p on fr.post_id = p.id ' .
                            'LEFT JOIN vw_contacts con on p.id = con.post_id ' .
                            'LEFT JOIN tbl_post_initiatives pi on fr.post_initiative_id = pi.id '.
                            'LEFT JOIN tbl_initiatives i on pi.initiative_id = i.id ' .
                            'LEFT JOIN tbl_campaigns camp on camp.id = i.campaign_id ' .
                            'LEFT JOIN tbl_clients cl on camp.client_id = cl.id ' .
                            'LEFT JOIN tbl_lkp_communication_status lkp_cs ON pi.status_id = lkp_cs.id ' .
                            'LEFT JOIN tbl_communications com on pi.last_effective_communication_id = com.id ' .
                            'LEFT JOIN tbl_rbac_users u on m.created_by = u.id ' .
                            'LEFT JOIN tbl_rbac_users u1 on m.modified_by = u1.id ' .
                            'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            (!empty($user['client_id']) 
                                ? 'AND p.data_owner_id = ' . self::$DB->quote($user['client_id'], 'integer') . ' '
                                : 'AND p.data_owner_id IS NULL ') .
                            'order by c.name, propensity_max desc, propensity_avg desc, propensity_min desc, propensity_sum desc, p.propensity desc, ' .
                            'p.job_title, con.surname, con.first_name';
                break;
			default:
				throw new Exception('No results format variable ($results_format) supplied.');
				break;

		}
//		echo $query;

		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

	}


	/**
	 * Returns the total number of rows in tbl_filter_results for a filter.
	 * This is used to guard very large exports that would exhaust memory
	 * when rendered to XLS.
	 *
	 * @param integer $filter_id
	 * @return integer
	 */
	public function getFilterResultCount($filter_id)
	{
		$query = 'SELECT COUNT(*) ' .
				 'FROM tbl_filter_results fr ' .
				 'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer');

		$result = self::$DB->queryOne($query);
		return (int) $result;
	}

	/**
	 * Constructs filter export results.
	 * @return raw array of app_mapper_FilterBuilderCollection -
	 */
	public function getFilterExport($filter_id, $results_format)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();

		switch ($results_format)
		{
			case 'Company':
			case 'Site':
				$query = 	'SELECT c.id as company_id, c.name as company_name, c.website, c.telephone as company_telephone, ' .
							's.id as site_id, s.address_1, s.address_2, s.town, s.city, s.postcode, ' .
							'lkp_cty.name as county, lkp_ctry.name as country ' .
							'FROM tbl_filter_results fr ' .
							'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
							'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
							'LEFT JOIN tbl_lkp_counties AS lkp_cty ON lkp_cty.id = s.county_id ' .
							'LEFT JOIN tbl_lkp_countries AS lkp_ctry ON lkp_ctry.id = s.country_id ' .
							'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
							'group by c.id, c.name, c.website, c.telephone, ' .
							's.id, s.address_1, s.address_2, s.town, s.city, s.postcode, ' .
							'lkp_cty.name, lkp_ctry.name ' .
							'order by c.name';
//				echo $query;
				break;
			case 'Company and posts':
			case 'Site and posts':
				$query = 	'SELECT c.id as company_id, c.name as company_name, c.website, c.telephone as company_telephone, ' .
							's.id as site_id, s.address_1, s.address_2, s.town, s.city, s.postcode, ' .
							'lkp_cty.name as county, lkp_ctry.name as country, ' .
							'p.id as post_id, p.job_title, p.telephone_1 as post_telephone_1, p.telephone_2 as post_telephone_2, ' .
							'p.telephone_switchboard as post_telephone_switchboard, p.telephone_fax as post_telephone_fax, ' .
							'con.id as contact_id, con.title, con.first_name, con.surname, con.email, con.telephone_mobile as contact_telephone_mobile ' .
							'FROM tbl_filter_results fr ' .
							'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
							'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
							'LEFT JOIN tbl_posts p on fr.post_id = p.id ' .
							'LEFT JOIN vw_contacts con on p.id = con.post_id ' .
							'LEFT JOIN tbl_lkp_counties AS lkp_cty ON lkp_cty.id = s.county_id ' .
							'LEFT JOIN tbl_lkp_countries AS lkp_ctry ON lkp_ctry.id = s.country_id ' .
                            'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            (!empty($user['client_id']) 
                                ? 'AND p.data_owner_id = ' . self::$DB->quote($user['client_id'], 'integer') . ' '
                                : 'AND p.data_owner_id IS NULL ') .
							'order by c.name, propensity_max desc, propensity_avg desc, propensity_min desc, propensity_sum desc, p.propensity desc, ' .
							'p.job_title, con.surname, con.first_name';
				break;
			case 'Client initiative':
			case 'Client initiative with last note':
			case 'Mailer':
				$query = 	'SELECT c.id as company_id, c.name as company_name, c.website, c.telephone as company_telephone, ' .
							's.id as site_id, s.address_1, s.address_2, s.town, s.city, s.postcode, ' .
							'lkp_cty.name as county, lkp_ctry.name as country, ' .
							'p.id as post_id, p.job_title, p.telephone_1 as post_telephone_1, p.telephone_2 as post_telephone_2, ' .
							'p.telephone_switchboard as post_telephone_switchboard, p.telephone_fax as post_telephone_fax, ' .
							'con.id as contact_id, con.title, con.first_name, con.surname, con.email, con.telephone_mobile as contact_telephone_mobile, ' .
							'cl.name as client_name, i.name as initiative_name, pi.initiative_id, pi.id as post_initiative_id, lkp_cs.description as status, ' .
							'com.communication_date as last_communication_date, pi.comment, pin.note as last_communication_note, ' .
							'fr.post_count ' .
							'FROM tbl_filter_results fr ' .
							'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
							'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
							'LEFT JOIN tbl_posts p on fr.post_id = p.id ' .
							'LEFT JOIN vw_contacts con on p.id = con.post_id ' .
							'LEFT JOIN tbl_post_initiatives pi on fr.post_initiative_id = pi.id '.
							'LEFT JOIN tbl_initiatives i on pi.initiative_id = i.id ' .
							'LEFT JOIN tbl_campaigns camp on camp.id = i.campaign_id ' .
							'LEFT JOIN tbl_clients cl on camp.client_id = cl.id ' .
							'LEFT JOIN tbl_lkp_communication_status lkp_cs ON pi.status_id = lkp_cs.id ' .
							'LEFT JOIN tbl_communications com on pi.last_effective_communication_id = com.id ' .
							'LEFT JOIN tbl_post_initiative_notes pin ON pin.id = com.note_id ' .
							'LEFT JOIN tbl_lkp_counties AS lkp_cty ON lkp_cty.id = s.county_id ' .
							'LEFT JOIN tbl_lkp_countries AS lkp_ctry ON lkp_ctry.id = s.country_id ' .
                            'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            (!empty($user['client_id']) 
                                ? 'AND p.data_owner_id = ' . self::$DB->quote($user['client_id'], 'integer') . ' '
                                : 'AND p.data_owner_id IS NULL ') .
							'order by c.name, propensity_max desc, propensity_avg desc, propensity_min desc, propensity_sum desc, p.propensity desc, ' .
							'p.job_title, con.surname, con.first_name';
				break;
			case 'Meeting':
                $query =    'SELECT c.id as company_id, c.name as company_name, c.website, c.telephone as company_telephone, ' .
                            's.id as site_id, s.address_1, s.address_2, s.town, s.city, s.postcode, ' .
                            'lkp_cty.name as county, lkp_ctry.name as country, ' .
                            'p.id as post_id, p.job_title, p.telephone_1 as post_telephone_1, p.telephone_2 as post_telephone_2, ' .
                            'p.telephone_switchboard as post_telephone_switchboard, p.telephone_fax as post_telephone_fax, ' .
                            'con.id as contact_id, con.title, con.first_name, con.surname, con.email, con.telephone_mobile as contact_telephone_mobile, ' .
                            'cl.name as client_name, i.name as initiative_name, pi.initiative_id, pi.id as post_initiative_id, lkp_cs.description as status, ' .
                            'com.communication_date as last_communication_date, pi.comment, pin.note as last_communication_note, ' .
                            'lkp_ls.description as lead_source, ' .
                            'u.name as meeting_created_by, u1.name as meeting_modified_by, ' .
                            'date_format(m.created_at, \'%d %M %Y\') as meeting_set_date, date_format(m.date, \'%d %M %Y\') as meeting_date, date_format(m.attended_date, \'%d %M %Y\') as meeting_attended_date, ' .
                            'fr.post_count ' .
                            'FROM tbl_filter_results fr ' .
                            'JOIN tbl_meetings AS m ON m.id = fr.meeting_id ' .
                            'LEFT JOIN tbl_companies c on c.id = fr.company_id ' .
                            'LEFT JOIN tbl_sites s on s.company_id = c.id ' .
                            'LEFT JOIN tbl_posts p on fr.post_id = p.id ' .
                            'LEFT JOIN vw_contacts con on p.id = con.post_id ' .
                            'LEFT JOIN tbl_post_initiatives pi on fr.post_initiative_id = pi.id '.
                            'LEFT JOIN tbl_initiatives i on pi.initiative_id = i.id ' .
                            'LEFT JOIN tbl_campaigns camp on camp.id = i.campaign_id ' .
                            'LEFT JOIN tbl_clients cl on camp.client_id = cl.id ' .
                            'LEFT JOIN tbl_lkp_communication_status lkp_cs ON pi.status_id = lkp_cs.id ' .
                            'LEFT JOIN tbl_communications com on pi.last_effective_communication_id = com.id ' .
                            'LEFT JOIN tbl_post_initiative_notes pin ON pin.id = com.note_id ' .
                            'LEFT JOIN tbl_lkp_counties AS lkp_cty ON lkp_cty.id = s.county_id ' .
                            'LEFT JOIN tbl_lkp_countries AS lkp_ctry ON lkp_ctry.id = s.country_id ' .
                            'LEFT JOIN tbl_lkp_lead_source AS lkp_ls ON lkp_ls.id = pi.lead_source_id ' .
                            'LEFT JOIN tbl_rbac_users u on m.created_by = u.id ' .
                            'LEFT JOIN tbl_rbac_users u1 on m.modified_by = u1.id ' .
                            'WHERE fr.filter_id = ' . self::$DB->quote($filter_id, 'integer') . ' ' .
                            (!empty($user['client_id']) 
                                ? 'AND p.data_owner_id = ' . self::$DB->quote($user['client_id'], 'integer') . ' '
                                : 'AND p.data_owner_id IS NULL ') .
                            'order by c.name, propensity_max desc, propensity_avg desc, propensity_min desc, propensity_sum desc, p.propensity desc, ' .
                            'p.job_title, con.surname, con.first_name';
                break;
			default:
				throw new Exception('No results format variable ($results_format) supplied.');
				break;

		}

		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

}

?>