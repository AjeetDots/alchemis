<?php

/**
 * Defines the app_mapper_ObjectCharacteristicHelperMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/controller/ApplicationHelper.php');

/**
 * @package Alchemis
 */
class app_mapper_ObjectCharacteristicHelperMapper
{
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
	 * Returns the object characteristic ID for a given object and characteristic combination.
	 * @param integer $parent_object_id
	 * @param string $parent_object_type in set {app_domain_Company, app_domain_Post, app_domain_PostInitiative}
	 * @param integer $characteristic_id
	 * @return
	 */
	public function getObjectCharacteristicIdByParentObjectIdAndCharacteristicId($parent_object_id, $parent_object_type, $characteristic_id)
	{
		
		$query = 'SELECT data_type FROM tbl_characteristics WHERE id = ' . self::$DB->quote($characteristic_id, 'integer');
		$data_type = self::$DB->queryOne($query);

		switch ($parent_object_type)
		{
			case 'app_domain_Company':
				$parent_id_field = 'company_id';
				break;
			
			case 'app_domain_Post':
				$parent_id_field = 'post_id';
				break;
			
			case 'app_domain_PostInitiative':
				$parent_id_field = 'post_initiative_id';
				break;
			
			default:
				throw new Exception('Unknown parent object type: ' . $parent_object_type);
		}

		$tables = array('tbl_object_characteristics');
		if (in_array($data_type, array('boolean', 'date', 'text'), true)) {
			array_unshift($tables, 'tbl_object_characteristics_' . $data_type);
		}

		foreach ($tables as $table_name) {
			$query = 'SELECT id FROM ' . $table_name . ' WHERE characteristic_id = ' . self::$DB->quote($characteristic_id, 'integer') . ' ' .
				'AND ' . $parent_id_field . ' = ' . self::$DB->quote($parent_object_id, 'integer') . ' ' .
				'ORDER BY id DESC LIMIT 1';
			$result = self::$DB->queryOne($query);
			if (!empty($result)) {
				return $result;
			}
		}

		return null;
	}

	/**
	 * Remove all object-characteristic rows (and element values) for a parent/characteristic pair.
	 */
	public function deleteByParentObjectAndCharacteristicId($parent_object_id, $parent_object_type, $characteristic_id)
	{
		switch ($parent_object_type)
		{
			case 'app_domain_Company':
				$parent_id_field = 'company_id';
				break;
			case 'app_domain_Post':
				$parent_id_field = 'post_id';
				break;
			case 'app_domain_PostInitiative':
				$parent_id_field = 'post_initiative_id';
				break;
			default:
				throw new Exception('Unknown parent object type: ' . $parent_object_type);
		}

		$parent_object_id = (int) $parent_object_id;
		$characteristic_id = (int) $characteristic_id;
		$parentClause = $parent_id_field . ' = ' . self::$DB->quote($parent_object_id, 'integer');
		$charClause = 'characteristic_id = ' . self::$DB->quote($characteristic_id, 'integer');

		$baseIds = self::$DB->queryCol(
			'SELECT id FROM tbl_object_characteristics WHERE ' . $charClause . ' AND ' . $parentClause
		);
		if (is_array($baseIds)) {
			foreach ($baseIds as $ocId) {
				$ocId = (int) $ocId;
				if ($ocId < 1) {
					continue;
				}
				foreach (array('boolean', 'text', 'date') as $elementType) {
					self::$DB->exec(
						'DELETE FROM tbl_object_characteristic_elements_' . $elementType .
						' WHERE object_characteristic_id = ' . $ocId
					);
				}
			}
			self::$DB->exec('DELETE FROM tbl_object_characteristics WHERE ' . $charClause . ' AND ' . $parentClause);
		}

		foreach (array('boolean', 'text', 'date') as $typedTable) {
			self::$DB->exec(
				'DELETE FROM tbl_object_characteristics_' . $typedTable .
				' WHERE ' . $charClause . ' AND ' . $parentClause
			);
		}
	}

	/**
	 * 
	 * @param MDB2_Statement_Common $stmt the statement to execute
	 * @param array $values array of data values to pass to use with the statement
	 * @return a result handle or MDB2_OK on success, a MDB2 error on failure
	 *         NB to test whether any results where returned use $res->numRows()
	 */
	public function doStatement($sth, $values)
	{
		$this->debug = (get_class($sth) == 'MDB2_Error');
		if ($this->debug) echo "<h2>app_mapper_ObjectCharacteristicHelperMapper::doStatement(".get_class($sth).", $values)</h2>";
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($sth);
		if ($this->debug) echo "</pre>";
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($values);
		if ($this->debug) echo "</pre>";
		if ($this->debug) echo "\$sth type = " . get_class($sth);
		
		$res = $sth->execute($values);
		
		if (MDB2::isError($res))
		{
			throw new app_base_MDB2Exception($res);
		}
		return $res;
	}

}

?>