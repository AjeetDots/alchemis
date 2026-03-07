<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_MailerItemMapper extends app_mapper_Mapper implements app_domain_MailerItemFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select all
//		$this->selectAllStmt = self::$DB->prepare('SELECT mi.* ' .
//					'FROM tbl_mailer_items AS mi');
					
		// Select single
//		$query = 'SELECT mi.* FROM tbl_mailer_items AS mi ' .
//					'WHERE mi.id = ?';
//		$types = array('integer');
//		$this->selectStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_MailerItem($array['id']);
		$obj->setMailerId($array['mailer_id']);
		$obj->setPostInitiativeId($array['post_initiative_id']);
		$obj->setDespatchedDate($array['despatched_date']);
		$obj->setDespatchedCommunicationId($array['despatched_communication_id']);
		$obj->setResponseDate($array['response_date']);
		$obj->setResponseCommunicationId($array['response_communication_id']);
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
		return 'app_domain_MailerItem';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_mailer_items');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		
		$query = 'INSERT INTO tbl_mailer_items (id, mailer_id, post_initiative_id, despatched_date, despatched_communication_id, ' .
				'response_date, response_communication_id, note) VALUES ' .
				'(:id, :mailer_id, :post_initiative_id, :despatched_date, :despatched_communication_id, :response_date, :response_communication_id, :note)';
		$types = array(	'id' => 'integer', 
						'mailer_id' => 'integer', 
						'post_initiative_id' => 'integer',
						'despatched_date' => 'date', 
						'despatched_communication_id' => 'integer',
						'response_date' => 'date',
						'response_communication_id' => 'integer', 
						'note' => 'text');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	'id' => $object->getId(),
						'mailer_id' => $object->getMailerId(),
						'post_initiative_id' => $object->getPostInitiativeId(),
						'despatched_date' => $object->getDespatchedDate(),
						'despatched_communication_id' => $object->getDespatchedCommunicationId(),
						'response_date' => $object->getResponseDate(),
						'response_communication_id' => $object->getResponseCommunicationId(), 
						'note' => $object->getNote());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		
		$query = 		'UPDATE tbl_mailer_items SET mailer_id = :mailer_id, post_initiative_id = :post_initiative_id, ' .
						'despatched_date = :despatched_date, despatched_communication_id = :despatched_communication_id, ' .
						'response_date = :response_date, response_communication_id = :response_communication_id, note = :note ' .
						'WHERE id = :id';
		$types = array(	'id' => 'integer', 
						'mailer_id' => 'integer', 
						'post_initiative_id' => 'integer',
						'despatched_date' => 'date', 
						'despatched_communication_id' => 'integer',
						'response_date' => 'date',
						'response_communication_id' => 'integer', 
						'note' => 'text');
		$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		
		$data = array(	'id' => $object->getId(),
						'mailer_id' => $object->getMailerId(),
						'post_initiative_id' => $object->getPostInitiativeId(),
						'despatched_date' => $object->getDespatchedDate(),
						'despatched_communication_id' => $object->getDespatchedCommunicationId(),
						'response_date' => $object->getResponseDate(),
						'response_communication_id' => $object->getResponseCommunicationId(), 
						'note' => $object->getNote());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_mailer_items WHERE id = :id'; 
		
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
		$query = 'SELECT mi.* FROM tbl_mailer_items AS mi ' .
					'WHERE mi.id = ?';
		$types = array('integer');
		$selectStmt = self::$DB->prepare($query, $types);
		
		$values = array($id);
		// Returns an MDB2_Result object 
		$result = $this->doStatement($selectStmt, $values);
		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

	/**
	 * Find all mailers items.
	 * @return app_mapper_MailerItemCollection collection of app_domain_MailerItem objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_MailerItemCollection($result, $this);
	}

 	/**
 	 * Find mailer items by mailer id.
	 * @return app_domain_DomainObject
	 */
	public function findByMailerId($mailer_id)
	{
		// NOTE: in following query we use tbl_companies rather than vw_companies since the view excludes deleted companies, 
		// and we may want to display deleted companies if they were part of the mailer
		
				$query = 	'SELECT mi.*, c.id as company_id, c.name as company_name, c.deleted as company_deleted, ' .
					'p.id as post_id, p.job_title, p.deleted as post_deleted, con.full_name as contact ' .
					'FROM tbl_mailer_items AS mi ' .
					'LEFT JOIN tbl_post_initiatives pi on mi.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts p on pi.post_id = p.id ' .
					'LEFT JOIN vw_contacts con on con.post_id = p.id ' .
					'LEFT JOIN tbl_companies c on p.company_id = c.id ' .
					'WHERE mi.mailer_id = ' . self::$DB->quote($mailer_id, 'integer');
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		
//		$query = 	'SELECT mi.*, c.id as company_id, c.name as company_name, c.deleted as company_deleted, ' .
//					'p.id as post_id, p.job_title, p.deleted as post_deleted, con.full_name as contact ' .
//					'FROM tbl_mailer_items AS mi ' .
//					'LEFT JOIN tbl_post_initiatives pi on mi.post_initiative_id = pi.id ' .
//					'LEFT JOIN tbl_posts p on pi.post_id = p.id ' .
//					'LEFT JOIN vw_contacts con on con.post_id = p.id ' .
//					'LEFT JOIN tbl_companies c on p.company_id = c.id ' .
//					'WHERE mi.mailer_id = ?';
//		$types = array('integer');
//		$stmt = self::$DB->prepare($query, $types);
//		
//		$values = array($mailer_id);
//		$result = $this->doStatement($stmt, $values);
//		$coll = new app_mapper_MailerCollection($result, $this);
//		return $coll->toRawArray();
	}

	/**
 	 * Find mailer items by mailer id which have NOT been despatched.
	 * @return app_domain_DomainObject
	 */
	public function findNotDespatchedByMailerId($mailer_id)
	{
		// NOTE: in following query we use tbl_companies rather than vw_companies since the view excludes deleted companies, 
		// and we may want to display deleted companies if they were part of the mailer
		$query = 	'SELECT mi.*, c.id as company_id, c.name as company_name, c.deleted as company_deleted, ' .
					's.*, lkp_county.name as county, lkp_country.name as country, ' .
					'p.id as post_id, p.job_title, p.deleted as post_deleted, p.telephone_fax, ' .
					'con.id as contact_id, con.title, con.first_name, con.surname, con.full_name as contact, con.email ' .
					'FROM tbl_mailer_items AS mi ' .
					'LEFT JOIN tbl_post_initiatives pi on mi.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts p on pi.post_id = p.id ' .
					'LEFT JOIN vw_contacts con on con.post_id = p.id ' .
					'LEFT JOIN tbl_companies c on p.company_id = c.id ' .
					'LEFT JOIN tbl_sites s on c.id = s.company_id ' .
					'LEFT JOIN tbl_lkp_counties lkp_county on s.county_id = lkp_county.id ' .
					'LEFT JOIN tbl_lkp_countries lkp_country on s.country_id = lkp_country.id ' .
					'WHERE mi.mailer_id = ? ' .
					'AND mi.despatched_date is null';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_MailerCollection($result, $this);
		return $coll->toRawArray();
		
	}
	
	/**
 	 * Find mailer items by mailer id which have NOT been despatched.
 	 * Returns data in ISO-8859-1 format which is needed by spreadsheet writer 
 	 * NOTE: As at 26/01/09 the version of PEAR Excel spreadsheet writer could not cope with
 	 * UTF-8 encoding. There is apprarently a later version which could, but this is a quick
 	 * fix for Rob Anning who needed to do a mailer export.
 	 * (rather than upgrading PEAR excel spreadsheet writer) 
	 * @return app_domain_DomainObject
	 */
	public function findNotDespatchedByMailerIdForExport($mailer_id)
	{
		// NOTE: in following query we use tbl_companies rather than vw_companies since the view excludes deleted companies, 
		// and we may want to display deleted companies if they were part of the mailer
		$query = 	'SELECT mi.*, c.id as company_id, c.name as company_name, c.deleted as company_deleted, ' .
					's.*, lkp_county.name as county, lkp_country.name as country, ' .
					'p.id as post_id, p.job_title, p.deleted as post_deleted, p.telephone_fax, ' .
					'con.id as contact_id, con.title, con.first_name, con.surname, con.full_name as contact, con.email ' .
					'FROM tbl_mailer_items AS mi ' .
					'LEFT JOIN tbl_post_initiatives pi on mi.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts p on pi.post_id = p.id ' .
					'LEFT JOIN vw_contacts con on con.post_id = p.id ' .
					'LEFT JOIN tbl_companies c on p.company_id = c.id ' .
					'LEFT JOIN tbl_sites s on c.id = s.company_id ' .
					'LEFT JOIN tbl_lkp_counties lkp_county on s.county_id = lkp_county.id ' .
					'LEFT JOIN tbl_lkp_countries lkp_country on s.country_id = lkp_country.id ' .
					'WHERE mi.mailer_id = ? ' .
					'AND mi.despatched_date is null';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_MailerCollection($result, $this);
		return $coll->toRawArrayWithEncodingChange('UTF-8','ISO-8859-1');
		
	}
	
	
 	/**
 	 * Find mailer items by mailer id which have been despatched.
	 * @return app_domain_DomainObject
	 */
	public function findDespatchedByMailerId($mailer_id)
	{
		// NOTE: in following query we use tbl_companies rather than vw_companies since the view excludes deleted companies, 
		// and we may want to display deleted companies if they were part of the mailer
		$query = 	'SELECT mi.*, c.id as company_id, c.name as company_name, c.deleted as company_deleted, ' .
					's.*, lkp_county.name as county, lkp_country.name as country, ' .
					'p.id as post_id, p.job_title, p.deleted as post_deleted, p.telephone_fax, ' .
					'con.id as contact_id, con.title, con.first_name, con.surname, con.full_name as contact, con.email ' .
					'FROM tbl_mailer_items AS mi ' .
					'LEFT JOIN tbl_post_initiatives pi on mi.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts p on pi.post_id = p.id ' .
					'LEFT JOIN vw_contacts con on con.post_id = p.id ' .
					'LEFT JOIN tbl_companies c on p.company_id = c.id ' .
					'LEFT JOIN tbl_sites s on c.id = s.company_id ' .
					'LEFT JOIN tbl_lkp_counties lkp_county on s.county_id = lkp_county.id ' .
					'LEFT JOIN tbl_lkp_countries lkp_country on s.country_id = lkp_country.id ' .
					'WHERE mi.mailer_id = ? ' .
					'AND mi.despatched_date is not null';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_MailerCollection($result, $this);
		return $coll->toRawArray();
	}
	
/**
 	 * Find mailer items by mailer id which have been despatched.
 	 * Returns data in ISO-8859-1 format which is needed by spreadsheet writer 
 	 * NOTE: As at 26/01/09 the version of PEAR Excel spreadsheet writer could not cope with
 	 * UTF-8 encoding. There is apprarently a later version which could, but this is a quick
 	 * fix for Rob Anning who needed to do a mailer export.
 	 * (rather than upgrading PEAR excel spreadsheet writer) 
	 * @return app_domain_DomainObject
	 */
	public function findDespatchedByMailerIdForExport($mailer_id)
	{
		// NOTE: in following query we use tbl_companies rather than vw_companies since the view excludes deleted companies, 
		// and we may want to display deleted companies if they were part of the mailer
		$query = 	'SELECT mi.*, c.id as company_id, c.name as company_name, c.deleted as company_deleted, ' .
					's.*, lkp_county.name as county, lkp_country.name as country, ' .
					'p.id as post_id, p.job_title, p.deleted as post_deleted, p.telephone_fax, ' .
					'con.id as contact_id, con.title, con.first_name, con.surname, con.full_name as contact, con.email ' .
					'FROM tbl_mailer_items AS mi ' .
					'LEFT JOIN tbl_post_initiatives pi on mi.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts p on pi.post_id = p.id ' .
					'LEFT JOIN vw_contacts con on con.post_id = p.id ' .
					'LEFT JOIN tbl_companies c on p.company_id = c.id ' .
					'LEFT JOIN tbl_sites s on c.id = s.company_id ' .
					'LEFT JOIN tbl_lkp_counties lkp_county on s.county_id = lkp_county.id ' .
					'LEFT JOIN tbl_lkp_countries lkp_country on s.country_id = lkp_country.id ' .
					'WHERE mi.mailer_id = ? ' .
					'AND mi.despatched_date is not null';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_MailerCollection($result, $this);
		return $coll->toRawArrayWithEncodingChange('UTF-8','ISO-8859-1');
		}
	
	
	
 	/**
 	 * Count mailer items by mailer id .
	 * @return app_domain_DomainObject
	 */
	public function countByMailerId($mailer_id)
	{
		$query = 	'SELECT count(*) FROM tbl_mailer_items AS mi ' .
					'WHERE mi.mailer_id = ? ';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
	/**
 	 * Count despatched mailer items by mailer id .
	 * @return app_domain_DomainObject
	 */
	public function countDespatchedDateByMailerId($mailer_id)
	{
		$query = 	'SELECT count(*) FROM tbl_mailer_items AS mi ' .
					'WHERE mi.mailer_id = ? ' .
					'AND mi.despatched_date is not null';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
	
	/**
 	 * Count mailer items responsed to by mailer id .
 	 * NOTE: only counts first response for each mailer item
	 * @return app_domain_DomainObject
	 */
	public function countResponseDateByMailerId($mailer_id)
	{
		$query = 	'SELECT count(*) FROM tbl_mailer_items AS mi ' .
					'WHERE mi.mailer_id = ? ' .
					'AND mi.response_date is not null';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		
		$values = array($mailer_id);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
	 /** Lookup id by post_initiative_id exist and mailer_id
	 * @param integer $post_initiative_id
	 * @param integer $mailer_id
	 * @return raw data - single item
	 */
	public function lookupIdByPostInitiativeIdByMailer($post_initiative_id, $mailer_id)
	{
		$values = array('post_initiative_id' => $post_initiative_id, 'mailer_id' => $mailer_id);
		$types = array('post_initiative_id' => 'integer', 'mailer_id' => 'integer');
		$query = 'select id from tbl_mailer_items where mailer_id = :mailer_id and post_initiative_id = :post_initiative_id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}
}

?>