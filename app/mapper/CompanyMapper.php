<?php

/**
 * Defines the app_mapper_CompanyMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_CompanyMapper extends app_mapper_ShadowMapper implements app_domain_CompanyFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM vw_companies ORDER BY name');

		// Select single
//		$query = 'SELECT * FROM tbl_companies WHERE id = ?';
//		$types = array('integer');
//		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select set
//		$query = 'SELECT * FROM tbl_companies LIMIT ?,?';
//		$types = array('integer', 'integer');
//		$this->selectSetStmt = self::$DB->prepare($query, $types);

		// Count
//		$this->countStmt = self::$DB->prepare('SELECT COUNT(*) FROM tbl_companies');
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Company($array['id']);
		$obj->setName($array['name']);
		$obj->setWebsite($array['website']);
		$obj->setParentCompany($array['parent_company_id']);
		$obj->setTelephone($array['telephone']);
		$obj->setTelephoneTps($array['telephone_tps']);
		$obj->setAdditionalInfo($array['additional_info']);
//		$obj->setCharacteristics(app_domain_Characteristic::findByCompanyId($array['id']));
//		$obj->setNotes($this->findNotes($array['id']));
		$obj->markClean();
		return $obj;
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
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_companies (id, name, telephone, website, parent_company_id) VALUES (?, ?, ?, ?, ?)';
		$types = array('integer', 'text', 'text', 'text');
		$insertStmt = self::$DB->prepare($query, $types);
		$data = array(
			$object->getId(),
			$object->getName(),
			$object->getTelephone(),
			$object->getWebsite(),
			$object->getParentCompany()
		);
		$this->doStatement($insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_companies SET name = ?, website = ?, telephone = ?, telephone_tps = ?, parent_company_id = ?, additional_info = ? WHERE id = ?';
		$types = array('text', 'text', 'text', 'boolean', 'text', 'integer');
		$updateStmt = self::$DB->prepare($query, $types);
		$data = array($object->getName(), $object->getWebsite(), $object->getTelephone(), $object->getTelephoneTps(), $object->getParentCompany(), $object->getAdditionalInfo(), $object->getId());
		$this->doStatement($updateStmt, $data);

		// Notes
		// NOTE: need to do check else foreach fails
		if ($object->getNotes())
		{
			foreach ($object->getNotes() as $note)
			{
				// TODO
			}
		}
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_companies SET deleted = 1 WHERE id = ?';
		$types = array('integer');
		$updateStmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($updateStmt, $data);
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT * FROM tbl_companies WHERE id = ?';
		$types = array('integer');
		$selectStmt = self::$DB->prepare($query, $types);

		$data = array($id);
		$result = $this->doStatement($selectStmt, $id);
		return $this->load($result);
	}

    /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public function doFindByName($name)
    {
        $query = 'SELECT * FROM tbl_companies WHERE name = ?';
        $types = array('text');
        $selectStmt = self::$DB->prepare($query, $types);

        $data = array($name);
        $result = $this->doStatement($selectStmt, $name);
        return $this->load($result);
    }

	/**
	 * Find all companies.
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_CompanyCollection($result, $this);
	}

	/**
	 * Find all clients limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public function findSet($limit, $offset)
	{
		$query = 'SELECT * FROM tbl_companies LIMIT ?,?';
		$types = array('integer', 'integer');
		$selectSetStmt = self::$DB->prepare($query, $types);

		$data = array($offset, $limit);
		$result = $this->doStatement($selectSetStmt, $data);
		return new app_mapper_CompanyCollection($result, $this);
	}

	/**
	 * Find all companies with a given name.
	 * @param string $name company name
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByNameStart($name)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies AS c WHERE c.name LIKE ? ORDER BY c.name';
		$types = array('text');
		$selectByNameLikeStmt = self::$DB->prepare($query, $types);

		$values = array($name . '%');
		$result = $this->doStatement($selectByNameLikeStmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}

	/**
	 * Find all companies with a given name which occurs in a semi-colon delimited list.
	 * @param string $name_llst - semi-colon delimited list of company names
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByNameListStart($name_list)
	{
		$where = '';
		$list_array = explode(';', $name_list);
		foreach ($list_array as $item)
		{
			$where .= '(c.name LIKE ' . self::$DB->quote(trim($item) . '%', 'text') . ') OR ';
		}

		$where = substr($where, 0, -4) . ' ';

		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies AS c ' .
				'WHERE ' . $where .
				'ORDER BY c.name';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CompanyCollection($result, $this);
	}


	/**
	 * Find all companies which are like a given name.
	 * @param string $name company name
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByNameIncludes($name)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies AS c WHERE c.name LIKE ? ORDER BY c.name';
		$types = array('text');
		$selectByNameLikeStmt = self::$DB->prepare($query, $types);

		$values = array('%' . $name . '%');
		$result = $this->doStatement($selectByNameLikeStmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}

	/**
	 * Find all companies which are like a given name.
	 * @param string $name company name
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByNameEqual($name)
	{
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies AS c WHERE c.name = ? ORDER BY c.name';
		$types = array('text');
		$selectByNameEqualStmt = self::$DB->prepare($query, $types);

		$values = array($name);
		$result = $this->doStatement($selectByNameEqualStmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}

	/** Find all initiative records for a given company name starting with $name.
	 * @param string $name - company name being searched
	 * @param integer $initiative_id - initiative being search
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByNameStartAndInitiativeId($name, $initiative_id)
	{
		$query = 'SELECT vw_c.id, vw_c.name, vw_c.website, vw_c.telephone, vw_c.telephone_tps, ' .
				'vw_pc.id as post_id, vw_pc.first_name, vw_pc.surname, vw_pc.job_title, vw_cl.initiative_id, vw_cl.initiative_name, vw_cl.client_name, ' .
				'lkp_cs.description as status, pi.id as post_initiative_id ' .
				'FROM vw_companies_sites AS vw_c ' .
				'INNER JOIN vw_posts_contacts AS vw_pc ON vw_pc.company_id = vw_c.id ' .
				'INNER JOIN tbl_post_initiatives pi on vw_pc.id = pi.post_id ' .
				'INNER JOIN tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
				'INNER JOIN vw_client_initiatives vw_cl on vw_cl.initiative_id = pi.initiative_id ' .
				'WHERE vw_c.name like ' . self::$DB->quote($name . '%', 'text') . ' ' .
				'AND pi.initiative_id = ' . self::$DB->quote($initiative_id, 'integer') . ' ' .
				'ORDER BY vw_c.name, vw_pc.propensity desc';
		$result = self::$DB->query($query);
		return new app_mapper_CompanyCollection($result, $this);
	}
	/**
	 * Find all company telephone number which start with a given string.
	 * @param string $telephone company telephone
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByTelephoneStart($telephone)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies_sites AS c WHERE c.telephone LIKE ? ORDER BY c.name';
		$types = array('text');
		$stmt = self::$DB->prepare($query, $types);

		$values = array($telephone .'%');
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}


	/**
	 * Find all company telephone number which includes a given string.
	 * @param string $telephone company telephone
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByTelephoneIncludes($telephone)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies_sites AS c WHERE c.telephone LIKE ? ORDER BY c.name';
		$types = array('text');
		$stmt = self::$DB->prepare($query, $types);

		$values = array('%' . $telephone .'%');
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}


	/**
	 * Find all company telephone number which equal a given string.
	 * @param string $telephone company telephone
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByTelephoneEqual($telephone)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies_sites AS c WHERE c.telephone = ? ORDER BY c.name';
		$types = array('text');
		$stmt = self::$DB->prepare($query, $types);

		$values = array($telephone);
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}


	/**
	 * Find all company postcodes which start with a given string.
	 * @param string $postcode company postcode
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByPostcodeStart($postcode)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies_sites AS c WHERE c.postcode LIKE ? ORDER BY c.name';
		$types = array('text');
		$stmt = self::$DB->prepare($query, $types);

		$values = array($postcode . '%');
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}

	/**
	 * Find all company postcodes which include a given string.
	 * @param string $postcode company postcode
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByPostcodeIncludes($postcode)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies_sites AS c WHERE c.postcode LIKE ? ORDER BY c.name';
		$types = array('text');
		$stmt = self::$DB->prepare($query, $types);

		$values = array('%' . $postcode . '%');
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}


	/**
	 * Find all company postcodes which equal a given string.
	 * @param string $postcode company postcode
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByPostcodeEqual($postcode)
	{
		// Select By Name Like
		$query = 'SELECT c.id, c.name, c.website, c.telephone, c.telephone_tps FROM vw_companies_sites AS c WHERE c.postcode = ? ORDER BY c.name';
		$types = array('text');
		$stmt = self::$DB->prepare($query, $types);

		$values = array($postcode);
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}

 	/**
 	 * Find all companies which have a brand which includes string $brand.
	 * @param string $brand company name
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findByBrandIncludes($brand)
	{
		// Select By Name Like
		$query = 'SELECT t.*, c.id, c.name, c.website, c.telephone, c.telephone_tps ' .
					'FROM vw_companies AS c ' .
					'JOIN tbl_company_tags AS ct ON c.id = ct.company_id ' .
					'JOIN tbl_tags AS t ON ct.tag_id = t.id ' .
					'JOIN tbl_tag_categories AS tc ON t.category_id = tc.id ' .
					'WHERE t.value LIKE ? ORDER BY c.name';
		$types = array('text');
		$selectByNameLikeStmt = self::$DB->prepare($query, $types);

		$values = array('%' . $brand . '%');
		$result = $this->doStatement($selectByNameLikeStmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}

	/**
	 * Returns the total number of companies.
	 * @param integer
	 */
	public function count()
	{
		$countStmt = self::$DB->prepare('SELECT COUNT(*) FROM tbl_companies');
		$result = $this->doStatement($countStmt, array());
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find all posts/contacts for a given company, ordered by job_title
	 * @param integer $id company id
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findPostsOrderByJobTitle($id)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();
        
        if (!empty($user['client_id'])) {
            $query = 'SELECT * FROM vw_posts_contacts WHERE company_id = ? AND data_owner_id = ? ORDER BY job_title';
            $types = array('integer', 'integer');
            $values = array($id, $user['client_id']);
        } else {
            $query = 'SELECT * FROM vw_posts_contacts WHERE company_id = ? AND data_owner_id IS NULL ORDER BY job_title';
            $types = array('integer');
            $values = array($id);
        }

        $stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
        $cols = array_keys($result->getColumnNames());

		$results = array();

		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();
			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}
			array_push($results, $output);
		}
		return $results;
	}

	/**
	 * Find all posts/contacts for a company by a given ID ordered by first name.
	 * @param integer $id company ID
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findPostsOrderByFirstName($id)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();
        
        if (!empty($user['client_id'])) {
            $query = 'SELECT * FROM vw_posts_contacts WHERE company_id = ? AND data_owner_id = ? ORDER BY first_name';
            $types = array('integer', 'integer');
            $values = array($id, $user['client_id']);
        } else {
            $query = 'SELECT * FROM vw_posts_contacts WHERE company_id = ? AND data_owner_id IS NULL ORDER BY first_name';
            $types = array('integer');
            $values = array($id);
        }

		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$cols = array_keys($result->getColumnNames());

		$results = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();
			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}
			array_push($results, $output);
		}
		return $results;
	}

	/**
	 * Find count of all posts/contacts for a company by a given ID
	 * @param integer $id company ID
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findPostCount($id)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();
        
        if (!empty($user['client_id'])) {
            $query = 'SELECT count(id) FROM vw_posts WHERE company_id = ? AND data_owner_id = ?';
            $types = array('integer', 'integer');
            $values = array($id, $user['client_id']);
        } else {
            $query = 'SELECT count(id) FROM vw_posts WHERE company_id = ? AND data_owner_id IS NULL';
            $types = array('integer');
            $values = array($id);
        }
        
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find all posts/contacts for a company by a given id ordered by job_title
	 * @param integer $company_id
	 * @param integer $initiative_id
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findCompanyPostInitiatives($company_id, $initiative_id)
	{
		// Select post initiatives for a company
		//TODO: data import - make sure there are no next call dates which are < last effective or last comm date
		$query = 'SELECT pi.id, pi.status, pi.next_communication_date, ' .
					'vw_ci.*, vw_pc.*, ' .
					'com1.communication_date AS last_communication_date, ' .
					'com2.communication_date AS last_effective_communication_date ' .
					'FROM vw_posts_contacts AS vw_pc ' .
					'INNER JOIN tbl_post_initiatives AS pi ON vw_pc.id = pi.post_id ' .
					'LEFT JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'LEFT JOIN tbl_communications AS com1 ON pi.last_communication_id = com1.id ' .
					'LEFT JOIN tbl_communications AS com2 ON pi.last_effective_communication_id = com2.id ' .
					'WHERE vw_pc.company_id = ? ' .
					'AND vw_ci.initiative_id = ?';
		$types = array('integer', 'integer');
		$stmt = self::$DB->prepare($query, $types);

		$values = array($company_id, $initiative_id);
		$result = $this->doStatement($stmt, $values);
		$cols = array_keys($result->getColumnNames());

		$results = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();
			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}
			array_push($results, $output);
		}
		return $results;
	}

	/**
	 * Find client initiatives for a given company, ordered by client name.
	 * @param integer $id company ID
	 * @return array
	 */
	public function findCompanyClientInitiatives($id)
	{
		$query = 'SELECT vw_ci.initiative_id AS id, vw_ci.client_name ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN tbl_posts AS p ON pi.post_id = p.id ' .
					'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'WHERE company_id = ? ' .
					'GROUP BY vw_ci.initiative_id, vw_ci.client_name ' .
					'ORDER BY vw_ci.client_name';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);

		$values = array($id);
		$result = $this->doStatement($stmt, $values);
		$cols = array_keys($result->getColumnNames());

		$results = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();
			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}
			array_push($results, $output);
		}
		return $results;
	}

	/**
	 * Return a collection of companies filtered by the given where clause, ordered by name.
	 * @param string $where_clause
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findCompanyByCustomWhereClause($where_clause)
	{
		$query = 'SELECT c.id, c.name, c.website, c.telephone FROM vw_companies AS c WHERE ' . $where_clause . ' ORDER BY c.name';
		$types = array();
		$this->selectPostInitiativesStmt = self::$DB->prepare($query, $types);

		$values = array();
		$result = $this->doStatement($this->selectPostInitiativesStmt, $values);
		return new app_mapper_CompanyCollection($result, $this);
	}

	/**
	 * Find the notes for a given company.
	 * @param integer $company_id
	 * @return array
	 */
	public function findNotes($company_id)
	{
		// Prepare
		$query = 'SELECT c.*, u.handle FROM tbl_company_notes AS c ' .
					'INNER JOIN tbl_rbac_users AS u ON c.created_by = u.id ' .
					'WHERE c.company_id = ? ' .
					'ORDER BY created_at DESC';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);

		// Execute
		$data = array($company_id);
		$result = $this->doStatement($stmt, $data);
		return self::mdb2ResultToArray($result);

	}




}

?>