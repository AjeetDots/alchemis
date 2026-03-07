<?php

/**
 * Defines the app_domain_MailerItemResponse class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_MailerItemResponse extends app_domain_DomainObject
{
	
	protected $mailer_item_id;
	protected $mailer_response_id;
	protected $note;
		
	function __construct($id = null)
	{
		parent::__construct($id);
	}
	
	
	/**
	 * Set the mailer item id
	 * @param number $mailer_item_id of this mailer response item - ie parent mailer item id
	 */
	public function setMailerItemId($mailer_item_id)
	{
		$this->mailer_item_id = $mailer_item_id;
		$this->markDirty();
	}
	
	/**
	 * Set the $mailer_response_id.
	 * @param number $mailer_response_id of the mailer response item
	 */
	public function setMailerResponseId($mailer_response_id)
	{
		$this->mailer_response_id = $mailer_response_id;
		$this->markDirty();
	}
	
	/**
	 * Set the mailer response item note
	 * @param string $note of the mailer response item
	 */
	public function setNote($note)
	{
		$this->note = $note;
		$this->markDirty();
	}		
		
	
	/**
	 * Get the mailer item id
	 * @param number $mailer_item_id of this mailer response item - ie parent mailer item id
	 */
	public function getMailerItemId()
	{
		return $this->mailer_item_id;
	}
	
	/**
	 * Get the post_initiative_id.
	 * @return string $post_initiative_id of the mailer item
	 */
	public function getMailerResponseId()
	{
		return $this->mailer_response_id;
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
	
	 /** Find mailer items response by mailer id 
	 * @param integer $mailer_id
	 * @return mailer item response domain collection
	 */
	public static function findCountAndDescriptionByMailerId($mailer_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountAndDescriptionByMailerId($mailer_id);
	}
	
	/** Find mailer items responded to by mailer_id 
	 * @param integer $mailer_id
	 * @return raw array
	 */
	public static function findByMailerId($mailer_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByMailerId($mailer_id);
	}
	
	/**
	 * Find mailer items response by mailer item id 
	 * @param integer $mailer_item_id
	 * @return mailer item response domain collection
	 */
	public static function findByMailerItemId($mailer_item_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByMailerItemId($mailer_item_id);
	}
	
	/**
	 * Find mailer items response by mailer item id 
	 * @param integer $mailer_item_id
	 * @return mailer item response domain collection
	 */
	public static function findByMailerItemIdAndMailerResponseId($mailer_item_id, $mailer_response_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByMailerItemIdAndMailerResponseId($mailer_item_id, $mailer_response_id);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupMailerResponseDescription($mailer_response_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupMailerResponseDescription($mailer_response_id);
	}	
		
	/**
	 * Find possible mailer responses  
	 * @param integer $id
	 * @return raw array
	 */
	public static function findAllPossibleResponses()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAllPossibleResponses();
	}
}

?>
