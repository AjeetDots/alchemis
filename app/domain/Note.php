<?php

/**
 * Defines the app_domain_CompanyNote class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/domain/Company.php');
require_once('app/mapper/CompanyMapper.php');

/**
 * @package Alchemis
 */
abstract class app_domain_Note extends app_domain_DomainObject
{
	/**
	 * ID of parent (e.g. company, post, post initiative).
	 * @var integer
	 */
	protected $parent_id;

	/**
	 * Time note created
	 * @var string
	 */
	protected $created_at;

	/**
	 * ID of user who created note.
	 * @var integer
	 */
	protected $created_by;

	/**
	 * The note text
	 * @var string
	 */
	protected $note;
	
	/**
	* The summary string (optional)
	* @var string
	*/
	protected $summary;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Set the ID of the parent.
	 * @param integer $parent_id
	 */
	public function setParentId($parent_id)
	{
		$this->parent_id = $parent_id;
		$this->markDirty();
	}

	/**
	 * Return the ID of the parent.
	 * @return integer
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * Set the time when created.
	 * @param string $timestamp
	 */
	public function setCreatedAt($timestamp)
	{
		$this->created_at = $timestamp;
		$this->markDirty();
	}

	/**
	 * Return the time when created.
	 * @return string
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

	/**
	 * Set the ID of the user who created the note.
	 * @param integer $user_id
	 */
	public function setCreatedBy($user_id)
	{
		$this->created_by = $user_id;
		$this->markDirty();
	}

	/**
	 * Return the ID of the user who created the note.
	 * @return integer
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}

	/**
	 * Set the note text.
	 * @param string $note
	 */
	public function setNote($note)
	{
		$this->note = $note;
		$this->markDirty();
	}

	/**
	 * Return the note text.
	 * @return string
	 */
	public function getNote()
	{
		return $this->note;
	}
	
	/**
	* Set the summary text.
	* @param string $summary
	*/
	public function setSummary($summary)
	{
		$this->summary = $summary;
		$this->markDirty();
	}
	
	/**
	 * Return the summary text.
	 * @return string
	 */
	public function getSummary()
	{
		return $this->summary;
	}

	
	
	/**
	 * Find the notes for a given company.
	 * @param integer $company_id
	 * @return app_mapper_CompanyNoteCollection collection of app_domain_CompanyNote objects
	 */
	public static function findByCompanyId($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyId($company_id);
	}

}

?>