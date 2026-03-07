<?php

/**
 * Defines the app_domain_CompanyNote class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Note.php');

/**
 * @package Alchemis
 */
class app_domain_CompanyNote extends app_domain_Note
{

	/**
	 * Set the parent company ID.
	 * @param integer $id
	 */
	public function setCompanyId($id)
	{
		$this->setParentId($id);
		$this->markDirty();
	}

	/**
	 * Return the parent company ID.
	 * @return integer
	 */
	public function getCompanyId()
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
	 * Find the notes for a given company.
	 * @param integer $id
	 * @return app_mapper_CompanyNoteCollection collection of app_domain_CompanyNote objects
	 */
	public static function findByCompanyId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyId($id);
	}

	/** Find the number of notes for a given company.
	 * @param integer $id
	 * @return single item
	 */
	public static function findCountByCompanyId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCountByCompanyId($id);
	}
	
}

?>