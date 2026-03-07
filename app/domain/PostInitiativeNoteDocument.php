<?php

/**
 * Defines the app_domain_PostInitiativeNoteDocument class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2012 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_PostInitiativeNoteDocument extends app_domain_DomainObject
{
	protected $post_initiative_note_id;
	protected $document_id;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/** Sets the post_initiative_note_id
	 * @param integer $post_initiative_note_id
	 */
	function setPostInitiativeNoteId($post_initiative_note_id)
	{
		$this->post_initiative_note_id = $post_initiative_note_id;
		$this->markDirty();
	}

	/** Gets the post_initiative_note_id of the object
	 * @return integer - $post_initiative_note_id - post_initiative_note_id of the object
	 */
	function getPostInitiativeNoteId()
	{
		return $this->post_initiative_note_id;
	}

	/** Sets the document_id of the object
	 * @param integer $document_id
	 */
	function setDocumentId($document_id)
	{
		$this->document_id = $document_id;
		$this->markDirty();
	}

	/** Gets the the document_id of the object
	 * @return string $document_id - document id of the object
	 */
	function getDocumentId()
	{
		return $this->document_id;
	}
	
	/**
 	 * Find a post initiative note document by a given id
	 * @param integer $id post initiative note document id
	 * @return app_mapper_PostInitiativeNoteDocumentCollection collection of app_domain_PostInitiativeNoteDocument objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	

	/**
 	 * Find all post initiative note document by post initiative note id
 	 * @param integer $post_initiative_note_id post initiative note id ID
	 * @return array collection of app_domain_PostInitiativeNoteDocument objects
	 */
	public static function findByPostInitiativeNoteId($post_initiative_note_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeNoteId($post_initiative_note_id);
	}

}

?>