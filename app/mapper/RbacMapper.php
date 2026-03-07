<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_RbacMapper extends app_mapper_Mapper
{
	protected static $DB;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all commands
		$this->selectAllCommandsStmt = self::$DB->prepare('SELECT * FROM tbl_rbac_commands ORDER BY name');

		// Select all roles
		$this->selectAllRolesStmt = self::$DB->prepare('SELECT * FROM tbl_rbac_roles ORDER BY name');
	}


	/**
	 *
	 * @return app_mapper_VenueCollection
	 */
	public function findAllRoles()
	{
		$result = $this->doStatement($this->selectAllRolesStmt, array());
		echo get_class($result);
		return new app_mapper_RbacRoleCollection($result, $this);
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
		$obj = new app_domain_Company($array['id']);
//		echo "here 1";
		$obj->setName($array['name']);
//		echo "<br />here 2";
//		$space_mapper = new app_mapper_SpaceMapper();
////		echo "<br />here 3";
//		$space_collection = $space_mapper->findByVenue($array['id']);
////		echo "<br />here 4";
//		$obj->setSpaces($space_collection);
//		echo "<br />here 5";
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
		return 'app_domain_Company';
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$name = $object->getName();
		$id = $object->getId();
		$values = array('id' => $id, 'name' => $name);

//		echo "<h1>app_mapper_VenueMapper::doInsert(".get_class($object).")<pre>";
//		print_r($values);
//		echo "</pre></h1>";

		try
		{
			$this->doStatement($this->insertStmt, $values);
		}
		catch (app_base_MDB2Exception $e)
		{
			// do nothing
		}
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_companies');
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

}

?>