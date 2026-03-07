<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_MailerMapper extends app_mapper_Mapper implements app_domain_MailerFinder
{
	protected static $DB;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT m.*, u.name AS created_by_name, ' .
					'mrg.description as response_group_name, mt.description as type_name ' .
					'FROM tbl_mailers AS m ' .
					'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
					'JOIN tbl_lkp_mailer_response_groups mrg on m.response_group_id = mrg.id ' .
					'JOIN tbl_lkp_mailer_types mt on m.type_id = mt.id ' .
					'ORDER BY created_at desc');

		// Select single
		$query = 'SELECT m.*, u.name AS created_by_name, mrg.description as response_group_name, ' .
					'mt.description as type_name ' .
					'FROM tbl_mailers AS m ' .
					'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
					'JOIN tbl_lkp_mailer_response_groups mrg on m.response_group_id = mrg.id ' .
					'JOIN tbl_lkp_mailer_types mt on m.type_id = mt.id ' .
					'WHERE m.id = ?';
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
		$obj = new app_domain_Mailer($array['id']);
		$obj->setClientInitiativeId($array['client_initiative_id']);
		$obj->setName($array['name']);
		$obj->setDescription($array['description']);
		$obj->setResponseGroupId($array['response_group_id']);
		$obj->setResponseGroupName($array['response_group_name']);
		$obj->setTypeId($array['type_id']);
		$obj->setTypeName($array['type_name']);
		$obj->setArchived($array['archived']);
		$obj->setCreatedAt($array['created_at']);
		$obj->setCreatedBy($array['created_by']);
		$obj->setCreatedByName($array['created_by_name']);
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
		return 'app_domain_Mailer';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_mailers');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{

		$query = 'INSERT INTO tbl_mailers (id, client_initiative_id, name, description, response_group_id, type_id, ' .
				'created_at, created_by) VALUES ' .
				'(:id, :client_initiative_id, :name, :description, :response_group_id, :type_id, :created_at, :created_by)';
		$types = array(	'id' => 'integer',
						'client_initiative_id' => 'integer',
						'name' => 'text',
						'description' => 'text',
						'response_group_id' => 'integer',
						'type_id' => 'integer',
						'created_at' => 'date',
						'created_by' => 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	'id' => $object->getId(),
						'client_initiative_id' => $object->getClientInitiativeId(),
						'name' => $object->getName(),
						'description' => $object->getDescription(),
						'response_group_id' => $object->getResponseGroupId(),
						'type_id' => $object->getTypeId(),
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

		$query = 		'UPDATE tbl_mailers SET client_initiative_id = :client_initiative_id, name = :name, ' .
						'description = :description, response_group_id = :response_group_id, type_id = :type_id, ' .
						'archived = :archived, created_at = :created_at, created_by = :created_by ' .
						'WHERE id = :id';
		$types = array(	'id' => 'integer',
						'client_initiative_id' => 'integer',
						'name' => 'text',
						'description' => 'text',
						'response_group_id' => 'integer',
						'type_id' => 'integer',
		                'archived' => 'integer',
						'created_at' => 'date',
						'created_by' => 'integer');
		$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	'id' => $object->getId(),
						'client_initiative_id' => $object->getClientInitiativeId(),
						'name' => $object->getName(),
						'description' => $object->getDescription(),
						'response_group_id' => $object->getResponseGroupId(),
						'type_id' => $object->getTypeId(),
		                'archived' => $object->getArchived(),
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
		$query = 'DELETE FROM tbl_mailers WHERE id = :id';

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

	/**
	 * Find all mailers.
	 * @return app_mapper_MailerCollection collection of app_domain_Mailer objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_MailerCollection($result, $this);
	}

    /**
     * Find by name
     *
     * @param string $name Mailer name
     *
     * @return app_mapper_Mapper::load()
     */
    public function findByName($name)
    {
		// Select single
		$query = 'SELECT m.*, u.name AS created_by_name, mrg.description as response_group_name, ' .
					'mt.description as type_name ' .
					'FROM tbl_mailers AS m ' .
					'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
					'JOIN tbl_lkp_mailer_response_groups mrg on m.response_group_id = mrg.id ' .
					'JOIN tbl_lkp_mailer_types mt on m.type_id = mt.id ' .
					'WHERE m.name = ?';

        $values = array($name);
		$stmt   = self::$DB->prepare($query);
		$result = $this->doStatement($stmt, $values);
		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
    }

   /**
     * Find archived mailers
     * @return app_domain_DomainObject
     */
    public function findArchived()
    {
    	// Select all
        $query = 'SELECT m.*, u.name AS created_by_name, ' .
                    'mrg.description as response_group_name, mt.description as type_name ' .
                    'FROM tbl_mailers AS m ' .
                    'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
                    'JOIN tbl_lkp_mailer_response_groups mrg on m.response_group_id = mrg.id ' .
                    'JOIN tbl_lkp_mailer_types mt on m.type_id = mt.id ' .
                    'WHERE archived = 1 ' .
                    'ORDER BY created_at desc';
        $types = array();
        $stmt = self::$DB->prepare($query, $types);

        $values = array();
        $result = $this->doStatement($stmt, $values);
        return new app_mapper_MailerCollection($result, $this);
    }


     /**
     * Find archived mailers
     * @return app_domain_DomainObject
     */
    public function findCurrent()
    {
        // Select all
        $query = 'SELECT m.*, u.name AS created_by_name, ' .
                    'mrg.description as response_group_name, mt.description as type_name ' .
                    'FROM tbl_mailers AS m ' .
                    'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
                    'JOIN tbl_lkp_mailer_response_groups mrg on m.response_group_id = mrg.id ' .
                    'JOIN tbl_lkp_mailer_types mt on m.type_id = mt.id ' .
                    'WHERE archived = 0 ' .
                    'ORDER BY created_at desc';
        $types = array();
        $stmt = self::$DB->prepare($query, $types);

        $values = array();
        $result = $this->doStatement($stmt, $values);
        return new app_mapper_MailerCollection($result, $this);
    }

 	/**
 	 * Find all possible responses by mailer id.
	 * @return app_domain_DomainObject
	 */
	public function findPossibleResponsesByMailerId($id)
	{
		$query = 	'SELECT lmr.* ' .
					'FROM tbl_lkp_mailer_responses lmr ' .
					'join tbl_lkp_mailer_response_groups lmrg on lmr.response_group_id = lmrg.id ' .
					'join tbl_mailers m on m.response_group_id = lmrg.id ' .
					'WHERE m.id = :id ' .
					'ORDER BY lmr.sort';
		$types = array('id' => 'integer');
		$stmt = self::$DB->prepare($query, $types);

		$values = array('id' => $id);
		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_MailerCollection($result, $this);
		return $col->toRawArray();
	}


 	/**
 	 * Find all mailer ids and names by id.
	 * @return app_domain_DomainObject
	 */
	public function findAllMailerIdsAndNames()
	{
		$query = 	'SELECT m.id, m.name ' .
					'FROM tbl_mailers m ' .
					'ORDER BY m.name';
		$types = array();
		$stmt = self::$DB->prepare($query, $types);

		$values = array();
		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_MailerCollection($result, $this);
		return $col->toRawArray();
	}

	/** Find mailer types
	 * @return raw data -
	 */
	public function lookupTypes()
	{

		$values = array();
		$types = array();
		$query = 'select id, description from tbl_lkp_mailer_types';
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$coll = new app_mapper_MailerCollection($result, $this);
		return $coll->toRawArray();
	}


	/** Find mailer response groups
	 * @return raw data
	 */
	public function lookupResponseGroups()
	{

		$values = array();
		$types = array();
		$query = 'select id, description from tbl_lkp_mailer_response_groups';
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$coll = new app_mapper_MailerCollection($result, $this);
		return $coll->toRawArray();
	}


	/** Find available filters which can be used to add mailer recipients
	 * @return raw data
	 */
	public function findAvailableFiltersByUserId($user_id)
	{
		$query = 'select id, name from tbl_filters where created_by = ? and results_format = \'mailer\' order by name';
		$stmt = self::$DB->prepare($query);

		$values = array($user_id);
		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_MailerCollection($result, $this);
		return $col->toRawArray();
	}

}


?>