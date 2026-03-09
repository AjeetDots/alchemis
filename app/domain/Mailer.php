<?php

/**
 * Defines the app_domain_Mailer class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Mailer extends app_domain_DomainObject
{

	protected $client_initiative_id;
	protected $name;
	protected $description;
	protected $response_group_id;
	protected $response_group_name;
	protected $type_id;
	protected $type_name;
	protected $archived;
	protected $created_at;
	protected $created_by;
	protected $created_by_name;
	protected $updated_at;

	function __construct($id = null)
	{
		parent::__construct($id);
	}


	/**
	 * Returns an array of field validation rules.
	 * @see app_base_RuleValidator
	 */
	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['client_initiative_id']	= array(	'alias'      => 'Client initiative',
													'type'       => 'integer',
													'mandatory'  => true,
													'min'		 => 1);
		$spec['name'] 					= array(	'alias'      => 'Name',
													'type'       => 'text',
													'mandatory'  => true,
													'max_length' => 255);
		$spec['description']			= array(	'alias'      => 'Description',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 1000);
		$spec['type_id'] 				= array(	'alias'      => 'Mailer Type',
													'type'       => 'integer',
													'mandatory'  => true,
													'min'		 => 1);
		$spec['response_group_id']		= array(	'alias'      => 'Mailer Response Group',
													'type'       => 'integer',
													'mandatory'  => true,
													'min'		 => 1);

		if (!is_null($field))
		{
			return $spec[$field];
		}
		else
		{
			return $spec;
		}
	}

	/**
	 * Set the client_initiative_id.
	 * @param string $client_initiative_id of the mailer
	 */
	public function setClientInitiativeId($client_initiative_id)
	{
		$this->client_initiative_id = $client_initiative_id;
		$this->markDirty();
	}


	/**
	 * Set the mailer name
	 * @param string $name of this mailer
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/**
	 * Set the mailer description (an optional field to describe the mailer more fully than in name)
	 * @param string $description of the mailer
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		$this->markDirty();
	}

	/** Set the response_group_id
	 * @param number $response_group_id of the mailer
	 */
	public function setResponseGroupId($response_group_id)
	{
		$this->response_group_id = $response_group_id;
		$this->markDirty();
	}

	/** Set the response_group_name
	 * @param number $response_group_name of the mailer
	 */
	public function setResponseGroupName($response_group_name)
	{
		$this->response_group_name = $response_group_name;
		$this->markDirty();
	}

	/**
	 * Set the mailer type id.
	 * @param number $type_id of the mailer
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}

	/**
	 * Set the mailer type name.
	 * @param string $type_name of the mailer
	 */
	public function setTypeName($type_name)
	{
		$this->type_name = $type_name;
		$this->markDirty();
	}


	 /**
     * Set the mailer archive value (0 = false, 1 = true)
     * @param string $archive - whether the mailer is archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        $this->markDirty();
    }


	/**
	 * Set the date the mailer was created
	 * @param string $created_at
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;
		$this->markDirty();
	}

	/**
	 * Set the id of the user who created the mailer.
	 * @param number $created_by the id of the user who created the mailer
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;
		$this->markDirty();
	}

	/**
	 * Set the string name of the user who created the mailer.
	 * @param string $created_by_name the name of the user who created the mailer
	 */
	public function setCreatedByName($created_by_name)
	{
		$this->created_by_name = $created_by_name;
		$this->markDirty();
	}


	/**
	 * Get the client_initiative_id.
	 * @return string $client_initiative_id of the mailer
	 */
	public function getClientInitiativeId()
	{
		return $this->client_initiative_id;
	}

	/**
	 * Get the mailer name
	 * @return string $name of this mailer
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the mailer description (an optional field to describe the mailer more fully than in name)
	 * @return string $description of this mailer
	 */
	public function getDescription()
	{
		return $this->description;
	}


	/** Get the response_group_id
	 * @return number $response_group_id of the mailer
	 */
	public function getResponseGroupId()
	{
		return $this->response_group_id;
	}


	/** Get the response group name
	 * @return string response group name of the mailer
	 */
	public function getResponseGroupName()
	{
		return $this->response_group_name;
	}

	/**
	 * Get the mailer type id.
	 * @return string $type_id of the mailer
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}

	/**
	 * Get the mailer type name.
	 * @return string $type_name of the mailer
	 */
	public function getTypeName()
	{
		return $this->type_name;
	}

	 /**
     * Get the mailer archive value (0 = false, 1 = true).
     * @return string $archive = whether the mailer is archived
     */
    public function getArchived()
    {
        return $this->archived;
    }

 	/** Return the date the mailer was created
	 * @return string $created_at
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

	/**
	 * Return the id of the user who created the mailer.
	 * @return number $created_by the id of the user who created the mailer
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}

	/**
	 * Return the name of the user who created the mailer.
	 * @return number $created_by the id of the user who created the mailer
	 */
	public function getCreatedByName()
	{
		return $this->created_by_name;
	}

	/**
 	 * Find a mailer by a given ID
	 * @param integer $id mailer ID
	 * @return app_mapper_MailerCollection collection of app_domain_Mailer objects
	 */
	public static function find($id)
	{

		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	public static function findByName($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByName($name);
	}

	/**
 	 * Find all mailers
	 * @return app_mapper_MailerCollection collection of app_domain_Mailer objects
	 */
	public static function findAll()
	{

		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

    /**
     * Find archived mailers
     * @return app_mapper_MailerCollection collection of app_domain_Mailer objects
     */
    public static function findArchived()
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findArchived();
    }

        /**
     * Find current mailers
     * @return app_mapper_MailerCollection collection of app_domain_Mailer objects
     */
    public static function findCurrent()
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findCurrent();
    }


	/**
	 * Find possible response by mailer id
	 * @param integer $id
	 * @return raw array
	 */
	public static function findPossibleResponsesByMailerId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPossibleResponsesByMailerId($id);
	}

	/** Find all mailer ids and names
	 * @return raw array
	 */
	public static function findAllMailerIdsAndNames()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAllMailerIdsAndNames();
	}

	/** Lookup mailer types - eg post, email etc
	 * @return raw array
	 */
	public static function lookupTypes()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupTypes();
	}


	/** Lookup mailer reponse groups
	 * @return raw array
	 */
	public static function lookupResponseGroups()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupResponseGroups();
	}


	/** Find available filters which can be used to add mailer recipients
	 * @return raw array
	 */
	public static function findAvailableFiltersByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAvailableFiltersByUserId($user_id);
	}


}

?>
