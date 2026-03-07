<?php

/**
 * Defines the app_domain_PostInitiative class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('include/Utils/Utils.class.php');

use Illuminate\Database\Capsule\Manager as DB;

/**
 * @package Alchemis
 */
class app_domain_PostInitiative extends app_domain_DomainObject
{
	private $post_id;
	private $initiative_id;
	private $status_id;
	private $status;
	private $comment;
	private $next_action_by;
	private $next_action_by_name;
	private $last_effective_communication_date;
	private $last_communication_date;
	private $next_communication_date;
	private $last_effective_communication_id;
	private $last_communication_id;
	private $last_mailer_communication_id;
	private $last_communication_user_client_alias;
	private $lead_source_id;
    private $lead_source;
    private $data_source_id;
    private $data_source;
    private $data_source_changed_date;
	private $priority_callback;

	function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Set the post initiative post id
	 * @param string $post_id of this post initiative
	 */
	public function setPostId($post_id)
	{
		$this->post_id = $post_id;
		$this->markDirty();
	}

	/**
	 * Set the post initiative initiative id.
	 * @param string $initiative_id of the post initiative
	 */
	public function setInitiativeId($initiative_id)
	{
		$this->initiative_id = $initiative_id;
		$this->markDirty();
	}

	/**
	 * Set the post initiative lead source id.
	 * @param string $lead_source_id of the post initiative
	 */
	public function setLeadSourceId($lead_source_id)
	{
		$this->lead_source_id = $lead_source_id;
		$this->markDirty();
	}

	/**
	 * Set the post initiative lead source.
	 * @param string $lead_source of the post initiative
	 */
	public function setLeadSource($lead_source)
	{
		$this->lead_source = $lead_source;
		$this->markDirty();
    }
    
    /**
	 * Set the post initiative data source id.
	 * @param string $data_source_id of the post initiative
	 */
    public function setDataSourceId($data_source_id)
    {
        $this->data_source_id = $data_source_id;
        $this->markDirty();
    }

    /**
	 * Set the post initiative data source.
	 * @param string $data_source of the post initiative
	 */
	public function setDataSource($data_source)
	{
		$this->data_source = $data_source;
		$this->markDirty();
    }

    /**
     * Set the post initiative data source changed date.
     * @param string $data_source_changed_date of the post initiative.
     */
    public function setDataSourceChangedDate($data_source_changed_date)
    {
        $this->data_source_changed_date = $data_source_changed_date;
        $this->markDirty();
    }

	/**
	 * Set the post initiative status_id.
	 * @param string $status_id of the post initiative
	 */
	public function setStatusId($status_id)
	{
		$this->status_id = $status_id;
		$this->markDirty();
	}

	/**
	 * Set the post initiative status.
	 * @param string $status of the post initiative
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		$this->markDirty();
	}


	/**
	 * Set the post initiative comment.
	 * @param string $comment of the post initiative
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
		$this->markDirty();
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

	/** Set the next_action_by name
	 * @param integer $next_action_by_name
	 */
	public function setNextActionByName($next_action_by_name)
	{
		$this->next_action_by_name= $next_action_by_name;
		$this->markDirty();
	}

	/**
	 * Return the next_action_by name.
	 * @return integer $next_action_by_name
	 */
	public function getNextActionByName()
	{
		return $this->next_action_by_name;
	}


	/**
	 * Set the post initiative last effective communication date
	 * @param string $last_effective_communication_date for the post initiative
	 */
	public function setLastEffectiveCommunicationDate($last_effective_communication_date)
	{
		$this->last_effective_communication_date = $last_effective_communication_date;
		$this->markDirty();
	}

	/**
	 * Set the post initiative last communication idate .
	 * @param string $last_communication_id for the post initiative
	 */
	public function setLastCommunicationDate($last_communication_date)
	{
		$this->last_communication_date = $last_communication_date;
		$this->markDirty();
	}

	/**
	 * Set the post initiative next communication date .
	 * @param string $next_communication_date for the post initiative
	 */
	public function setNextCommunicationDate($next_communication_date)
	{
		$this->next_communication_date = $next_communication_date;
		$this->markDirty();
	}

	/**
	 * Set the post initiative last effective communication id
	 * @param integer $last_effective_communication_id for the post initiative
	 */
	public function setLastEffectiveCommunicationId ($last_effective_communication_id)
	{
		$this->last_effective_communication_id = $last_effective_communication_id;
		$this->markDirty();
	}

	/**
	 * Set the post initiative last communication id .
	 * @param integer $last_communication_id for the post initiative
	 */
	public function setLastCommunicationId($last_communication_id)
	{
		$this->last_communication_id = $last_communication_id;
		$this->markDirty();
	}


	/**
	 * Set the post initiative last mailer communication id .
	 * @param integer $last_mailer_communication_id for the post initiative
	 */
	public function setLastMailerCommunicationId($last_mailer_communication_id)
	{
		$this->last_mailer_communication_id = $last_mailer_communication_id;
		$this->markDirty();
	}


	/**
	 * Set the post initiative last communication user client alias name .
	 * @param integer $last_communication_user_client_alias for the post initiative
	 */
	public function setLastCommunicationUserClientAlias($last_communication_user_client_alias)
	{
		$this->last_communication_user_client_alias = $last_communication_user_client_alias;
		$this->markDirty();
	}

	/**
	 * Get the post initiative post id
	 * @return integer $post_id of this post initiative
	 */
	public function getPostId()
	{
		return $this->post_id;
	}

	/** Get the post object for the $post_id
	 * @return object post for this post initiative
	 */
	public function getPost()
	{
		return app_domain_Post::find($this->post_id);
	}

	/**
	 * Get the post initiative initiative ID.
	 * @return integer $initiative_id of the post initiative
	 */
	public function getInitiativeId()
	{
		return $this->initiative_id;
	}

	/** Get the initiative object for the $initiative_id
	 * @return object post for this post initiative
	 */
	public function getInitiative()
	{
		return app_domain_Initiative::find($this->initiative_id);
	}

	/** Get the post initiative lead source id.
	 * @return integer $lead_source_id of the post initiative
	 */
	public function getLeadSourceId()
	{
		return $this->lead_source_id;
	}

	/** Get the post initiative lead source.
	 * @return integer $lead_source of the post initiative
	 */
	public function getLeadSource()
	{
		return $this->lead_source;
    }
    
    /**
     * Get the post initiative data source id.
     * @return integer $data_source_id of the post initiative.
     */
    public function getDataSourceId()
    {
        return $this->data_source_id;
    }
    
    /**
     * Get the post initiative data source.
     * @return string $data_source of the post initiative.
     */
    public function getDataSource()
    {
        return $this->data_source;
    }

    /**
     * Get the post initiative data source changed date.
     * @return string $data_source_changed_date of the post initiative.
     */
    public function getDataSourceChangedDate()
    {
        return $this->data_source_changed_date;
    }

	/**
	 * Get the post initiative status ID.
	 * @return integer ID of the post initiative
	 */
	public function getStatusId()
	{
		return $this->status_id;
	}

	/**
	 * Get the post initiative status.
	 * @return string $status of the post initiative
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Get the post initiative comment.
	 * @return string $comment of the post initiative
	 */
	public function getComment()
	{
		return $this->comment;
	}


	/**
	 * Get the post initiative last effective communication date
	 * @return string $last_effective_communication_date for the post initiative
	 */
	public function getLastEffectiveCommunicationDate()
	{
		return $this->last_effective_communication_date;
	}

	/**
	 * Get the post initiative last communication date .
	 * @@return string $last_communication_date for the post initiative
	 */
	public function getLastCommunicationDate()
	{
		return $this->last_communication_date;
	}

	/**
	 * Get the post initiative next communication date .
	 * @return $next_communication_date for the post initiative
	 */
	public function getNextCommunicationDate()
	{
		return $this->next_communication_date;
	}

	/**
	 * Get the id of the last effective communication
	 * @return string $last_effective_communication_id for the post initiative
	 */
	public function getLastEffectiveCommunicationId()
	{
		return $this->last_effective_communication_id;
	}

	/**
	 * Get the id of the last communication .
	 * @@return string $last_communication_id for the post initiative
	 */
	public function getLastCommunicationId()
	{
		return $this->last_communication_id;
	}

	/** Get the id of the last mailer communication.
	 * @@return string $last_mailer_communication_id for the post initiative
	 */
	public function getLastMailerCommunicationId()
	{
		return $this->last_mailer_communication_id;
	}

	/**
	 * Get the name of the last communication user client alias.
	 * @@return string $last_communication_user_client_alias for the post initiative
	 */
	public function getLastCommunicationUserClientAlias()
	{
		return $this->last_communication_user_client_alias;
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
     * Has Tag
     *
     * @param int $tagId Tag ID
     *
     * @return bool
     */
    public function hasTag($tagId)
    {
        $finder = self::getFinder(__CLASS__);
		return $finder->hasTag($this->getId(), $tagId);
    }

	/**
 	 * Find a post initiative by a given ID
	 * @param integer $id post initiative ID
	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

//	/**
// 	 * Find all post initiatives.
//	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
//	 */
//	public static function findAll()
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->findAll();
//	}

	/**
	 * Find all post initiatives associated with a given post
	 * @param integer $post_id
	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
	 */
	public static function findByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostId($post_id);
	}

	/**
	 * Find by post id and initiative_id
	 * @param integer $post_id
	 * @param integer $initiative_id
	 * @return post initiative domain object
	 */
	public static function findByPostAndInitiative($post_id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostAndInitiative($post_id, $initiative_id);
	}

	/**
	 * Find by post id and initiative_id for current user
	 * @param integer $post_id
	 * @param integer $initiative_id
	 * @return post initiative domain object
	 */
	public static function findByPostAndInitiativeForCurrentUser($post_id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostAndInitiativeForCurrentUser($post_id, $initiative_id);
	}

	/** Find post initiative id by post id and initiative_id
	 * @param integer $post_id
	 * @param integer $initiative_id
	 * @return post initiative domain object
	 */
	public static function lookupIdByPostIdAndInitiativeId($post_id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupIdByPostIdAndInitiativeId($post_id, $initiative_id);
	}



	/**
	 * Find communication note notes and related information by post_initiative_id
	 * @param integer $id
	 * @return raw associative array
	 */
	public static function findCommunicationNotes($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCommunicationNotes($id);
	}

	/**
	 * Find effective communication note notes and related information by post_initiative_id
	 * @param integer $id
	 * @return raw associative array
	 */
	public static function findEffectiveCommunicationNotes($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findEffectiveCommunicationNotes($id);
	}

	/**
	 * Find communication notes and related information by company_id and initiative_id
	 * @param integer $company_id
	 * @param integer $initiative_id
	 * @return raw associative array
	 */
	public static function findCommunicationNotesByCompanyAndInitiative($company_id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCommunicationNotesByCompanyAndInitiative($company_id, $initiative_id);
	}

	/** Find post initiative notes and related information by post_initiative_id
	 * @param integer $id
	 * @return raw associative array
	 */
	public static function findNotes($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findNotes($id);
	}

// 	/** Find post by a given email address
// 	* @param string $post_email
// 	* @param string $campaign_nbm_email
// 	* @return array
// 	*/
// 	public static function findIdByPostEmailAndCampaignNbmEmail($post_email, $campaign_nbm_email)
// 	{

// 		$finder = self::getFinder(__CLASS__);
// 		return $finder->findIdByPostEmailAndCampaignNbmEmail($post_email, $campaign_nbm_email);
// 	}

	/**
	 * Find post initiative notes and related information by company_id and initiative_id
	 * @param integer $company_id
	 * @param integer $initiative_id
	 * @return raw associative array
	 */
	public static function findNotesByCompanyAndInitiative($company_id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findNotesByCompanyAndInitiative($company_id, $initiative_id);
	}

	/**
	 * Find tags by post_initiative_id and category id
	 * @param integer $id
	 * @param integer $category_id
	 * @return post initiative collection
	 */
	public static function findTagsByPostInitiativeIdAndCategoryId($id, $category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findTagsByPostInitiativeIdAndCategoryId($id, $category_id);
	}

	/**
	 * Find call backs due for a given user in a given date range.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public static function findCallBacksByUserId($user_id, $start_datetime, $end_datetime)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCallBacksByUserId($user_id, $start_datetime, $end_datetime);
	}

	/**
	* Find priority call backs due for a given user in a given date range.
	* @param integer $user_id
	* @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	* @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	* @return array
	*/
	public static function findPriorityCallBacksByUserId($user_id, $start_datetime, $end_datetime)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCallBacksByUserId($user_id, $start_datetime, $end_datetime, true);
	}

	/**
	 *
	 *  @return app_mapper_PostInitiativeMapper raw array
	 */
	public static function lookupLeadSourceAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupLeadSourceAll();
	}

    public static function lookupDataSourcesAll()
    {
        return DB::table('tbl_lkp_data_sources')->where('client_specific', true)->get();
    }

	/**
	 * Is the current user allowed access to a given post initiative record
	 * @param integer $id - id of the post initiative record
	 * @return boolean
	 */
	public static function isAccessibleByCurrentUser($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->isAccessibleByCurrentUser($id);
	}


}

?>
