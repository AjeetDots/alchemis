<?php

/**
 * Defines the app_mapper_TeamMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/Mapper.php');

/**
 * @package Alchemis
 */
class app_mapper_TeamMapper extends app_mapper_Mapper implements app_domain_MessageFinder
{

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Team($array['id']);
		$obj->setName($array['name']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_teams');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
//		echo "<p><b>app_mapper_TeamMapper::doInsert()</b></p>";
		if (!isset($this->insertStmt))
		{
			$query = 'INSERT INTO tbl_teams (id, name) VALUES (?, ?)';
			$types = array('integer', 'text');
			$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		}
		$data = array($object->getId(), $object->getName());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
//		echo "<p><b>app_mapper_TeamMapper::update()</b></p>";
		if (!isset($this->updateStmt))
		{
			$query = 'UPDATE tbl_teams SET name = ? WHERE id = ?';
			$types = array('text', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		}
		$data = array($object->getName(), $object->getId());
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
			$query = 'DELETE FROM tbl_teams WHERE id = ?';
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
		$query = 'SELECT * FROM tbl_teams WHERE id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/**
	 * Find all teams.
	 * @return app_mapper_TeamCollection collection of app_domain_Team objects
	 */
	public function findAll()
	{
		$query = 'SELECT * FROM tbl_teams ORDER BY name';
		$result = self::$DB->query($query);
		return new app_mapper_TeamCollection($result, $this);
	}

	/**
	 * Find the progress of campaigns associated with a user.
	 * @param integer $user_id
	 * @return array
	 */
	public function findDashboardStatistics()
	{
		$year_month = date('Ym', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));

		$query = 'SELECT ds.`year_month`, SUM(ds.call_count) AS call_count, ' .
					'SUM(ds.call_effective_count) AS call_effective_count, SUM(ds.meeting_set_count) AS meeting_set_count, ' .
					'SUM((ds.call_count + (ds.call_ote_count * 10) + (ds.meeting_set_count * 100))) AS kpi, ' .
					'n.team_id, t.name AS team ' .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_team_nbms AS n ON ds.user_id = n.user_id ' .
					'INNER JOIN tbl_teams AS t ON n.team_id = t.id ' .
					'WHERE ds.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
					'GROUP BY n.team_id, t.name, ds.`year_month` ' .
					'ORDER BY t.name';

		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Return the naem of a team given an ID.
	 * @param integer $team_id
	 * @return string
	 */
	public function getTeamName($team_id)
	{
		$sql = 'SELECT name FROM tbl_teams WHERE id = ' . self::$DB->quote($team_id, 'integer');
		return self::$DB->queryOne($sql);
	}

}

?>