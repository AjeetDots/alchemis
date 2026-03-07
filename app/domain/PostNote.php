<?php

/**
 * Defines the app_domain_PostNote class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Note.php');

/**
 * @package Alchemis
 */
class app_domain_PostNote extends app_domain_Note
{

	/**
	 * Set the parent post ID.
	 * @param integer $id
	 */
	public function setPostId($id)
	{
		$this->setParentId($id);
		$this->markDirty();
	}

	/**
	 * Return the parent post ID.
	 * @return integer
	 */
	public function getPostId()
	{
		return $this->getParentId();
	}

	
	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_NoteMapper
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * Find the notes for a given post.
	 * @param integer $id
	 * @return app_mapper_PostNoteCollection collection of app_domain_PostNote objects
	 */
	public static function findByPostId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostId($id);
	}

	/** Find the number of notes for a given post.
	 * @param integer $id
	 * @return single item
	 */
	public static function findCountByPostId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountByPostId($id);
	}
}

?>