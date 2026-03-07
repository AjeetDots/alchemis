<?php

/**
 * Defines the app_mapper_ContactMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_ContactMapper extends app_mapper_ShadowMapper implements app_domain_ContactFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM vw_contacts ORDER BY surname');

		// Select single
		$query = 'SELECT * FROM vw_contacts WHERE id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select single by post ID
		$query = 'SELECT * FROM vw_contacts WHERE post_id = ? AND deleted = 0';
		$types = array('integer');
		$this->selectByPostStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Contact($array['id']);
		$obj->setPostId($array['post_id']);
		$obj->setTitle($array['title']);
		$obj->setFirstName($array['first_name']);
		$obj->setSurname($array['surname']);
		$obj->setTelephoneMobile($array['telephone_mobile']);
		$obj->setEmail($array['email']);
		$obj->setLinkedIn($array['linked_in']);
		$obj->setDeleted($array['deleted']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_contacts');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_contacts ' .
						'(id, post_id, title, first_name, surname, full_name, telephone_mobile, email, linked_in) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'text', 'text', 'text', 'text', 'text', 'text', 'text');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getId(), $object->getPostId(), $object->getTitle(),
						$object->getFirstName(), $object->getSurname(), $object->getName(),
						$object->getTelephoneMobile(), $object->getEmail(), $object->getLinkedIn());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_contacts SET post_id = ?, title = ?, first_name = ?, ' .
						'surname = ?, full_name = ?, telephone_mobile = ?, ' .
						'email = ?, linked_in = ?, deleted = ? WHERE id = ?';
		$types = array('integer', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'integer', 'integer');
		$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getPostId(), $object->getTitle(), $object->getFirstName(),
						$object->getSurname(), $object->getName(), $object->getTelephoneMobile(),
						$object->getEmail(), $object->getLinkedIn(), $object->getDeleted(), $object->getId());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Find the given contact.
	 * @param integer $id contact ID
	 * @return app_domain_Contact
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$values = array($id);
		$result = $this->doStatement($this->selectStmt, $values);
		return $this->load($result);
	}

	/**
	 * Find all contacts.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_ContactCollection($result, $this);
	}

 	/**
 	 * Find the current contact by post ID.
	 * @param integer $post_id post ID
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findByPostId($post_id)
	{
		$values = array($post_id);
		$result = $this->doStatement($this->selectByPostStmt, $values);
		return new app_mapper_ContactCollection($result, $this);
	}

	/**
	 * Find the current contact for a given post ID. There should only ever be
	 * ONE current contact for a given post ID.
	 * @param integer $post_id post ID
	 * @return app_domain_Contact object
	 */
	public function findCurrentByPostId($post_id)
	{
		$values = array($post_id);
		$result = $this->doStatement($this->selectByPostStmt, $values);
		return $this->load($result);
	}

	/**
	 * Find all contacts where surnames start with the query string.
	 * @param string $name query string
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findByContactSurnameStart($name)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();

        if (!empty($user['client_id'])) {
            $query = 'SELECT c.*, pc.id AS post_id, pc.job_title, pc.first_name, pc.surname, pc.propensity ' .
                'FROM vw_posts_contacts AS pc ' .
                'INNER JOIN vw_companies_sites AS c ON pc.company_id = c.id ' .
                'WHERE pc.surname LIKE ? AND pc.data_owner_id = ? ' .
                'ORDER BY pc.surname';
            $types = array('text', 'integer');
            $values = array($name . '%', $user['client_id']);
        } else {
            $query = 'SELECT c.*, pc.id AS post_id, pc.job_title, pc.first_name, pc.surname, pc.propensity ' .
                'FROM vw_posts_contacts AS pc ' .
                'INNER JOIN vw_companies_sites AS c ON pc.company_id = c.id ' .
                'WHERE pc.surname LIKE ? AND pc.data_owner_id IS NULL ' .
                'ORDER BY pc.surname';
            $types = array('text');
            $values = array($name . '%');
        }
        
		$stmt = self::$DB->prepare($query, $types);
        $result = $this->doStatement($stmt, $values);
		return new app_mapper_ContactCollection($result, $this);
	}

	/**
	 * Find all contacts where full names start with the query string.
	 * @param string $name query string
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findByContactFullNameStart($name)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();

        if (!empty($user['client_id'])) {
            $query = 'SELECT c.*, pc.id AS post_id, pc.job_title, pc.first_name, pc.surname, pc.propensity ' .
                'FROM vw_posts_contacts AS pc ' .
                'INNER JOIN vw_companies_sites AS c ON pc.company_id = c.id ' .
                'WHERE pc.full_name LIKE ? AND pc.data_owner_id = ? ' .
                'ORDER BY pc.full_name';
            $types = array('text', 'integer');
            $values = array($name . '%', $user['client_id']);
        } else {
            $query = 'SELECT c.*, pc.id AS post_id, pc.job_title, pc.first_name, pc.surname, pc.propensity ' .
                'FROM vw_posts_contacts AS pc ' .
                'INNER JOIN vw_companies_sites AS c ON pc.company_id = c.id ' .
                'WHERE pc.full_name LIKE ? AND pc.data_owner_id IS NULL ' .
                'ORDER BY pc.full_name';
            $types = array('text');
            $values = array($name . '%');
        }
        
        $stmt = self::$DB->prepare($query, $types);
        $result = $this->doStatement($stmt, $values);
		return new app_mapper_ContactCollection($result, $this);
	}

	/**
	 * Find contact by email
     *
	 * @param string $email Email address
     *
	 * @return app_domain_Contact
	 */
	public function findByContactEmail($email)
	{
        $query     = "SELECT * FROM vw_contacts WHERE email = '" . $email . "' AND deleted = 0";
        $result    = self::$DB->query($query);
		return new app_mapper_ContactCollection($result, $this);
	}
}

?>