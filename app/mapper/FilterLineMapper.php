<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_FilterLineMapper extends app_mapper_Mapper implements app_domain_FilterLineFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_filter_lines ORDER BY id');

		// Select single
		$query = 	'SELECT * FROM tbl_filter_lines ' .
					'WHERE id = :id';
				
		$types = array('id' => 'integer');
		$this->selectStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Filter($array['id']);
		$obj->setFilterId($array['filter_id']);
		$obj->setTableName($array['table_name']);
		$obj->setFieldName($array['field_name']);
		$obj->setParams($array['params']);
		$obj->setParamsDisplay($array['params_display']);
		$obj->setOperator($array['operator']);
		$obj->setConcatenator($array['concatenator']);
		$obj->setBracketOpen($array['bracket_open']);
		$obj->setBracketClose($array['bracket_close']);
		$obj->setDirection($array['direction']);
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
		return 'app_domain_FilterLine';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_filter_lines');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		
		$query = 'INSERT INTO tbl_filter_lines (id, filter_id, table_name, field_name, params, params_display, ' .
				'operator, concatenator, bracket_open, bracket_close, direction) VALUES ' .
				'(:id, :filter_id, :table_name, :field_name, :params, :params_display, ' .
				':operator, :concatenator, :bracket_open, :bracket_close, :direction)';
		$types = array(	'id' => 'integer', 
						'filter_id' => 'integer', 
						'table_name' => 'text', 
						'field_name' => 'text', 
						'params' => 'text', 
						'params_display' => 'text',
						'operator' => 'text',
						'concatenator' => 'text',
						'bracket_open' => 'text',
						'bracket_close' => 'text',
						'direction' => 'text');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	'id' => $object->getId(),
						'filter_id' => $object->getFilterId(),
						'table_name' => $object->getTableName(),
						'field_name' => $object->getFieldName(),
						'params' => $object->getParams(),
						'params_display' => $object->getParamsDisplay(),
						'operator' => $object->getOperator(),
						'concatenator' => $object->getConcatenator(),
						'bracket_open' => $object->getBracketOpen(),
						'bracket_close' => $object->getBracketClose(),
						'direction' => $object->getDirection());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object){}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$values = array('id' => $id);
		// Returns an MDB2_Result object 
		$result = $this->doStatement($this->selectStmt, $values);
		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

	/**
	 * Find all posts.
	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_PostInitiativeCollection($result, $this);
	}


}

?>