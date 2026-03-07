<?php

/**
 * Defines the app_mapper_CommunicationAttachmentMapper class. 
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
class app_mapper_CommunicationAttachmentMapper extends app_mapper_Mapper implements app_domain_CommunicationAttachmentFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT c.*, d.* ' .
				'FROM tbl_communication_attachments c ' .
				'JOIN tbl_documents d on c.document_id = d.id ' .
				'WHERE c.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_communication_attachments';
		$types = array();
		$this->selectAllStmt = self::$DB->prepare($query, $types);
		
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_CommunicationAttachment($array['id']);
		$obj->setCommunicationId($array['communication_id']);
		$obj->setDocumentId($array['document_id']);
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
		return 'app_domain_CommunicationAttachment';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_communication_attachments');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_communication_attachments (id, communication_id, document_id) VALUES ' .
				'(?, ?, ?)';
				
		$types = array('integer', 'integer', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	$object->getId(), 
						$object->getCommunicationId(), 
						$object->getDocumentId());
						
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_communication_attachments SET communication_id = ?, document_id = ? ' .
				'WHERE id = ?';
				
		$types = array(	'integer', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	$object->getCommunicationId(), 
						$object->getDocumentId(), 
						$object->getId());
		$this->doStatement($updateStmt, $data);	
	}
	
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_communication_attachments WHERE id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($stmt, $data);
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
	 * Find all campaign_nbms.
	 * @return app_mapper_CommunicationAttachmentCollection collection of app_domain_CommunicationAttachment objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_CommunicationAttachmentCollection($result, $this);
	}

	/**
	 * Find all communication attachments by communication id.
	 * @return app_mapper_CommunicationAttachmentCollection collection of app_mapper_CommunicationAttachment objects
	 */
	public function findByCommunicationId($communication_id)
	{
		$query = 'SELECT c.*, d.* ' .
				'FROM tbl_communication_attachments c ' .
				'JOIN tbl_documents d on c.document_id = d.id ' .
				'WHERE c.communication_id = ' . self::$DB->quote($communication_id, 'integer') . ' ' .
				'ORDER BY c.id';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CommunicationAttachmentCollection($result, $this);
	}
	
	/**
	 * Get count of all communication attachments by communication id.
	 * @return raw data - single item
	 */
	public function findCountByCommunicationId($communication_id)
	{
		$query = 'SELECT count(id) ' .
				'FROM tbl_communication_attachments ' .
				'WHERE communication_id = ' . self::$DB->quote($communication_id, 'integer');
		return $result = self::$DB->queryOne($query);
	}
	
			
}

?>