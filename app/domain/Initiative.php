<?php

/**
 * Defines the app_domain_Initiative class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Initiative extends app_domain_DomainObject
{
	/**
	 * ID of parent campaign.
	 * @var integer
	 */
	private $campaign_id;
	
	/**
	 * Initiative name.
	 * @var string
	 */
	private $initiative_id;

	function __construct($id = null)
	{
		parent::__construct($id);
	}
	
	/**
	 * Set the ID of the parent campaign.
	 * @param integer $campaign_id 
	 */
	public function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		$this->markDirty();
	}
	
	/**
	 * Get the ID of the parent campaign.
	 * @return integer 
	 */
	public function getCampaignId()
	{
		return $this->campaign_id;
	}
	
	/**
	 * Set the initiative name.
	 * @param string $name 
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}
	
	/**
	 * Get the initiative name.
	 * @return string 
	 */
	public function getName()
	{
		return $this->name;
	}

	/** Get the parent client name.
	 * @return string 
	 */
	public function getClientName()
	{
		return app_domain_Client::find($this->campaign_id)->getName();
	}


	/**
 	 * Find a post initiative by a given ID
	 * @param integer $id post initiative ID
	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	

//	/**
// 	 * Find all post initiatives.
//	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
//	 */
//	public static function findAll()
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->findAll();
//	}	

	/**
	 * Find all post initiatives associated with a given post
	 * @param integer $post_id
	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
	 */
	public static function findByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostId($post_id);
	}

	/**
	 * Find by post id and initiative_id
	 * @param integer $post_id
	 * @param integer $initiative_id  
	 * @return post initiative domain object
	 */
	public static function findByPostAndInitiative($post_id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostAndInitiative($post_id, $initiative_id);
	}
	
	/**
	 * Find communication note information by post_initiative_id
	 * @param integer $post_id
	 * @param integer $initiative_id  
	 * @return raw associative array
	 */
	public static function findCommunicationNotes($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCommunicationNotes($id);
	}

	/**
	 * Find tags by post_initiative_id and category id
	 * @param integer $id
	 * @param integer $category_id
	 * @return post initiative collection
	 */
	public static function findTagsByPostInitiativeIdAndCategoryId($id, $category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findTagsByPostInitiativeIdAndCategoryId($id, $category_id);
	}

	/**
	 * Find call backs due for a given user in a given date range.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public static function findCallBacksByUserId($user_id, $start_datetime, $end_datetime)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCallBacksByUserId($user_id, $start_datetime, $end_datetime);
	}

	
	/**
	 * Find initiative name in format `client: initiative`
	 * @param integer $initiative_id
	 * @return string
	 */
	public static function findClientInitiativeNameById($initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findClientInitiativeNameById($initiative_id);
	}
}

?>
