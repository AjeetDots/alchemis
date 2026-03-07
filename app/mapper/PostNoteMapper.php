<?php

/**
 * Defines the app_mapper_PostNoteMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/NoteMapper.php');
require_once('app/mapper/PostMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_PostNoteMapper extends app_mapper_NoteMapper implements app_domain_PostNoteFinder
{
	protected function init()
	{
		// Select single
		$query = 'SELECT n.*, u.handle, u.name ' .
					'FROM tbl_post_notes AS n ' .
					'INNER JOIN tbl_rbac_users AS u ON n.created_by = u.id ' .
					'WHERE n.id = ? ' .
					'ORDER BY n.created_at DESC';
		$types = array('integer');
		$this->select_stmt = self::$DB->prepare($query, $types);
		
		// Select by company ID
		$query = 'SELECT n.*, u.handle, u.name ' .
					'FROM tbl_post_notes AS n ' .
					'INNER JOIN tbl_rbac_users AS u ON n.created_by = u.id ' .
					'WHERE n.post_id = ? ' .
					'ORDER BY n.created_at DESC';
		$types = array('integer');
		$this->select_by_post_id_stmt = self::$DB->prepare($query, $types);
		
		// Insert
		$query = 'INSERT INTO tbl_post_notes (id, post_id, created_at, created_by, note) ' .
					'VALUES (?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'text', 'integer', 'text');
		$this->insert_stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		
		// Update
		$query = 'UPDATE tbl_post_notes SET post_id = ?, created_at = ?, created_by = ?, note = ? WHERE id = ?';
		$types = array('integer', 'text', 'integer', 'text', 'integer');
		$this->update_stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_PostNote($array['id']);
		$obj->setPostId($array['post_id']);
		$obj->setCreatedAt($array['created_at']);
		$obj->setCreatedBy($array['created_by']);
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
		return 'app_domain_PostNote';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_post_notes');
		return $this->id;
	}
	
	

	/**
	 * Find the notes for a given post.
	 * @param integer $company_id
	 * @return app_mapper_PostNoteCollection collection of app_domain_PostNote objects
	 */
	public function findByPostId($post_id)
	{
		$data = array($post_id);
		$result = $this->doStatement($this->select_by_post_id_stmt, $data);
		return new app_mapper_PostNoteCollection($result, $this);
	}
	
	
	/** Find the notes for a given post.
	 * @param integer $post_id
	 * @return array
	 */
	public function findCountByPostId($post_id)
	{
		$query = 'SELECT count(id) FROM tbl_post_notes pn ' .
					'WHERE pn.post_id = ' . self::$DB->quote($post_id, 'integer');
		return self::$DB->queryOne($query);
	}
	

}

?>