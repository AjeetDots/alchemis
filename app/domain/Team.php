<?php

/**
 * Defines the app_domain_Team class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Team extends app_domain_DomainObject
{
	protected $name;
	protected $nbms;

	/**
	 * @param integer $id
	 * @param string $name 
	 */
	public function __construct($id = null, $name = null)
	{
		parent::__construct($id);
		if ($this->id)
		{

		}
		else
		{
			echo "<br />skip load";
		}
	}

	/**
	 * @param string $field
	 */
	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['name'] = array('alias'      => 'Name',
		                      'type'       => 'text',
		                      'mandatory'  => true,
		                      'max_length' => 50);
		if (!is_null($field))
		{
			return $spec[$field];
		}
		else
		{
			return $spec;
		}
		
	}

	/**
	 * Set the ID of the owner user.
	 * @param integer $user_id
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}
	
	/**
	 * Return the ID of the owner user.
	 * @return integer
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Find all teams.
	 * @return app_mapper_TeamCollection collection of app_domain_Team objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
	 * @param integer $id
	 * @return app_mapper_VenueMapper
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * Find the progress of campaigns associated with a user.
	 * @param integer $user_id
	 * @return array
	 */
	public static function findDashboardStatistics()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDashboardStatistics();
	}

	/**
	 * Find teams in a form suitable for a drop-down box.
	 * @return array
	 */
	public static function findForDropdown()
	{
		$items = self::findAll()->toRawArray();
		foreach ($items as $item)
		{
			$res[$item['id']] = $item['name'];
		}
		return $res;
	}

//	public static function addNbm($team_id, $nbm_id)
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->addNbm($team_id, $nbm_id);
//	}

	/**
	 * Return the naem of a team given an ID.
	 * @param integer $team_id
	 * @return string
	 */
	public static function getTeamName($team_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getTeamName($team_id);
	}

}

?>