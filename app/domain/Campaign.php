<?php

require_once('app/domain/DomainObject.php');
require_once('app/domain/Client.php');

/**
 * @package Alchemis
 */
class app_domain_Campaign extends app_domain_DomainObject
{
	private $client_name;
	private $client_id;
	private $type_id;
	private $type_name;
	private $start_year_month;
	private $end_year_month;
	private $duration;
	private $initial_fee;
	private $current_fee;
	private $contract_sent_date;
	private $contract_received_date;
	private $so_form_received_date;
	private $billing_terms_id;
	private $billing_terms;
	private $payment_terms_id;
	private $payment_terms;
	private $payment_method_id;
	private $payment_method;
	private $minimum_duration;
	private $notice_period;
	private $notice_date;
	private $additional_terms_exist;
	private $created_at;
	private $created_by;
	private $created_by_name;
	
	function __construct($id = null, $name = 'main')
	{
		parent::__construct($id);
	}
	
	/**
	 * Set the campaign's parent client id.
	 * @param integer $client_id the parent client
	 */
	public function setClientId($client_id)
	{
		$this->client_id = $client_id;
		$this->markDirty();
	}

	/**
	 * Get the campaign's parent client id.
	 * @return client_id
	 */
	public function getClientId()
	{
		return $this->client_id;
	}

	/**
	 * Set the campaign's type id.
	 * @param integer $type_id the type id
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}

	/**
	 * Get the campaign's type id.
	 * @return type_id
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}
	
	/**
	 * Set the campaign's type name.
	 * @param string $type_name the type name
	 */
	public function setTypeName($type_name)
	{
		$this->type_name = $type_name;
		$this->markDirty();
	}

	/**
	 * Get the campaign's type name.
	 * @return type_name
	 */
	public function getTypeName()
	{
		return $this->type_name;
	}
	
	/**
	 * Set the campaign's parent client name.
	 * @param integer $client_name the parent client
	 */
	public function setClientName($client_name)
	{
		$this->client_name = $client_name;
		$this->markDirty();
	}
	
	/**
	 * Get the campaign's parent client name.
	 * @return string the parent client name
	 */
	public function getClientName()
	{
		return $this->client_name;
	}

	/**
	 * Set the campaign's start year/month.
	 * @param integer $start_year_month - the year/month in which work is first done on the campaign
	 */
	public function setStartYearMonth($start_year_month)
	{
		$this->start_year_month = $start_year_month;
		$this->markDirty();
	}
	
	/**
	 * Get the campaign's start year/month.
	 * @return string the start year/month of the campaign.
	 */
	public function getStartYearMonth()
	{
		return $this->start_year_month;
	}
	
	
	/**
	 * Set the campaign's end year/month.
	 * @param integer $end_year_month - the year/month in which work is last done on the campaign
	 */
	public function setEndYearMonth($end_year_month)
	{
		$this->end_year_month = $end_year_month;
		$this->markDirty();
	}
	
	/**
	 * Get the campaign's end year/month.
	 * @return string the end year/month of the campaign.
	 */
	public function getEndYearMonth()
	{
		return $this->end_year_month;
	}
	

	/**
	 * Set the campaign's minimum duration in months
	 * @param integer $duration the campaign's minimum duration in months
	 */
	public function setDuration($duration)
	{
		$this->duration = $duration;
		$this->markDirty();
	}
	
	/**
	 * Get the campaign's minimum duration in months
	 * @return integer the minimum duration in months
	 */
	public function getDuration()
	{
		return $this->duration;
	}


	/**
	 * Set the campaign's initial fee 
	 * @param integer $initial_fee the campaign's initial fee
	 */
	public function setInitialFee($initial_fee)
	{
		$this->initial_fee = $initial_fee;
		$this->markDirty();
	}
	
	/**
	 * Get the campaign's initial fee 
	 * @return integer the campaign's initial fee 
	 */
	public function getInitialFee()
	{
		return $this->initial_fee;
	}
	
	
	/**
	 * Set the campaign's current fee 
	 * @param integer $current_fee the campaign's current fee
	 */
	public function setCurrentFee($current_fee)
	{
		$this->current_fee = $current_fee;
		$this->markDirty();
	}
	
	/**
	 * Get the campaign's current fee 
	 * @return integer the campaign's current fee 
	 */
	public function getCurrentFee()
	{
		return $this->current_fee;
	}
	
	/**
	 * Set the date the campaign contract was sent 
	 * @param string $contract_sent_date - the date the campaign contract was sent 
	 */
	public function setContractSentDate($contract_sent_date)
	{
		$this->contract_sent_date = $contract_sent_date;
		$this->markDirty();
	}
	
	/**
	 * Get the date the campaign contract was sent 
	 * @return string - the date the campaign contract was sent  
	 */
	public function getContractSentDate()
	{
		return $this->contract_sent_date;
	}
	
	/**
	 * Set the date the campaign contract was received 
	 * @param string $contract_received_date - the date the campaign contract was received 
	 */
	public function setContractReceivedDate($contract_received_date)
	{
		$this->contract_received_date = $contract_received_date;
		$this->markDirty();
	}
	
	/**
	 * Get the date the campaign contract was received 
	 * @return string - the date the campaign contract was received  
	 */
	public function getContractReceivedDate()
	{
		return $this->contract_received_date;
	}
	
	/**
	 * Set the date the campaign so form was received 
	 * @param string $so_form_received_date - the date the campaign so form was received 
	 */
	public function setSoFormReceivedDate($so_form_received_date)
	{
		$this->so_form_received_date = $so_form_received_date;
		$this->markDirty();
	}
	
	/**
	 * Get the date the campaign contract was received 
	 * @return string - the date the campaign so form was received 
	 */
	public function getSoFormReceivedDate()
	{
		return $this->so_form_received_date;
	}
	
	/**
	 * Set the billing terms id of the campaign 
	 * @param integer $billing_terms_id - the billing terms id of the campaign
	 */
	public function setBillingTermsId($billing_terms_id)
	{
		$this->billing_terms_id = $billing_terms_id;
		$this->markDirty();
	}
	
	/**
	 * Get the billing terms id of the campaign
	 * @return integer - the billing terms id of the campaign
	 */
	public function getBillingTermsId()
	{
		return $this->billing_terms_id;
	}
	
	/**
	 * Set the billing terms of the campaign 
	 * @param integer $billing_terms - the billing terms of the campaign
	 */
	public function setBillingTerms($billing_terms)
	{
		$this->billing_terms = $billing_terms;
		$this->markDirty();
	}
	
	/**
	 * Get the billing terms of the campaign
	 * @return integer - the billing terms of the campaign
	 */
	public function getBillingTerms()
	{
		return $this->billing_terms;
	}
	
	/**
	 * Set the payment terms id of the campaign 
	 * @param integer $payment_terms_id - the payment terms id of the campaign
	 */
	public function setPaymentTermsId($payment_terms_id)
	{
		$this->payment_terms_id = $payment_terms_id;
		$this->markDirty();
	}
	
	/**
	 * Get the payment terms id of the campaign
	 * @return integer - the payment terms id of the campaign
	 */
	public function getPaymentTermsId()
	{
		return $this->payment_terms_id;
	}
	
	/**
	 * Set the payment terms of the campaign 
	 * @param integer $payment_terms - the payment terms of the campaign
	 */
	public function setPaymentTerms($payment_terms)
	{
		$this->payment_terms = $payment_terms;
		$this->markDirty();
	}
	
	/**
	 * Get the payment terms of the campaign
	 * @return integer - the payment terms of the campaign
	 */
	public function getPaymentTerms()
	{
		return $this->payment_terms;
	}
	
	/**
	 * Set the payment method id of the campaign 
	 * @param integer $payment_method_id - payment method id of the campaign 
	 */
	public function setPaymentMethodId($payment_method_id)
	{
		$this->payment_method_id = $payment_method_id;
		$this->markDirty();
	}
	
	/**
	 * Get the payment method id of the campaign 
	 * @return integer - the payment method id of the campaign 
	 */
	public function getPaymentMethodId()
	{
		return $this->payment_method_id;
	}	
	
	/**
	 * Set the payment method of the campaign 
	 * @param integer $payment_method - payment method of the campaign 
	 */
	public function setPaymentMethod($payment_method)
	{
		$this->payment_method = $payment_method;
		$this->markDirty();
	}
	
	/**
	 * Get the payment method of the campaign 
	 * @return integer - the payment method of the campaign 
	 */
	public function getPaymentMethod()
	{
		return $this->payment_method;
	}	
	
	/**
	 * Set the minimum duration of the campaign 
	 * @param integer $minimum_duration - minimum duration of the campaign 
	 */
	public function setMinimumDuration($minimum_duration)
	{
		$this->minimum_duration = $minimum_duration;
		$this->markDirty();
	}
	
	/**
	 * Get the minimum duration of the campaign 
	 * @return integer - the minimum duration of the campaign 
	 */
	public function getMinimumDuration()
	{
		return $this->minimum_duration;
	}	
	
	/**
	 * Set the notice period of the campaign 
	 * @param integer $notice_period - the notice period of the campaign
	 */
	public function setNoticePeriod($notice_period)
	{
		$this->notice_period = $notice_period;
		$this->markDirty();
	}
	
	/**
	 * Get the notice period of the campaign
	 * @return integer - the notice period of the campaign
	 */
	public function getNoticePeriod()
	{
		return $this->notice_period;
	}	
	
	/** Set the date notice was given on campaign termination
	 * @param string $notice_date - the date notice was given on campaign termination 
	 */
	public function setNoticeDate($notice_date)
	{
		$this->notice_date = $notice_date;
		$this->markDirty();
	}
	
	/**
	 * Get the date notice was given on campaign termination
	 * @return string - the date notice was given on campaign termination 
	 */
	public function getNoticeDate()
	{
		return $this->notice_date;
	}
		
	/**
	 * Set the additional terms exist flag for the campaign 
	 * @param integer $additional_terms_exist - the additional terms exist flag for the campaign 
	 */
	public function setAdditionalTermsExist($additional_terms_exist)
	{
		$this->additional_terms_exist = $additional_terms_exist;
		$this->markDirty();
	}
	
	/**
	 * Get the additional terms exist flag for the campaign 
	 * @return integer - the additional terms exist flag for the campaign 
	 */
	public function getAdditionalTermsExist()
	{
		return $this->additional_terms_exist;
	}	
	
	/**
	 * Set the time when created.
	 * @param string $created_at
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;
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
	 * Set the ID of the user who created the campaign.
	 * @param integer $created_by
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;
		$this->markDirty();
	}

	/**
	 * Return the ID of the user who created the campaign.
	 * @return integer
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}
	
	
	/**
	 * Set the name of the user who created the campaign.
	 * @param integer $created_by_name
	 */
	public function setCreatedByName($created_by_name)
	{
		$this->created_by_name = $created_by_name;
		$this->markDirty();
	}

	/**
	 * Return the name of the user who created the campaign.
	 * @return integer
	 */
	public function getCreatedByName()
	{
		return $this->created_by_name;
	}
	
	
	/**
	 * Find 
	 * @return app_mapper_CampaignCollection
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_CampaignMapper
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	* Finds campaigns by client id
	* @return array of raw post mapper data
	*/
	public static function findByClientId($client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByClientId($client_id);
	}
	
	
	/**
 	 * Find campaign id by client id
 	 * @param integer $client_id client id
	 * @return single item
	 */
	public static function findIdByClientId($client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findIdByClientId($client_id);
	}
	
	/**
 	 * Find all campaigns by user
 	 * @param integer $user_id user id
	 * @return app_mapper_CampaignCollection collection of app_domain_Campaign objects
	 */
	public static function findByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByUserId($user_id);
	}
	
	
	
	/** Find all campaigns the current user
 	 * @return app_mapper_CampaignCollection collection of app_domain_Campaign objects
	 */
	public static function findByCurrentUserId()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCurrentUserId();
	}
	
	/**
	 * Gets the campaign parent client name.
	 * @param integer $id the client id
	 * @return string
	 */
	public static function findLatestTargetPeriodByCampaignId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findLatestTargetPeriodByCampaignId($id);
	}

	/**
	 * Find the progress of campaigns associated with a user.
	 * @param integer $user_id
	 * @return array
	 */
	public static function findProgressByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findProgressByUserId($user_id);
	}
	
	/**
	 * Find the list of marketing services from tbl_tiered_characteristics.
	 * @return array
	 */
	public static function findAllDisciplines()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAllDisciplines();
	}

	/**
	 * Find the list of marketing services for this campaign (ie assigned to this campaign).
	 * @param integer $campaign_id
	 * @return array
	 */
	public static function findDisciplines($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDisciplines($campaign_id);
	}
		
	/**
 	 * Find campaign_id by initiative_id
 	 * @param integer $initiative_id initiative id
	 * @return single value
	 */
	public static function findCampaignIdByInitiativeId($initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCampaignIdByInitiativeId($initiative_id);
	}
	
	/**
	 * Find the list of marketing services for the parent campaign of this initiative 
	 * (ie assigned to the parent campaign).
	 * @param integer $initiative_id
	 * @return array
	 */
	public static function findDisciplinesByInitiativeId($initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDisciplinesByInitiativeId($initiative_id);
	}
			
	/**
	 * Find the list of marketing services available for this campaign (ie not yet assigned to this campaign).
	 * @param integer $campaign_id
	 * @return array
	 */
	public static function findAvailableDisciplines($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAvailableDisciplines($campaign_id);
	}
	
	/**
	 * Find the list of marketing services available for the parent campaign of this initiative 
	 * (ie not yet assigned to the parent campaign).
	 * @param integer $initiative_id
	 * @return array
	 */
	public static function findAvailableDisciplinesByInitiativeId($initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAvailableDisciplinesByInitiativeId($initiative_id);
	}
	
	/**
	 * 
	 * @return raw data - array
	 */
	public static function findCampaignDisciplineRecordsByCampaignIdPostId($campaign_id, $post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCampaignDisciplineRecordsByCampaignIdPostId($campaign_id, $post_id);
	}
	
	/**
	 * Find campaign type lookup information
	 * @return array
	 */
	public static function lookupCampaignTypeOptions()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCampaignTypeOptions();
	}
	
	/** Find billing terms lookup information
	 * @return array
	 */
	public static function lookupBillingTermsOptions()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupBillingTermsOptions();
	}
	
	/** Find payment terms lookup information
	 * @return array
	 */
	public static function lookupPaymentTermsOptions()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupPaymentTermsOptions();
	}
	
	/** Find payment methods lookup information
	 * @return array
	 */
	public static function lookupPaymentMethodsOptions()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupPaymentMethodsOptions();
	}

	/**
	 * Returns the date of the last effective made on a given campaign.
	 * @param integer $campaign_id
	 * @return string datetime in the format 'YYYY-MM-DD HH:MM:SS'
	 */
	public static function findLastEffectiveDate($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findLastEffectiveDate($campaign_id);
	}

	/**
	 * Gets the current prospect status for a given campaign.
	 * @param integer $campaign_id
	 * @return array
	 */
	public static function getProspectsStatuses($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getProspectsStatuses($campaign_id);
	}

	/**
	 * Get the infromation request stats for a given campaign 
	 * @param integer $campaign_id
	 * @return array
	 */
	public static function getInformationRequestSummary($client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getInformationRequestSummary($client_id);
	}

	/**
	 * Return the number of calls made between two dates, optionally fitered to a given campaign.
	 * @param string $start
	 * @param string $end
	 * @param integer $campaign_id
	 * @return integer
	 */
	public static function getCallCount($start, $end, $campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getCallCount($start, $end, $campaign_id);
	}


	/**
	 * Make standard dm notes for a campaign/post.
	 * @param string $start
	 * @param string $end
	 * @param integer $campaign_id
	 * @return integer
	 */
	public static function makeDecisionMakerAndAgencyUserStandardNote($discipline)
	{
		$discipline_note = '';
		
		$d = $discipline['decision_maker_type'];
		if (!is_null($d))
		{
			switch ($d)
			{
				case 'Yes':
					$discipline_note .= ' is a decision maker';
					break;
				case 'No':
					$discipline_note .= ' is not a decision maker';
					break;
				default:
					break;
			}
		}
		
		if ($discipline_note != '')
		{
			$discipline_note .= ' and';
		}
					
		$d = $discipline['agency_user_type'];
		if (!is_null($d))
		{
			switch ($d)
			{
				case 'Yes':
					if ($discipline_note != '')
					{
						$discipline_note .= ' an agency user.';
					}
					else
					{
						$discipline_note .= ' is an agency user.';
					}
					break;
				case 'No':
					$discipline_note .= ' is not an agency user.';
					break;
				case 'Project frequent':
				case 'Project infrequent':
				case 'Retained frequent':
					$discipline_note .= ' uses agencies for ad-hoc work on the basis of \'' . $d . '\'.';
				default:
					break;
			}
		}
		
		// strip last 'and' and replace with a fullstop
		if (substr($discipline_note, -4) == ' and')
		{
			$discipline_note = substr($discipline_note, 0, -4) . '.';
		}
		
		$d = $discipline['review_date'];
		if (!is_null($d))
		{
			if ($discipline_note != '')
			{
				$discipline_note .= ' A review is expected around ' . date('F Y', strtotime($d)) . '.';
			}
			else
			{
				$discipline_note .= ' is expecting a review around ' . date('F Y', strtotime($d)) . '.';
			}
		}
		
		return $discipline_note;
	}

	 /**
	 * Make standard agency notes for a campaign/post.
	 * @param array $post_incumbent_agencies
	 * @param string $contact_first_name
	 * @return string
	 */
	public static function makeIncumbentAgencyStandardNote($post_incumbent_agencies, $contact_first_name)
	{
		$discipline_note = '';
		
		$agency_count = count($post_incumbent_agencies->toRawArray());
			
		if ($agency_count > 0)
		{
			if ($agency_count > 1)
			{
				if ($discipline_note != '')
				{
					$discipline_note .= ' We were also able to confirm that the following agencies are used:';
				}
				else
				{
					$discipline_note .= ' we were able to confirm that the following agencies are used:';
				}
				
				foreach ($post_incumbent_agencies as $post_incumbent_agency)
				{
					$discipline_note .= ' \'' . $post_incumbent_agency->getAgencyCompanyName() . '\',';
				}
			
				$discipline_note = substr($discipline_note, 0, -1) . '.';
			}
			else
			{
					
				foreach ($post_incumbent_agencies as $post_incumbent_agency)
				{
					$incumbent_agency_name = $post_incumbent_agency->getAgencyCompanyName();
				}
			
				if ($discipline_note != '')
				{
					$discipline_note .= ' We were also able to confirm that ' . $contact_first_name . 
										' uses \'' .$incumbent_agency_name . '\' as an agency.';
				}
				else
				{
					$discipline_note .= ' confirmed that \'' .$incumbent_agency_name . '\' is currently an incumbent agency.';
				}
			}
			
		}

		return $discipline_note;
	}
}

?>