<?php

/**
 * Defines the app_domain_MailerItem class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_MailerItem extends app_domain_DomainObject
{
	
	protected $mailer_id;
	protected $post_initiative_id;
	protected $despatched_date;
	protected $despatched_communication_id;
	protected $response_date;
	protected $response_communication_id;
	protected $note;
		
	function __construct($id = null)
	{
		parent::__construct($id);
	}
	
	
	/**
	 * Set the mailer id
	 * @param number $mailer_id of this mailer item - ie parent mailer id
	 */
	public function setMailerId($mailer_id)
	{
		$this->mailer_id = $mailer_id;
		$this->markDirty();
	}
	
	/**
	 * Set the post_initiative_id.
	 * @param number $post_initiative_id of the mailer item
	 */
	public function setPostInitiativeId($post_initiative_id)
	{
		$this->post_initiative_id = $post_initiative_id;
		$this->markDirty();
	}
	
	/**
	 * Set the mailer item despatched_date 
	 * @param string $despatched_date of the mailer item
	 */
	public function setDespatchedDate($despatched_date)
	{
		$this->despatched_date = $despatched_date;
		$this->markDirty();
	}
	
	/**
	 * Set the mailer item despatched_communication_id
	 * @param number $despatched_communication_id of the mailer item
	 */
	public function setDespatchedCommunicationId($despatched_communication_id)
	{
		$this->despatched_communication_id = $despatched_communication_id;
		$this->markDirty();
	}
	
	/**
	 * Set the mailer item response_date 
	 * @param string $response_date of the mailer item
	 */
	public function setResponseDate($response_date)
	{
		$this->response_date = $response_date;
		$this->markDirty();
	}
	
	/**
	 * Set the mailer item response_communication_id
	 * @param number $response_communication_id of the mailer item
	 */
	public function setResponseCommunicationId($response_communication_id)
	{
		$this->response_communication_id = $response_communication_id;
		$this->markDirty();
	}
	
	/**
	 * Set the mailer item note
	 * @param string $note of the mailer item
	 */
	public function setNote($note)
	{
		$this->note = $note;
		$this->markDirty();
	}		
		
	
	/**
	 * Get the mailer id
	 * @param number $mailer_id of this mailer item - ie parent mailer id
	 */
	public function getMailerId()
	{
		return $this->mailer_id;
	}
	
	/**
	 * Get the post_initiative_id.
	 * @return string $post_initiative_id of the mailer item
	 */
	public function getPostInitiativeId()
	{
		return $this->post_initiative_id;
	}
	
	/**
	 * Get the mailer item despatched_date
	 * @return string $despatched_date of this mailer 
	 */
	public function getDespatchedDate()
	{
		return $this->despatched_date;
	}
		
	/**
	 * Get the mailer item despatched_communication_id
	 * @return number $despatched_communication_id of this mailer 
	 */
	public function getDespatchedCommunicationId()
	{
		return $this->despatched_communication_id;
	}
	
	/**
	 * Get the mailer item response_date
	 * @return string $response_date of this mailer 
	 */
	public function getResponseDate()
	{
		return $this->response_date;
	}
	
	/**
	 * Get the mailer item response_communication_id
	 * @return number $response_communication_id of this mailer 
	 */
	public function getResponseCommunicationId()
	{
		return $this->response_communication_id;
	}
			
	/** Get the mailer item note
	 * @return string $note of the mailer
	 */
	public function getNote()
	{
		return $this->note;
	}
	
	
	/**
 	 * Find a mailer item by a given ID
	 * @param integer $id mailer item id
	 * @return app_mapper_MailerItemCollection collection of app_domain_MailerItem objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	
	
	/**
 	 * Find all mailer items
	 * @return app_mapper_MailerItemCollection collection of app_domain_MailerItem objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	
	/**
	 * Find mailer items by mailer_id 
	 * @param integer $mailer_id
	 * @return mailer item domain collection
	 */
	public static function findByMailerId($mailer_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByMailerId($mailer_id);
	}
	
	
	
		
	/** For a given mailer, find items which have not been dispatched
	 * @return raw array
	 */
	public static function findNotDespatchedByMailerId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findNotDespatchedByMailerId($id);
	}
	
	/** For a given mailer, find items which have not been dispatched
	 * See function notes in Mapper Object for further explanation
	 * @return raw array
	 */
	public static function findNotDespatchedByMailerIdForExport($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findNotDespatchedByMailerIdForExport($id);
	}
	
	
	/** For a given mailer, find items which have been dispatched
	 * @return raw array
	 */
	public static function findDespatchedByMailerId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDespatchedByMailerId($id);
	}
	
	/** For a given mailer, find items which have been dispatched
	 * See function notes in Mapper Object for further explanation
	 * @return raw array
	 */
	public static function findDespatchedByMailerIdForExport($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDespatchedByMailerIdForExport($id);
	}
	
	
	
	/** For a given mailer, find count of items 
	 * @param integer $mailer_id
	 * @return raw data - single item
	 */
	public static function countByMailerId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countByMailerId($id);
	}	
	
	
	/** For a given mailer, find count of items despatched
	 * @param integer $mailer_id
	 * @return raw data - single item
	 */
	public static function countDespatchedDateByMailerId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countDespatchedDateByMailerId($id);
	}	
	
	
	/** For a given mailer, find count of items where response date is not null
	 * @param integer $mailer_id
	 * @return raw data - single item
	 */
	public static function countResponseDateByMailerId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countResponseDateByMailerId($id);
	}	
	
	 /** Lookup id by post_initiative_id exist and mailer_id
	 * @param integer $post_initiative_id
	 * @param integer $mailer_id
	 * @return raw data - single item
	 */
	public static function lookupIdByPostInitiativeIdByMailer($post_initiative_id, $mailer_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupIdByPostInitiativeIdByMailer($post_initiative_id, $mailer_id);
	}
	
}

?>
