<?php

/**
 * Defines the app_mapper_EventMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/Mapper.php');

/**
 * @package Alchemis
 */
class app_mapper_EventMapper extends app_mapper_Mapper implements app_domain_EventFinder
{
	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Event($array['id']);
		$obj->setSubject($array['subject']);
		$obj->setNotes($array['notes']);
		$obj->setDate($array['date']);
		$obj->setReminderDate($array['reminder_date']);
		$obj->setUserId($array['user_id']);
		$obj->setTypeId($array['type_id']);
		$obj->setClientId($array['client_id']);
		$obj->setDayPart($array['day_part']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_events');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_events ' .
					'(id, subject, notes, date, reminder_date, user_id, type_id, client_id, day_part) ' .
					'VALUES (' .
					self::$DB->quote($object->getId(), 'integer') . ', ' .
					self::$DB->quote($object->getSubject(), 'text') . ', ' .
					self::$DB->quote($object->getNotes(), 'text') . ', ' .
					self::$DB->quote($object->getDate(), 'timestamp') . ', ' .
					self::$DB->quote($object->getReminderDate(), 'timestamp') . ', ' .
					self::$DB->quote($object->getUserId(), 'integer') . ', ' .
					self::$DB->quote($object->getTypeId(), 'integer') . ', ' .
					self::$DB->quote($object->getClientId(), 'integer') . ', ' .
					self::$DB->quote($object->getDayPart(), 'decimal') . ')';
		self::$DB->query($query);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_events SET ' .
					'subject = ' . self::$DB->quote($object->getSubject(), 'text') . ', ' .
					'notes = ' . self::$DB->quote($object->getNotes(), 'text') . ', ' .
					'date = ' . self::$DB->quote($object->getDate(), 'timestamp') . ', ' .
					'reminder_date = ' . self::$DB->quote($object->getReminderDate(), 'timestamp') . ', ' .
					'user_id = ' . self::$DB->quote($object->getUserId(), 'integer') . ', ' .
					'type_id = ' . self::$DB->quote($object->getTypeId(), 'integer') . ', ' .
					'client_id = ' . self::$DB->quote($object->getClientId(), 'integer') . ', ' .
		            'day_part = ' . self::$DB->quote($object->getDayPart(), 'decimal') . ' ' .
					'WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_events WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);
	}

	/**
	 * Find the given action.
	 * @param integer $id contact ID
	 * @return app_domain_Contact
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT * FROM tbl_events WHERE id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/**
	 * Find all actions.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findAll()
	{
		$query = 'SELECT * FROM tbl_events ORDER BY date';
		$result = self::$DB->query($query);
		new app_mapper_EventCollection($result, $this);
	}

 	/**
 	 * Find the events owned by a given user.
	 * @param integer $user_id user ID
	 * @param integer $limit
	 * @return app_mapper_EventCollection collection of app_domain_Event objects
	 */
	public function findByUserId($user_id, $limit = null)
	{
		$query = 'SELECT *, IF(date < NOW(), -1, 0) AS compare ' .
					'FROM tbl_events ' .
					'WHERE user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'ORDER BY date ASC';
		if (!is_null($limit))
		{
			$query .= ' LIMIT ' . self::$DB->quote($limit, 'integer');
		}
		$result = self::$DB->query($query);
		return new app_mapper_EventCollection($result, $this);
	}

	/**
 	 * Count each type of event for a given user between two dates.
	 * @param integer $user_id user ID
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @return array
	 */
	public function countByUserIdAndDates($user_id, $start, $end)
	{
//		$start = $start . ' 00:00:00';
//		$end   = $end . ' 23:59:59';
//		$query = 'SELECT et.name, COUNT(e.user_id) AS count ' .
		$query = 'SELECT et.name, sum(e.day_part) AS count ' .
					'FROM tbl_events AS e ' .
					'INNER JOIN tbl_lkp_event_types AS et ON e.type_id = et.id ' .
					'WHERE e.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND e.date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND e.date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'GROUP BY et.name ' .
					'ORDER BY et.name';
//		echo $query;
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

	}

 	/**
 	 * Find the events associated with a given client.
	 * @param integer $client_id client ID
	 * @param integer $limit
	 * @return app_mapper_EventCollection collection of app_domain_Event objects
	 */
	public function findByClientId($client_id, $limit = null)
	{
		$query = 'SELECT *, IF(date < NOW(), -1, 0) AS compare ' .
					'FROM tbl_events ' .
					'WHERE client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'ORDER BY date ASC';
		if (!is_null($limit))
		{
			$query .= ' LIMIT ' . self::$DB->quote($limit, 'integer');
		}
		$result = self::$DB->query($query);
		return new app_mapper_EventCollection($result, $this);
	}

	/**
	 * Lookup possible event types.
	 * @return array
	 */
	public static function lookupTypes()
	{
		$query = 'SELECT * FROM tbl_lkp_event_types ORDER BY name';
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Lookup event type by ID.
	 * @param integer $type_id
	 * @return array
	 */
	public static function lookupTypeById($type_id)
	{
		$query = 'SELECT * FROM tbl_lkp_event_types WHERE id = ' . self::$DB->quote($type_id, 'integer');
		return self::$DB->queryRow($query, null, MDB2_FETCHMODE_ASSOC);
	}

}

?>