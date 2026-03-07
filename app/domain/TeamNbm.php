<?php

/**
 * Defines the app_domain_TeamNbm class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_TeamNbm extends app_domain_DomainObject
{
	/**
	 * Team ID
	 * @var integer
	 */
	protected $team_id;

	/**
	 * User ID
	 * @var integer
	 */
	protected $user_id;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Sets the team_id of the team NBM.
	 * @param integer $team_id
	 */
	function setTeamId($team_id)
	{
		$this->team_id = $team_id;
		$this->markDirty();
	}

	/**
	 * Gets the team_id of the team NBM.
	 * @return integer
	 */
	function getTeamId()
	{
		return $this->team_id;
	}

	/**
	 * Sets the user_id of the team NBM
	 * @param integer $user_id
	 */
	function setUserId($user_id)
	{
		$this->user_id = $user_id;
		$this->markDirty();
	}

	/**
	 * Gets the user_id of the team NBM
	 * @return integer
	 */
	function getUserId()
	{
		return $this->user_id;
	}

	/**
 	 * Find a team NBM by a given ID.
	 * @param integer $id team NBM ID
	 * @return app_mapper_TeamNbmCollection collection of app_domain_TeamNbm objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
 	 * Find all team NBMs.
	 * @return app_mapper_TeamNbmCollection collection of app_domain_TeamNbm objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
 	 * Find NBMs in a given team.
 	 * @param integer $team_id team ID
	 * @return app_mapper_TeamNbmCollection collection of app_domain_TeamNbm objects
	 */
	public static function findByTeamId($team_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByTeamId($team_id);
	}

	/**
 	 * Find the team NBM record ID for a given user ID.
 	 * @param integer $user_id user ID
	 * @return integer
	 */
	public static function findIdByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findIdByUserId($user_id);
	}

	/**
 	 * Return the number of NBMs in a given team.
 	 * @param integer $team_id team ID
	 * @return integer
	 */
	public static function findCountByTeamId($team_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountByTeamId($team_id);
	}

}

?>