<?php

/**
 * Defines the app_domain_CampaignCompanyDoNotCall class.
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
class app_domain_CampaignCompanyDoNotCall extends app_domain_DomainObject
{
	/**
//	 * Field spec and validation rules.
//	 */
//	protected $spec;

	
	protected $campaign_id;
	protected $company_id;
	protected $company_name;
	protected $created_at;
	protected $created_by;
	protected $created_by_name;
	
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

	/** Sets the campaign_id of the company do not call entry
	 * @param integer $campaign_id
	 */
	function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		$this->markDirty();
	}

	/** Gets the campaign_id of the company do not call entry
	 * @return integer $campaign_id - campaign_id of the company do not call entry
	 */
	function getCampaignId()
	{
		return $this->campaign_id;
	}

	/** Sets the company_id of the company do not call entry
	 * @param integer $company_id
	 */
	function setCompanyId($company_id)
	{
		$this->company_id = $company_id;
		$this->markDirty();
	}

	/** Gets the company_id of the company do not call entry
	 * @return integer $company_id - company_id of the company do not call entry
	 */
	function getCompanyId()
	{
		return $this->company_id;
	}
	
	
	/** Sets the company name of the company do not call entry
	 * @param integer $company_name
	 */
	function setCompanyName($company_name)
	{
		$this->company_name = $company_name;
		$this->markDirty();
	}

	/** Gets the company name of the company do not call entry
	 * @return integer $company_name - company name of the company do not call entry
	 */
	function getCompanyName()
	{
		return $this->company_name;
	}
	
	
	/**
	 * Set the date the company do not call entry was created
	 * @param string $created_at
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;
		$this->markDirty();
	}

	/**
	 * Return the date the company do not call entry was created
	 * @return string $created_at
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}
	
	/**
	 * Set the id of the user who created the company do not call entry.
	 * @param number $created_by the id of the user who created the company do not call entry 
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;
		$this->markDirty();
	}

	/**
	 * Return the id of the user who created the company do not call entry.
	 * @return number $created_by the id of the user who created the company do not call entry 
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}

 	/** Set the string name of the user who created the company do not call entry.
	 * @param string $created_by_name the name of the user who created the company do not call entry 
	 */
	public function setCreatedByName($created_by_name)
	{
		$this->created_by_name = $created_by_name;
		$this->markDirty();
	}
	
		
	/**
	 * Return the name of the user who created the company do not call entry.
	 * @return number $created_by the id of the user who created the company do not call entry 
	 */
	public function getCreatedByName()
	{
		return $this->created_by_name;
	}
	
	/**
 	 * Find a campaign company do not call entry by a given id
	 * @param integer $id campaign company do not call entry id
	 * @return domain object
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	
	

	/**
 	 * Find all campaign company do not call entries
	 * @return app_mapper_CampaignCompanyDoNotCallCollection collection of app_domain_CampaignCompanyDoNotCall objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
 	 * Find all campaign company do not call entries by campaign id
 	 * @param integer $campaign_id campaign id
	 * @return app_mapper_CampaignCompanyDoNotCallCollection collection of app_domain_CampaignCompanyDoNotCall objects
	 */
	public static function findByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCampaignId($campaign_id);
	}

	/**
 	 * Find all campaign company do not call entries by client id
 	 * @param integer $client_id client id
	 * @return app_mapper_CampaignCompanyDoNotCallCollection collection of app_domain_CampaignCompanyDoNotCall objects
	 */
	public static function findByClientId($client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByClientId($client_id);
	}
	
	
	
	
	/**
 	 * Get count of all companies in a campaign do not call list
 	 * @param integer $campaign_id campaign id
	 * @return raw data - single item
	 */
	public static function findCountByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountByCampaignId($campaign_id);
	}
	
	/** lookup if a company is in a campaign do not call list
	 * @param integer $campaign_id
	 * @param integer $company_id
	 * @return boolean
	 */
	public static function isCompanyDoNotCall($campaign_id, $company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->isCompanyDoNotCall($campaign_id, $company_id);
	}

}


?>