<?php

require_once('app/domain/DomainObject.php');


require_once('app/mapper/RbacCommandMapper.php');
require_once('app/mapper/RbacRoleMapper.php');
require_once('app/mapper/RbacPermissionMapper.php');
require_once('app/mapper/RbacUserMapper.php');

/**
 * @package framework
 */
class app_domain_RbacRole extends app_domain_DomainObject
{
	/**
	 * The role name.
	 * @var string
	 */
	private $name;
	
	/**
	 * Collection of permissions.
	 * @var app_domain_RbacPermissionCollection
	 */
	private $permissions;

	/**
	 * Collection of users.
	 * @var app_domain_RbacUserCollection
	 */
	private $users;

	/**
	 * @param integer $id
	 * @param string $name 
	 */
	public function __construct($id = null, $name = null)
	{
		$this->name = $name;
		$this->permissions = self::getCollection('app_domain_RbacPermission');
		$this->users = self::getCollection('app_domain_RbacUser');
		
		parent::__construct($id);
		
		if ($this->id)
		{
			$finder = self::getFinder('app_domain_RbacPermission');
			$this->setPermissions($finder->findByRole($this->id));

			$finder = self::getFinder('app_domain_RbacUser');
			$this->setUsers($finder->findByRole($this->id));
		}
		else
		{
			echo "<br />skip load";
		}
	}

	/**
	 * Sets the permissions collection.
	 * @param app_domain_RbacPermissionCollection $permissions
	 */
	public function setPermissions(app_domain_RbacPermissionCollection $permissions)
//	public function setPermissions($permissions)
	{
//		echo "<p><b>setPermissions($permissions)</b></p>";
//		echo "get_class = " .get_class($permissions);
		$this->permissions = $permissions;
	}

	/**
	 * Returns the permissions collection.
	 * @return app_mapper_Collection the collection of app_domain_RbacPermission objects
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}

	/**
	 * Add a permission to the permissions collection.
	 * @param app_domain_RbacUser $user the users to add
	 */
	public function addPermission(app_domain_RbacPermission $permission)
	{
		$this->permissions->add($permission);
//		$permission->setRole($this);
	}

	/**
	 * Sets the users collection.
	 * @param app_domain_RbacUserCollection $users
	 */
	public function setUsers(app_domain_RbacUserCollection $users)
	{
		$this->users = $users;
	}

	/**
	 * Returns the users collection.
	 * @return app_mapper_Collection the collection of app_domain_RbacUser objects
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * Add a user to the users collection, and add this role to the set of the user's roles.
	 * @param app_domain_RbacUser $user the users to add
	 */
	public function addUser(app_domain_RbacUser $user)
	{
		$this->users->add($user);
		$user->addRole($this);
	}

	/**
	 * Set the role name.
	 * @param string $name the role name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/**
	 * Return the role name.
	 * @return string the role name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Return a collection of all roles.
	 * @return app_mapper_RbacRoleCollection
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * Return a specific role.
	 * @param integer $id role ID
	 * @return app_domain_RbacRole
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * Return a collection of all roles.
	 * @param integer $role_id the role ID
	 * @param integer $command_id the command ID
	 * @return app_mapper_RbacRoleCollection
	 */
	public static function findByRoleAndCommand($role_id, $command_id)
	{
		return app_domain_RbacPermission::findByRoleAndCommand($role_id, $command_id);
	}

}

?>