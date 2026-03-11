<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_MailerItemResponseMapper extends app_mapper_Mapper implements app_domain_MailerItemResponseFinder
{
	protected static $DB;

	/**
	 * Prepared statement handles.
	 * Explicitly declared to avoid PHP 8.2 dynamic property deprecation.
	 */
	protected $selectAllStmt;
	protected $selectStmt;
	protected $insertStmt;
	protected $updateStmt;

	/**
	 * Last generated identifier.
	 */
	protected $id;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT mir.* ' .
					'FROM tbl_mailer_item_responses AS mir');
					
		// Select single
		$query = 'SELECT mir.* FROM tbl_mailer_item_responses AS mir ' .
					'WHERE mir.id = ?';
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
		$obj = new app_domain_MailerItemResponse($array['id']);
		$obj->setMailerItemId($array['mailer_item_id']);
		$obj->setMailerResponseId($array['mailer_response_id']);
		$obj->setNote($array['note']);
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
		return 'app_domain_MailerItemResponse';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_mailer_item_responses');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		
		$query = 'INSERT INTO tbl_mailer_item_responses (id, mailer_item_id, mailer_response_id, ' .
				'note) VALUES ' .
				'(:id, :mailer_item_id, :mailer_response_id, :note)';
		$types = array(	'id' => 'integer', 
						'mailer_item_id' => 'integer', 
						'mailer_response_id' => 'integer',
						'note' => 'text');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	'id' => $object->getId(),
						'mailer_item_id' => $object->getMailerItemId(),
						'mailer_response_id' => $object->getMailerResponseId(),
						'note' => $object->getNote());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		
		$query = 		'UPDATE tbl_mailer_item_responses ' .
						'SET mailer_item_id = :mailer_item_id, ' .
						'mailer_response_id = :mailer_response_id, ' .
						'note = :note ' .
						'WHERE id = :id';
		$types = array(	'id' => 'integer', 
						'mailer_item_id' => 'integer', 
						'mailer_response_id' => 'integer',
						'note' => 'text');
		$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		
		$data = array(	'id' => $object->getId(),
						'mailer_item_id' => $object->getMailerItemId(),
						'mailer_response_id' => $object->getMailerResponseId(),
						'note' => $object->getNote());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_mailer_item_responses WHERE id = :id'; 
		
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
	 * Find all mailers response items.
	 * @return app_mapper_MailerItemResponseCollection collection of app_domain_MailerResponseItem objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_MailerItemResponseCollection($result, $this);
	}


 	/**
 	 * Find by mailer id.
	 * @return app_domain_DomainObject
	 */
	public function findCountAndDescriptionByMailerId($mailer_id)
	{
		$query = 	'SELECT count(mir.id) as count, lmr.description ' .
					'FROM tbl_mailer_item_responses AS mir ' .
					'join tbl_lkp_mailer_responses lmr on mir.mailer_response_id = lmr.id ' .
					'join tbl_mailer_items mi on mi.id = mir.mailer_item_id ' .
					'WHERE mi.mailer_id = ? ' .
					'group by mailer_response_id';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_MailerItemResponseCollection($result, $this);
		return $col->toRawArray();
	}
	
	
 	/**
 	 * Find mailer items by mailer id which have been responded to.
	 * @return app_domain_DomainObject
	 */
	public function findByMailerId($mailer_id)
	{
		// NOTE: in following query we use tbl_companies rather than vw_companies since the view excludes deleted companies, 
		// and we may want to display deleted companies if they were part of the mailer
		$query = 	'SELECT mi.*, mi.note as response_note, lmr.description, c.id as company_id, c.name as company_name, c.deleted as company_deleted, ' .
					's.*, lkp_county.name as county, lkp_country.name as country, ' .
					'p.id as post_id, p.job_title, p.deleted as post_deleted, p.telephone_fax, ' .
					'con.id as contact_id, con.title, con.first_name, con.surname, con.full_name as contact, con.email ' .
					'FROM tbl_mailer_items AS mi ' .
					'LEFT JOIN tbl_mailer_item_responses mir on mir.mailer_item_id = mi.id ' .
					'LEFT JOIN tbl_lkp_mailer_responses lmr on mir.mailer_response_id = lmr.id ' .
					'LEFT JOIN tbl_post_initiatives pi on mi.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts p on pi.post_id = p.id ' .
					'LEFT JOIN tbl_contacts con on con.post_id = p.id ' .
					'LEFT JOIN tbl_companies c on p.company_id = c.id ' .
					'LEFT JOIN tbl_sites s on c.id = s.company_id ' .
					'LEFT JOIN tbl_lkp_counties lkp_county on s.county_id = lkp_county.id ' .
					'LEFT JOIN tbl_lkp_countries lkp_country on s.country_id = lkp_country.id ' .
					'WHERE mi.mailer_id = ? ' .
		 			'AND mi.response_date is not null ' . 
					'AND p.deleted = 0 ' . 
					'AND con.deleted = 0 ' . 
					'AND c.deleted = 0 ';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_MailerItemResponseCollection($result, $this);
		return $coll->toRawArray();
	}
	
 	/**
 	 * Find by mailer item id.
	 * @return app_domain_DomainObject
	 */
	public function findByMailerItemId($mailer_item_id)
	{
		$query = 	'SELECT mir.*, lmr.description FROM tbl_mailer_item_responses AS mir ' .
					'join tbl_lkp_mailer_responses lmr on mir.mailer_response_id = lmr.id ' .
					'WHERE mir.mailer_item_id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_item_id);
		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_MailerItemResponseCollection($result, $this);
		return $col->toRawArray();
	}
	
	/**
 	 * Find by mailer item id and response id.
	 * @return app_domain_DomainObject
	 */
	public function findByMailerItemIdAndMailerResponseId($mailer_item_id, $mailer_response_id)
	{
		$query = 	'SELECT mir.* FROM tbl_mailer_item_responses AS mir ' .
					'WHERE mir.mailer_item_id = ? AND mir.mailer_response_id = ?';
		$types = array('integer', 'integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_item_id, $mailer_response_id);
		$result = $this->doStatement($stmt, $values);
		return $this->load($result);
	}
	
	/** Find mailer response description from mailer_response_id 
	 * @return raw data - single row
	 */
	public function lookupMailerResponseDescription($mailer_response_id)
	{
		
		$values = array('mailer_response_id' => $mailer_response_id);
		$types = array('mailer_response_id' => 'integer');
		$query = 'select description from tbl_lkp_mailer_responses where id = :mailer_response_id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
 	/**
 	 * Find all possible responses
	 * @return app_domain_DomainObject
	 */
	public function findAllPossibleResponses()
	{
		$query = 	'SELECT lmr.* ' .
					'FROM tbl_lkp_mailer_responses lmr ' .
					'ORDER BY lmr.sort';
		$types = array();
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array();
		$result = $this->doStatement($stmt, $values);
		$col = new app_mapper_MailerCollection($result, $this);
		return $col->toRawArray();
	}

}

?>