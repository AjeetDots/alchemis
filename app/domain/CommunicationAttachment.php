<?php

/**
 * Defines the app_domain_CommunicationAttachment class.
 *  
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/domain/Document.php');

/**
 * @package Alchemis
 */
class app_domain_CommunicationAttachment extends app_domain_DomainObject
{
	
	protected $communication_id;
	protected $document_id;
	protected $document;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
		
		if ($this->id)
		{
//			$this->document = app_domain_Document::find($this->document_id);
		}
	}

	/** Sets the communication_id of the communication attachment
	 * @param integer $communication_id
	 */
	public function setCommunicationId($communication_id)
	{
		$this->communication_id = $communication_id;
		$this->markDirty();
	}

	/** Gets the communication_id of the communication attachment
	 * @return integer $communication_id - communication_id of the communication attachment
	 */
	public function getCommunicationId()
	{
		return $this->communication_id;
	}

	/** Sets the document_id of the communication attachment
	 * @param integer $document_id
	 */
	public function setDocumentId($document_id)
	{
		$this->document_id = $document_id;
		$this->markDirty();
	}

	/** Gets the document_id of the communication attachment
	 * @return integer $document_id - id of the communication attachment
	 */
	public function getDocumentId()
	{
		return $this->document_id;
	}
	
	/** Sets the document object of the communication attachment
	 */
	public function setDocument()
	{
		$this->document = app_domain_Document::find($this->document_id);
	}

	/** Gets the document object of the communication attachment
	 * @return integer $document - document object of the communication attachment
	 */
	public function getDocument()
	{
		return $this->document;
	}
	
	
	/**
 	 * Find a communication attachment by a given id
	 * @param integer $id id of the communication attachment 
	 * @return app_mapper_CommunicationAttachmentCollection collection of app_domain_CommunicationAttachment objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	

	/**
 	 * Find all communication attachments
	 * @return app_mapper_CommunicationAttachmentCollection collection of app_domain_CommunicationAttachment objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
 	 * Find all communication attachments by communication id
 	 * @param integer $communication_id communication_id
	 * @return app_mapper_CommunicationAttachmentCollection collection of app_domain_CommunicationAttachment objects
	 */
	public static function findByCommunicationId($communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCommunicationId($communication_id);
	}
	
	/**
 	 * Get count of all nbms in a campaign
 	 * @param integer $campaign_id campaign id
	 * @return raw data - single item
	 */
	public static function findCountByCommunicationId($communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountByCommunicationId($communication_id);
	}
	
	
}


?>