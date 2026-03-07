<?php

require_once('app/domain/DomainObject.php');

/**
 * @package framework
 */
class app_domain_RbacCommand extends app_domain_DomainObject
{
	private $name;
	private $description;
	private $permissions;
	
	/**
	 * @param integer $id
	 * @param string $name 
	 */
	public function __construct($id = null, $name = null)
	{
		$this->name = $name;
		$this->permissions = self::getCollection('app_domain_RbacPermission');
		parent::__construct($id);
		
		if ($this->id)
		{
			$finder = self::getFinder('app_domain_RbacPermission');
			$this->setPermissions($finder->findByCommand($this->id));
			
//			$this->doIanLoad();
//			echo "<br />do load";
//			$finder = $this->finder();
//			$old = $finder->find($this->id);
//			$this->setName($old->getName());
//			$x = self::find($this->id);
//			echo "<pre>";
//			print_r($x);
//			echo "</pre>";
//			exit;
		}
		else
		{
			echo "<br />skip load";
		}
//		echo "<br />\$this->id = ".$this->id;
//		echo "<br />\$this->name = ".$this->name;
//		echo "</div>";
//		exit;
	}

	/**
	 * Sets the spaces collection.
	 * @param app_domain_SpaceCollection $spaces
	 */
	public function setPermissions(app_domain_RbacPermissionCollection $permissions)
	{
		$this->permissions = $permissions;
	}
	
	/**
	 * Returns the spaces collection.
	 * @return app_mapper_Collection the collection of app_domain_Space objects
	 */
	public function getPermissions()
	{
		return $this->permissions;
	} 

	/**
	 * Add a space to the spaces collection, and set the space's parent venue to self.
	 * @param app_domain_Space $space the space to add
	 */
	public function addPermission(app_domain_RbacPermission $permission)
	{
		$this->permissions->add($permission);
		$permission->setCommand($this);
	}

	/**
	 * Set the venue name.
	 * @param string $name_s the venue name
	 */
	public function setName($name_s)
	{
		$this->name = $name_s;
		$this->markDirty();
	}
	
	/**
	 * Return the venue name.
	 * @return string the venue name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the venue name.
	 * @param string $name_s the venue name
	 */
	public function setDescription($name_s)
	{
		$this->description = $name_s;
		$this->markDirty();
	}
	
	/**
	 * Return the venue name.
	 * @return string the venue name
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * 
	 * @return app_mapper_VenueMapper
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_VenueMapper
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

}

?>