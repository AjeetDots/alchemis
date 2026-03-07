<?php

/**
 * Defines the app_domain_PostInitiativeNote class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Note.php');

/**
 * @package Alchemis
 */
class app_domain_PostInitiativeNote extends app_domain_Note
{

	/**
	* id of the post initiative note type (as per tbl_lkp_post_initiative_note_types)
	* @var integer
	*/
	protected $post_initiative_note_type_id;
	
	/**
	 * Set the parent company ID.
	 * @param integer $id
	 */
	public function setPostInitiativeId($id)
	{
		$this->setParentId($id);
		$this->markDirty();
	}

	/**
	* Return the parent company ID.
	* @return integer
	*/
	public function getPostInitiativeId()
	{
		return $this->getParentId();
	}
	
	/**
	* Set the post initiative note type.
	* @param integer $id
	*/
	public function setPostInitiativeNoteTypeId($post_initiative_note_type_id)
	{
		$this->post_initiative_note_type_id = $post_initiative_note_type_id;
		$this->markDirty();
	}
	
	/**
	* Return the post initiative note type.
	* @return integer
	*/
	public function getPostInitiativeNoteTypeId()
	{
		return $this->post_initiative_note_type_id;
	}
	

	/**
	 * Find a post initiative note by a given ID
	 * @param integer $id post initiative ID
	 * @return app_domain_PostInitiative
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * Find the notes for a given post initiative.
	 * @param integer $id
	 * @return app_mapper_PostInitiativeNoteCollection collection of app_domain_PostInitiativeNote objects
	 */
	public static function findByPostInitiativeId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeId($id);
	}

	/**
	* Find the note for a given post initiative note id.
	* @param integer $id
	* @return app_mapper_PostInitiativeNoteCollection collection of app_domain_PostInitiativeNote objects
	*/
	public static function findByPostInitiativeNoteId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeNoteId($id);
	}
	
}

?>