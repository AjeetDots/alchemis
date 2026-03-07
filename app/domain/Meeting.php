<?php

/**
 * Defines the app_domain_Meeting class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/mapper/MeetingMapper.php');

/**
 * @package Alchemis
 */
class app_domain_Meeting extends app_domain_DomainObject
{
	/**
	 * Field spec and validation rules.
	 */
	protected $spec;

	private $post_initiative_id;
	private $communication_id;
	private $is_current;
	private $status_id;
	private $type_id;
	private $date;
	private $reminder_date;
	private $attended_date;
	private $location_id;
	private $nbm_predicted_rating;
	private $notes;
	private $created_at;
	private $created_by;
	private $modified_at;
    private $modified_by;
	private $created_by_name;
	private $feedback_rating;
	private $feedback_decision_maker;
	private $feedback_agency_user;
	private $feedback_budget_available;
	private $feedback_receptive;
	private $feedback_targeting;
	private $feedback_meeting_length;
	private $feedback_comments;
	private $feedback_next_steps;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);

		if ($this->id)
		{
			// do nothing
		}
	}

	/**
	 * Returns an array of field validation rules.
	 * @see app_base_RuleValidator
	 */
	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['date'] 		= array(	'alias'      => 'Meeting date',
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
	 * Set the meeting post_initiative_id .
	 * @param number post_initiative_id the post_initiative_id
	 */
	public function setPostInitiativeId($post_initiative_id)
	{
		$this->post_initiative_id = $post_initiative_id;
		$this->markDirty();
	}

	/**
	 * Return the meeting post initiative id.
	 * @return number the post initiativeid
	 */
	public function getPostInitiativeId()
	{
		return $this->post_initiative_id;
	}

	/**
	 * Set the meeting communication id.
	 * @param string $communication_id the meeting communication id
	 */
	public function setCommunicationId($communication_id)
	{
		$this->communication_id = $communication_id;
		$this->markDirty();
	}

	/**
	 * Return the meeting communication id.
	 * @return number $communication_id the meeting communication id
	 */
	public function getCommunicationId()
	{
		return $this->communication_id;
	}

	/**
	 * Set the meeting is_current - note: there can only be one current meeting per post_initiative_id.
	 * @param boolean $is_curent - the meeting is_current status
	 */
	public function setIsCurrent($is_current)
	{
		$this->is_current = $is_current;
		$this->markDirty();
	}

	/**
	 * Return the meeting is_current status.
	 * @return boolean $is_current - whether the meeting is current. Note: there can only be one current meeting per post_initiative_id.
	 */
	public function getIsCurrent()
	{
		return $this->is_current;
	}

	/**
	 * Set the meeting statusid.
	 * @param number $status_id the meeting status id
	 */
	public function setStatusId($status_id)
	{
		$this->status_id = $status_id;
		$this->markDirty();
	}

	/**
	 * Return the meeting status_id.
	 * @return number $status_id the meeting status id
	 */
	public function getStatusId()
	{
		return $this->status_id;
	}

	/**
	 * Return the meeting status.
	 * @return string $status the meeting status
	 */
	public function getStatus()
	{
		return ucfirst(self::lookupStatusById($this->status_id));
	}

	/**
	 * Set the meeting type id.
	 * @param number $type_id the meeting type id
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}

	/**
	 * Return the meeting type id.
	 * @return number $type_id the meeting type id
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}


	/**
	 * Return the meeting type.
	 * @return string $type the meeting type
	 */
	public function getType()
	{
		return ucfirst(self::lookupTypeById($this->type_id));
	}

	/**
	 * Set the meeting date
	 * @param string $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
		$this->markDirty();
	}

	/**
	 * Return the meeting date.
	 * @return string $date
	 */
	public function getDate()
	{
		return $this->date;
	}

    /**
     * Set the meeting reminder date
     * @param string $reminder_date
     */
    public function setReminderDate($reminder_date)
    {
        $this->reminder_date = $reminder_date;
        $this->markDirty();
    }

    /**
     * Return the meeting reminder date.
     * @return string $reminder_date
     */
    public function getReminderDate()
    {
        return $this->reminder_date;
    }

	/**
	 * Set the meeting attended date
	 * @param string $attended_date
	 */
	public function setAttendedDate($attended_date)
	{
		$this->attended_date = $attended_date;
		$this->markDirty();
	}

	/**
	 * Return the meeting attended date.
	 * @return string $attended_date
	 */
	public function getAttendedDate()
	{
		return $this->attended_date;
	}

	/**
	 * Set the meeting location id
	 * @param string $location_id
	 */
	public function setLocationId($location_id)
	{
		$this->location_id = $location_id;
		$this->markDirty();
	}

	/**
	 * Return the meeting location_id.
	 * @return string $location_id
	 */
	public function getLocationId()
	{
		return $this->location_id;
	}

	/**
	 * Set the meeting nbm predicted rating
	 * @param string $nbm_predicted_rating
	 */
	public function setNbmPredictedRating($nbm_predicted_rating)
	{
		$this->nbm_predicted_rating = $nbm_predicted_rating;
		$this->markDirty();
	}

	/**
	 * Return the meeting nbm predicted rating.
	 * @return string $nbm_predicted_rating
	 */
	public function getNbmPredictedRating()
	{
		return $this->nbm_predicted_rating;
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
	 * Set the date the meeting was created
	 * @param string $created_at
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;
		$this->markDirty();
	}

	/**
	 * Return the date the meeting was created
	 * @return string $created_at
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

    /**
     * Set the date the meeting was modified
     * @param string $modified_at
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
        $this->markDirty();
    }

    /**
     * Return the date the meeting was modified
     * @return string $modified_at
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

	/**
	 * Set the id of the user who created the meeting.
	 * @param number $created_by the id of the user who created the meeting
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;
		$this->markDirty();
	}

	/**
	 * Return the id of the user who created the meeting.
	 * @return number $created_by the id of the user who created the meeting
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}

    /**
     * Set the id of the user who modified the meeting.
     * @param number $modified_by the id of the user who modified the meeting
     */
    public function setModifiedBy($modified_by)
    {
        $this->modified_by = $modified_by;
        $this->markDirty();
    }

    /**
     * Return the id of the user who modified the meeting.
     * @return number $modified_by the id of the user who modified the meeting
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

 	/** Set the string name of the user who created the meeting.
	 * @param string $created_by_name the name of the user who created the meeting
	 */
	public function setCreatedByName($created_by_name)
	{
		$this->created_by_name = $created_by_name;
		$this->markDirty();
	}


	/**
	 * Return the name of the user who created the meeting.
	 * @return number $created_by the id of the user who created the meeting
	 */
	public function getCreatedByName()
	{
		return $this->created_by_name;
	}


	/**
	 * Set the meeting feedback rating
	 * @param string $feedback_rating
	 */
	public function setFeedbackRating($feedback_rating)
	{
		$this->feedback_rating = $feedback_rating;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback rating.
	 * @return string $feedback_rating
	 */
	public function getFeedbackRating()
	{
		return $this->feedback_rating;
	}


	/**
	 * Set the meeting feedback decision maker
	 * @param string $feedback_decision_maker
	 */
	public function setFeedbackDecisionMaker($feedback_decision_maker)
	{
		$this->feedback_decision_maker = $feedback_decision_maker;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback decision maker
	 * @return string $feedback_decision_maker
	 */
	public function getFeedbackDecisionMaker()
	{
		return $this->feedback_decision_maker;
	}


	/**
	 * Set the meeting feedback agency user
	 * @param string $feedback_agency_user
	 */
	public function setFeedbackAgencyUser($feedback_agency_user)
	{
		$this->feedback_agency_user = $feedback_agency_user;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback agency user
	 * @return string $feedback_agency_user
	 */
	public function getFeedbackAgencyUser()
	{
		return $this->feedback_agency_user;
	}


	/**
	 * Set the meeting feedback budget available
	 * @param string $feedback_budget_available
	 */
	public function setFeedbackBudgetAvailable($feedback_budget_available)
	{
		$this->feedback_budget_available = $feedback_budget_available;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback budget available
	 * @return string $feedback_budget_available
	 */
	public function getFeedbackBudgetAvailable()
	{
		return $this->feedback_budget_available;
	}


	/**
	 * Set the meeting feedback receptive
	 * @param string $feedback_receptive
	 */
	public function setFeedbackReceptive($feedback_receptive)
	{
		$this->feedback_receptive = $feedback_receptive;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback receptive
	 * @return string $feedback_receptive
	 */
	public function getFeedbackReceptive()
	{
		return $this->feedback_receptive;
	}


	/**
	 * Set the meeting feedback targeting
	 * @param string $feedback_targeting
	 */
	public function setFeedbackTargeting($feedback_targeting)
	{
		$this->feedback_targeting = $feedback_targeting;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback targeting
	 * @return string $feedback_targeting
	 */
	public function getFeedbackTargeting()
	{
		return $this->feedback_targeting;
	}

	/**
	 * Set the meeting feedback meeting length
	 * @param string $feedback_meeting_length
	 */
	public function setFeedbackMeetingLength($feedback_meeting_length)
	{
		$this->feedback_meeting_length = $feedback_meeting_length;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback meeting length
	 * @return string $feedback_meeting_length
	 */
	public function getFeedbackMeetingLength()
	{
		return $this->feedback_meeting_length;
	}


	/**
	 * Set the meeting feedback comments
	 * @param string $feedback_comments
	 */
	public function setFeedbackComments($feedback_comments)
	{
		$this->feedback_comments = $feedback_comments;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback comments
	 * @return string $feedback_comments
	 */
	public function getFeedbackComments()
	{
		return $this->feedback_comments;
	}

	/**
	 * Set the meeting feedback next steps
	 * @param string $feedback_next_steps
	 */
	public function setFeedbackNextSteps($feedback_next_steps)
	{
		$this->feedback_next_steps = $feedback_next_steps;
		$this->markDirty();
	}

	/**
	 * Return the meeting feedback comments
	 * @return string feedback_next_steps
	 */
	public function getFeedbackNextSteps()
	{
		return $this->feedback_next_steps;
	}

	/**
	 * @return app_domain_Post
	 */
	public function getPost()
	{
		$finder = self::getFinder('app_domain_Post');
		return $finder->findByMeetingId($this->id);
	}

	/**
	 * Find all meetings
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * Find all meetings limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public static function findSet($limit = 15, $offset = 0)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findSet($limit, $offset);
	}

	/**
	 * Find a meeting by a given ID
	 * @param integer $id meeting ID
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/** Find a meeting by a given post initiative id
	 * @param integer $post_initiative_id post initiative id
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public static function findByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeId($post_initiative_id);
	}

	/**
	 * Find a meeting by a given communication id
	 * @param integer $communication_id communication id
	 * @return app_domain_Meeting object
	 */
	public static function findByCommunicationId($communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCommunicationId($communication_id);
	}



	/** Find any current meetings by a given post initiative id
	 * @param integer $post_initiative_id post initiative id
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public static function findCurrentByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentByPostInitiativeId($post_initiative_id);
	}

	/**
	 *
	 * @return app_mapper_MeetingMapper raw array
	 */
	public static function lookupStatusById($status_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupStatusById($status_id);
	}


	/**
	 *
	 * @return app_mapper_MeetingMapper raw array
	 */
	public static function getStatusAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getStatusAll();
	}


	/**
	 *
	 * @return app_mapper_MeetingMapper raw array
	 */
	public static function lookupTypeById($type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupTypeById($type_id);
	}

	/**
	 *
	 * @return app_mapper_MeetingMapper raw array
	 */
	public static function getTypesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getTypesAll();
	}

	/**
	 * Find a meeting by a given ID
	 * @param integer $id meeting ID
	 * @return app_mapper_MeetingHistoryCollection collection of app_domain_Meeting objects
	 */
	public static function findHistory($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findHistory($id);
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

	/**
	 * Return the current meetings of a given status for a given user.
	 * @param integer $user_id
	 * @param integer $status_id
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public static function findByUserIdStatusId($user_id, $status_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByUserIdStatusId($user_id, $status_id);
	}

	/**
	 * Return the ID of the company a given meeting is associated with.
	 * @param integer $meeting_id
	 * @return integer
	 */
	public static function findCompanyId($meeting_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCompanyId($meeting_id);
	}

	/**
	 * Return the current meetings of a given status.
	 * @param integer $status_id
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public static function findByStatusId($status_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByStatusId($status_id);
	}

	/**
	 * Return an associated array of status values.
	 * @return array
	 */
	public static function lookupStatuses()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupStatuses();
	}

	/**
	 * Return an associated array of meeting locations.
	 * @return array
	 */
	public static function lookupLocationAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupLocationAll();
	}




}

?>