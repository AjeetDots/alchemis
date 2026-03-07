<?php

/**
 * Defines the app_domain_PostDisciplineReviewDate class.
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
class app_domain_PostDisciplineReviewDate extends app_domain_DomainObject
{
	protected $post_id;
	protected $discipline_id;
	protected $year_month;
	protected $communication_id;
	protected $last_updated_at;
	protected $last_updated_by;
	
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
	
	 /** Set the post id
	 * @param string $post_id of this decision maker
	 */
	public function setPostId($post_id)
	{
		$this->post_id = $post_id;
		$this->markDirty();
	}

	/**
	 * Get the post id
	 * @return integer $post_id of this decision maker
	 */
	public function getPostId()
	{
		return $this->post_id;
	}

	/**
	 * Set the discipline id
	 * @param string $discipline_id of this decision maker
	 */
	public function setDisciplineId($discipline_id)
	{
		$this->discipline_id = $discipline_id;
		$this->markDirty();
	}

	/**
	 * Get the discipline id
	 * @return integer $discipline_id of this decision maker
	 */
	public function getDisciplineId()
	{
		return $this->discipline_id;
	}

	/**
	 * Set the year month.
	 * @param string $type_id of the review date
	 */
	public function setYearMonth($year_month)
	{
		$this->year_month = $year_month;
		$this->markDirty();
	}

	/**
	 * Return the year month
	 * @return number $year_month of the review date
	 */
	public function getYearMonth()
	{
		return $this->year_month;
	}
	
	/**
	 * Set the communication id.
	 * @param string $communication_id of the review date
	 */
	public function setCommunicationId($communication_id)
	{
		$this->communication_id = $communication_id;
		$this->markDirty();
	}

	/**
	 * Return the communication_id.
	 * @return number $communication_id of the review date
	 */
	public function getCommunicationId()
	{
		return $this->communication_id;
	}
	
		
	/**
	 * Set the date the record was last updated
	 * @param string $last_updated_at
	 */
	public function setLastUpdatedAt($last_updated_at)
	{
		$this->last_updated_at = $last_updated_at;
		$this->markDirty();
	}

	/**
	 * Return the date the record was last updated
	 * @return string $last_updated_at
	 */
	public function getLastUpdatedAt()
	{
		return $this->last_updated_at;
	}
	
	/**
	 * Set the id of the user who last updated the record.
	 * @param number $last_updated_by the id of the user who last updated the record
	 */
	public function setLastUpdatedBy($last_updated_by)
	{
		$this->last_updated_by = $last_updated_by;
		$this->markDirty();
	}

	/**
	 * Return the id of the user who last updated the record.
	 * @return number $created_by the id of the user who last updated the record 
	 */
	public function getLastUpdatedBy()
	{
		return $this->last_updated_by;
	}
	
	/**
 	 * Find an agency user for a given id
	 * @param integer $id agency user record id
	 * @return app_mapper_PostDecisionMakerCollection collection of app_domain_PostDecisionMaker objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	
	

	/**
 	 * Find all post agency users
	 * @return app_mapper_PostDisciplineReviewDateCollection collection of app_domain_PostDisciplineReviewDate objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	
	/**
 	 * Find post agency user record by post id and discipline id
 	 * @param integer $post_id
 	 * @param integer $discipline_id
	 * @return app_domain_PostDisciplineReviewDate object
	 */
	public static function findByPostIdAndDisciplineId($post_id, $discipline_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostIdAndDisciplineId($post_id, $discipline_id);
	}
	
	/** Sets the communication_id field to null for a given communication_id
	 * @param integer $communication_id
	 */
	public static function setCommunicationIdNullByCommunicationId($communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->setCommunicationIdNullByCommunicationId($communication_id);
	}	
	
}


?>