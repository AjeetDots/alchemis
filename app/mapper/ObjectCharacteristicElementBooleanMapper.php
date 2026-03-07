<?php

/**
 * Defines the app_mapper_ObjectCharacteristicElementBooleanMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ObjectCharacteristicElementMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_ObjectCharacteristicElementBooleanMapper
{
	protected function init()
	{
		// Select single
		$query = 'SELECT * FROM tbl_object_characteristic_elements_boolean WHERE id = ?';
		$types = array('integer');
		$this->select_stmt = self::$DB->prepare($query, $types);

		// Select all records by object characteristic id
		$query = 'SELECT e.id FROM tbl_object_characteristic_elements_boolean AS e ' .
					'WHERE e.object_characteristic_id = ?';
		$types = array('integer');
		$this->select_records_by_obj_char_id_stmt = self::$DB->prepare($query, $types);

		// Select value for company
		$query = 'SELECT e.* FROM tbl_object_characteristic_elements_boolean AS e ' .
					'INNER JOIN tbl_object_characteristics AS c ON e.object_characteristic_id = c.id ' .
					'WHERE e.characteristic_element_id = ? AND c.company_id = ?';
		$types = array('integer', 'integer');
		$this->select_company_value_stmt = self::$DB->prepare($query, $types);

		// Select value for post
		$query = 'SELECT e.* FROM tbl_object_characteristic_elements_boolean AS e ' .
					'INNER JOIN tbl_object_characteristics AS c ON e.object_characteristic_id = c.id ' .
					'WHERE e.characteristic_element_id = ? AND c.post_id = ?';
		$types = array('integer', 'integer');
		$this->select_post_value_stmt = self::$DB->prepare($query, $types);

		// Select value for post initiative
		$query = 'SELECT e.* FROM tbl_object_characteristic_elements_boolean AS e ' .
					'INNER JOIN tbl_object_characteristics AS c ON e.object_characteristic_id = c.id ' .
					'WHERE e.characteristic_element_id = ? AND c.post_initiative_id = ?';
		$types = array('integer', 'integer');
		$this->select_post_initiative_value_stmt = self::$DB->prepare($query, $types);

		// Insert
		$query = 'INSERT INTO tbl_object_characteristic_elements_boolean ' .
					'(id, object_characteristic_id, characteristic_element_id, value) ' .
					'VALUES (?, ?, ?, ?)';
		$types = array('integer', 'integer', 'integer', 'integer');
		$this->insert_stmt = self::$DB->prepare($query, $types);

		// Update
		$query = 'UPDATE tbl_object_characteristic_elements_boolean ' .
					'SET object_characteristic_id = ?, characteristic_element_id = ?, value = ? ' .
					'WHERE id = ?';
		$types = array('integer', 'integer', 'integer', 'integer');
		$this->update_stmt = self::$DB->prepare($query, $types);

		// Delete
		$query = 'DELETE FROM tbl_object_characteristic_elements_boolean WHERE id = ?';
		$types = array('integer');
		$this->delete_stmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array) {}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		// $this->id = self::$DB->nextID('tbl_object_characteristic_elements_boolean');
		return null;
	}

}

?>