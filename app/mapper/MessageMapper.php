<?php

/**
 * Defines the app_mapper_MessageMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/Mapper.php');

/**
 * @package Alchemis
 */
class app_mapper_MessageMapper extends app_mapper_Mapper implements app_domain_MessageFinder
{
	protected $selectByPostStmt;
	protected $insertStmt;
	protected $updateStmt;
	protected $deleteStmt;
	protected $id;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
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
		$obj = new app_domain_Message($array['id']);
		$obj->setTimestamp($array['timestamp']);
		$obj->setUserId($array['user_id']);
		$obj->setMessage($array['message']);
		$obj->setPublished($array['published']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_messages');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
//		echo "<p><b>app_mapper_MessageMapper::doInsert()</b></p>";
		if (!isset($this->insertStmt))
		{
			$query = 'INSERT INTO tbl_messages (id, timestamp, user_id, message, published) VALUES (?, ?, ?, ?, ?)';
			$types = array('integer', 'timestamp', 'integer', 'text', 'boolean');
			$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		}
		$data = array($object->getId(), $object->getTimestamp(), $object->getUserId(), $object->getMessage(), $object->isPublished());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
//		echo "<p><b>app_mapper_MessageMapper::update()</b></p>";
		if (!isset($this->updateStmt))
		{
			$query = 'UPDATE tbl_messages SET timestamp = ?, user_id = ?, message = ?, published = ? WHERE id = ?';
			$types = array('timestamp', 'integer', 'text', 'boolean', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		}
		$data = array($object->getTimestamp(), $object->getUserId(), $object->getMessage(), $object->isPublished(), $object->getId());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		if (!isset($this->deleteStmt))
		{
			$query = 'DELETE FROM tbl_messages WHERE id = ?';
			$types = array('integer');
			$this->deleteStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		}
		$data = array($object->getId());
		$this->doStatement($this->deleteStmt, $data);
	}

	/**
	 * Find the given action.
	 * @param integer $id contact ID
	 * @return app_domain_Contact
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT * FROM tbl_messages WHERE id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/**
	 * Find all messages.
	 * @return app_mapper_MessageCollection collection of app_domain_Message objects
	 */
	public function findAll()
	{
		$query = 'SELECT * FROM tbl_messages ORDER BY timestamp DESC';
		$result = self::$DB->query($query);
		return new app_mapper_MessageCollection($result, $this);
	}

	/**
	 * Find all messages limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_MessageCollection collection of app_domain_Message objects
	 */
	public function findSet($limit = 3, $offset = 0)
	{
		$query = 'SELECT * FROM tbl_messages WHERE published = 1 ORDER BY timestamp DESC ' .
					'LIMIT ' . self::$DB->quote($offset, 'integer') . ',' . self::$DB->quote($limit, 'integer');
		$result = self::$DB->query($query);
		return new app_mapper_MessageCollection($result, $this);
	}

}

?>