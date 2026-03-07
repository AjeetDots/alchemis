<?php

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_CmCampaign extends app_domain_DomainObject
{

    const RESULTS_PER_PAGE = 1000;

    protected $id;
    private $created;
    private $cm_name;
    private $cm_id;
    private $total_recipients;
    private $processed;
    private $last_stats_import;
    private $stats_updated_opens;
    private $stats_updated_subscriberClick;
    private $tag_open_id;
    private $tag_click_id;
    private $filter_id;

    /**
     * Campaign monitor to project reference oboject
     * @param app_domain_CmToProjectRel
     */
    protected $cmRel;

    function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * Set the time when created.
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
        $this->markDirty();
    }

    /**
     * Return the time when created.
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the campaign monitor campaign name.
     * @param string $cm_name
     */
    public function setCmName($cm_name)
    {
        $this->cm_name = $cm_name;
        $this->markDirty();
    }

    /**
     * Return the campaign monitor campaign name.
     * @return string
     */
    public function getCmName()
    {
        return $this->cm_name;
    }

    /**
     * Get the cm rel object
     *
     * @return app_domain_CmToProjectRel
     */
    protected function _getCmRel()
    {
        if (null === $this->cmRel)
        {
            $this->cmRel = new app_domain_CmToProjectRel;
        }
        return $this->cmRel;
    }

    /**
     * Set the campaign monitor id
     * @param string $cm_id
     */
    public function setCmId($cm_id)
    {
        $this->cm_id = $cm_id;
        $this->markDirty();
    }

    /**
     * Return the campaign monitor id
     * @return string
     */
    public function getCmId()
    {
        return $this->cm_id;
    }

    /**
     * Set the total recipients.
     * @param string $total_recipients
     */
    public function setTotalRecipients($total_recipients)
    {
        $this->total_recipients = $total_recipients;
        $this->markDirty();
    }

    /**
     * Return the total recipients.
     * @return string
     */
    public function getTotalRecipients()
    {
        return $this->total_recipients;
    }

    /**
     * Set if the campapign has been processed
     * @param int $processed
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;
        $this->markDirty();
    }

    /**
     * Return if the campapign has been processed
     * @return int
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * Set the time when created.
     * @param string $last_stats_import
     */
    public function setLastStatsImport($last_stats_import)
    {
        $this->last_stats_import = $last_stats_import;
        $this->markDirty();
    }

    /**
     * Return the time when created.
     * @return string
     */
    public function getLastStatsImport()
    {
        return $this->last_stats_import;
    }

    /**
     * Set the total number of clicks last imported
     * @param integer $stats_updated_subscriberClick
     */
    public function setStatsUpdatedSubscriberClick($stats_updated_subscriberClick)
    {
        $this->stats_updated_subscriberClick = $stats_updated_subscriberClick;
        $this->markDirty();
    }

    /**
     * Return the total number of opens last imported
     * @return integer
     */
    public function getStatsUpdatedSubscriberClick()
    {
        return $this->stats_updated_subscriberClick;
    }

    /**
     * Set the total number of opens last imported
     * @param integer $stats_updated_opens
     */
    public function setStatsUpdatedOpens($stats_updated_opens)
    {
        $this->stats_updated_opens = $stats_updated_opens;
        $this->markDirty();
    }

    /**
     * Return the total number of opens last imported
     * @return integer
     */
    public function getStatsUpdatedOpens()
    {
        return $this->stats_updated_opens;
    }

    /**
     * Set the tag open id
     * @param integer $tag_open_id
     */
    public function setTagOpenId($tag_open_id)
    {
        $this->tag_open_id = $tag_open_id;
        $this->markDirty();
    }

    /**
     * Return the tag open id
     * @return integer
     */
    public function getTagOpenId()
    {
        return $this->tag_open_id;
    }

    /**
     * Set the tag click id
     * @param integer $tag_click_id
     */
    public function setTagClickId($tag_click_id)
    {
        $this->tag_click_id = $tag_click_id;
        $this->markDirty();
    }

    /**
     * Return the tag click id
     * @return integer
     */
    public function getTagClickId()
    {
        return $this->tag_click_id;
    }

    /**
     * Set the filter Id
     * @param integer $filter_id
     */
    public function setFilterId($filter_id)
    {
        $this->filter_id = $filter_id;
        $this->markDirty();
    }

    /**
     * Return the filter Id
     * @return integer
     */
    public function getFilterId()
    {
        return $this->filter_id;
    }

    /**
     *
     * @return app_mapper_CmCampaignMapper
     */
    public static function findAll()
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findAll();
    }

    /**
     *
     * @param integer $id
     * @return app_mapper_CmCampaignMapper
     */
    public static function find($id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->find($id);
    }

    /**
     * @param string $cm_id
     * @return app_mapper_CmCampaignMapper
     */
    public static function findByCmId($cm_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findByCmId($cm_id);
    }

    /**
     * Get the latest page of stats that are needed for the campaign
     *
     * @param type $total
     * @return \stdClass
     */
    public function getPage($total)
    {

        $data = new stdClass();
        if (null === $total)
        {
            $data->page = 1;
            $data->item = 1;
            return $data;
        }

        $pageDiv    = $total / self::RESULTS_PER_PAGE;
        $curPage    = floor($pageDiv);
        $data->page = ( $curPage ) + 1;
        $data->item = ( $total - ( $curPage * self::RESULTS_PER_PAGE ) ) + 1;

        return $data;
    }

    /**
     *
     * update CM stats
     *
     * @return boolean
     */
    public function updateStats()
    {

        require_once 'include/campaignmonitor/csrest_campaigns.php';
        
        $cmId = $this->getCmId();
        if (empty($cmId))
        {
            return true;
        }

        $now = strtotime( date('Y-m-d H:i:s') );
        $lastUpdated = strtotime( $this->getLastStatsImport() );
        $created = strtotime( $this->getCreated() );

        $cm = new CS_REST_Campaigns($this->getCmId(), app_base_ApplicationRegistry::getItem('campaign_monitor_api_key'));

        $opens = array();
        $subscriberClick = array();
        $methods = array(
            'get_opens'  => 'opens',
            'get_clicks' => 'subscriberClick'
        );

        $tagIds = array(
            'get_opens'  => 'getTagOpenId',
            'get_clicks' => 'getTagClickId'
        );

        foreach ($methods as $methodName => $methodVar)
        {
            $tagMethod = $tagIds[$methodName];
            $tagId     = $this->{$tagMethod}();
            $stats     = "StatsUpdated{$methodVar}";
            $getStats  = "get{$stats}";
            $setStats  = "set{$stats}";

            $pageData = $this->getPage($this->{$getStats}());
            do
            {
                $result = $cm->{$methodName}("1970-01-01", $pageData->page, self::RESULTS_PER_PAGE, 'date', 'asc');

                if (!$result->was_successful())
                {
                    var_dump('campaign import subscriber error');
                    var_dump($body);
                }
                else
                {
                    $dataResults = $result->response->Results;
                    $data        = array();
                    foreach ($dataResults as $dataResult)
                    {
                      $data[] = $dataResult;
                      $this->_setProjectReference($dataResult->EmailAddress, $methodName);
                    }

                    ${$methodVar} = array_merge(${$methodVar}, $data);
                }
                $pageData->page++;
            } while ($result->response->NumberOfPages >= $pageData->page);
            $this->{$setStats}($result->response->TotalNumberOfRecords);
            $this->setLastStatsImport( date('Y-m-d H:i:s') );
            $this->commit();
        }
        $this->_regenerateFilterResults();
    }

    /**
     * Set project reference
     *
     * @param string $email Email address
     * @param string $tagId Tag ID
     *
     * @return void
     */
    protected function _setProjectReference($email, $methodName)
    {
        $cmRel = $this->_getCmRel();
        if ('get_opens' === $methodName)
        {
            return $cmRel->attachPostInitiativeViewed($email, $this->getCmName());
        }
        else
        {
            return $cmRel->attachPostInitiativeClicked($email, $this->getCmName());
        }
    }

    /**
     * Regenerate filter results
     *
     * @return void
     */
    protected function _regenerateFilterResults()
    {
        $filterId = $this->_getCmRel()->getFilterId();
        if (null !== $filterId)
        {
			$filterBuilder = new app_domain_FilterBuilder();
			$filterLinesInclude = app_domain_Filter::findFilterLinesByFilterIdAndDirection($filterId, 'include');
			$filterLinesExclude = app_domain_Filter::findFilterLinesByFilterIdAndDirection($filterId, 'exclude');
			$filterBuilder->makeSQLData($filterId, $filterLinesInclude, 'include');
			$filterBuilder->makeSQLData($filterId, $filterLinesExclude, 'exclude');
			$filterBuilder->makeMainSQL($filterId, true);
        }
    }
    
    public function regenerateFilter()
    {
        var_dump('update filter for ' . $this->getCmName());
        return $this->_regenerateFilterResults();
    }

}