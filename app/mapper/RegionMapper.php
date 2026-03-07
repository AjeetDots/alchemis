<?php

/**
 * Defines the app_mapper_RegionMapper class. 
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
class app_mapper_RegionMapper extends app_mapper_Mapper implements app_domain_RegionFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT * FROM tbl_lkp_regions r WHERE r.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_lkp_regions';
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
		$obj = new app_domain_Region($array['id']);
		$obj->setName($array['name']);
		$obj->setDescription($array['description']);
		$obj->setPostcodes();
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
		return 'app_domain_Region';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_lkp_regions');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_lkp_regions (id, name, description) VALUES ' .
				'(?, ?, ?)';
				
		$types = array('integer', 'text', 'text');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getId(), $object->getName(), $object->getDescription());
		$this->doStatement($this->insertStmt, $data);	
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_lkp_regions SET name = ?, description = ? ' .
				'WHERE id = ?';
				
		$types = array(	'text', 'text', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getName(), $object->getDescription(), $object->getId());
		$this->doStatement($updateStmt, $data);	
	
	}
	
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_lkp_regions WHERE id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($stmt, $data);
	}
	
	/** 
	 * Responsible for adding new postcode_ids to tbl_lkp_region_postcodes
	 * @param $region_id - id of the region being added to
	 * @param $postcode_id - id of the postcode being added 
	 */
	public function addPostcode($region_id, $postcode_id)
	{
		$query = 'INSERT INTO tbl_lkp_region_postcodes (region_id, postcode_id) VALUES ' .
				'(?, ?)';
				
		$types = array(	'integer', 'integer');
		$stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($region_id, $postcode_id);
		$this->doStatement($stmt, $data);	
		
		return true;
	}
	
	/** 
	 * Responsible for removing a postcode_ids from tbl_lkp_region_postcodes
	 * @param $region_id - id of the region being removed from 
	 * @param $postcode_id - id of the postcode being removed from 
	 */
	public function deletePostcode($region_id, $postcode_id)
	{
		$query = 'DELETE FROM tbl_lkp_region_postcodes WHERE region_id = ? and postcode_id = ?';
				
		$types = array('integer', 'integer');
		$stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($region_id, $postcode_id);
		$this->doStatement($stmt, $data);	
		
		return true;
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
	 * Find all contacts.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_RegionCollection($result, $this);
	}
	
	/**
	 * 
	 * @param integer $d of the region for which to find postcodes
	 * @return array of raw data
	 */
	public function findPostcodes($id)
	{
		$types = array ('id' => 'integer');
		$values = array('id' => $id);
		
		$query = 	'select lkp_p.id as postcode_id, lkp_p.postcode from tbl_lkp_region_postcodes lkp_rp ' .
					'join tbl_lkp_postcodes lkp_p on lkp_rp.postcode_id = lkp_p.id	' .
					'where region_id = :id'; 
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_RegionCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * @return array of raw data
	 */
	public function findPostcodesAll()
	{
		$types = array ();
		$values = array();
		
		$query = 	'select * from tbl_lkp_postcodes order by postcode'; 
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_RegionCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * @return array of raw data
	 */
	public function findPostcodesStartWith($search)
	{
		$values = array($search . '%');
		$types = array('text');
		$query = 'select * from tbl_lkp_postcodes where postcode like ? order by postcode'; 
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt, $values);
		$coll = new app_mapper_RegionCollection($result, $this);
		return $coll->toRawArray();
	}

}

?>