<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_TagMapper extends app_mapper_Mapper implements app_domain_TagFinder
{
	protected static $DB;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
//		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_tags ORDER BY id');

		// Select single
//		$query = 'SELECT * FROM tbl_tags WHERE id = :id';
//		$types = array('id' => 'integer');
//		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select by company id
//		$query = 'SELECT t.* FROM tbl_tags t JOIN tbl_company_tags ct ON t.id = ct.tag_id ' .
//				'WHERE ct.company_id = :company_id ' .
//				'order by value';
//		$types = array('company_id' => 'integer');
//		$this->selectByCompanyIdStmt = self::$DB->prepare($query, $types);

		// Select by company id and category id
//		$query = 'SELECT t.* FROM tbl_tags t JOIN tbl_company_tags ct ON t.id = ct.tag_id ' .
//				'WHERE ct.company_id = :company_id ' .
//				'AND t.category_id = :category_id ' .
//				'order by value';
//		$types = array('company_id' => 'integer', 'category_id' => 'integer');
//		$this->selectByCompanyIdAndCategoryIdStmt = self::$DB->prepare($query, $types);


		// Select set
//		$query = 'SELECT * FROM vw_companies LIMIT :offset,:limit';
//		$types = array('offset' => 'integer', 'limit' => 'integer');
//		$this->selectSetStmt = self::$DB->prepare($query, $types);

		// Update
//		$query = 'UPDATE tbl_tags SET id = :id, category_id = :category_id, value = :value WHERE id = :id';
//		$types = array('id' => 'integer', 'category_id' => 'integer', 'value' => 'text');
//		$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		// Insert
//		$query = 'INSERT INTO tbl_tags (id, category_id, value) ' .
//				'VALUES (' .
//				':id, :category_id, :value)';
//		$types = array('id' => 'integer', 'category_id' => 'integer',
//						'value' => 'text');
//		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		// Delete
//		$query = 'DELETE FROM tbl_tags WHERE id = :id';
//		$types = array('id' => 'integer');
//		$this->deleteStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

//		echo "<pre>";
//		print_r($this->insertStmt);
//		echo "</pre>";

		// Count
//		$this->countStmt = self::$DB->prepare('SELECT COUNT(*) FROM vw_companies');



	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Tag($array['id']);
		$obj->setValue($array['value']);
        $obj->setCategoryId($array['category_id']);
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
		return 'app_domain_Tag';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_tags');
//		echo "<pre>";
//		print_r($this->id);
//		echo "</pre>";

		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_tags (id, category_id, value) ' .
				'VALUES (' .
				':id, :category_id, :value)';
		$types = array('id' => 'integer', 'category_id' => 'integer',
						'value' => 'text');
		$insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);


		// insert the tag
		$data = array(	'id' 			=> $object->getId(),
						'category_id' 	=> $object->getCategoryId(),
                        'value' 		=> $object->getValue());


		$this->doStatement($insertStmt, $data);
        $this->_assignTag($object);
	}


    protected function _assignTag($object)
    {
		// insert the tag into the relevant table to associate it with a parent object - eg company or post
		foreach (app_domain_Tag::getValidTypes() as $key => $value)
		{

//			echo "<pre>";
//			echo '$key = ' . $key . '\n';
//			echo "</pre>";
			if ($key == get_class($object->getParentDomainObject()))
			{

				$query = 	'INSERT INTO '. $value['table'] .' (tag_id, ' .$value['field'] . ') ' .
							'VALUES (' .
							':tag_id, :object_id)';
				$types = array(	'tag_id' 		=> 'integer',
								'object_id' 	=> 'integer');

//				echo '$query = ' . $query . '\n';

				$stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

				$data = array('tag_id' => $object->getId(), 'object_id' => $object->getParentDomainObject()->getId());

//				echo '$object->getId() = ' . $object->getId() . '<br />';
//				echo '$object->getparentDomainObject()->getId() = ' . $object->getparentDomainObject()->getId() . '<br />';

				$this->doStatement($stmt, $data);

//				$sql = 'INSERT INTO ' . $value['table'] . ' (tag_id, ' .$value['field'] . ') VALUES ' . $this->id . ', ' . $this->parentDomainObject->getId();
				break;
			}

		}
    }


	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_tags SET id = :id, category_id = :category_id, value = :value WHERE id = :id';
		$types = array('id' => 'integer', 'category_id' => 'integer', 'value' => 'text');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		// update the tag
		$data = array(	'id' => $object->getId(),
						'category_id' => $object->getCategoryId(),
                        'value' => $object->getValue());
		$this->doStatement($updateStmt, $data);
        $this->_assignTag($object);
	}


	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_tags WHERE id = :id';
		$types = array('id' => 'integer');
		$deleteStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		// delete the tag from the relevant table which associates it with a parent object - eg company or post
		foreach (app_domain_Tag::getValidTypes() as $key => $value)
		{

//			echo "<pre>";
//			echo '$key = ' . $key . '<br />';
//			echo "</pre>";

			if ($key == get_class($object->getParentDomainObject()))
			{

				$query = 	'DELETE FROM '. $value['table'] .' WHERE  tag_id = :tag_id ' .
							'AND '. $value['field'] . ' = :object_id';
				$types = array(	'tag_id' 		=> 'integer',
								'object_id' 	=> 'integer');

//				echo '$query = ' . $query . '\n';

				$stmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

				$values = array('tag_id' => $object->getId(), 'object_id' => $object->getParentDomainObject()->getId());

//				echo '$object->getId() = ' . $object->getId() . '<br />';
//				echo '$object->getparentDomainObject()->getId() = ' . $object->getparentDomainObject()->getId() . '<br />';

				$this->doStatement($stmt, $values);

//				$sql = 'INSERT INTO ' . $value['table'] . ' (tag_id, ' .$value['field'] . ') VALUES ' . $this->id . ', ' . $this->parentDomainObject->getId();
				break;
			}

		}

		// delete the tag
		$values = array('id' => $object->getId());
		$this->doStatement($deleteStmt, $values);
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper()
	 */
	public function doFind($id)
	{
		$query = 'SELECT * FROM tbl_tags WHERE id = :id';
		$types = array('id' => 'integer');
		$selectStmt = self::$DB->prepare($query, $types);

		$values = array('id' => $id);
		// factor this out

		// Returns an MDB2_Result object
		$result = $this->doStatement($selectStmt, $values);

		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper()
	 */
	public function findByValue($value)
	{
		$query      = 'SELECT * FROM tbl_tags WHERE value = :value';
		$selectStmt = self::$DB->prepare($query);
		$values     = array('value' => $value);

		$result = $this->doStatement($selectStmt, $values);
		return $this->load($result);
	}

	/**
	 * Find all tags.
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findAll()
	{
		$selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_tags ORDER BY id');
		$result = $this->doStatement($selectAllStmt, array());
		return new app_mapper_TagCollection($result, $this);
	}

	/**
	 * Find if a tag of a given value already exists
	 * @return integer
	 */
	public function countByValue($value)
	{
		$query = 'SELECT count(id) ' .
				'FROM tbl_tags ' .
				'WHERE value = ' . self::$DB->quote($value, 'text');
		return self::$DB->queryOne($query);
	}

	/**
	 * Find all tags by post initiative id.
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByCompanyId($company_id)
	{
		$query = 'SELECT t.* FROM tbl_tags t JOIN tbl_company_tags ct ON t.id = ct.tag_id ' .
				'WHERE ct.company_id = :company_id ' .
				'order by value';
		$types = array('company_id' => 'integer');
		$selectByCompanyIdStmt = self::$DB->prepare($query, $types);

		$values = array('company_id' => $company_id);
		$result = $this->doStatement($selectByCompanyIdStmt, $values);
		return new app_mapper_TagCollection($result, $this);
	}


	/**
	 * Find all tags by post parent object id and category id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByParentObjectIdAndCategoryId($parent_object_type, $parent_object_id, $category_id)
	{

		foreach (app_domain_Tag::getValidTypes() as $key => $value)
		{
			if ($key == $parent_object_type)
			{
				// Select by object parent id and category id
//				$query = 'SELECT t.* FROM tbl_tags t JOIN ' . $value['table'] . ' pt ON t.id = pt.tag_id ' .
//						'WHERE pt.' . $value['field'] .' = :parent_object_id ' .
//						'AND t.category_id = :category_id ' .
//						'order by value';
				$query = 'SELECT t.* ' .
						'FROM tbl_tags t JOIN ' . $value['table'] . ' pt ON t.id = pt.tag_id ' .
						'WHERE pt.' . $value['field'] .' = :parent_object_id ' .
						'AND t.category_id = :category_id ' .
						'order by value';
				$types = array('parent_object_id' => 'integer', 'category_id' => 'integer');
				break;
			}
		}

		// check we have assigned something to the $query and $types variables
		if (count($types) == 0)
		{
			return false;
		}

		$stmt = self::$DB->prepare($query, $types);

		$values = array('parent_object_id' => $parent_object_id,
						'category_id' => $category_id);

        $result = $this->doStatement($stmt, $values);
//		return self::mdb2ResultToArray($result);
		return new app_mapper_TagCollection($result, $this);
	}


	/**
	 * Counts the occurence of a tag value by post parent object id and category id
	 * @return integer -
	 */
	public function countOfTagValueByParentObjectIdAndCategoryId($parent_object_type, $parent_object_id, $category_id, $tag_value)
	{

		foreach (app_domain_Tag::getValidTypes() as $key => $value)
		{
			if ($key == $parent_object_type)
			{
				// Select by object parent id and category id
				$query = 'SELECT count(t.value) FROM tbl_tags t JOIN ' . $value['table'] . ' pt ON t.id = pt.tag_id ' .
						'WHERE pt.' . $value['field'] .' = :parent_object_id ' .
						'AND t.category_id = :category_id ' .
						'AND t.value = :value ' .
						'group by value';
				$types = array(	'parent_object_id' => 'integer',
								'category_id' => 'integer',
								'value' => 'text');

				break;
			}
		}

		// check we have assigned something to the $query and $types variables
		if (count($types) == 0)
		{
			return false;
		}

		$stmt = self::$DB->prepare($query, $types);

		$values = array('parent_object_id' => $parent_object_id,
						'category_id' => $category_id,
						'value' => $tag_value);

		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];

	}


	/**
	 * Find all tags by post initiative id.
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByCompanyIdAndCategoryId($company_id, $category_id)
	{
		$query = 'SELECT t.* FROM tbl_tags t JOIN tbl_company_tags ct ON t.id = ct.tag_id ' .
				'WHERE ct.company_id = :company_id ' .
				'AND t.category_id = :category_id ' .
				'order by value';
		$types = array('company_id' => 'integer', 'category_id' => 'integer');
		$selectByCompanyIdAndCategoryIdStmt = self::$DB->prepare($query, $types);

		$values = array('company_id' => $company_id, 'category_id' => $category_id);
		$result = $this->doStatement($selectByCompanyIdAndCategoryIdStmt, $values);
		return new app_mapper_TagCollection($result, $this);
	}

	/** Find tag category description from $category_id
	 * @return app_mapper_TagCollection raw data - single item
	 */
	public function lookupCategoryById($category_id)
	{

		$values = array('category_id' => $category_id);
		$types = array('category_id' => 'integer');
		$query = 'select name from tbl_tag_categories where id = :category_id';
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/** Find all project ref tags for given initiative id
	 * @param integer $initiative_id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findProjectRefByInitiativeId($initiative_id)
	{
		$query = 'SELECT t.id, t.value FROM tbl_tags t ' .
				'JOIN tbl_post_initiative_tags pit ON t.id = pit.tag_id ' .
				'JOIN tbl_post_initiatives pi on pit.post_initiative_id = pi.id ' .
				'WHERE pi.initiative_id = ' . self::$DB->quote($initiative_id, 'integer') . ' ' .
				'AND t.category_id = 3 ' .
				'GROUP BY t.value ' .
				'order by t.value';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find all initiative records which equal a given string.
	 * @param string $project_ref project ref
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByProjectRefEqual($project_ref)
	{
		// Select By project ref equal
		$query = 'SELECT vw_c.id, vw_c.name, vw_c.website, vw_c.telephone, vw_c.telephone_tps, ' .
				'vw_pc.id as post_id, vw_pc.first_name, vw_pc.surname, vw_pc.job_title, vw_cl.initiative_id, vw_cl.initiative_name, vw_cl.client_name, ' .
				'lkp_cs.description as status, pi.id as post_initiative_id ' .
				'FROM vw_companies_sites AS vw_c ' .
				'INNER JOIN vw_posts_contacts AS vw_pc ON vw_pc.company_id = vw_c.id ' .
				'INNER JOIN tbl_post_initiatives pi on vw_pc.id = pi.post_id ' .
				'INNER JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id ' .
				'INNER JOIN tbl_tags t on pit.tag_id = t.id ' .
				'INNER JOIN tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
				'INNER JOIN vw_client_initiatives vw_cl on vw_cl.initiative_id = pi.initiative_id ' .
				'WHERE t.value = ' . self::$DB->quote($project_ref, 'text') . ' ' .
				'AND t.category_id = 3 ' .
				'ORDER BY vw_c.name, vw_pc.propensity';
		$result = self::$DB->query($query);
		return new app_mapper_TagCollection($result, $this);
	}

	/**
	 * Find all initiative records which start with a given string.
	 * @param string $project_ref project ref
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByProjectRefStart($project_ref)
	{
		// Select By project ref equal
		$query = 'SELECT vw_c.id, vw_c.name, vw_c.website, vw_c.telephone, vw_c.telephone_tps, ' .
				'vw_pc.id as post_id, vw_pc.first_name, vw_pc.surname, vw_pc.job_title, vw_cl.initiative_id, vw_cl.initiative_name, vw_cl.client_name, ' .
				'lkp_cs.description as status, pi.id as post_initiative_id ' .
				'FROM vw_companies_sites AS vw_c ' .
				'INNER JOIN vw_posts_contacts AS vw_pc ON vw_pc.company_id = vw_c.id ' .
				'INNER JOIN tbl_post_initiatives pi on vw_pc.id = pi.post_id ' .
				'INNER JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id ' .
				'INNER JOIN tbl_tags t on pit.tag_id = t.id ' .
				'INNER JOIN tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
				'INNER JOIN vw_client_initiatives vw_cl on vw_cl.initiative_id = pi.initiative_id ' .
				'WHERE t.value like ' . self::$DB->quote($project_ref . '%', 'text') . ' ' .
				'AND t.category_id = 3 ' .
				'ORDER BY vw_c.name, vw_pc.propensity';
		$result = self::$DB->query($query);
		return new app_mapper_TagCollection($result, $this);
	}

	/**
	 * Find all initiative records which include a given string.
	 * @param string $project_ref project ref
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByProjectRefInclude($project_ref)
	{
		// Select By project ref equal
		$query = 'SELECT vw_c.id, vw_c.name, vw_c.website, vw_c.telephone, vw_c.telephone_tps, ' .
				'vw_pc.id as post_id, vw_pc.first_name, vw_pc.surname, vw_pc.job_title, vw_cl.initiative_id, vw_cl.initiative_name, vw_cl.client_name, ' .
				'lkp_cs.description as status, pi.id as post_initiative_id ' .
				'FROM vw_companies_sites AS vw_c ' .
				'INNER JOIN vw_posts_contacts AS vw_pc ON vw_pc.company_id = vw_c.id ' .
				'INNER JOIN tbl_post_initiatives pi on vw_pc.id = pi.post_id ' .
				'INNER JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id ' .
				'INNER JOIN tbl_tags t on pit.tag_id = t.id ' .
				'INNER JOIN tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
				'INNER JOIN vw_client_initiatives vw_cl on vw_cl.initiative_id = pi.initiative_id ' .
				'WHERE t.value like ' . self::$DB->quote('%' . $project_ref . '%', 'text') . ' ' .
				'AND t.category_id = 3 ' .
				'ORDER BY vw_c.name, vw_pc.propensity';
		$result = self::$DB->query($query);
		return new app_mapper_TagCollection($result, $this);
	}

	/** Find all company records which equal a given string.
	 * @param string $value value
	 * @param integer $category_id tag category id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByCompanyTagCategoryIdEqual($value, $category_id)
	{
		$query = 'SELECT vw_c.id, vw_c.name, vw_c.website, vw_c.telephone, vw_c.telephone_tps ' .
				'FROM vw_companies_sites AS vw_c ' .
				'INNER JOIN tbl_company_tags ct ON vw_c.id = ct.company_id ' .
				'INNER JOIN tbl_tags t on ct.tag_id = t.id ' .
				'WHERE t.value = ' . self::$DB->quote($value, 'text') . ' ' .
				'AND t.category_id = ' . self::$DB->quote($category_id, 'integer') . ' ' .
				'ORDER BY vw_c.name';
		$result = self::$DB->query($query);
		return new app_mapper_TagCollection($result, $this);
	}

	/** Find all company records which start with a given string.
	 * @param string $value value
	 * @param integer $category_id tag category id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByCompanyTagCategoryIdStart($value, $category_id)
	{
		$query = 'create temporary table t_max_company ' .
				'SELECT vw_c.id as id ' .
				'FROM vw_companies_sites AS vw_c ' .
				'INNER JOIN tbl_company_tags ct ON vw_c.id = ct.company_id ' .
				'INNER JOIN tbl_tags t on ct.tag_id = t.id ' .
				'WHERE t.value like ' . self::$DB->quote($value . '%', 'text') . ' ' .
				'AND t.category_id = ' . self::$DB->quote($category_id, 'integer') . ' ' .
				'GROUP BY vw_c.id';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt);

		$query = 'SELECT vw_c.id, vw_c.name, vw_c.website, vw_c.telephone ' .
				'FROM vw_companies_sites AS vw_c ' .
				'JOIN t_max_company t on t.id = vw_c.id ' .
				'ORDER BY vw_c.name';

		$result = self::$DB->query($query);

		$query = 'DROP TEMPORARY TABLE t_max_company';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt);

		return new app_mapper_TagCollection($result, $this);
	}

	/** Find all company records which include a given string.
	 * @param string $value value
	 * @param integer $category_id tag category id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public function findByCompanyTagCategoryIdIncludes($value, $category_id)
	{
		$query = 'create temporary table t_max_company ' .
				'SELECT vw_c.id as id ' .
				'FROM vw_companies_sites AS vw_c ' .
				'INNER JOIN tbl_company_tags ct ON vw_c.id = ct.company_id ' .
				'INNER JOIN tbl_tags t on ct.tag_id = t.id ' .
				'WHERE t.value like ' . self::$DB->quote('%' . $value . '%', 'text') . ' ' .
				'AND t.category_id = ' . self::$DB->quote($category_id, 'integer') . ' ' .
				'GROUP BY vw_c.id';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt);

		$query = 'SELECT vw_c.id, vw_c.name, vw_c.website, vw_c.telephone ' .
				'FROM vw_companies_sites AS vw_c ' .
				'JOIN t_max_company t on t.id = vw_c.id ' .
				'ORDER BY vw_c.name';

		$result = self::$DB->query($query);

		$query = 'DROP TEMPORARY TABLE t_max_company';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt);

		return new app_mapper_TagCollection($result, $this);
	}
}

?>