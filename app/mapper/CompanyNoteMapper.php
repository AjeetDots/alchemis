<?php

/**
 * Defines the app_mapper_CompanyNoteMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/NoteMapper.php');
require_once('app/mapper/CompanyMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_CompanyNoteMapper extends app_mapper_NoteMapper implements app_domain_CompanyNoteFinder
{
	protected function init()
	{
		// Select single
		$query = 'SELECT n.*, u.handle, u.name ' .
					'FROM tbl_company_notes AS n ' .
					'INNER JOIN tbl_rbac_users AS u ON n.created_by = u.id ' .
					'WHERE n.id = ? ' .
					'ORDER BY n.created_at DESC';
		$types = array('integer');
		$this->select_stmt = self::$DB->prepare($query, $types);
		
		// Select by company ID
		$query = 'SELECT n.*, u.handle, u.name ' .
					'FROM tbl_company_notes AS n ' .
					'INNER JOIN tbl_rbac_users AS u ON n.created_by = u.id ' .
					'WHERE n.company_id = ? ' .
					'ORDER BY n.created_at DESC';
		$types = array('integer');
		$this->select_by_company_id_stmt = self::$DB->prepare($query, $types);
		
		// Insert
		$query = 'INSERT INTO tbl_company_notes (id, company_id, created_at, created_by, note) ' .
					'VALUES (?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'text', 'integer', 'text');
		$this->insert_stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		
		// Update
		$query = 'UPDATE tbl_company_notes SET company_id = ?, created_at = ?, created_by = ?, note = ? WHERE id = ?';
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
		$obj = new app_domain_CompanyNote($array['id']);
//		$obj->setCompany(app_mapper_CompanyMapper::find($array['company_id']));
		$obj->setCompanyId($array['company_id']);
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
		return 'app_domain_CompanyNote';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_company_notes');
		return $this->id;
	}
	
	

	/**
	 * Find the notes for a given company.
	 * @param integer $company_id
	 * @return app_mapper_CompanyNoteCollection collection of app_domain_CompanyNote objects
	 */
	public function findByCompanyId($company_id)
	{
		$data = array($company_id);
		$result = $this->doStatement($this->select_by_company_id_stmt, $data);
		return new app_mapper_CompanyNoteCollection($result, $this);
	}

	/** Find the notes for a given company.
	 * @param integer $company_id
	 * @return array
	 */
	public function findCountByCompanyId($company_id)
	{
		$query = 'SELECT count(id) FROM tbl_company_notes cn ' .
					'WHERE cn.company_id = ' . self::$DB->quote($company_id, 'integer');
		return self::$DB->queryOne($query);
	}
	
}

?>