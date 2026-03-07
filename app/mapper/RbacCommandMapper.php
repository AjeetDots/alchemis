<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/RbacCollections.php');
require_once('app/domain.php');

/**
 * 
 */
class app_mapper_RbacCommandMapper extends app_mapper_Mapper implements app_domain_RbacCommandFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$query = 'SELECT * FROM tbl_rbac_commands ORDER BY name';
		$this->selectAllStmt = self::$DB->prepare($query);

		// Select single
		$query = 'SELECT C.* ' .
					'FROM rbac_commands C ' .
					'WHERE C.id = :id';
		$types = array('id' => 'integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

//		// Update
//		$query = 'UPDATE tbl_campaigns SET id = :id, clientId = :clientId, name = :name WHERE id = :id';
//		$types = array('id' => 'integer', 'clientId' => 'integer', 'name' => 'text', 'id' => 'integer');
//		$this->updateStmt = self::$DB->prepare($query, $types);
//		
//		// Insert
//		$query = 'INSERT INTO tbl_campaigns (id, clientId, name) VALUES (:id, :clientId, :name)';
//		$types = array('id' => 'integer', 'clientId' => 'integer', 'name' => 'text');
//		// parameters:
//		// 1) the query (notice we are using named parameters, but we could also use ? instead
//		// 2) types of the placeholders
//		// 3) true denotes a DML statement
//		$result_types = MDB2_PREPARE_MANIP;
////		$result_types = MDB2_PREPARE_RESULT;
//		$this->insertStmt = self::$DB->prepare($query, $types, $result_types);
	}

	
	/**
	 * 
	 * @return app_mapper_RbacCommandCollection
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_RbacCommandCollection($result, $this);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
//		echo "<p><b>app_mapper_VenueMapper::doLoad($array)</b></p>";
//		echo "<pre>";
//		print_r($array);
//		echo "</pre>";
		$obj = new app_domain_RbacCommand($array['id']);
//		echo "here 1";
		$obj->setName($array['name']);
//		$obj->setDescription($array['description']);
//		echo "<br />here 2";
//		$space_mapper = new app_mapper_SpaceMapper();
////		echo "<br />here 3";
//		$space_collection = $space_mapper->findByVenue($array['id']);
////		echo "<br />here 4";
//		$obj->setSpaces($space_collection);
////		echo "<br />here 5";
		$obj->markClean();
//		echo "<br />here 6";
		return $obj;
	}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e. 
	 * @return string
	 */
	protected function targetClass()
	{
		return 'app_domain_RbacCommand';
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
//		$name = $object->getName();
//		$id = $object->getId();
//		$values = array('id' => $id, 'name' => $name);
//		
//		
////		echo "<h1>app_mapper_VenueMapper::doInsert(".get_class($object).")<pre>";
////		print_r($values);
////		echo "</pre></h1>";
//		
//		try
//		{
//			$this->doStatement($this->insertStmt, $values);
//		}
//		catch (app_base_MDB2Exception $e)
//		{
//			// do nothing
//		}
	}
	
	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
//		$this->id = self::$DB->nextID('tbl_campaigns');
//		return $this->id;
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	public function update(app_domain_DomainObject $object)
	{
////		$query = 'UPDATE tbl_campaigns SET id = :id, clientId = :clientId, name = :name WHERE id = :id';
////		$types = array('id' => 'integer', 'clientId' => 'integer', 'name' => 'text', 'id' => 'integer');
//		$values = array($object->getId(), $object->getClientId(), $object->getName(), $object->getId());
//		$this->doStatement($this->updateStmt, $values);
	}
	
	# custom
	# .............................................................................................
    # end_custom
	

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see load()
	 */
	public function doFind($id)
	{
		$values = array('id' => $id);
		// factor this out
		
		// Returns an MDB2_Result object 
		$result = $this->doStatement($this->selectStmt, $values);
		
		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

}

?>