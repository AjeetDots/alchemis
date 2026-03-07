<?php

require_once('app/domain/DomainObject.php');
require_once('app/mapper/PostMapper.php');
require_once('app/mapper/ContactMapper.php');

use Illuminate\Database\Capsule\Manager as DB;

/**
 * @package alchemis
 */
class app_domain_Post extends app_domain_DomainObject
{
	private $company_id;
	private $job_title;
	private $propensity;
	private $telephone_1;
	private $telephone_2;
	private $telephone_switchboard;
	private $telephone_fax;
    private $deleted;
    private $data_source_id;
    private $data_source;
    private $data_source_changed_date;
    private $data_owner_id;
	protected $additional_info;

	/**
	 * Collection of contact objects
	 * @var app_mapper_ContactsCollection collection of app_domain_Contact objects
	 */
	protected $contacts;

	function __construct($id = null)
	{
		parent::__construct($id);
		$finder = self::getFinder('app_domain_Contact');
		$this->setContacts($finder->findByPostId($this->id));
	}

	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['job_title']				= array(	'alias'      => 'Job Title',
													'type'       => 'text',
													'mandatory'  => true,
													'max_length' => 255);
		$spec['telephone_1']			= array(	'alias'      => 'Telephone 1',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 50);
		$spec['telephone_2'] 			= array(	'alias'      => 'Telephone 2',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 50);
		$spec['telephone_switchboard']	= array(	'alias'      => 'Switchboard',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 50);
		$spec['telephone_fax'] 			= array(	'alias'      => 'Fax',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 50);
		$spec['new_company_id']			= array(	'alias'      => 'New Location',
													'type'       => 'integer',
													'mandatory'  => true,
													'max_length' => 50);
		$spec['additional_info'] 	= array(	'alias'      => 'Additional Info',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 255);

		if (!is_null($field))
		{
			return $spec[$field];
		}
		else
		{
			return $spec;
		}
	}

	/** TODO - assumes at least one item in collection
	 * Get a site in the collection
	 * @param integer $key item to get from collection
	 */
	public function getContactName($key = null)
	{
		if ($this->contacts->current())
		{
			return $this->contacts->current()->getName();
		}
		else
		{
			return null;
		}
	}

	/** TODO - assumes at least one item in collection
	 * Get a site in the collection
	 * @param integer $key item to get from collection
	 */
	public function getContactTitle($key = null)
	{
		if ($this->contacts->current())
		{
			return $this->contacts->current()->getTitle();
		}
		else
		{
			return null;
		}
	}


	/** TODO - assumes at least one item in collection
	 * Get a first name for the post contact item
	 * @param integer $key item to get from collection
	 */
	public function getContactFirstName($key = null)
	{
		if ($this->contacts->current())
		{
			return $this->contacts->current()->getFirstName();
		}
		else
		{
			return null;
		}
	}


	/** TODO - assumes at least one item in collection
	 * Get a surname for the post contact item
	 * @param integer $key item to get from collection
	 */
	public function getContactSurname($key = null)
	{
		if ($this->contacts->current())
		{
			return $this->contacts->current()->getSurname();
		}
		else
		{
			return null;
		}
	}

	 /** TODO - assumes at least one item in collection
	 * Get a contact in the collection
	 * @param integer $key item to get from collection
	 */
	public function getContact($key = null)
	{
		if ($this->contacts->current())
		{
			return $this->contacts->current();
		}
		else
		{
			return null;
		}
	}

	/**
	 *
	 * @param app_domain_ContactCollection $contacts
	 */
	public function setContacts(app_domain_ContactCollection $contacts)
	{
		$this->contacts = $contacts;
	}

	/**
	 * Returns the contacts collection.
	 * @return
	 */
	public function getContacts()
	{
		if (is_null($this->contacts))
		{
			$this->contacts = self::getFinder('app_domain_Contact')->findByPostId($this->getId());
		}
		return $this->contacts;
	}

	/**
	 * Set the post's company id.
	 * @param string $deleted the post's company id
	 */
	public function setCompanyId($company_id)
	{
		$this->company_id = $company_id;
		$this->markDirty();
	}

	/**
	 * Return the post's company id.
	 * @return string the post's company id.
	 */
	public function getCompanyId()
	{

		return $this->company_id;
	}

	/** Return the post's company name.
	 * @return string the post's company name.
	 */
	public function getCompanyName()
	{

		return app_domain_Company::find($this->company_id)->getName();
	}

	/**
	 * Set the post job title.
	 * @param string $jobTitle the post job title
	 */
	public function setJobTitle($job_title)
	{
		$this->job_title = $job_title;
		$this->markDirty();
	}

	/**
	 * Get the post's job title.
	 * @return string job title
	 */
	public function getJobTitle()
	{
		return $this->job_title;
	}


	/**
	 * Set the post propensity.
	 * @param string $propensity the post propensity
	 */
	public function setPropensity($propensity)
	{
		$this->propensity = $propensity;
		$this->markDirty();
	}

	/**
	 * Get the post's propensity.
	 * @return string propensity
	 */
	public function getPropensity()
	{
		return $this->propensity;
	}


	/**
	 * Set the post telephone_1.
	 * @param string $telephone_1 the post telephone_1
	 */
	public function setTelephone1($telephone_1)
	{
		$this->telephone_1 = $telephone_1;
		$this->markDirty();
	}


	/**
	 * Get the post's telephone_1.
	 * @return string telephone_1
	 */
	public function getTelephone1()
	{
		return $this->telephone_1;
	}

	/**
	 * Set the post telephone_2.
	 * @param string $telephone_2 the post telephone_2
	 */
	public function setTelephone2($telephone_2)
	{
		$this->telephone_2 = $telephone_2;
		$this->markDirty();
    }

    /**
	 * Get the post data source id.
	 * @return integer $data_source_id of the post
	 */
	public function getDataSourceId()
	{
		return $this->data_source_id;
    }

    /**
	 * Get the post data source.
	 * @return string $data_source of the post
	 */
	public function getDataSource()
	{
		return $this->data_source;
    }

    /**
	 * Get the post data source changed date.
	 * @return integer $data_source_changed_date of the post
	 */
	public function getDataSourceChangedDate()
	{
		return $this->data_source_changed_date;
    }

    /**
	 * Get the post data source id.
	 * @param integer $data_source_id of the post
	 */
	public function setDataSourceId($data_source_id)
	{
        $this->data_source_id = $data_source_id;
        $this->markDirty();
    }

    /**
	 * Set the post data source.
	 * @param string $data_source of the post
	 */
	public function setDataSource($data_source)
	{
        $this->data_source = $data_source;
        $this->markDirty();
    }


	/**
	 * Set the post additional information.
	 * @param string $name the post additional information.
	 */
	public function setAdditionalInfo($additionalInformation)
	{
		$this->additional_info = $additionalInformation;
		$this->markDirty();
	}

	/**
	 * Return the post additional information.
	 * @return string the post additional information.
	 */
	public function getAdditionalInfo()
	{
		return $this->additional_info;
	}

    /**
	 * Get the post data source changed date.
	 * @return integer $data_source_changed_date of the post
	 */
	public function setDataSourceChangedDate($data_source_changed_date)
	{
        $this->data_source_changed_date = $data_source_changed_date;
        $this->markDirty();
    }

    public static function lookupDataSourcesAll()
    {
        return DB::table('tbl_lkp_data_sources')->where('global', true)->get();
    }

	/**
	 * Get the post's telephone_2.
	 * @return string telephone_2
	 */
	public function getTelephone2()
	{
		return $this->telephone_2;
	}


	/**
	 * Set the post telephone_switchboard.
	 * @param string $telephone_switchboard the post telephone_switchboard
	 */
	public function setTelephoneSwitchboard($telephone_switchboard)
	{
		$this->telephone_switchboard = $telephone_switchboard;
		$this->markDirty();
	}

	/**
	 * Get the post's telephone_switchboard.
	 * @return string telephone_switchboard
	 */
	public function getTelephoneSwitchboard()
	{
		return $this->telephone_switchboard;
	}

	/**
	 * Set the post telephone_fax.
	 * @param string $telephone_fax the post telephone_fax
	 */
	public function setTelephoneFax($telephone_fax)
	{
		$this->telephone_fax = $telephone_fax;
		$this->markDirty();
	}

	/**
	 * Get the post's telephone_fax.
	 * @return string telephone_fax
	 */
	public function getTelephoneFax()
	{
		return $this->telephone_fax;
    }
    
    /**
	 * Set the post telephone_fax.
	 * @param string $telephone_fax the post telephone_fax
	 */
	public function setDataOwnerId($data_owner_id)
	{
		$this->data_owner_id = $data_owner_id;
		$this->markDirty();
	}

	/**
	 * Get the post's telephone_fax.
	 * @return string telephone_fax
	 */
	public function getDataOwnerId()
	{
		return $this->data_owner_id;
	}

	/**
	 * Set the post's deleted flag.
	 * @param string $deleted the post's deleted flag
	 */
	public function setDeleted($deleted)
	{
		$this->deleted = $deleted;
		$this->markDirty();
	}

	/**
	 * Return the post's deleted flag..
	 * @return string the post's deleted flag.
	 */
	public function getDeleted()
	{

		return $this->deleted;
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
	 * Get the post's parent company.
	 * @return app_domain_Company
	 */
//	public function getCompany()
//	{
//		return $this->company;
//	}

	/**
 	 * Find a post by a given ID
	 * @param integer $id post ID
	 * @return app_mapper_PostCollection collection of app_domain_Post objects
	 */
	public static function find($id)
	{

		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
 	 * Find post by a given ID
	 * @param integer $id post ID
	 * @return app_mapper_PostCollection collection of app_domain_Post objects
	 */
	public static function findByCompanyId($company_id)
	{

		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompany($company_id);
	}

	 /** Find post by a given post_initiative_id
	 * @param integer $post_initiative_id post_initiative_id
	 * @return app_domain_Post
	 */
	public static function findByPostInitiativeId($post_initiative_id)
	{

		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeId($post_initiative_id);
	}

//
//	/**
//	 * Find last effective communication for a given post_id
//	 * @param integer $id post ID
//	 * @return array of raw post mapper data
//	 */
//	public static function findLastPostEffective($id)
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->findLastPostEffective($id);
//	}
//


	/**
	 * Find client initiatives for a given post_id
	 * @param integer $id post ID
	 * @return array of raw post mapper data
	 */
	public static function findPostInitiatives($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostInitiatives($id);
	}


	/**
	 * Find client initiatives for a given post_id which are available to the current user
	 * @param integer $id post ID
	 * @return array of raw post mapper data
	 */
	public static function findPostInitiativesForCurrentUser($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostInitiativesForCurrentUser($id);
	}


	/**
	 * Find client initiatives for a given post_id and client_id
	 * @param integer $id post id
	 * @return array of raw post mapper data
	 */
	public static function findPostsByCompanyAndInitiative($company_id, $initiative_id, $post_id = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostsByCompanyAndInitiative($company_id, $initiative_id, $post_id);
	}


	/**
	 * Find client initiatives for a given post_id and client_id available to the current user
	 * @param integer $id post id
	 * @return array of raw post mapper data
	 */
	public static function findPostsByCompanyAndInitiativeForCurrentUser($company_id, $initiative_id, $post_id = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostsByCompanyAndInitiativeForCurrentUser($company_id, $initiative_id, $post_id);
	}

	/**
	 * Find count of meetings for a given post_id
	 * @param integer $id post id
	 * @return array of raw data - single item
	 */
	public static function findMeetingCountByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findMeetingCountByPostId($post_id);
	}

	/**
	 * Find the post associated with a given meeting.
	 * @param integer $meeting meeting ID
	 * @return app_domain_Post
	 */
	public static function findByMeetingId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByMeetingId($post_id);
	}

	/**
	 * Find the decision maker, agency user and review date info associated with a post/campaign combo.
	 * @param integer $post_id post ID
	 * @param integer $campaign_id campaign id
	 * @return app_domain_Post
	 */
	public static function findDisciplinesGridByPostIdAndCampaignId($post_id, $campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDisciplinesGridByPostIdAndCampaignId($post_id, $campaign_id);
	}

	/**
	 * Find the decision maker, agency user and review date info associated with a campaign.
	 * @param integer $campaign_id campaign id
	 * @return app_domain_Post
	 */
	public static function findDisciplinesGridByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDisciplinesGridByCampaignId($campaign_id);
	}

	/**
	 * Find the decision maker, agency user and review date info associated with a post combo
	 * where the disciplines are not part of the campaign
	 * @param integer $post_id post id
	 * @return app_domain_Post
	 */
	public static function findNonCampaignDisciplinesGridByPostId($post_id, $campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findNonCampaignDisciplinesGridByPostId($post_id, $campaign_id);
	}

	/**
	 * Find the decision maker, agency user and review date info associated with a post combo.
	 * @param integer $post_id post id
	 * @return app_domain_Post
	 */
	public static function findDisciplinesGridByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDisciplinesGridByPostId($post_id);
	}

	/**
	 * Count the number of calls in a given period, filtered by client ID.
	 * @param integer $post_id
	 * @param string $start date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $client_id
	 */
	public static function countCallsInPeriod($post_id, $start, $end, $client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countCallsInPeriod($post_id, $start, $end, $client_id);
	}

	/**
	 * Count the number of effectives in a given period, filtered by client ID.
	 * @param integer $post_id
	 * @param string $start date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $client_id
	 * @return integer
	 */
	public static function countEffectivesInPeriod($post_id, $start, $end, $client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countEffectivesInPeriod($post_id, $start, $end, $client_id);
	}

}

?>