<?php

/**
 * Defines the app_domain_CampaignTarget class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_CampaignTarget extends app_domain_DomainObject
{
	/**
//	 * Field spec and validation rules.
//	 */
//	protected $spec;

	
	protected $campaign_id;
	protected $year_month;
	
	/**
	 * Target number of calls.
	 * @var integer
	 */
	protected $calls;

	/**
	 * Target number of efective calls.
	 * @var integer
	 */
	protected $effectives;

	protected $meetings_set;
	protected $meetings_attended;
	protected $opportunities;
	protected $wins;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/** Sets the campaign_id of the campaign target
	 * @param integer $campaign_id
	 */
	function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		$this->markDirty();
	}

	/** Gets the campaign_id of the campaign target
	 * @return integer - $campaign_id - campaign_id of the campaign target
	 */
	function getCampaignId()
	{
		return $this->campaign_id;
	}

	/** Sets the year_month of the campaign target
	 * @param string $year_month
	 */
	function setYearMonth($year_month)
	{
		$this->year_month = $year_month;
		$this->markDirty();
	}

	/** Gets the the year_month of the campaign target
	 * @return string $year_month - year_month of the campaign target
	 */
	function getYearMonth()
	{
		return $this->year_month;
	}

	/**
	 * Sets the number of calls set in a year_month period for a client.
	 * @param integer $calls
	 */
	function setCalls($calls)
	{
		$this->calls = $calls;
		$this->markDirty();
	}

	/**
	 * Gets the number of calls set in a year_month period for a client.
	 * @param integer
	 */
	function getCalls()
	{
		return $this->calls;
	}

	/**
	 * Sets the number of effective calls set in a year_month period for a client.
	 * @param integer $calls
	 */
	function setEffectives($effectives)
	{
		$this->effectives = $effectives;
		$this->markDirty();
	}

	/**
	 * Gets the number of effective calls set in a year_month period for a client.
	 * @param integer
	 */
	function getEffectives()
	{
		return $this->effectives;
	}

	/**
	 * Sets the number of meetings set in a year_month period for a client.
	 * @param integer $meetings_set
	 */
	function setMeetingsSet($meetings_set)
	{
		$this->meetings_set = $meetings_set;
		$this->markDirty();
	}
		
	/**
	 * Gets the number of meetings set in a year_month period for a client.
	 * @return integer - number of meetings set in a year_month period for a client.
	 */
	function getMeetingsSet()
	{
		return $this->meetings_set;
	} 
		
	/**
	 * Sets the number of meetings attended in a year_month period for a client.
	 * @param integer $meetings_attended
	 */
	function setMeetingsAttended($meetings_attended)
	{
		$this->meetings_attended = $meetings_attended;
		$this->markDirty();
	}
		
	/**
	 * Gets the number of meetings attended in a year_month period for a client.
	 * @return integer - number of meetings attended in a year_month period for a client.
	 */
	function getMeetingsAttended()
	{
		return $this->meetings_attended;
	} 
			
	/**
	 * Sets the number of opportunities in a year_month period for a client.
	 * @param integer $opportunities
	 */
	function setOpportunities($opportunities)
	{
		$this->opportunities = $opportunities;
		$this->markDirty();
	}
		
	/**
	 * Gets the number of opportunities in a year_month period for a client.
	 * @return integer - number of opportunities in a year_month period for a client.
	 */
	function getOpportunities()
	{
		return $this->opportunities;
	} 
		
	/**
	 * Sets the number of wins in a year_month period for a client.
	 * @param integer $wins
	 */
	function setWins($wins)
	{
		$this->wins = $wins;
		$this->markDirty();
	}
		
	/**
	 * Gets the number of wins in a year_month period for a client.
	 * @return integer - number of wins in a year_month period for a client.
	 */
	function getWins()
	{
		return $this->wins;
	} 
	
	/**
 	 * Find a campaign target by a given id
	 * @param integer $id campaign target id
	 * @return app_mapper_CampaignTargetCollection collection of app_domain_CampaignTarget objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	
	

	/**
 	 * Find all campaign targets
	 * @return app_mapper_CampaignTargetCollection collection of app_domain_CampaignTarget objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
 	 * Find all campaign targets by campaign ID
 	 * @param integer $id campaign ID
	 * @return app_mapper_CampaignTargetCollection collection of app_domain_CampaignTarget objects
	 */
	public static function findByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCampaignId($campaign_id);
	}

}

?>