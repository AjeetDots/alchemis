<?php

require_once('app/domain/DomainObject.php');

/**
 * @package alchemis
 */
class app_domain_Communication extends app_domain_DomainObject
{
	protected $post_initiative_id;
	protected $user_id;
	protected $lead_source_id;
	protected $type_id;
	protected $status_id;
	protected $next_action_by;
	protected $communication_date;
	protected $direction;
	protected $effective;
	protected $is_effective;
	protected $ote;
	protected $targeting_id;
	protected $receptiveness_id;
	protected $decision_maker_type_id;
	protected $next_communication_date;
	protected $priority_callback;
	protected $next_communication_date_reason_id;
	protected $comments;
	protected $note_id;
	protected $notes;
	protected $attachments;
	protected $has_attachment;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
		
		if ($this->id)
		{
			$finder = self::getFinder('app_domain_CommunicationAttachment');
			$this->setAttachments($finder->findByCommunicationId($this->id));
		}
		else
		{
			echo "<br />skip load";
		}	
	}

	/**
	 * Set the post initiative id.
	 * @param integer $post_initiative_id
	 */
	public function setPostInitiativeId($post_initiative_id)
	{
		$this->post_initiative_id = $post_initiative_id;
		$this->markDirty();
	}

	/**
	 * Return the post initiative id
	 * @return integer the post initiative id
	 */
	public function getPostInitiativeId()
	{
		return $this->post_initiative_id;
	}


	/**
	 * Set the user id
	 * @param integer $user_id
	 */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;
		$this->markDirty();
	}

	/**
	 * Return the user id
	 * @return integer the user id
	 */
	public function getUserId()
	{
		return $this->user_id;
	}
	
	
	/**
	 * Set the lead source id
	 * @param integer $lead_source_id
	 */
	public function setLeadSourceId($lead_source_id)
	{
		$this->lead_source_id = $lead_source_id;
		$this->markDirty();
	}

	/**
	 * Return the lead_source_id
	 * @return integer the lead_source_id
	 */
	public function getLeadSourceId()
	{
		return $this->lead_source_id;
	}
	
	/**
	 * Set the communication type id.
	 * @param string $type_id the communication type id
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}

	/**
	 * Return the communication type id.
	 * @return number $status the communication type id
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}
	
	/**
	 * Set the status_id
	 * @param integer $status_id
	 */
	public function setStatusId($status_id)
	{
		$this->status_id = $status_id;
		$this->markDirty();
	}

	/**
	 * Return the status_id.
	 * @return integer $status_id
	 */
	public function getStatusId()
	{
		return $this->status_id;
	}
	
	
	/**
	 * Set the next_action_by
	 * @param integer $next_action_by
	 */
	public function setNextActionBy($next_action_by)
	{
		$this->next_action_by = $next_action_by;
		$this->markDirty();
	}

	/**
	 * Return the next_action_by.
	 * @return integer $next_action_by
	 */
	public function getNextActionBy()
	{
		return $this->next_action_by;
	}
	
		/**
	 * Set the communication date
	 * @param string $communication_date
	 */
	public function setCommunicationDate($communication_date)
	{
		$this->communication_date = $communication_date;
		$this->markDirty();
	}

	/**
	 * Return the communication date.
	 * @return string $communication_date
	 */
	public function getCommunicationDate()
	{
		return $this->communication_date;
	}
	
	/**
	 * Set the direction
	 * @param string $direction
	 */
	public function setDirection($direction)
	{
		$this->direction = $direction;
		$this->markDirty();
	}

	/**
	 * Return the direction.
	 * @return string $direction
	 */
	public function getDirection()
	{
		return $this->direction;
	}
	
	/**
	 * Set the communication effectiveness
	 * @param string $effective
	 */
	public function setEffective($effective)
	{
		$this->effective = $effective;
		$this->markDirty();
	}

	/**
	 * Return the communication effectiveness
	 * @return string effectiveness
	 */
	public function getEffective()
	{
		return $this->effective;
	}
	
		
	/**
	 * Set the communication is_effective value
	 * @param string $is_effective
	 */
	public function setIsEffective($is_effective)
	{
		$this->is_effective = $is_effective;
		$this->markDirty();
	}
	
	/**
	 * Return the communication effectiveness as an integer
	 * @return integer
	 */
	public function getIsEffective()
	{
		return $this->is_effective;
	
//		if ($this->effective == 'effective')
//		{
//			return 1;
//		}
//		else
//		{
//			return 0;
//		}
	}

	/**
	 * Set the ote flag
	 * @param string $ote
	 */
	public function setOTE($ote)
	{
		$this->ote = $ote;
		$this->markDirty();
	}

	/**
	 * Return the OTE flag
	 * @return string OTE
	 */
	public function getOTE()
	{
		if (is_null($this->ote)) return 0;
		return $this->ote;
	}
	
	/**
	 * Set the targeting id 
	 * @param integer $targeting_id
	 */
	public function setTargetingId($targeting_id)
	{
		$this->targeting_id = $targeting_id;
		$this->markDirty();
	}

	/**
	 * Return the targeting id.
	 * @return integer $targeting_id
	 */
	public function getTargetingId()
	{
		return $this->targeting_id;
	}
	
	/**
	 * Return the targeting description for a targeting_id.
	 * @return string $targeting
	 */
	public function getTargetingDescription()
	{
		return self::lookupTargetingDescription($this->targeting_id);
	}
	
	
	/**
	 * Set the receptiveness_id
	 * @param integer $receptiveness_id
	 */
	public function setReceptivenessId($receptiveness_id)
	{
		$this->receptiveness_id = $receptiveness_id;
		$this->markDirty();
	}

	/**
	 * Return the receptiveness id.
	 * @return integer $receptiveness_id
	 */
	public function getReceptivenessId()
	{
		return $this->receptiveness_id;
	}
	
	/**
	 * Return the receptiveness description for a receptiveness_id
	 * @return integer $receptiveness
	 */
	public function getReceptivenessDescription()
	{
		return self::lookupReceptivenessDescription($this->receptiveness_id);
	}
	
	/**
	 * Set the decision maker type id
	 * @param integer $decision_maker_type_id
	 */
	public function setDecisionMakerTypeId($decision_maker_type_id)
	{
		$this->decision_maker_type_id = $decision_maker_type_id;
		$this->markDirty();
	}

	/**
	 * Return the decision maker type id.
	 * @return integer $decision_maker_type_id
	 */
	public function getDecisionMakerTypeId()
	{
		return $this->decision_maker_type_id;
	}
	
	/**
	 * Return the decision maker type.
	 * @return string decision maker type - the decision maker type in words
	 */
	public function getDecisionMakerTypeDescription()
	{
		return self::lookupDecisionMakerTypeDescription($this->decision_maker_type_id);
	}
	
	/**
	 * Set the next communication date
	 * @param string $next_communication_date
	 */
	public function setNextCommunicationDate($next_communication_date)
	{
		$this->next_communication_date = $next_communication_date;
		$this->markDirty();
	}

	/**
	 * Return the next communication date.
	 * @return string $next_communication_date
	 */
	public function getNextCommunicationDate()
	{
		return $this->next_communication_date;
	}
	
	/**
	* Set the callback priority
	* @param boolean $callback_priority
	*/
	public function setPriorityCallBack($priority_callback)
	{
		$this->priority_callback = $priority_callback;
		$this->markDirty();
	}
	
	/**
	 * Return the callback priority date.
	 * @return boolean $priority_callback
	 */
	public function getPriorityCallBack()
	{
		return $this->priority_callback;
	}
	
	/**
	 * Set the next communication date reason id
	 * @param integer $next_communication_date_reason_id
	 */
	public function setNextCommunicationDateReasonId($next_communication_date_reason_id)
	{
		$this->next_communication_date_reason_id = $next_communication_date_reason_id;
		$this->markDirty();
	}

	/**
	 * Return the communication date reason id.
	 * @return integer $next_communication_date_reason_id
	 */
	public function getNextCommunicationDateReasonId()
	{
		return $this->next_communication_date_reason_id;
	}
	
	
	/**
	 * Return the communication date reason.
	 * @return string $next_communication_date_reason. The next communication reason in words
	 */
	public function getNextCommunicationDateReasonDescription()
	{
		return self::lookupNextCommunicationReasonDescription($this->next_communication_date_reason_id);
	}
		
	/**
	 * Set the comments
	 * @param string $comments
	 */
	public function setComments($comments)
	{
		$this->comments = $comments;
		$this->markDirty();
	}

	/**
	 * Return the comments.
	 * @return string $comments
	 */
	public function getComments()
	{
		return $this->comments;
	}

	 /** Set the note Id.
	 * @param string $note_id
	 */
	public function setNoteId($note_id)
	{
		$this->note_id = $note_id;
		$this->markDirty();
	}

	/**
	 * Return the note id.
	 * @return string
	 */
	public function getNoteId()
	{
		return $this->note_id;
	}
	
	/**
	 * Set the notes.
	 * @param string $notes
	 */
	public function setNotes($notes)
	{
		if (!is_null($notes) && trim($notes) != '')
		{
			$this->notes = $notes;
			$this->markDirty();
		}
	}

	/**
	 * Return the notes.
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
	}
	
	/**
	 * Set the attachments.
	 * @param string $attachments
	 */
	public function setAttachments(app_domain_CommunicationAttachmentCollection $attachments)
	{
		$this->attachments = $attachments;
		$this->markDirty();
	}

	/**
	 * Return the attachments (a collection of app_domain_CommunicationAttachment objects).
	 * @return collection
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}
	
	/**
	 * Add an attachment to the $attachments collection.
	 * @param app_domain_CommunicationAttachment $attachment the attachment to add
	 */
	public function addAttachment(app_domain_CommunicationAttachment $attachment)
	{
		$this->attachments->add($attachment);
		$this->markDirty();
	}
	
	 /** Set the $has_attachment variable - holds flag to show whether or not the communication has
	  * one or more attachments.
	 * @param boolean $has_attachment
	 */
	public function setHasAttachment($has_attachment)
	{
		$this->has_attachment = $has_attachment;
		$this->markDirty();
	}

	/**
	 * Return the has_attachment variable.
	 * @return boolean
	 */
	public function getHasAttachment()
	{
		return $this->has_attachment;
	}
	

	/**
	 * Find a given communication.
	 * @param integer $id
	 * @return app_domain_Communication
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}
	
	/**
	 * 
	 * @param integer $post_initiative_id
	 * @return app_mapper_CommunicationMapper
	 */
	public static function findLastByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->doFindLastByPostInitiativeId($post_initiative_id);
	}
	
	/**
	 * 
	 * @param integer $post_initiative_id
	 * @param integer $type_id
	 * @return integer - communication id
	 */
	public static function doFindByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->doFindByPostInitiativeIdAndTypeId($post_initiative_id, $type_id);
	}

	/**
	 * 
	 * @param integer $post_initiative_id
	 * @param integer $type_id
	 * @return integer - communication id
	 */
	public static function findPreviousByCommunicationIdAndTypeId($post_initiative_id, $type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPreviousByCommunicationIdAndTypeId($post_initiative_id, $type_id);
	}


 	/** @param integer $post_initiative_id
	 * @return single item - id of the penultimate communication id
	 */
	public static function findPreviousByPostInitiativeIdAndCommunicationId($post_initiative_id, $communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPreviousByPostInitiativeIdAndCommunicationId($post_initiative_id, $communication_id);
	}


 	/** @param integer $communication_id
	 * @return single item - id of the effective communication previous to $communication_id
	 */
	public static function findPreviousEffectiveByPostInitiativeIdAndCommunicationId($post_initiative_id, $communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPreviousEffectiveByPostInitiativeIdAndCommunicationId($post_initiative_id, $communication_id);
	}
	



	/**
	 * @param integer $post_id
	 * @param integer $initiative_id
	 * @return app_mapper_CommunicationMapper
	 */
	public static function findLastByPostIdAndInitiativeId($post_id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findLastByPostIdAndInitiativeId($post_id, $initiative_id);
	}

	
	/**
	 * Find all communication status' from lookup data
	 * @return app_mapper_CommunicationMapper
	 */
	public static function findStatusAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findStatusAll();
	}
	
	/**
	 * Find all communication status' from lookup data in form ready for html select options
	 * @return app_mapper_CommunicationMapper
	 */
	public static function findStatusAllForDropdown()
	{
		$options = array();
		if ($items = self::findStatusAll())
		{
			foreach ($items as $item)
			{
				$options[$item['id']] = $item['description'];
			}
		}
		return $options;

	}
	
	
	
	/**
	 * Find all communication types from lookup data
	 * @return app_mapper_CommunicationMapper
	 */
	public static function findTypesAllActive()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findTypesAllActive();
	}
	
	/**
	 * 
	 *  @return app_mapper_CommunicationMapper raw array
	 */
	public static function lookupStatusForHtmlSelect($status_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupStatusForHtmlSelect($status_id);
	}
	
	
	/**
	 * 
	 * @return app_mapper_CommunicationMapper raw array
	 */
	public static function lookupCommunicationTargeting()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCommunicationTargeting();
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupTargetingDescription($targeting_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupTargetingDescription($targeting_id);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupTargetingStatusScore($targeting_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupTargetingStatusScore($targeting_id);
	}

	/**
	 * 
	 * @return app_mapper_CommunicationMapper raw array
	 */
	public static function lookupCommunicationReceptiveness()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCommunicationReceptiveness();
	}

	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupReceptivenessDescription($receptiveness_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupReceptivenessDescription($receptiveness_id);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupReceptivenessStatusScore($receptiveness_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupReceptivenessStatusScore($receptiveness_id);
	}
	
	/**
	 * 
	 * @return app_mapper_CommunicationMapper raw array
	 */
	public static function lookupNextCommunicationReasons()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupNextCommunicationReasons();
	}
	
	/**
	 * 
	 * @return raw array
	 */
	public static function lookupDecisonMakerOptions()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupDecisonMakerOptions();
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupDecisionMakerTypeDescription($decision_maker_type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupDecisionMakerTypeDescription($decision_maker_type_id);
	}
	
	/**
	 * 
	 * @return raw array
	 */
	public static function lookupAgencyUserOptions()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupAgencyUserOptions();
	}
	
	 /** @return raw array
	 */
	public static function lookupDisciplineOptions()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupDisciplineOptions();
	}
	
	 /** @return raw array
	 */
	public static function lookupDisciplineIds()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupDisciplineIds();
	}
	
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupAgencyUserTypeDescription($agency_user_type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupAgencyUserTypeDescription($agency_user_type_id);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupNextCommunicationReasonDescription($next_communication_reason_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupNextCommunicationReasonDescription($next_communication_reason_id);
	}	
		
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupNextCommunicationStatusScore($next_communication_reason_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupNextCommunicationStatusScore($next_communication_reason_id);
	}

	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupCommunicationStatusDescriptionByStatusScore($status_score)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCommunicationStatusDescriptionByStatusScore($status_score);
	}

	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupCommunicationStatusIdByStatusScore($status_score)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCommunicationStatusIdByStatusScore($status_score);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupCountByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCountByUserId($user_id);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupEffectiveCountByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupEffectiveCountByUserId($user_id);
	}
	
	/** @return raw data - single item
	 */
	public static function lookupCountByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCountByPostInitiativeId($post_initiative_id);
	}
	
	/**
	 * 
	 * @return raw data - array
	 */
	public static function findCampaignDisciplineRecordsByCommunicationId($campaign_id, $communication_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCampaignDisciplineRecordsByCommunicationId($campaign_id, $communication_id);
	}
	
	
	
	/**
	 * Calculate the status_id for a post initiative
	 * @param integer $id
	 * @param boolean $last_effective_dm
	 * @return integer $status_id
	 */
	public static function calculateStatus($post_id, $next_communication_date, $agency_user, $OTE, $next_communication_reason_id, $targeting_id, $receptiveness_id)
	{
		$status_score = 0;
//		if (!is_null($next_communication_date))
//		{
//			$next_comm_month_diff = Utils::dateDiff('d', date('Y-m-d'), $next_communication_date);
//			switch (true)
//			{
//				case $next_comm_month_diff < 30:
//					$status_score += 3;
//					break;
//				case $next_comm_month_diff >= 30 && $next_comm_month_diff < 120:
//					$status_score += 2;
//					break;
//				case $next_comm_month_diff >= 120 && $next_comm_month_diff < 180:
//					$status_score += 1;
//					break;
//				default:
//					break;
//			}
//		}
		
		$status_score += ($agency_user ? 7 : 0); 
		$status_score += ($OTE ? 6 : 0);

		
		
		// lookup the next communication reason id status score
		$status_score += app_domain_Communication::lookupNextCommunicationStatusScore($next_communication_reason_id);

		// lookup proximity
		// TODO: calculate company proximity status score
		
		// lookup percentage match to offer status score
		$status_score += app_domain_Communication::lookupTargetingStatusScore($targeting_id);

		// lookup receptiveness status score
		$status_score += app_domain_Communication::lookupReceptivenessStatusScore($receptiveness_id);

		$meeting_count = app_domain_Post::findMeetingCountByPostId($post_id);

		// TODO: need to check if there are any meetings in the session communication array
		if ($meeting_count > 1)
		{
			$status_score += 3;
		}
		elseif ($meeting_count == 1)
		{
			$status_score += 2;
		}
		elseif ($meeting_count == 0)
		{
			// do nothing
		}
		else
		{
			$status_score -= 1;
		}
		
		// lookup status_id from tbl_lkp_communication_status
		$status_id = self::lookupCommunicationStatusIdByStatusScore($status_score);
		
		// if status is time related (eg receptive, v.receptive) then need to lookup next comm date to see which status should be used
		$next_comm_month_diff = Utils::dateDiff('d', date('Y-m-d'), $next_communication_date);
		switch ($status_id)
		{
			case 2: //long term
			case 3: //medium term 
				if ($next_comm_month_diff >= 120)
				{
					$status_id = 2;
				}
				else
				{
					$status_id = 3;
				}
				break;
			case 4: //medium term
			case 5: //near term
				if ($next_comm_month_diff < 30)
				{
					$status_id = 5;
				}
				else
				{
					$status_id = 4;
				}
				break;
			default:
		}
		
		return $status_id;
	}
	
	
	

}

?>