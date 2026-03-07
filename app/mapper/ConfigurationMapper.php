<?php

/**
 * Defines the app_mapper_ConfigurationMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

/**
 * @package Framework
 */
class app_mapper_ConfigurationMapper
{
	private $debug = false;

	/**
	 * Holds database connection resource
	 * @var resource
	 */
	protected static $DB;

	/**
	 * Uses and ApplicationHelper to get a MDB2_Common object
	 */
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
	}

	/**
	 * 
	 * @param MDB2_Statement_Common $stmt the statement to execute
	 * @param array $values array of data values to pass to use with the statement
	 * @return a result handle or MDB2_OK on success, a MDB2 error on failure
	 *         NB to test whether any results where returned use $res->numRows()
	 */
	public function doStatement($sth, $values = null)
	{
		$this->debug = (get_class($sth) == 'MDB2_Error');
		
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($sth);
		if ($this->debug) echo "</pre>";
		
		if ($this->debug) echo "<h2>app_mapper_Mapper::doStatement(".get_class($sth).", $values)</h2>";
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($values);
		if ($this->debug) echo "</pre>";
		if ($this->debug) echo "\$sth type = " . get_class($sth);
		
		try
		{
			$res = $sth->execute($values);
		}
		catch (Exception $e)
		{
			exit($e->getMessage());
		}
		
		if (MDB2::isError($res))
		{
			throw new app_base_MDB2Exception($res);
		}
		return $res;
	}

	/**
	 */
	public function find($key)
	{
		$query = 'SELECT value FROM tbl_configuration WHERE property = ' . self::$DB->quote($key, 'integer');
		$result = self::$DB->query($query);
		return $result->fetchOne(0, 0);
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	public function save($property, $value)
	{
		$query = 'SELECT COUNT(*) FROM tbl_configuration WHERE property = ' . self::$DB->quote($key, 'integer');
		$result = self::$DB->query($query);
		$count = $result->fetchOne(0, 0);
		
		if ($count == 0)
		{
			return $this->doInsert($property, $value);
		}
		else
		{
			return $this->doUpdate($property, $value);
		}
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	public function delete($property)
	{
		$query = 'DELETE FROM tbl_configuration WHERE property = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($stmt, $data);
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	protected function doInsert($property, $value)
	{
		$query = 'INSERT INTO tbl_configuration (property, value) VALUES (?, ?)';
		$types = array('text', 'text');
		$this->insertStmt = self::$DB->prepare($query, $types);
		$data = array($property, $value);
		$this->doStatement($this->insertStmt, $data);	
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	protected function doUpdate($property, $value)
	{
		$query = 'UPDATE tbl_configuration SET value = ? WHERE property = ?';
		$types = array('text', 'text');
		$updateStmt = self::$DB->prepare($query, $types);
		$data = array($property, $value);
		$this->doStatement($updateStmt, $data);	
	}


}

?>