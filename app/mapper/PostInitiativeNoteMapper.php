<?php

/**
 * Defines the app_mapper_PostInitiativeNoteMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/NoteMapper.php');
require_once('app/mapper/PostInitiativeMapper.php');

/**
 * @package Alchemis
 */
//class app_mapper_PostInitiativeNoteMapper extends app_mapper_NoteMapper implements app_domain_PostInitiativeNoteFinder
class app_mapper_PostInitiativeNoteMapper extends app_mapper_Mapper implements app_domain_PostInitiativeNoteFinder
{
	protected function init() {}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_PostInitiativeNote($array['id']);
		$obj->setPostInitiativeId($array['post_initiative_id']);
		$obj->setCreatedAt($array['created_at']);
		$obj->setCreatedBy($array['created_by']);
		$obj->setNote($array['note']);
		if (array_key_exists('summary', $array)) {
			$obj->setSummary($array['summary']);
		} else {
			$obj->setSummary(null);
		}
		if (array_key_exists('note_type_id', $array)) {
			$obj->setPostInitiativeNoteTypeId($array['note_type_id']);
		}
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_post_initiative_notes');
		return $this->id;
	}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e. 
	 * @return string
	 */
	protected function targetClass() {}
	
	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		
		if (!is_null($object->getParentId()))
		{
			if (!is_null($object->getPostInitiativeNoteTypeId()))
			{
				$query = 'INSERT INTO tbl_post_initiative_notes (id, post_initiative_id, created_at, created_by, note, summary, note_type_id) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?)';
				$types = array('integer', 'integer', 'text', 'integer', 'text', 'text', 'integer');
				$data = array($object->getId(), $object->getParentId(), $object->getCreatedAt(),
					$object->getCreatedBy(), $object->getNote(), $object->getSummary(), $object->getPostInitiativeNoteTypeId());
			} else {
				$query = 'INSERT INTO tbl_post_initiative_notes (id, post_initiative_id, created_at, created_by, note, summary) ' .
										'VALUES (?, ?, ?, ?, ?, ?)';
				$types = array('integer', 'integer', 'text', 'integer', 'text', 'text');
				$data = array($object->getId(), $object->getParentId(), $object->getCreatedAt(),
					$object->getCreatedBy(), $object->getNote(), $object->getSummary());
			}
			
			$insert_stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
			$this->doStatement($insert_stmt, $data);
		}
		else
		{
			throw new Exception('Parent ID is null');
		}
	}
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		if (!is_null($object->getParentId()))
		{
			$query = 'UPDATE tbl_post_initiative_notes SET post_initiative_id = ?, created_at = ?, created_by = ?, note = ?, summary = ? WHERE id = ?';
			$types = array('integer', 'text', 'integer', 'text', 'text', 'integer');
			$update_stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

			$data = array($object->getParentId(), $object->getCreatedAt(), 
							$object->getCreatedBy(), $object->getNote(), $object->getSummary(), $object->getId());
			$this->doStatement($update_stmt, $data);
		}
		else
		{
			throw new Exception('Parent ID is null');
		}
	}
	
	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_post_initiative_notes WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);
	}
	
	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT n.*, u.handle, u.name ' .
					'FROM tbl_post_initiative_notes AS n ' .
					'INNER JOIN tbl_rbac_users AS u ON n.created_by = u.id ' .
					'LEFT JOIN tbl_lkp_post_initiative_note_types AS pint ON n.note_type_id = pint.id ' .
					'WHERE n.id = ? ' .
					'ORDER BY n.created_at DESC';
		$types = array('integer');
		$select_stmt = self::$DB->prepare($query, $types);

		$data = array($id);
		$result = $this->doStatement($select_stmt, $id);
		return $this->load($result);
	}
	
	/**
	 * Find the notes for a given post initiative.
	 * @param integer $id
	 * @return app_mapper_PostInitiativeNoteCollection collection of app_domain_PostInitiativeNote objects
	 */
	public function findByPostInitiativeId($id)
	{
		$query = 'SELECT n.*, u.handle, u.name ' .
					'FROM tbl_post_initiative_notes AS n ' .
					'INNER JOIN tbl_rbac_users AS u ON n.created_by = u.id ' .
					'WHERE n.post_initiative_id = ? ' .
					'ORDER BY n.created_at DESC';
		$types = array('integer');
		$select_by_post_initiative_id_stmt = self::$DB->prepare($query, $types);
		
		$data = array($id);
		$result = $this->doStatement($select_by_post_initiative_id_stmt, $data);
		return new app_mapper_CompanyNoteCollection($result, $this);
	}

	/**
	* Find the note for a given post initiative note id.
	* @param integer $id
	* @return app_mapper_PostInitiativeNoteCollection collection of app_domain_PostInitiativeNote objects
	*/
	public function findByPostInitiativeNoteId($id)
	{
		$query = 'SELECT n.*, u.handle, u.name ' .
						'FROM tbl_post_initiative_notes AS n ' .
						'INNER JOIN tbl_rbac_users AS u ON n.created_by = u.id ' .
						'WHERE n.id = ?';
		$types = array('integer');
		$select_by_post_initiative_id_stmt = self::$DB->prepare($query, $types);
	
		$data = array($id);
		$result = $this->doStatement($select_by_post_initiative_id_stmt, $data);
		return new app_mapper_CompanyNoteCollection($result, $this);
	}
}

?>