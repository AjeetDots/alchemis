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
class app_mapper_RbacUserMapper extends app_mapper_Mapper implements app_domain_RbacUserFinder
{
	protected static $DB;

	protected $selectAllStmt;
	protected $selectAllActiveStmt;
	protected $selectAllActiveClientStmt;
	protected $findByRoleStmt;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_rbac_users ORDER BY is_active DESC, handle');

		// Select all active
		$this->selectAllActiveStmt = self::$DB->prepare('SELECT * FROM tbl_rbac_users WHERE is_active = 1 ORDER BY handle');
		
		$this->selectAllActiveClientStmt = self::$DB->prepare('SELECT * FROM tbl_rbac_users WHERE is_active = 1 AND client_id = :client_id ORDER BY handle');

		// Select by role ID
		$query = 'SELECT u.* FROM tbl_rbac_users u ' .
					'INNER JOIN tbl_rbac_user_roles ur ON u.id = ur.user_id ' .
					'WHERE ur.role_id = :role_id';
		$types = array('role_id' => 'integer');
		$this->findByRoleStmt = self::$DB->prepare($query, $types);


//		// Update roles
//		$query = 'UPDATE tbl_rbac_users SET id = :id, handle = :handle, password = :password, is_active = :is_active WHERE id = :id';
//		$types = array('id' => 'integer', 'handle' => 'text', 'password' => 'text', 'is_active' => 'integer', 'id' => 'integer');
//		$this->updateRoleStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
//		echo '<pre>';
//		print_r($array);
//		echo '</pre>';
		$obj = new app_domain_RbacUser($array['id']);
		$obj->setHandle($array['handle']);
		$obj->setPassword($array['password']);
		$obj->setName($array['name']);
		$obj->setEmail($array['email']);
		$obj->setLastLogin($array['last_login']);
		$obj->setClientId($array['client_id']);
		$obj->setActive((bool)$array['is_active']);

//		$obj->add_call_name($array['add_call_name']);
//		$obj->add_client_record($array['add_client_record']);


//		$obj->admin_users       = $array['admin_users'];
//		$obj->add_call_name     = $array['add_call_name'];
//		$obj->add_client_record = $array['add_client_record'];



		// Add each of the permission fields
		$fields = array_keys($array);
		foreach ($fields as $field)
		{
			if (substr($field, 0, 11) == 'permission_')
			{
				$obj->$field = $array[$field];
			}
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
		$this->id = self::$DB->nextID('tbl_rbac_users');
		return $this->id;
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
//		print_r($object);
		$query = 'INSERT INTO tbl_rbac_users SET id = ?, handle = ?, password = ?, name = ?, email = ?, is_active = ?, client_id = ?';
		$types = array('integer', 'text', 'text', 'text', 'text', 'boolean', 'integer');
		$stmt = self::$DB->prepare($query, $types);

//		$id = $object->getId();
//		echo "$id\n";
		$values = array($object->getId(), $object->getHandle(), $object->getPassword(), $object->getName(), $object->getEmail(), $object->isActive(), $object->getClientId());

//		echo "<p>$query</p>\n";
//		echo '<pre>';
//		print_r($values);
//		echo '</pre>';

		try
		{
			$this->doStatement($stmt, $values);
		}
		catch (app_base_MDB2Exception $e)
		{
			// do nothing
		}
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	public function update(app_domain_DomainObject $object)
	{
//		echo "<p><b>app_mapper_RbacUserMapper::update()</b></p>";

		// Start query
		$query  = 'UPDATE tbl_rbac_users SET handle = ?, password = ?, name = ?, email = ?, is_active = ?, client_id = ?, ';
		$types  = array('text', 'text', 'text', 'text', 'boolean', 'integer');
		$values = array($object->getHandle(), $object->getPassword(), $object->getName(), $object->getEmail(), $object->isActive(), $object->getClientId());

		// Add each permission into the query
		foreach ($object->getPermissions() as $permission => $value)
		{
			$query   .= $permission . ' = ?, ';
			$types[]  = 'boolean';
			$values[] = $value;
		}

		// Finish off query
		if (substr($query, -2) == ', ')
		{
			$query = substr($query, 0, strlen($query)-2);
		}

		$query .= ' WHERE id = ?';
		$types[] = 'integer';
		$values[] = $object->getId();

		$stmt = self::$DB->prepare($query, $types);
		$this->doStatement($stmt, $values);

		// if user has been marked as in_active then unset any related client permissions
		$campaign_nbms = app_domain_CampaignNbm::findByUserId($object->getId());
		foreach ($campaign_nbms as $campaign_nbm)
		{
			if (!$object->isActive())
			{
				// make this next check so that we don't lose any information
				// about records where the deactivated date has already been set
				if ($campaign_nbm->getDeactivatedDate() == '0000-00-00')
				{
					$campaign_nbm->setDeactivatedDate(date('Y-m-d H:i:s'));
					$campaign_nbm->commit();
				}
			}

		}
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT * FROM tbl_rbac_users WHERE id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/**
	 *
	 * @return app_mapper_RbacRoleCollection
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_RbacUserCollection($result, $this);
	}

	/**
	 *
	 * @return app_mapper_RbacRoleCollection
	 */
	public function findAllActive($client_id = null)
	{
		if ($client_id) {
			$result = $this->doStatement($this->selectAllActiveClientStmt, ['client_id' => $client_id]);
		} else {
			$result = $this->doStatement($this->selectAllActiveStmt, []);
		}
		return new app_mapper_RbacUserCollection($result, $this);
	}

	/**
	 * Find all users who are assigned to a given role.
	 * @param integer $id role ID
	 * @return app_mapper_RbacUserCollection collection of app_domain_RbacUser objects
	 */
	public function findByRole($role_id)
	{
		$values = array('role_id' => $role_id);
		$result = $this->doStatement($this->findByRoleStmt, $values);
		return new app_mapper_RbacUserCollection($result, $this);
	}

	/**
	 * Find all campaigns associated with a given NBM.
	 * @param integer $user_id
	 * @return app_mapper_CampaingCollection collection of app_domain_Campaign objects
	 */
	public function findByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT c.*, r.name FROM tbl_campaign_nbms c JOIN tbl_rbac_users r on c.user_id = r.id WHERE c.campaign_id = ? ' .
				'ORDER BY is_active desc, is_lead_nbm desc, r.name';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CampaignNbmCollection($result, $this);
	}

	/**
	 * Return users complete with team details.
	 * @return array
	 */
	public function findTeamDetails()
	{
		$query = 'SELECT u.*, tn.team_id, t.name AS team ' .
					'FROM tbl_rbac_users AS u ' .
					'LEFT JOIN tbl_team_nbms AS tn ON u.id = tn.user_id ' .
					'LEFT JOIN tbl_teams AS t ON tn.team_id = t.id ' .
					'WHERE u.is_active = 1 ORDER BY name';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
 	 * Find the team ID for a given user ID.
 	 * @param integer $user_id user ID
	 * @return integer
	 */
	public static function findTeamIdByUserId($user_id)
	{
		$query = 'SELECT team_id FROM tbl_team_nbms WHERE user_id = ' . self::$DB->quote($user_id, 'integer');
		return self::$DB->queryOne($query);
	}

	/**
	 * Return the name of a user given an ID.
	 * @param integer $user_id
	 * @return string
	 */
	public function getUserName($user_id)
	{
		$sql = 'SELECT name FROM tbl_rbac_users WHERE id = ' . self::$DB->quote($user_id, 'integer');
		return self::$DB->queryOne($sql);
	}

    /**
     * Return the name of a user given an ID.
     * @param integer $user_id
     * @return string
     */
    public function getAllActiveUsersArray()
    {
        $sql = 'SELECT * FROM tbl_rbac_users WHERE is_active = 1 ORDER BY name';
        $result =  self::$DB->query($sql);
        return self::mdb2ResultToArray($result);
    }

}

?>