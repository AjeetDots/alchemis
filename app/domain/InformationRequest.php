<?php

require_once('app/domain/DomainObject.php');

/**
 * @package alchemis
 */
class app_domain_InformationRequest extends app_domain_DomainObject
{
	private $post_initiative_id;
	private $communication_id;
	private $status_id;
	private $type_id;
	private $comm_type_id;
	private $date;
	private $reminder_date;
	private $notes;
	private $created_at;
	private $created_by;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
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
		$spec['date'] 		= array(	'alias'      => 'Information Request date',
										'type'       => 'text',
										'mandatory'  => true,
										'max_length' => 10);
		
		$spec['notes'] 	= array(		'alias'      => 'Notes',
										'type'       => 'text',
										'mandatory'  => true);
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
	 * Set the information request post_initiative_id .
	 * @param number post_initiative_id the post_initiative_id
	 */
	public function setPostInitiativeId($post_initiative_id)
	{
		$this->post_initiative_id = $post_initiative_id;
		$this->markDirty();
	}

	/**
	 * Return the information request post initiative id.
	 * @return number the post initiativeid 
	 */
	public function getPostInitiativeId()
	{
		return $this->post_initiative_id;
	}


	/**
	 * Set the information request communication id.
	 * @param string $communication_id the information request communication id
	 */
	public function setCommunicationId($communication_id)
	{
		$this->communication_id = $communication_id;
		$this->markDirty();
	}

	/**
	 * Return the information request communication_id.
	 * @return number $communication_id the information request communication id
	 */
	public function getCommunicationId()
	{
		return $this->communication_id;
	}
	
	/**
	 * Set the information request statusid.
	 * @param string $status_id the information request status id
	 */
	public function setStatusId($status_id)
	{
		$this->status_id = $status_id;
		$this->markDirty();
	}

	/**
	 * Return the information request status_id.
	 * @return number $status the information request status id
	 */
	public function getStatusId()
	{
		return $this->status_id;
	}

	/**
	 * Return the information request status.
	 * @return string $status the information request status
	 */
	public function getStatus()
	{
		return ucfirst(self::lookupStatusById($this->status_id));
	}

	/**
	 * Set the information request type id.
	 * @param string $type_id the information request type id
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}

	/**
	 * Return the information request type id.
	 * @return number $status the information request type id
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}

	
	/**
	 * Set the information request comm type id.
	 * @param string $comm_type_id the information request comm type id
	 */
	public function setCommTypeId($comm_type_id)
	{
		$this->comm_type_id = $comm_type_id;
		$this->markDirty();
	}

	/**
	 * Return the information request comm type id.
	 * @return number $comm_type_id the information request commtype id
	 */
	public function getCommTypeId()
	{
		return $this->comm_type_id;
	}
	
	/**
	 * Set the information request date
	 * @param string $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
		$this->markDirty();
	}

	/**
	 * Return the information request date.
	 * @return string $date
	 */
	public function getDate()
	{
		return $this->date;
	}
	
	/**
	 * Set the information request reminder date
	 * @param string $reminder_date
	 */
	public function setReminderDate($reminder_date)
	{
		$this->reminder_date = $reminder_date;
		$this->markDirty();
	}

	/**
	 * Return the information request reminder date.
	 * @return string $reminder_date
	 */
	public function getReminderDate()
	{
		return $this->reminder_date;
	}
	
	/**
	 * Set the notes
	 * @param string $notes
	 */
	public function setNotes($notes)
	{
		$this->notes = $notes;
		$this->markDirty();
	}

	/**
	 * Return the notes.
	 * @return integer $notes
	 */
	public function getNotes()
	{
		return $this->notes;
	}
	
	
	/**
	 * Set the date the information request was created
	 * @param string $created_at
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;
		$this->markDirty();
	}

	/**
	 * Return the date the information request was created
	 * @return string $created_at
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}
	
	/**
	 * Set the id of the user who created the information request.
	 * @param number $created_by the id of the user who created the information request 
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;
		$this->markDirty();
	}

	/**
	 * Return the id of the user who created the information request.
	 * @return number $created_by the id of the user who created the information request 
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}
	
	
	/**
	 * Find all information requests
	 * @return app_mapper_InformationRequestCollection collection of app_domain_InformationRequest objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * Find all information requests limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_InformationRequestCollection collection of app_domain_InformationRequest objects
	 */
	public static function findSet($limit = 15, $offset = 0)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findSet($limit, $offset);
	}

	/**
	 * Find a information request by a given ID
	 * @param integer $id information request ID
	 * @return app_mapper_InformationRequestCollection collection of app_domain_InformationRequest objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/** Find a information request by a given post initiative id
	 * @param integer $post_initiative_id post initiative id
	 * @return app_mapper_InformationRequestCollection collection of app_domain_InformationRequest objects
	 */
	public static function findByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeId($post_initiative_id);
	}

	/** Find a information request by a given communication id
	 * @param integer $communication_id communication id
	 * @return app_domain_InformationRequest object
	 */
	public static function findByCommunicationId($communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCommunicationId($communication_id);
	}
	
	/**
	 * 
	 * @return app_mapper_InformationRequestMapper raw array
	 */
	public static function lookupStatusById($status_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupStatusById($status_id);
	}
	
	
	/**
	 * 
	 * @return app_mapper_InformationRequestMapper raw array
	 */
	public static function getStatusAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getStatusAll();
	}
	
	
	/**
	 * 
	 * @return app_mapper_InformationRequestMapper raw array
	 */
	public static function lookupTypeById($type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupTypeById($type_id);
	}
	
	
	/**
	 * 
	 * @return app_mapper_InformationRequestMapper raw array
	 */
	public static function getTypesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getTypesAll();
	}
	
	/**
	 * 
	 * @return app_mapper_InformationRequestMapper raw array
	 */
	public static function lookupCommTypeById($comm_type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCommTypeById($comm_type_id);
	}
	
	
	/**
	 * 
	 * @return app_mapper_InformationRequestMapper raw array
	 */
	public static function getCommTypesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getCommTypesAll();
	}

	/**
	 * Find those for a given user in a given range.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public static function findByUserId($user_id, $start_datetime, $end_datetime)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByUserId($user_id, $start_datetime, $end_datetime);
	}

}

?>