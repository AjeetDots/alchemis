<?php

/**
 * Defines the app_domain_FilterLine class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_FilterLine extends app_domain_DomainObject
{
	private $filter_id;
	private $table_name;
	private $field_name;
	private $params;
	private $params_display;
	private $operator;
	private $concatenator;
	private $bracket_open;
	private $bracket_close;
	private $direction;
				
		
	function __construct($id = null)
	{
		parent::__construct($id);
	}
	
	/**
	 * Set the filter_id - the id of the parent filter
	 * @param number $filter_id of this filter line 
	 */
	public function setFilterId($filter_id)
	{
		$this->filter_id = $filter_id;
		$this->markDirty();
	}
	
	/**
	 * Set the table of the filter line 
	 * @param string $table name of this filter line 
	 */
	public function setTableName($table_name)
	{
		$this->table_name = $table_name;
		$this->markDirty();
	}
	
	/**
	 * Set the filter line field 
	 * @param string $description of the filter line
	 */
	public function setFieldName($field_name)
	{
		$this->field_name = $field_name;
		$this->markDirty();
	}
	
	/**
	 * Set the filter line params.
	 * @param string $params of the filter line
	 */
	public function setParams($params)
	{
		$this->params = $params;
		$this->markDirty();
	}

	/**
	 * Set the filter line display params.
	 * @param string $params of the filter line to display to the user
	 */
	public function setParamsDisplay($params_display)
	{
		$this->params_display = $params_display;
		$this->markDirty();
	}
	
	/**
	 * Set the operator of the filter line
	 * @param string $operator
	 */
	public function setOperator($operator)
	{
		$this->operator = $operator;
		$this->markDirty();
	}
	
	/**
	 * Set the concatenator of the filter line
	 * @param string $concatenator
	 */
	public function setConcatenator($concatenator)
	{
		$this->concatenator = $concatenator;
		$this->markDirty();
	}

	/**
	 * Set the opening bracket string of the filter line.
	 * @param string $bracket_open string of the filter line 
	 */
	public function setBracketOpen($bracket_open)
	{
		$this->bracket_open = $bracket_open;
		$this->markDirty();
	}
	
	/**
	 * Set the closing bracket string of the filter line.
	 * @param string $bracket_close string of the filter line 
	 */
	public function setBracketClose($bracket_close)
	{
		$this->bracket_close = $bracket_close;
		$this->markDirty();
	}
	
	/**
	 * Set the string direction of the filter line (whether the filter line is for an include or exclude statement)
	 * @param string $direction the name of the user who created the filter 
	 */
	public function setDirection($direction)
	{
		$this->direction = $direction;
		$this->markDirty();
	}
	
	/**
	 * Get the filter_id - the id of the parent filter
	 * @return string $filter_id (parent id of this filter line)
	 */
	public function getFilterId()
	{
		return $this->filter_id;
	}
	
	/**
	 * get the table of the filter line 
	 * @return string $table name of this filter line 
	 */
	public function getTableName()
	{
		return $this->table_name;
	}
	
	/**
	 * get the filter line field 
	 * @return string $field_name of the filter line
	 */
	public function getFieldName()
	{
		return $this->field_name;
	}
	
	/**
	 * get the filter line params.
	 * @return string $params of the filter line
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * get the filter line display params.
	 * @return string $params of the filter line to display to the user
	 */
	public function getParamsDisplay()
	{
		return $this->params_display;
	}
	
	/**
	 * get the operator of the filter line
	 * @return string $operator
	 */
	public function getOperator()
	{
		return $this->operator;
	}
	
	/**
	 * get the concatenator of the filter line
	 * @return string $concatenator
	 */
	public function getConcatenator()
	{
		return $this->concatenator;
	}

	/**
	 * get the opening bracket string of the filter line.
	 * @return string $bracket_open string of the filter line 
	 */
	public function getBracketOpen()
	{
		return $this->bracket_open;
	}
	
	/**
	 * get the closing bracket string of the filter line.
	 * @return string $bracket_close string of the filter line 
	 */
	public function getBracketClose()
	{
		return $this->bracket_close;
	}
	
	/**
	 * get the string direction of the filter line (whether the filter line is for an include or exclude statement)
	 * @return string $direction the name of the user who created the filter 
	 */
	public function getDirection()
	{
		return $this->direction;
	}
	
	
	/**
 	 * Find a filter line by a given id
	 * @param integer $id filter line id
	 * @return app_mapper_FilterLineCollection collection of app_domain_FilterLine objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	
	
}

?>
