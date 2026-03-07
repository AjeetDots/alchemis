<?php

/**
 * Defines the app_domain_CampaignRegion class.
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
class app_domain_CampaignRegion extends app_domain_DomainObject
{
	/**
//	 * Field spec and validation rules.
//	 */
//	protected $spec;

	
	protected $campaign_id;
	protected $region_id;
	protected $name;
	
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

	/** Sets the region_id of the campaign region
	 * @param integer $region_id
	 */
	function setRegionId($region_id)
	{
		$this->region_id = $region_id;
		$this->markDirty();
	}

	/** Gets the user_id of the campaign region
	 * @return integer $region_id - id of the campaign region
	 */
	function getRegionId()
	{
		return $this->region_id;
	}
	
	
	/** Sets the name of the campaign NBM
	 * @param string $name
	 */
	function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/** Gets the name of the campaign NBM
	 * @return string $name - name of the campaign NBM
	 */
	function getName()
	{
		return $this->name;
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