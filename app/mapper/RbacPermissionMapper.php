<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

require_once('app/mapper/RbacCollections.php');


/**
 * @package alchemis
 */
class app_mapper_RbacPermissionMapper extends app_mapper_Mapper implements app_domain_RbacPermissionFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_rbac_roles ORDER BY name');
		
		// Select single
		$query = 'SELECT * FROM tbl_rbac_roles WHERE id = :id';
		$types = array('id' => 'integer');
		$this->selectStmt = self::$DB->prepare($query, $types);
		
		// Other
		$query = 'SELECT p.* FROM tbl_rbac_role_permissions rp ' .
					'INNER JOIN tbl_rbac_permissions p ON rp.permission_id = p.id ' .
					'WHERE rp.role_id = :role_id';
		$types = array('role_id' => 'integer');
		$this->findByRoleStmt = self::$DB->prepare($query, $types);

		// Other
		$query = 'SELECT p.* FROM tbl_rbac_role_permissions rp ' .
					'INNER JOIN tbl_rbac_permissions p ON rp.permission_id = p.id ' .
					'INNER JOIN tbl_rbac_commands c ON p.command_id = c.id ' .
					'WHERE rp.role_id = :role_id AND c.id = :command_id';
		$types = array('role_id' => 'integer', 'command_id' => 'integer');
		$this->findByRoleAndCommandStmt = self::$DB->prepare($query, $types);

		// All by command ID
		$query = 'SELECT p.* FROM tbl_rbac_permissions p ' .
					'WHERE p.command_id = :command_id';
		$types = array('command_id' => 'integer');
		$this->findByCommandStmt = self::$DB->prepare($query, $types);

//		// Update
//		$query = 'UPDATE tbl_companies SET id = :id, name = :name WHERE id = :id';
//		$types = array('id' => 'integer', 'name' => 'text');
//		$this->updateStmt = self::$DB->prepare($query, $types);
		
		// Insert
		$query = 'INSERT INTO tbl_rbac_permissions (command_id, name) VALUES (:command_id, :name)';
		$types = array('command_id' => 'integer', 'name' => 'text');
		// parameters:
		// 1) the query (notice we are using named parameters, but we could also use ? instead
		// 2) types of the placeholders
		// 3) true denotes a DML statement
		$result_types = MDB2_PREPARE_MANIP;
//		$result_types = MDB2_PREPARE_RESULT;
		$this->insertStmt = self::$DB->prepare($query, $types, $result_types);
	}

	
	/**
	 * 
	 * @return app_mapper_RbacRoleCollection
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_RbacRoleCollection($result, $this);
	}
	
	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
//		echo "<p><b>app_mapper_RbacPermissionMapper::doLoad($array)</b></p>";
//		echo "<pre>";
//		print_r($array);
//		echo "</pre>";
		$obj = new app_domain_RbacRole($array['id']);
//		echo "here 1";
		$obj->setName($array['name']);
//		echo "<br />here 2";

//		$space_mapper = new app_mapper_SpaceMapper();
////		echo "<br />here 3";
//		$space_collection = $space_mapper->findByVenue($array['id']);
////		echo "<br />here 4";
//		$obj->setSpaces($space_collection);
//		echo "<br />here 5";


		$permission_collection = $this->findByRole($array['id']);
////		echo "<br />here 4";
		$obj->setPermissions($permission_collection);

		$obj->markClean();
//		echo "<br />here 6";
		return $obj;
	}

	/**
	 * @TODO docs - this can be figured out automated surely and overriden if required
	 * Returns the target class name, i.e. 
	 * @return string
	 */
	protected function targetClass()
	{
		return 'app_domain_RbacPermission';
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	protected function doInsert(app_domain_DomainObject $object)
	{
		echo "<P>app_mapper_RbacPermissionMapper";
		$name = $object->getName();
		$id = $object->getId();
		$values = array('command_id' => $id, 'name' => $name);

		echo "<h1>app_mapper_VenueMapper::doInsert(".get_class($object).")<pre>";
		print_r($values);
		echo "</pre></h1>";
		
		try
		{
			$this->doStatement($this->insertStmt, $values);
		}
		catch (app_base_MDB2Exception $e)
		{
			// do nothing
			echo "<p>boo!";
		}
	}
	
	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_rbac_roles');
		return $this->id;
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$values = array($object->getId(), $object->getName(), $object->getId());
		$this->doStatement($this->updateStmt, $values);
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




	public function findByRole($role_id)
	{
		$values = array('role_id' => $role_id);
		$result = $this->doStatement($this->findByRoleStmt, $values);
		return new app_mapper_RbacPermissionCollection($result, $this);
	}

	/**
	 * 
	 */
	public function findByRoleAndCommand($role_id, $command_id)
	{
		$values = array('role_id' => $role_id, 'command_id' => $command_id);
//		echo "<pre>";
//		print_r($this->findByRoleStmt);
//		echo "</pre>";
		$result = $this->doStatement($this->findByRoleAndCommandStmt, $values);
		return new app_mapper_RbacPermissionCollection($result, $this);
	}

	/**
	 * @param integer $command_id command ID
	 * @resutl
	 */
	public function findByCommand($command_id)
	{
		$values = array('command_id' => $command_id);
		$result = $this->doStatement($this->findByCommandStmt, $values);
		return new app_mapper_RbacPermissionCollection($result, $this);
	}

}

?>