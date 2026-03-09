<?php
require_once ('app/base/Exceptions.php');
require_once ('app/mapper.php');
require_once ('app/mapper/Mapper.php');
require_once ('app/mapper/Collections.php');
require_once ('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_InformationRequestMapper extends app_mapper_Mapper implements app_domain_InformationRequestFinder
{
	protected $selectAllStmt;
	protected $selectStmt;
	protected $selectByPostInitiativeIdStmt;
	protected $insertStmt;
	protected $updateStmt;
	protected $id;

	public function __construct()
	{
		if (!self :: $DB)
		{
			self :: $DB = app_controller_ApplicationHelper :: instance()->DB();
		}

		// Select all
		$this->selectAllStmt = self :: $DB->prepare('SELECT * FROM tbl_information_requests ORDER BY id');

		// Select single
		$query = 'SELECT * FROM tbl_information_requests WHERE id = :id';
		$types = array (
			'id' => 'integer'
		);
		$this->selectStmt = self :: $DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array) {
		$obj = new app_domain_InformationRequest($array['id']);
		$obj->setPostInitiativeId($array['post_initiative_id']);
		$obj->setCommunicationId($array['communication_id']);
		$obj->setStatusId($array['status_id']);
		$obj->setTypeId($array['type_id']);
		$obj->setCommTypeId($array['comm_type_id']);
		$obj->setDate($array['date']);
		$obj->setReminderDate($array['reminder_date']);
		$obj->setNotes($array['notes']);
		$obj->setCreatedAt($array['created_at']);
		$obj->setCreatedBy($array['created_by']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e. 
	 * @return string
	 */
	protected function targetClass() {
		return 'app_domain_InformationRequest';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
	public function newId() {
		$this->id = self :: $DB->nextID('tbl_information_requests');
		//		echo "<pre>";
		//		print_r($this->id);
		//		echo "</pre>";

		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object) {

		// Insert
		$query = 'INSERT INTO tbl_information_requests (id, post_initiative_id, communication_id, type_id, status_id, comm_type_id, ' .
		'date, reminder_date, notes, ' .
		'created_at, created_by) ' .
		'VALUES (' .
		':id, :post_initiative_id, :communication_id, :type_id, :status_id, :comm_type_id, ' .
		':date, :reminder_date, :notes, ' .
		':created_at, :created_by)';
		$types = array (
			'id' => 'integer',
			'post_initiative_id' => 'integer',
			'communication_id' => 'integer',
			'type_id' => 'integer',
			'status_id' => 'integer',
			'comm_type_id' => 'integer',
			'date' => 'date',
			'reminder_date' => 'date',
			'notes' => 'text',
			'created_at' => 'date',
			'created_by' => 'integer'
		);
		$this->insertStmt = self :: $DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array (	'id' => $object->getId(), 
						'post_initiative_id' => $object->getPostInitiativeId(),
						'communication_id' => $object->getCommunicationId(),
						'type_id' => $object->getTypeId(),
						'status_id' => $object->getStatusId(), 
						'comm_type_id' => $object->getCommTypeId(),
						'date' => $object->getDate(), 
						'reminder_date' => $object->getReminderDate(), 
						'notes' => $object->getNotes(), 
						'created_at' => $object->getCreatedAt(), 
						'created_by' => $object->getCreatedBy());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object) {

		//		Update
		$query =	'UPDATE tbl_information_requests SET id = :id, post_initiative_id = :post_initiative_id, ' .
					'communication_id = :communication_id, type_id = :type_id, status_id = :status_id, ' .
					'comm_type_id = :comm_type_id, ' .
					'date = :date, reminder_date = :reminder_date, notes = :notes WHERE id = :id';
		$types = array ('id' => 'integer',
						'post_initiative_id' => 'integer',
						'communication_id' => 'integer',
						'type_id' => 'integer',
						'status_id' => 'integer',
						'comm_type_id' => 'integer',
						'date' => 'date',
						'reminder_date' => 'date',
						'notes' => 'text'
		);
		$this->updateStmt = self :: $DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array (	'id' => $object->getId(), 
						'post_initiative_id' => $object->getPostInitiativeId(), 
						'communication_id' => $object->getCommunicationId(),
						'type_id' => $object->getTypeId(),
						'status_id' => $object->getStatusId(), 
						'comm_type_id' => $object->getCommTypeId(),
						'date' => $object->getDate(), 
						'reminder_date' => $object->getReminderDate(), 
						'notes' => $object->getNotes());
		$this->doStatement($this->updateStmt, $data);
	}


	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_information_requests WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);
	}
	
	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id) {
		$values = array (
			'id' => $id
		);
		// factor this out

		// Returns an MDB2_Result object 
		$result = $this->doStatement($this->selectStmt, $values);

		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

	/**
	 * Find all information requests.
	 * @return app_mapper_InformationRequestCollection collection of app_domain_Company objects
	 */
	public function findAll() {
		$result = $this->doStatement($this->selectAllStmt, array ());
		return new app_mapper_InformationRequestCollection($result, $this);
	}

	/**
	 * Find all information requests by post initiative id.
	 * @return app_mapper_InformationRequestCollection collection of app_domain_InformationRequest objects
	 */
	public function findByPostInitiativeId($post_initiative_id) {
		// Select by post initiative id
		$query = 'SELECT ir.*, irs.description as status FROM tbl_information_requests ir JOIN tbl_lkp_information_request_status irs ON ir.status_id = irs.id ' .
		'WHERE post_initiative_id = :post_initiative_id ' .
		'order by date desc';
		$types = array (
			'post_initiative_id' => 'integer'
		);
		$this->selectByPostInitiativeIdStmt = self :: $DB->prepare($query, $types);

		$values = array (
			'post_initiative_id' => $post_initiative_id
		);
		$result = $this->doStatement($this->selectByPostInitiativeIdStmt, $values);
		return new app_mapper_InformationRequestCollection($result, $this);
	}

	/**
	 * Find information request by communication id.
	 * @return app_domain_InformationRequest object
	 */
	public function findByCommunicationId($communication_id)
	{
		$query = 'SELECT ir.*, irs.description as status FROM tbl_information_requests ir JOIN tbl_lkp_information_request_status irs ON ir.status_id = irs.id ' .
		'WHERE communication_id = ' . self::$DB->quote($communication_id, 'integer');
		return $this->load(self::$DB->query($query));
	}

	/** Find status description from $status_id
	 * @return app_mapper_InformationRequestCollection raw array - single item
	 */
	public function lookupStatusById($status_id)
	{
		$values = array (
			'status_id' => $status_id
		);
		$types = array (
			'status_id' => 'integer'
		);
		$query = 'select description from tbl_lkp_information_request_status where id = :status_id';
		$result = $this->doStatement(self :: $DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/** Get all status descriptions
	 * @return app_mapper_InformationRequestCollection raw array
	 */
	public function getStatusAll() 
	{
		$values = array ();
		$types = array ();
		$query = 'select * from tbl_lkp_information_request_status order by sort_order';
		$result = $this->doStatement(self :: $DB->prepare($query, $types), $values);
		$coll = new app_mapper_InformationRequestCollection($result, $this);
		return $coll->toRawArray();
	}
	
	
	/** Find type description from $type_id
	 * @return app_mapper_InformationRequestCollection raw data - single item
	 */
	public function lookupTypeById($type_id)
	{
		$values = array ('type_id' => $type_id);
		$types = array ('type_id' => 'integer');
		$query = 'select description from tbl_lkp_information_request_types where id = :type_id';
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/** Get all type descriptions
	 * @return app_mapper_InformationRequestCollection raw array
	 */
	public function getTypesAll() {

		$values = array ();
		$types = array ();
		$query = 'select * from tbl_lkp_information_request_types order by sort_order';
		$result = $this->doStatement(self :: $DB->prepare($query, $types), $values);
		$coll = new app_mapper_InformationRequestCollection($result, $this);
		return $coll->toRawArray();
	}


	/** Find comm type description from $comm_type_id
	 * @return app_mapper_InformationRequestCollection raw data - single item
	 */
	public function lookupCommTypeById($comm_type_id)
	{
		$values = array ('comm_type_id' => $comm_type_id);
		$types = array ('comm_type_id' => 'integer');
		$query = 'select description from tbl_lkp_information_request_comm_types where id = :comm_type_id';
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/** Get all comm type descriptions
	 * @return app_mapper_InformationRequestCollection raw array
	 */
	public function getCommTypesAll() {

		$values = array ();
		$types = array ();
		$query = 'select * from tbl_lkp_information_request_comm_types order by sort_order';
		$result = $this->doStatement(self :: $DB->prepare($query, $types), $values);
		$coll = new app_mapper_InformationRequestCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find those for a given user in a given range.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findByUserId($user_id, $start_datetime, $end_datetime)
	{
		$query = 'SELECT ir.id, ir.date, ir.notes, pc.propensity, ' .
					'pc.id AS post_id, pc.job_title, pc.full_name, ' .
					'comp.id AS company_id, comp.name AS company_name, comp.website ' .
					'FROM tbl_information_requests AS ir ' .
					'INNER JOIN tbl_post_initiatives AS pi ON ir.post_initiative_id = pi.id ' .
					'INNER JOIN vw_posts_contacts AS pc ON pi.post_id = pc.id ' .
					'INNER JOIN tbl_companies AS comp ON pc.company_id = comp.id ' .
					'WHERE ir.created_by = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND ir.date >= ' . self::$DB->quote($start_datetime, 'timestamp') . ' ' .
					'AND ir.date <= ' . self::$DB->quote($end_datetime, 'timestamp') . ' ' .
					'ORDER BY ir.date';
		$result = self::$DB->query($query);
		return app_mapper_Collection::mdb2ResultToArray($result);
	}

}

?>