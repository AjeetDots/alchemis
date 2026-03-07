<?php

/**
 * Defines the app_domain_CampaignDiscipline class.
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
class app_domain_CampaignDiscipline extends app_domain_DomainObject
{
	protected $campaign_id;
	protected $discipline_id;
	protected $discipline_name;
	
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

	/** Sets the discipline_id of the campaign discipline
	 * @param integer $discipline_id
	 */
	function setDisciplineId($discipline_id)
	{
		$this->discipline_id = $discipline_id;
		$this->markDirty();
	}

	/** Gets the id of the campaign discipline
	 * @return integer $discipline_id - id of the campaign discipline
	 */
	function getDisciplineId()
	{
		return $this->discipline_id;
	}
	
	
	/** Sets the name of the campaign discipline
	 * @param string $discipline_name
	 */
	function setDisciplineName($discipline_name)
	{
		$this->discipline_name = $discipline_name;
		$this->markDirty();
	}

	/** Gets the name of the campaign discipline
	 * @return string $name - name of the campaign discipline
	 */
	function getDisciplineName()
	{
		return $this->discipline_name;
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
 	 * Find all campaign disciplines
	 * @return app_mapper_CampaignDisciplineCollection collection of app_domain_CampaignDiscipline objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
 	 * Find all campaign disciplines by campaign id
 	 * @param integer $campaign_id campaign id
	 * @return app_mapper_CampaignDisciplineCollection collection of app_domain_CampaignDiscipline objects
	 */
	public static function findByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCampaignId($campaign_id);
	}
	
	/**
 	 * Get count of all disciplines in a campaign
 	 * @param integer $campaign_id campaign id
	 * @return raw data - single item
	 */
	public static function findCountByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountByCampaignId($campaign_id);
	}
	
	/**
	 * Find the list of marketing services available for this campaign (ie not yet assigned to a campaign).
	 * @param integer $campaign_id
	 * @return array
	 */
	public static function findAvailableDisciplinesByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAvailableDisciplinesByCampaignId($campaign_id);
	}
	
	
	
	
}


?>