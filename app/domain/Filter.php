<?php

/**
 * Defines the app_domain_Filter class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Filter extends app_domain_DomainObject
{
	protected $name;
	protected $description;
	protected $type_id;
	protected $type;
	protected $campaign_id;
	protected $campaign_name;
	protected $results_format;
	protected $is_report_source;
	protected $report_format_source;
	protected $company_count;
	protected $post_count;
	protected $created_at;
	protected $created_by;
	protected $created_by_name;
	protected $updated_at;
	protected $deleted;

	function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Set the filter name
	 * @param string $name of this filter
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/**
	 * Set the filter description (an optional field to describe the filter more fully than in name)
	 * @param string $description of the filter
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		$this->markDirty();
	}

	/**
	 * Set the filter type id.
	 * @param string $type_id of the filter
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}


	/**
	 * Set the filter type.
	 * @param string $type of the filter
	 */
	public function setType($type)
	{
		$this->type = $type;
		$this->markDirty();
	}


	/**
	 * Set the filter campaign id.
	 * @param string $campaign_id of the filter
	 */
	public function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		$this->markDirty();
	}


	/**
	 * Set the filter campaign name.
	 * @param string $campaign_name of the filter
	 */
	public function setCampaignName($campaign_name)
	{
		$this->campaign_name = $campaign_name;
		$this->markDirty();
	}

	/**
	 * Set the filter results format.
	 * @param string $results_format of the filter
	 */
	public function setResultsFormat($results_format)
	{
		$this->results_format = $results_format;
		$this->markDirty();
	}

    /**
     * Set whether the filter should be used as a source for reports.
     * @param string $is_report_source of the filter
     */
    public function setIsReportSource($is_report_source)
    {
        $this->is_report_source = $is_report_source;
        $this->markDirty();
    }

    /**
     * Set whether the report parameter description. This may be used on reports which use this filter
     * as their report source
     * @param string $report_parameter_description of the filter
     */
    public function setReportParameterDescription($report_parameter_description)
    {
        $this->report_parameter_description = $report_parameter_description;
        $this->markDirty();
    }


	/** Set the filter company count statistic - ie the number of companies this filter produced at the date
	 *  it was last run, or when the statistics were last updated
	 * @param number $company_count of the filter
	 */
	public function setCompanyCount($company_count)
	{
		$this->company_count = $company_count;
		$this->markDirty();
	}

	/** Set the filter post count statistic - ie the number of posts this filter produced at the date
	 *  it was last run, or when the statistics were last updated
	 * @param number $post_count of the filter
	 */
	public function setPostCount($post_count)
	{
		$this->post_count = $post_count;
		$this->markDirty();
	}

	/**
	 * Set the date the filter was created
	 * @param string $created_at
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;
		$this->markDirty();
	}

	/**
	 * Set the id of the user who created the filter.
	 * @param number $created_by the id of the user who created the filter
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;
		$this->markDirty();
	}

	/**
	 * Set the string name of the user who created the filter.
	 * @param string $created_by_name the name of the user who created the filter
	 */
	public function setCreatedByName($created_by_name)
	{
		$this->created_by_name = $created_by_name;
		$this->markDirty();
	}

	/** Set the date the filter was last updated (which would include a regen of the stats)
	 * @param string $updated_at
	 */
	public function setUpdatedAt($updated_at)
	{
		$this->updated_at = $updated_at;
		$this->markDirty();
	}


	/**
	 * Get the filter name
	 * @return string $name of this filter
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the filter description (an optional field to describe the filter more fully than in name)
	 * @return string $description of this filter
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Get the filter type id.
	 * @return string $type_id of the filter
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}


	/**
	 * Get the filter type.
	 * @return string $type of the filter
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get the filter campaign id.
	 * @return string $campaign_id of the filter
	 */
	public function getCampaignId()
	{
		return $this->campaign_id;
	}


	/**
	 * Get the filter campaign name.
	 * @return string $campaign_name of the filter
	 */
	public function getCampaignName()
	{
		return $this->campaign_name;
	}


	/**
	 * Get the filter results format.
	 * @return string $results_format of the filter
	 */
	public function getResultsFormat()
	{
		return $this->results_format;
	}

    /**
     * Get the filter is_report_source value.
     * @return string $is_report_source of the filter
     */
    public function getIsReportSource()
    {
        return $this->is_report_source;
    }

    /**
     * Get the filter report_parameter_description value.
     * @return string $report_parameter_description of the filter
     */
    public function getReportParameterDescription()
    {
        return $this->report_parameter_description;
    }

	/**
	 * Get the filter company count statistic - ie the number of companies this filter produced at the date
	 *  it was last run, or when the statistics were last updated
	 * @return string $company_count of the filter
	 */
	public function getCompanyCount()
	{
		return $this->company_count;
	}

	/**
	 * Get the filter post count statistic - ie the number of posts this filter produced at the date
	 *  it was last run, or when the statistics were last updated
	 * @return string $post_count of the filter
	 */
	public function getPostCount()
	{
		return $this->post_count;
	}

 	/** Return the date the filter was created
	 * @return string $created_at
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

	/**
	 * Return the id of the user who created the filter.
	 * @return number $created_by the id of the user who created the filter
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}

	/**
	 * Return the name of the user who created the filter.
	 * @return number $created_by the id of the user who created the filter
	 */
	public function getCreatedByName()
	{
		return $this->created_by_name;
	}

 	/** Return the date the filter was last updated
	 * @return string $updated_at
	 */
	public function getUpdatedAt()
	{
		return $this->updated_at;
	}


	/**
 	 * Find a filter by a given ID
	 * @param integer $id filter ID
	 * @return app_mapper_FilterCollection collection of app_domain_Filter objects
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
	 * Find by user id
	 * @param integer $user_id
	 * @return filter domain collection
	 */
	public static function findPersonalByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPersonalByUserId($user_id);
	}


	/**
	 * Find client filters which are avaiable to the specified user id
	 * @param integer $user_id
	 * @return filter domain collection
	 */
	public static function findCampaignFiltersByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCampaignFiltersByUserId($user_id);
	}

	/**
     * Find rport source filters by client (to which an NBM has access)
     * @param integer $client_id
     * @param integer $user_id
     * @return filter domain collection
     */
    public static function findReportSourceFiltersByClientIdAndUserId($client_id, $user_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findReportSourceFiltersByClientIdAndUserId($client_id, $user_id);
    }


	/** Find global filters
	 * @return filter domain collection
	 **/
	public static function findGlobalFilters()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findGlobalFilters();
	}

	/**
	 * Find by user id
	 * @param integer $user_id
	 * @return filter domain collection
	 */
	public static function findDeletedPersonalByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDeletedPersonalByUserId($user_id);
	}


	/**
	 * Find client filters which are avaiable to the specified user id
	 * @param integer $user_id
	 * @return filter domain collection
	 */
	public static function findDeletedCampaignFiltersByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDeletedCampaignFiltersByUserId($user_id);
	}

	/** Find global filters
	 * @return filter domain collection
	 **/
	public static function findDeletedGlobalFilters()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findDeletedGlobalFilters();
	}

	/**
	 * Find all children filter lines for a given filter id
	 * @param integer $id - id of the filter
	 * @return filter line domain collection
	 */
	public static function findFilterLinesByFilterId($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findFilterLinesByFilterId($id);
	}


	/**
	 * Find all children filter lines for a given filter id and a direction (eg include/exclude)
	 * @param integer $id - id of the filter
	 * @param string $direction - direction of the lines to include
	 * @return filter line domain collection
	 */
	public static function findFilterLinesByFilterIdAndDirection($id, $direction)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findFilterLinesByFilterIdAndDirection($id, $direction);
	}


	/**
	 * delete all children filter lines for a given filter id and a direction
	 * @param integer $id - id of the filter
	 * @param integer $direction - direction of the children filter lines to delete (include or exclude)
	 * @return boolean $true/false
	 */
	public static function deleteFilterLinesByIdAndDirection($id, $direction)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->deleteFilterLinesByIdAndDirection($id, $direction);
	}


}

?>
