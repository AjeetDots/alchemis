<?php

/**
 * Defines the app_domain_CampaignSector class.
 *  
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_CampaignSector extends app_domain_DomainObject
{
	protected $campaign_id;
	protected $sector_id;
	protected $sector_name;
	protected $weighting;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
		
		if ($this->id)
		{
			
		}
	}

	/** Sets the campaign_id of the campaign NBM
	 * @param integer $campaign_id
	 */
	function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		$this->markDirty();
	}

	/** Gets the campaign_id of the campaign NBM
	 * @return integer $campaign_id - campaign_id of the campaign NBM
	 */
	function getCampaignId()
	{
		return $this->campaign_id;
	}

	/** Sets the sector_id of the campaign sector
	 * @param integer $sector_id
	 */
	function setSectorId($sector_id)
	{
		$this->sector_id = $sector_id;
		$this->markDirty();
	}

	/** Gets the id of the campaign sector
	 * @return integer $sector_id - id of the campaign sector
	 */
	function getSectorId()
	{
		return $this->sector_id;
	}
	
	
	/** Sets the name of the campaign sector
	 * @param string $sector_name
	 */
	function setSectorName($sector_name)
	{
		$this->sector_name = $sector_name;
		$this->markDirty();
	}

	/** Gets the name of the campaign sector
	 * @return string $name - name of the campaign sector
	 */
	function getSectorName()
	{
		return $this->sector_name;
	}
	
	
	/** Sets the weighting of the campaign/sector record
	 * @param integer $weighting
	 */
	function setWeighting($weighting)
	{
		$this->weighting = $weighting;
		$this->markDirty();
	}

	/** Gets the weighting of the campaign/sector record
	 * @return integer $weighting
	 */
	function getWeighting()
	{
		return $this->weighting;
	}
	
	/**
 	 * Find a campaign nbm by a given id
	 * @param integer $id campaign nbm id
	 * @return app_mapper_CampaignNbmCollection collection of app_domain_CampaignNbm objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	
	

	/**
 	 * Find all campaign NBMs
	 * @return app_mapper_CampaignNbmCollection collection of app_domain_CampaignNbm objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
 	 * Find all campaign NBMs by campaign id
 	 * @param integer $campaign_id campaign id
	 * @return app_mapper_CampaignNbmCollection collection of app_domain_CampaignNbm objects
	 */
	public static function findByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCampaignId($campaign_id);
	}
	
	/**
 	 * Find all campaign NBMs by campaign id ordered by weighting (desc)
 	 * @param integer $campaign_id campaign id
	 * @return app_mapper_CampaignNbmCollection collection of app_domain_CampaignNbm objects
	 */
	public static function findByCampaignIdOrderByWeighting($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCampaignIdOrderByWeighting($campaign_id);
	}
	
	
	
	/**
 	 * Get count of all nbms in a campaign
 	 * @param integer $campaign_id campaign id
	 * @return raw data - single item
	 */
	public static function findCountByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountByCampaignId($campaign_id);
	}
	
	
}


?>