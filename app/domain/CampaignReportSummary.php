<?php

/**
 * Defines the app_domain_CampaignReportSummary class.
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
class app_domain_CampaignReportSummary extends app_domain_DomainObject
{
	protected $campaign_id;
	protected $subject;
	protected $note;
	protected $updated_at;
	protected $user_id;
	protected $user_name;

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
	
	/** Sets the subject of the campaign report summary
	 * @param string $subject
	 */
	function setSubject($subject)
	{
		$this->subject = $subject;
		$this->markDirty();
	}

	/** Gets the subject of the campaign report summary
	 * @return string $subject - name of the campaign report summary
	 */
	function getSubject()
	{
		return $this->subject;
	}
	
	
	/** Sets the note of the campaign report summary
	 * @param string $note
	 */
	function setNote($note)
	{
		$this->note = $note;
		$this->markDirty();
	}

	/** Gets the note of the campaign report summary
	 * @return string $note - name of the campaign report summary
	 */
	function getNote()
	{
		return $this->note;
	}

	/** Sets the date/time the campaign report summary was updated
	 * @param string $updated_at
	 */
	function setUpdatedAt($updated_at)
	{
		$this->updated_at = $updated_at;
		$this->markDirty();
	}
	
	/** Gets the date/time the campaign report summary was updated
	 * @return string $updated_at - date/time the campaign report summary was updated
	 */
	function getUpdatedAt()
	{
		return $this->updated_at;
	}
	
	/** Sets the user_id of the user who created the campaign report summary
	 * @param integer $user_id
	 */
	function setUserId($user_id)
	{
		$this->user_id = $user_id;
		$this->markDirty();
	}

	/** Gets the id of the user who created the campaign report summary
	 * @return integer $user_id - id of the user who created the campaign report summary
	 */
	function getUserId()
	{
		return $this->user_id;
	}
	
	/** Sets the name of the user who created the campaign report summary
	 * @param integer $user_name
	 */
	function setUserName($user_name)
	{
		$this->user_name = $user_name;
		$this->markDirty();
	}

	/** Gets the name of the user who created the campaign report summary
	 * @return integer $user_name - id of the user who created the campaign report summary
	 */
	function getUserName()
	{
		return $this->user_name;
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
	

	
	
	
}


?>