<?php

/**
 * Defines the app_domain_CampaignNbmTarget class.
 *
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_CampaignNbmTarget extends app_domain_DomainObject
{
	/**
//	 * Field spec and validation rules.
//	 */
//	protected $spec;


	protected $campaign_id;
	protected $user_id;
	protected $year_month;
	protected $planned_days;
	protected $project_management_days;
	protected $effectives;
	protected $meetings_set;
	protected $meetings_set_imperative;
	protected $meetings_attended;


	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);

		if ($this->id)
		{

		}
	}

	/** Sets the $user_id of the nbm target
	 * @param integer $user_id
	 */
	function setUserId($user_id)
	{
		$this->user_id = $user_id;
		$this->markDirty();
	}

	/** Gets the user_id of the nbm target
	 * @return integer - $user_id - campaign_id of the nbm target
	 */
	function getUserId()
	{
		return $this->user_id;
	}


	/** Sets the campaign_id of the nbm target
	 * @param integer $campaign_id
	 */
	function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		$this->markDirty();
	}

	/** Gets the campaign_id of the nbm target
	 * @return integer - $campaign_id - campaign_id of the nbm target
	 */
	function getCampaignId()
	{
		return $this->campaign_id;
	}


	/** Sets the year_month of the campaign target
	 * @param string $year_month
	 */
	function setYearMonth($year_month)
	{
		$this->year_month = $year_month;
		$this->markDirty();
	}

	/** Gets the the year_month of the campaign target
	 * @return string $year_month - year_month of the campaign target
	 */
	function getYearMonth()
	{
		return $this->year_month;
	}


	/** Sets the planned_days of the nbm target
	 * @param integer $planned_days
	 */
	function setPlannedDays($planned_days)
	{
		$this->planned_days = $planned_days;
		$this->markDirty();
	}

	/** Gets the $planned_days of the nbm target
	 * @return integer - $planned_days - planned_days of the nbm target
	 */
	function getPlannedDays()
	{
		return $this->planned_days;
	}

	/** Sets the project_management_days of the nbm target
	 * @param integer $project_management_days
	 */
	function setProjectManagementDays($project_management_days)
	{
		$this->project_management_days = $project_management_days;
		$this->markDirty();
	}

	/** Gets the project_management_days of the nbm target
	 * @return integer - $project_management_days - project_management_days of the nbm target
	 */
	function getProjectManagementDays()
	{
		return $this->project_management_days;
	}

	/** Sets the effectives of the nbm target
	 * @param integer $effectives
	 */
	function setEffectives($effectives)
	{
		$this->effectives = $effectives;
		$this->markDirty();
	}

	/** Gets the effectives of the nbm target
	 * @return integer - $effectives - effectives of the nbm target
	 */
	function getEffectives()
	{
		return $this->effectives;
	}

	/**
	 * Sets the number of meetings set in a year_month period for an nbm campaign.
	 * @param integer $meetings_set
	 */
	function setMeetingsSet($meetings_set)
	{
		$this->meetings_set = $meetings_set;
		$this->markDirty();
	}

	/**
	 * Gets the number of meetings set in a year_month period for an nbm campaign.
	 * @return integer - number of meetings set in a year_month period for a an nbm campaign.
	 */
	function getMeetingsSet()
	{
		return $this->meetings_set;
	}

	/** Sets the number of $meetings_set_imperative in a year_month period for an nbm campaign.
	 * @param integer $meetings_set_imperative
	 */
	function setMeetingsSetImperative($meetings_set_imperative)
	{
		$this->meetings_set_imperative = $meetings_set_imperative;
		$this->markDirty();
	}

	/**
	 * Gets the number of $meetings_set_imperative in a year_month period for an nbm campaign.
	 * @return integer - number of $meetings_set_imperative in a year_month period for an nbm campaign.
	 */
	function getMeetingsSetImperative()
	{
		return $this->meetings_set_imperative;
	}

	/**
	 * Sets the number of meetings attended in a year_month period for an nbm campaign.
	 * @param integer $meetings_attended
	 */
	function setMeetingsAttended($meetings_attended)
	{
		$this->meetings_attended = $meetings_attended;
		$this->markDirty();
	}

	/**
	 * Gets the number of meetings attended in a year_month period for an nbm campaign.
	 * @return integer - number of meetings attended in a year_month period for an nbm campaign.
	 */
	function getMeetingsAttended()
	{
		return $this->meetings_attended;
	}

    /**
     * Check the correct NBM targets exist in the database, if not insert them
     *
     * @param int $campaignId Campaign
     *
     * @return bool
     */
    public static function checkNbmTargets($campaignId)
    {
        $team    = app_domain_CampaignNbm::findByCampaignId($campaignId);
        $targets = app_domain_CampaignTarget::findByCampaignId($campaignId);
        foreach ($team as $member)
        {
            foreach ($targets as $target)
            {
                $nbmTarget = self::findByCampaignIdUserIdAndYearMonth(
                    $campaignId,
                    $member->getId(),
                    $target->getYearMonth()
                );
            }
        }
    }

// 	/**
// 	 * Responsible for deleting any existing campaign nbm target periods and replacing with the latest campaign target periods
// 	 * @param integer $campaign_id
// 	 */
// 	public static function copyCampaignTargetPeriodsToCampaignNbmTargets($campaign_id)
// 	{
// 		$finder = self::getFinder(__CLASS__);
// 		return $finder->copyCampaignTargetPeriodsToCampaignNbmTargets($campaign_id);
// 	}


	/**
	* Find the latest period for a campaign nbm target
	* @param integer $id campaign target id
	* @param integer $id campaign target user_id
	* @return app_mapper_NbmCampaignTargetCollection collection of app_domain_CampaignNbmTarget objects
	*/
	public static function findLatestTargetPeriodByCampaignIdAndUserId($campaign_id, $user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findLatestTargetPeriodByCampaignIdAndUserId($campaign_id, $user_id);
	}

	/**
 	 * Find a campaign target by a given id
	 * @param integer $id campaign target id
	 * @return app_mapper_NbmCampaignTargetCollection collection of app_domain_CampaignNbmTarget objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
 	 * Find all nbm campaign targets
	 * @return app_mapper_NbmCampaignTargetCollection collection of app_domain_CampaignNbmTarget objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
 	 * Find all nbm campaign targets by campaign_id, user_id and year_month
	 * @return app_mapper_NbmCampaignTargetCollection collection of app_domain_CampaignNbmTarget objects
	 */
	public static function findByCampaignIdUserIdAndYearMonth($campaign_id, $user_id, $year_month)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCampaignIdUserIdAndYearMonth($campaign_id, $user_id, $year_month);
	}

    /**
     * Find max year_month for campaigns by user_id
     * @return app_mapper_NbmCampaignTargetCollection collection of app_domain_CampaignNbmTarget objects
     */
    public static function findMaxYearMonthByUserId($user_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findMaxYearMonthByUserId($user_id);
    }



	/**
 	 * Find all nbm campaign targets and monthly planning data (from tbl_data_statistics) by id (in tbl_campaign_nbm_targets)
 	 * @param integer $id
 	 * @return array of mdb2 results set
	 */
	public static function findStatisticsById($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findStatisticsById($id);
	}

	/**
 	 * Find all nbm campaign targets and monthly planning data (from tbl_data_statistics) by user_id and year_month
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return array of mdb2 results set
	 */
	public static function findStatisticsByUserIdAndYearMonth($user_id, $year_month)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findStatisticsByUserIdAndYearMonth($user_id, $year_month);
	}

	/**
 	 * Find all nbm campaign targets and monthly planning data (from tbl_data_statistics) by user_id and year_month
 	 * where effectives and meetings set targets are both zero
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return array of mdb2 results set
	 */
	public static function findStatisticsZeroTargetsByUserIdAndYearMonth($user_id, $year_month)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findStatisticsZeroTargetsByUserIdAndYearMonth($user_id, $year_month);
	}

	/**
 	 * Find all nbm campaign targets and monthly planning data (from tbl_data_statistics) by user_id and year_month
 	 * where effectives and meetings set targets are both > zero
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return array of mdb2 results set
	 */
	public static function findStatisticsNonZeroTargetsByUserIdAndYearMonth($user_id, $year_month)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findStatisticsNonZeroTargetsByUserIdAndYearMonth($user_id, $year_month);
	}

	/**
 	 * Find total of all nbm campaign targets and monthly planning data (from tbl_data_statistics) by user_id and year_month
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return array of mdb2 results set
	 */
	public static function findTotalStatisticsByUserIdAndYearMonth($user_id, $year_month)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findTotalStatisticsByUserIdAndYearMonth($user_id, $year_month);
	}

    /**
     * Find project management days by nbm and year_month
     * @param integer $user_id
     * @param string $year_month in the format 'YYYYMM'
     * @return array of mdb2 results set
     */
    public static function findTotalProjectManagementDaysByUserIdAndYearMonth($user_id, $year_month)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findTotalProjectManagementDaysByUserIdAndYearMonth($user_id, $year_month);
    }

    /**
	 * Commit any changes to the object to the database.
	 */
	public function commit()
	{
		return parent::commit($this);
	}

}

?>