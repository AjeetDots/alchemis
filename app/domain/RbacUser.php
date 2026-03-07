<?php

require_once('app/domain/DomainObject.php');

/**
 * @package framework
 */
class app_domain_RbacUser extends app_domain_DomainObject
{
	/**
	 * The user handle.
	 * @var string
	 */
	protected $handle;

	/**
	 * The user password (should already be MD5'd).
	 * @var string
	 */
	protected $password;

	/**
	 * The user name.
	 * @var string
	 */
	protected $name;

	/**
	 * The user email address.
	 * @var string
	 */
	protected $email;

	/**
	 * The datetime of the users last login.
	 * @var string
	 */
	protected $last_login;

	/**
	 * Whether the user is active.
	 * @var boolean
	 */
	protected $is_active;

	/**
	 * Collection of roles.
	 * @var app_domain_RbacRoleCollection
	 */
	protected $roles;
	
	protected $client_id;

	/**
	 * @param integer $id
	 * @param string $name
	 */
	public function __construct($id = null, $handle = null)
	{
		$this->handle = $handle;
		$this->is_active = true;
		$this->roles = self::getCollection('app_domain_RbacRole');
		parent::__construct($id);

		if ($this->id)
		{
			$finder = self::getFinder('app_domain_RbacRole');
			$this->setRoles($finder->findByUser($this->id));
		}
	}

	/**
	 * Returns an array of field validation rules.
	 * @param string $field optional field name
	 * @return spec
	 * @see app_base_RuleValidator
	 */
	public static function getFieldSpec($field = null)
	{
		$spec = array();

		$spec['handle'] 	= array(	'alias'      => 'Username',
										'type'       => 'text',
										'mandatory'  => true,
										'max_length' => 100);
		$spec['name'] 		= array(	'alias'      => 'Name',
										'type'       => 'text',
										'mandatory'  => true,
										'max_length' => 255);
		$spec['email'] 		= array(	'alias'      => 'Email',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 100);
		$spec['password'] 	= array(	'alias'      => 'Password',
										'type'       => 'text',
										'mandatory'  => true,
										'max_length' => 32);

		if (!is_null($field))
		{
			return $spec[$field];
		}
		else
		{
			return $spec;
		}
	}

	/**
	 * Set the user handle.
	 * @param string $handle the user handle
	 */
	public function setHandle($handle)
	{
		$this->handle = $handle;
		$this->markDirty();
	}

	/**
	 * Return the user handle.
	 * @return string the user handle
	 */
	public function getHandle()
	{
		return $this->handle;
	}

	/**
	 * Set the user's password (should already be MD5'd).
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		$this->markDirty();
	}

	/**
	 * Return the user's password (should already be MD5'd).
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the user's name.
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/**
	 * Return the user's name.
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the user's email.
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		$this->markDirty();
	}

	/**
	 * Return the user's email.
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the user handle.
	 * @param string $handle the user handle
	 */
	public function setLastLogin($last_login)
	{
		$this->last_login = $last_login;
		$this->markDirty();
	}

	/**
	 * Return the user handle.
	 * @return string the user handle
	 */
	public function getLastLogin()
	{
		return $this->last_login;
	}

	/**
	 * Set whether the user is active.
	 * @param boolean $active
	 */
	public function setActive($active)
	{
		$this->is_active = $active;
		$this->markDirty();
	}

	/**
	 * Return whether the user is active.
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->is_active;
	}
	
	public function setClientId($client_id)
	{
		$this->client_id = $client_id;
		$this->markDirty();
	}
	
	public function getClientId()
	{
		return $this->client_id;
	}

	/**
	 * Sets the roles collection.
	 * @param app_domain_RbacRoleCollection $roles
	 */
	public function setRoles(app_domain_RbacRoleCollection $roles)
	{
		$this->roles = $roles;
	}

	/**
	 * Returns the roles collection.
	 * @return app_mapper_Collection the collection of app_domain_RbacRole objects
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * Add a user to the users collection, and add this role to the set of the user's roles.
	 * @param app_domain_RbacUser $user the users to add
	 */
	public function addRole(app_domain_RbacRole $role)
	{
		$this->roles->add($role);
		// TODO - recursive linking going on here
//		$user->addUser($this);
		$this->markDirty();
	}


	/**
	 * The permissions array.
	 * @var string
	 */
	protected $permissions = array();

	public function hasPermission($permission)
	{
//		echo '<pre>';
//		print_r($this->permissions);
//		echo '</pre>';
		if (isset($this->permissions[$permission]))
		{
			return $this->permissions[$permission];
		}
		else
		{
			return false;
		}
	}

	public function getPermissions()
	{
//		echo '<pre>';
//		print_r($this->permissions);
//		echo '</pre>';
		return $this->permissions;
	}

	public function revokeAllPermissions()
	{
		$keys = array_keys($this->permissions);
		foreach ($keys as $key)
		{
			$this->permissions[$key] = false;
		}
	}

	/**
	 * Callback method for getting a property
	 * @param $string $property
	 */
	public function __get($property)
	{
//		echo "<p><b>app_domain_RbacUser::__get($property)</b></p>";
		if (isset($this->permissions[$property]))
		{
			return $this->permissions[$property];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Callback method for setting a property
	 * @param $string $property
	 * @param $mixed $value
	 */
	public function __set($property, $value)
	{
//		echo "<p><b>app_domain_RbacUser::__set($property, $value)</b></p>";
		$this->permissions[$property] = $value;
    }

	/**
	 * Return a collection of all users.
	 * @return app_mapper_RbacUserCollection
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * Return a collection of all users.
	 * @return app_mapper_RbacUserCollection
	 */
	public static function findAllActive($client_id = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAllActive($client_id);
	}

	/**
	 * Return an array of user for drop-down.
	 * @param boolean $include_select whether to include a 'select' option at the top of the list.
	 * @return array
	 */
	public static function findAllActiveForDropdown($include_select = true, $client_id = null)
	{
		$options = array();
		if ($items = self::findAllActive($client_id))
		{
			$items = $items->toRawArray();
			if ($include_select)
			{
				$options[0] = '-- select --';
			}
			foreach ($items as $item)
			{
				$options[$item['id']] = $item['name'];
			}
		}
		return $options;
	}

   /**
     * Return an array of user for drop-down.
     * @param boolean $include_select whether to include a 'select' option at the top of the list.
     * @return array
     */
    public static function findAllActiveForFilterDropdown($client_id = null)
    {
        $options = array();
        if ($items = self::findAllActive($client_id))
        {
            $items = $items->toRawArray();
            foreach ($items as $item)
            {
                $options[] = array('id'     => $item['id'],
                                    'name'  => $item['name']);
            }
        }
        return $options;
    }

	/**
	 * Return a specific user.
	 * @param integer $id user ID
	 * @return app_domain_RbacUser
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}


	 /**
     * Return a specific user.
     * @param integer $id user ID
     * @return app_domain_RbacUser
     */
    public static function findCurrentUser()
    {
    	$id = self::getCurrentUserId();
        $finder = self::getFinder(__CLASS__);
        return $finder->find($id);
    }


    /**
     * Return a specific user.
     * @param integer $id user ID
     * @return app_domain_RbacUser
     */
    public static function findCurrentUserForDropdown()
    {
        $id = self::getCurrentUserId();

        $options = array();
        if ($item = self::find($id))
        {
            $options[] = array('id'     => $item->getId(),
                                'name'  => $item->getName());
        }

        return $options;

    }


	/**
	 * TODO this checks against the database, which may be incorrect (i.e. doesn't check against
	 * live $roles array.
	 *
	 * Return a collection roles that are available for the user, i.e. those not part of the currently selected ones.
	 * @return app_mapper_RbacRoleCollection
	 * @see http://en.wikipedia.org/wiki/Set
	 */
	public function findAvailableRoles()
	{
		$finder = self::getFinder('app_domain_RbacRole');
		$all_roles = $finder->findAvailableForUser($this->id);
//		$finder = self::getFinder('app_domain_RbacRole');
//		$all_roles = $finder->findAll();
//
//		echo "<pre>";
//		echo "<p>type all: " . get_class($all_roles);
//		print_r($all_roles);
//		echo "<p>type user: " . get_class($this->roles);
//		$diff = array_diff($all_roles, $this->roles);
//
//		print_r($diff);
//		echo "</pre>";
		return $all_roles;
	}

	/**
	 * Return users complete with team details.
	 * @return array
	 */
	public static function findTeamDetails()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findTeamDetails();
	}

	/**
 	 * Find the team ID for a given user ID.
 	 * @param integer $user_id user ID
	 * @return integer
	 */
	public static function findTeamIdByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findTeamIdByUserId($user_id);
	}

	/**
	 * Return the name of a user given an ID.
	 * @param integer $user_id
	 * @return string
	 */
	public static function getUserName($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getUserName($user_id);
	}

    /**
     * Return the details for all active users as an array
     * @return string
     */
    public static function getAllActiveUsersArray()
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->getAllActiveUsersArray();
    }



}

?>