<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_FilterMapper extends app_mapper_Mapper implements app_domain_FilterFinder
{
	protected static $DB;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_filters ORDER BY id');

		// Select single
		$query = 'SELECT f.*, lkp_ft.description as filter_type, u.name AS created_by_name, cl.name as campaign_name ' .
					'FROM tbl_filters AS f JOIN tbl_rbac_users AS u ON f.created_by = u.id ' .
					'LEFT JOIN tbl_lkp_filter_types lkp_ft on f.type_id = lkp_ft.id ' .
					'LEFT JOIN tbl_campaigns camp on f.campaign_id = camp.id ' .
					'LEFT JOIN tbl_clients cl on cl.id = camp.client_id ' .
					'WHERE f.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Filter($array['id']);
		$obj->setName($array['name']);
		$obj->setDescription($array['description']);
		$obj->setTypeId($array['type_id']);
		$obj->setType($array['filter_type']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setCampaignName($array['campaign_name']);
		$obj->setResultsFormat($array['results_format']);
		$obj->setIsReportSource($array['is_report_source']);
		$obj->setReportParameterDescription($array['report_parameter_description']);
		$obj->setCompanyCount($array['company_count']);
		$obj->setPostCount($array['post_count']);
		$obj->setCreatedAt($array['created_at']);
		$obj->setCreatedBy($array['created_by']);
		$obj->setCreatedByName($array['created_by_name']);
		$obj->setUpdatedAt($array['updated_at']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e.
	 * @return string
	 */
	protected function targetClass()
	{
		return 'app_domain_Filter';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_filters');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{

		$query = 'INSERT INTO tbl_filters (id, name, description, type_id, campaign_id, results_format, is_report_source, report_parameter_description, created_at, created_by) VALUES ' .
				'(:id, :name, :description, :type_id, :campaign_id, :results_format, :is_report_source, :report_parameter_description, :created_at, :created_by)';
		$types = array(	'id' => 'integer',
						'name' => 'text',
						'description' => 'text',
						'type_id' => 'integer',
						'campaign_id' => 'integer',
		                'results_format' => 'text',
		                'is_report_source' => 'integer',
		                'report_parameter_description' => 'text',
						'created_at' => 'date',
						'created_by' => 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	'id' => $object->getId(),
						'name' => $object->getName(),
						'description' => $object->getDescription(),
						'type_id' => $object->getTypeId(),
						'campaign_id' => $object->getCampaignId(),
		                'results_format' => $object->getResultsFormat(),
		                'is_report_source' => $object->getIsReportSource(),
		                'report_parameter_description' => $object->getReportParameterDescription(),
						'created_at' => $object->getCreatedAt(),
						'created_by' => $object->getCreatedBy());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{

		$query = 		'UPDATE tbl_filters SET name = :name, ' .
						'description = :description, type_id = :type_id, campaign_id = :campaign_id,' .
		                'results_format = :results_format, ' .
		                'is_report_source = :is_report_source, report_parameter_description = :report_parameter_description, ' .
						'created_at = :created_at, created_by = :created_by ' .
						'WHERE id = :id';
		$types = array(	'id'	=> 'integer',
						'name' => 'text',
						'description' => 'text',
						'type_id' => 'integer',
						'campaign_id' => 'integer',
						'results_format' => 'text',
		                'is_report_source' => 'integer',
		                'report_parameter_description' => 'text',
						'created_at' => 'date',
						'created_by' => 'integer');
		$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	'id'	=> $object->getId(),
						'name' => $object->getName(),
						'description' => $object->getDescription(),
						'type_id' => $object->getTypeId(),
						'campaign_id' => $object->getCampaignId(),
						'results_format' => $object->getResultsFormat(),
		                'is_report_source' => $object->getIsReportSource(),
                        'report_parameter_description' => $object->getReportParameterDescription(),
						'created_at' => $object->getCreatedAt(),
						'created_by' => $object->getCreatedBy());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_filters WHERE id = :id';

		$types = array('id' => 'integer');
		$values = array('id' => $object->getId());

		$stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$this->doStatement($stmt, $values);
	}


	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$values = array($id);
		// Returns an MDB2_Result object
		$result = $this->doStatement($this->selectStmt, $values);
		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

	public function findByName($name)
	{
		$query = 'SELECT f.*, lkp_ft.description as filter_type, u.name AS created_by_name, cl.name as campaign_name ' .
					'FROM tbl_filters AS f JOIN tbl_rbac_users AS u ON f.created_by = u.id ' .
					'LEFT JOIN tbl_lkp_filter_types lkp_ft on f.type_id = lkp_ft.id ' .
					'LEFT JOIN tbl_campaigns camp on f.campaign_id = camp.id ' .
					'LEFT JOIN tbl_clients cl on cl.id = camp.client_id ' .
					'WHERE f.name = ?';

		$selectStmt = self::$DB->prepare($query);
		$values     = array($name);

		$result = $this->doStatement($selectStmt, $values);
		return $this->load($result);
	}

	/**
	 * Find all posts.
	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_PostInitiativeCollection($result, $this);
	}

 	/**
 	 * Find filter by user id.
	 * @return app_domain_DomainObject
	 */
	public function findPersonalByUserId($user_id)
	{
		$query = 'SELECT f.*, u.name as created_by_name ' .
					'FROM tbl_filters f ' .
					'join tbl_rbac_users u on f.created_by = u.id ' .
					'WHERE f.created_by = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND f.type_id = 1 ' .
					'AND f.deleted = 0 ' .
					'ORDER BY name';
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt);
		return new app_mapper_FilterCollection($result, $this);
	}

 	/** Find campaign filters which are available to the specified user id
	 * @param integer $user_id
	 * @return filter domain collection
	 **/
	public function findCampaignFiltersByUserId($user_id)
	{
		$query = 'SELECT f.*, u.name as created_by_name, cl.name as campaign_name ' .
					'FROM tbl_filters f ' .
					'join tbl_rbac_users u on f.created_by = u.id ' .
					'JOIN tbl_campaigns camp on f.campaign_id = camp.id ' .
					'JOIN tbl_clients cl on cl.id = camp.client_id ' .
					'JOIN tbl_campaign_nbms cn_user_access ON f.campaign_id = cn_user_access.campaign_id ' .
					'WHERE f.type_id = 2 ' .
					'AND cn_user_access.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND cn_user_access.deactivated_date = \'0000-00-00\' ' .
					'AND f.deleted = 0 ' .
					'ORDER BY name';
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt);
		return new app_mapper_FilterCollection($result, $this);
	}


	/** Find report source campaign filters which are available to the specified user id
     * @param integer $user_id
     * @return filter domain collection
     **/
    public function findReportSourceFiltersByClientIdAndUserId($client_id, $user_id)
    {
        $query = 'SELECT f.id, f.name ' .
                    'FROM tbl_filters f ' .
                    'join tbl_rbac_users u on f.created_by = u.id ' .
                    'JOIN tbl_campaigns camp on f.campaign_id = camp.id ' .
                    'JOIN tbl_clients cl on cl.id = camp.client_id ' .
                    'JOIN tbl_campaign_nbms cn_user_access ON f.campaign_id = cn_user_access.campaign_id ' .
                    'WHERE f.type_id = 2 ' .
                    'AND cl.id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
                    'AND f.is_report_source = 1 ' .
                    'AND cn_user_access.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
                    'AND cn_user_access.deactivated_date = \'0000-00-00\' ' .
                    'ORDER BY name';
//        $stmt = self::$DB->prepare($query);
//        $result = $this->doStatement($stmt);
//        return new app_mapper_FilterCollection($result, $this);
        $result =  self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
//        print_r($result);
        return $result;
    }




 	/** Find global filters
	 * @return filter domain collection
	 **/
	public function findGlobalFilters()
	{

		$query = 'SELECT f.*, u.name as created_by_name ' .
					'FROM tbl_filters f ' .
					'join tbl_rbac_users u on f.created_by = u.id ' .
					'WHERE f.type_id = 3 ' .
					'AND f.deleted = 0 ' .
					'ORDER BY name';
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt);
		return new app_mapper_FilterCollection($result, $this);
	}

	/**
 	 * Find deleted filter by user id.
	 * @return app_domain_DomainObject
	 */

	public function findDeletedPersonalByUserId($user_id)
	{
		$query = 'SELECT f.*, u.name as created_by_name ' .
					'FROM tbl_filters f ' .
					'join tbl_rbac_users u on f.created_by = u.id ' .
					'WHERE f.created_by = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND f.type_id = 1 ' .
					'AND f.deleted = 1 ' .
					'ORDER BY name';
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt);
		return new app_mapper_FilterCollection($result, $this);
	}

	 	/** Find deleted campaign filters which are available to the specified user id
		 * @param integer $user_id
		 * @return filter domain collection
		 **/
		public function findDeletedCampaignFiltersByUserId($user_id)
		{
			$query = 'SELECT f.*, u.name as created_by_name, cl.name as campaign_name ' .
						'FROM tbl_filters f ' .
						'join tbl_rbac_users u on f.created_by = u.id ' .
						'JOIN tbl_campaigns camp on f.campaign_id = camp.id ' .
						'JOIN tbl_clients cl on cl.id = camp.client_id ' .
						'JOIN tbl_campaign_nbms cn_user_access ON f.campaign_id = cn_user_access.campaign_id ' .
						'WHERE f.type_id = 2 ' .
						'AND cn_user_access.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
						'AND cn_user_access.deactivated_date = \'0000-00-00\' ' .
						'AND f.deleted = 1 ' .
						'ORDER BY name';
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt);
		return new app_mapper_FilterCollection($result, $this);
		}

		/** Find deleted global filters
	 	* @return filter domain collection
	 	**/
		public function findDeletedGlobalFilters()
		{

			$query = 'SELECT f.*, u.name as created_by_name ' .
						'FROM tbl_filters f ' .
						'join tbl_rbac_users u on f.created_by = u.id ' .
						'WHERE f.type_id = 3 ' .
						'AND f.deleted = 1 ' .
						'ORDER BY name';
			$stmt = self::$DB->prepare($query);
			$result = $this->doStatement($stmt);
			return new app_mapper_FilterCollection($result, $this);
		}	



 	/**
 	 * Find filter by id.
 	 * @param integer - id of the filter
	 * @return app_domain_DomainObject
	 */
	public function findFilterLinesByFilterId($id)
	{
		$query = 	'SELECT * ' .
					'FROM tbl_filter_lines ' .
					'WHERE filter_id = :id ' .
					'ORDER BY id';
		$types = array('id' => 'integer');
		$stmt = self::$DB->prepare($query, $types);

		$values = array('id' => $id);
		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_FilterLineCollection($result, $this);
		return $col->toRawArray();
	}


 	/**
 	 * Find filter by id and direction
 	 * @param integer - id of the filter
 	 * @param string - direction of the filter lines to extract
	 * @return app_domain_DomainObject
	 */
	public function findFilterLinesByFilterIdAndDirection($id, $direction)
	{
		$query = 	'SELECT * ' .
					'FROM tbl_filter_lines ' .
					'WHERE filter_id = :id ' .
					'AND direction = :direction ' .
					'ORDER BY id';
		$types = array(	'id' => 'integer',
						'direction' => 'text');

		$stmt = self::$DB->prepare($query, $types);

		$values = array('id' => $id,
						'direction' => $direction);

		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_FilterLineCollection($result, $this);
		return $col->toRawArray();
	}


	public function deleteFilterLinesByIdAndDirection($id, $direction)
	{
		$query = 	'DELETE FROM tbl_filter_lines ' .
					'WHERE filter_id = :id ' .
					'AND direction = :direction';
		$types = array(	'id' => 'integer',
						'direction' => 'text');
		$stmt = self::$DB->prepare($query, $types);

		$values = array('id' => $id,
						'direction' => $direction);

		$this->doStatement($stmt, $values);
	}

}

?>