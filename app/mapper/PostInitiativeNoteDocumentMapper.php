<?php

/**
 * Defines the app_mapper_CampaignTargetMapper class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/ShadowMapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package Alchemis
 */
class app_mapper_PostInitiativeNoteDocumentMapper extends app_mapper_Mapper implements app_domain_PostInitiativeNoteDocumentFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT * FROM tbl_post_initiative_note_documents WHERE id = ?';
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
		$obj = new app_domain_PostInitiativeNoteDocument($array['id']);
		$obj->setPostInitiativeNoteId($array['post_initiative_note_id']);
		$obj->setDocumentId($array['document_id']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_post_initiative_note_documents');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_post_initiative_note_documents (id, post_initiative_note_id, document_id) ' .
					'VALUES (?, ?, ?)';
				
		$types = array('integer', 'integer', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types);

		$data = array(	$object->getId(), 
						$object->getPostInitiativeNoteId(), 
						$object->getDocumentId());
		$this->doStatement($this->insertStmt, $data);	
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_post_initiative_note_documents SET post_initiative_note_id = ?, document_id = ? ' .
					'WHERE id = ?';
				
		$types = array('integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types);

		$data = array(	$object->getPostInitiativeNoteId(), 
						$object->getYearMonth(), 
						$object->getDocumentId());
		$this->doStatement($updateStmt, $data);	
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_post_initiative_note_documents WHERE id = ?';
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
 	 * Find all post initiative note documents by post initiative note ID
 	 * @param integer post_initiative_note_id post_initiative_note_id
	 * @return app_mapper_CampaignTargetCollection collection of app_domain_PostInitiativeNoteDocuments objects
	 */
	public function findByPostInitiativeNoteId($post_initiative_note_id)
	{
		$query = 'SELECT pind.*, d.* FROM tbl_post_initiative_note_documents pind ' .
		           	'JOIN tbl_documents d on pind.document_id = d.id ' .
					'WHERE post_initiative_note_id = ' . self::$DB->quote($post_initiative_note_id, 'integer') . ' ' .
					'ORDER BY d.id';
// 		echo $query;
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
// 		print_r($result);
		
		return $result;
		
// 		return new app_mapper_CampaignTargetCollection($result, $this);
		
		
//		$values = array($campaign_id);
//		$query = 'SELECT * FROM tbl_campaign_targets WHERE campaign_id = ? order by `year_month` desc';
//		$types = array('integer');
//		$stmt = self::$DB->prepare($query, $types);
//		$result = $this->doStatement($stmt, $values);
//		return new app_mapper_CampaignTargetCollection($result, $this);
	}

}

?>