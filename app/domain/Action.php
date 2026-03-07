<?php

/**
 * Defines the app_domain_Action class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Action extends app_domain_DomainObject
{
	protected $subject;
	protected $notes;
	protected $due_date;
	protected $reminder_date;
	protected $completed_date;
	protected $user_id;
	protected $actioned_by_client = true;
	protected $post_initiative_id;
	protected $meeting_id;
	protected $information_request_id;
	protected $communication_id;
	protected $type_id;
	protected $communication_type_id;
	protected $communication_type;
	
	// NOTE: need both of the following as resource_ids is used to return the resource items that
	// should be highlighted in an edit form
	protected $resource_ids; //array of resource_ids associated with the action 
	protected $resources; //array of resources associated with the action 

	/**
	 * Whether the action has been completed.
	 * @var boolean
	 */
	protected $completed;

	/**
	 * @param integer $id
	 * @param string $name 
	 */
	public function __construct($id = null, $name = null)
	{
		parent::__construct($id);
	}

	/**
	 * Returns an array of field validation rules.
	 * @param string $field optional field name
	 * @return spec
	 * @see app_base_RuleValidator
	 */
	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['subject_mandatory']  = array(
			'alias'      => 'Subject',
			'type'       => 'text',
		    'mandatory'  => true,
		    'max_length' => 100
		);
		
		$spec['subject']  = array(
			'alias'      => 'Subject',
			'type'       => 'text',
			'mandatory'  => false,
			'max_length' => 100
		);
		
		$spec['notes_mandatory']    = array(
			'alias'      => 'Notes',
			'type'       => 'text',
		    'mandatory'  => true
		);
		
		$spec['notes']    = array(
			'alias'      => 'Notes',
            'type'       => 'text',
            'mandatory'  => false
		);

		$spec['due_date_mandatory'] = array(
			'alias'      => 'Due Date',
			'type'       => 'datetime',
			'mandatory'  => true
		);
		
		$spec['due_date'] = array(
			'alias'      => 'Due Date',
            'type'       => 'datetime',
            'mandatory'  => false
		);

		$spec['reminder_date_mandatory'] = array(
			'alias'      => 'Reminder Date',
			'type'       => 'datetime',
			'mandatory'  => true
		);
		
		$spec['reminder_date'] = array(
			'alias'      => 'Reminder Date',
			'type'       => 'datetime',
			'mandatory'  => false
		);
		
		// post initiative action specs
		$spec['type_id']  = array(
			'alias'      => 'Action type',
		    'type'       => 'integer',
		    'mandatory'  => true
		);
		
		$spec['communication_type_id_mandatory'] = array(
			'alias'      => 'Communication type',
            'type'       => 'integer',
            'mandatory'  => true
		);

		$spec['communication_type_id'] = array(
			'alias'      => 'Communication type',
		    'type'       => 'integer',
		    'mandatory'  => false
		);
		
		$spec['resource_type_id'] = array('alias'      => 'Resource type',
                                  'type'       => 'integer',
                                  'mandatory'  => true);

		$spec['actioned_by_client'] = array('alias'      => 'Actioned by client',
                                       'type'       => 'boolean',
                                       'mandatory'  => false);
                                       
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
	 * Returns whether the object exists in the database.
	 * @return boolean
	 */
	public static function existsInDb($id)
	{
		return self::lookupExistsInDb($id);
	}
	
	
	/**
	 * Set the subject.
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = trim($subject);
		$this->markDirty();
	}

	/**
	 * Return the subject.
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Set the notes.
	 * @param string $notes
	 */
	public function setNotes($notes)
	{
		$this->notes = $notes;
		$this->markDirty();
	}
	
	/**
	 * Return the notes.
	 * @return string
	 */
	public function getNotes()
	{
		if (is_null($this->notes) || strlen(trim($this->notes)) == 0)
		{
			$this->notes = ' ';
		}
		return $this->notes;
	}

	/**
	 * Set the due date.
	 * @param string $due_date the contact's forename
	 */
	public function setDueDate($due_date)
	{
		$this->due_date = $due_date;
		$this->markDirty();
	}
	
	/**
	 * Return the due date.
	 * @return string
	 */
	public function getDueDate()
	{
		return $this->due_date;
	}

	/**
	 * Return the due date.
	 * @return string
	 */
	public function isOverdue()
	{
		return ($this->due_date < date('Y-m-d H:i:s'));
	}

	/**
	 * Set the reminder date.
	 * @param string $reminder_date
	 */
	public function setReminderDate($reminder_date)
	{
		$this->reminder_date = $reminder_date;
		$this->markDirty();
	}

	/**
	 * Return the reminder date.
	 * @return string
	 */
	public function getReminderDate()
	{
		return $this->reminder_date;
	}

	/**
	 * Set the date the action has been completed.
	 * @param boolean $completed_date
	 */
	public function setCompletedDate($completed_date)
	{
		$this->completed_date = $completed_date;
		$this->markDirty();
	}

	/**
	 * Returns the comleted date for the action.
	 * @return boolean
	 */
	public function getCompletedDate()
	{
		return $this->completed_date;
	}
	
	/**
	 * Returns whether the the action is completed.
	 */
	public function getIsCompleted()
	{
		return (!is_null($this->completed_date));
	}
	
	/**
	 * Alias for getIsCompleted()
	 * @see
	 */
	public function isCompleted()
	{
		return $this->getIsCompleted();
	}

	/**
	 * Set the ID of the owner user.
	 * @param integer $user_id
	 */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;
		$this->markDirty();
	}
	
	/**
	 * Return the ID of the owner user.
	 * @return integer
	 */
	public function getUserId()
	{
		return $this->user_id;
	}
	
	/**
	 * Return the owner user.
	 * @return app_domain_User
	 */
	public function getUser()
	{
		require_once('app/domain/RbacUser.php');
		return app_domain_RbacUser::find($this->user_id);
	}

	/**
	 * Set whether the action is to actioned by the client.
	 * @param boolean $actioned_by_client
	 */
	public function setActionedByClient($actioned_by_client)
	{
		$this->actioned_by_client = $actioned_by_client;
		$this->markDirty();
	}
	
	/**
	 * Get whether the action is to actioned by the client.
	 * @return boolean
	 */
	public function getActionedByClient()
	{
		return $this->actioned_by_client;
	}

	/**
	 * Set the $post_initiative_id of the action.
	 * @param integer $post_initiative_id
	 */
	public function setPostInitiativeId($post_initiative_id)
	{
		$this->post_initiative_id = $post_initiative_id;
		$this->markDirty();
	}
	
	/**
	 * Return the $post_initiative_id of the client.
	 * @return integer
	 */
	public function getPostInitiativeId()
	{
		return $this->post_initiative_id;
	}
	
	/**
	 * Return the client.
	 * @return app_domain_Client
	 */
	public function getClient()
	{
		require_once('app/domain/Client.php');
		return app_domain_Client::findByPostInitiativeId($this->post_initiative_id);
	}

	/**
	 * Set the ID of the meeting.
	 * @param integer $meeting_id
	 */
	public function setMeetingId($meeting_id)
	{
		$this->meeting_id = $meeting_id;
		$this->markDirty();
	}
	
	/**
	 * Return the ID of the meeting.
	 * @return integer
	 */
	public function getMeetingId()
	{
		return $this->meeting_id;
	}

	/**
	 * Set the ID of the information request.
	 * @param integer $information_request_id
	 */
	public function setInformationRequestId($information_request_id)
	{
		$this->information_request_id = $information_request_id;
		$this->markDirty();
	}
	
	/**
	 * Return the ID of the information request.
	 * @return integer
	 */
	public function getInformationRequestId()
	{
		return $this->information_request_id;
	}

 	/** Set the ID of the originating communcation.
	 * @param integer $communication_id
	 */
	public function setCommunicationId($communication_id)
	{
		$this->communication_id = $communication_id;
		$this->markDirty();
	}
	
	/**
	 * Return the ID of the originating communication_id.
	 * @return integer
	 */
	public function getCommunicationId()
	{
		return $this->communication_id;
	}
	
	/**
	 * Set the action type ID.
	 * @param integer $type_id
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}
	
	/**
	 * Get the action type ID.
	 * @return integer
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}
	
	/**
	 * Get the action type.
	 * @return integer
	 */
	public function getTypeName()
	{
		return self::findActionTypeById($this->type_id);
	}

	/**
	 * Set the action communication type ID.
	 * @param integer $communication_type_id
	 */
	public function setCommunicationTypeId($communication_type_id)
	{
		$this->communication_type_id = $communication_type_id;
		$this->markDirty();
	}
	
	/**
	 * Get the action communication type ID.
	 * @return integer
	 */
	public function getCommunicationTypeId()
	{
		return $this->communication_type_id;
	}

 	/** Set the action communication type.
	 * @param integer $communication_type
	 */
	public function setCommunicationType($communication_type)
	{
		$this->communication_type = $communication_type;
		$this->markDirty();
	}
	
 	/** Get the action communication type.
	 * @return string
	 */
	public function getCommunicationType()
	{
		return $this->communication_type;
	}
	
	/**
	 * Set the action resource ids.
	 * @param array $resource_ids
	 */
	public function setResourceIds($resource_ids)
	{
		$this->resource_ids = $resource_ids;
		$this->markDirty();
	}
	
	/**
	 * Get the action resources.
	 * @return array
	 */
	public function getResourceIds()
	{
		return $this->resource_ids;
	}
	
	
	/**
	 * Set the action resources.
	 * @param array $resources
	 */
	public function setResources($resources)
	{
		$this->resources = $resources;
		$this->markDirty();
	}
	
	/**
	 * Get the action resources.
	 * @return array
	 */
	public function getResources()
	{
		return $this->resources;
	}
	
	/**
	 * 
	 * @return app_mapper_ContactMapper
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_VenueMapper
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	public static function lookupExistsInDb($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupExistsInDb($id);
	}
	
	/**
 	 * Find the actions owned by a given user.
	 * @param integer $user_id user ID
	 * @param integer $limit
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findByUserId($user_id, $limit = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByUserId($user_id, $limit);
	}
	
	/**
 	 * Find the actions owned by a given user.
	 * @param integer $user_id user ID
	 * @param integer $limit
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findCurrentByUserId($user_id, $limit = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentByUserId($user_id, $limit);
	}
	
	/**
 	 * Find the actions associated with a given client.
	 * @param integer $client_id client ID
	 * @param integer $limit
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findByClientId($client_id, $limit = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByClientId($client_id, $limit);
	}

 	/** Find the actions associated with a given post initiative id.
	 * @param integer $post_initiative_id post initiative id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeId($post_initiative_id);
	}
	
	/** Find the current actions (ie not completed) associated with a given post initiative id.
	 * @param integer $post_initiative_id post initiative id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findCurrentByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentByPostInitiativeId($post_initiative_id);
	}
	
	/** Find the actions associated with a given post initiative id for a particular action type id.
	 * @param integer $post_initiative_id post initiative id
	 * @param integer $type_id type id (from tbl_lkp_action_types)
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeIdAndTypeId($post_initiative_id, $type_id);
	}
	
	
	/** Find the actions associated with a given post initiative id for a particular action type id.
	 * @param integer $post_initiative_id post initiative id
	 * @param integer $type_id type id (from tbl_lkp_action_types)
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findCurrentByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentByPostInitiativeIdAndTypeId($post_initiative_id, $type_id);
	}
	
	
	
	/** Find the actions associated with a given post initiative id for a particular set of type ids.
	 * @param integer $post_initiative_id post initiative id
	 * @param array $type_ids type ids (from tbl_lkp_action_types) - array
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids);
	}
	
	/** Find the current actions associated with a given post initiative id for a particular set of type ids.
	 * @param integer $post_initiative_id post initiative id
	 * @param array $type_ids type ids (from tbl_lkp_action_types) - array
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findCurrentByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids);
	}
	
	
	/**
 	 * Find the actions associated with a given meeting.
	 * @param integer $meeting_id meeting ID
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findByMeetingId($meeting_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByMeetingId($meeting_id);
	}
	
	
	/**
 	  * Find the actions associated with a given communication id and type id.
	 * @param integer $communication_id communication id
	 * @param integer $type_id type_id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findByCommunicationIdAndTypeId($communication_id, $type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCommunicationIdAndTypeId($communication_id, $type_id);
	}
	

	/**
 	 * Find the current actions associated with a given post_initiitive_id.
	 * @param integer $post_initiative_id post initiative id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findCurrentCountByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentCountByPostInitiativeId($post_initiative_id);
	}

	/**
 	 * Find the overdue actions associated with a given post_initiitive_id.
	 * @param integer $post_initiative_id post initiative id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findOverdueCountByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findOverdueCountByPostInitiativeId($post_initiative_id);
	}

 	/** Find the count of actions associated with a given post_initiitive_id of a particular type.
	 * @param integer $post_initiative_id post initiative id
	 * @param integer $type_id action type id 
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findCurrentCountByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentCountByPostInitiativeIdAndTypeId($post_initiative_id, $type_id);
	}
	
	
 	/** Find the count of actions associated with a given post initiative id for a particular set of type ids.
	 * @param integer $post_initiative_id post initiative id
	 * @param integer $type_ids action type ids
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public static function findCurrentCountByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentCountByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids);
	}
	
	
	
	/**
 	 * Find all action types.
	 * @return array
	 */
	public static function findActionTypesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findActionTypesAll();
	}
	
	/**
 	 * Find all action type by id.
	 * @return array
	 */
	public static function findActionTypeById($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findActionTypeById($id);
	}
	
	
	/**
 	 * Find all action communication types.
	 * @return array
	 */
	public static function findActionCommunicationTypesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findActionCommunicationTypesAll();
	}
	
	/**
 	 * Find all action resource types.
	 * @return array
	 */
	public static function findActionResourceTypesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findActionResourceTypesAll();
	}
	
	/**
 	 * Find all action resources by array of resource ids.
	 * @return array
	 */
	public static function findResourcesById($resource_ids)
	{
		$id_string = implode(",", $resource_ids); 
		$finder = self::getFinder(__CLASS__);
		return $finder->findResourcesById($id_string);
	}
	

	
}

?>