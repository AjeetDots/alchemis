<?php

/**
 * Defines the app_mapper_CmCampaignMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_CmCampaignMapper extends app_mapper_ShadowMapper implements app_domain_CmCampaignFinder
{

    public function __construct()
    {
        if (!self::$DB)
        {
            self::$DB = app_controller_ApplicationHelper::instance()->DB();
        }

        // Select all
        $this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_cm_campaign ORDER BY id');

        // Select single
        $query = 'SELECT * FROM tbl_cm_campaign WHERE id = ?';
        $types = array('integer');
        $this->selectStmt = self::$DB->prepare($query, $types);

        // Select single by post ID
        $query = 'SELECT * FROM tbl_cm_campaign WHERE cm_id = ?';
        $types = array('text');
        $this->selectByCmIdStmt = self::$DB->prepare($query, $types);
    }

    /**
     * Load an object from an associative array.
     * @param array $array an associative array
     * @return app_domain_DomainObject
     */
    protected function doLoad($array)
    {
        $obj = new app_domain_CmCampaign($array['id']);
        $obj->setCreated($array['created']);
        $obj->setCmName($array['cm_name']);
        $obj->setCmId($array['cm_id']);
        $obj->setTotalRecipients($array['total_recipients']);
        $obj->setProcessed($array['processed']);
        $obj->setLastStatsImport($array['last_stats_import']);
        $obj->setStatsUpdatedOpens($array['stats_updated_opens']);
        $obj->setStatsUpdatedSubscriberClick($array['stats_updated_subscriberclick']);
        $obj->setTagOpenId($array['tag_open_id']);
        $obj->setTagClickId($array['tag_click_id']);
        $obj->setFilterId($array['filter_id']);
        $obj->markClean();
        return $obj;
    }

    /**
     * Get a new ID to use from the database.
     * @return integer
     */
    public function newId()
    {
        $this->id = self::$DB->nextID('tbl_cm_campaign');
        return $this->id;
    }

    /**
     * Insert a new database record for the object.
     * @param app_domain_DomainObject $object
     */
    function doInsert(app_domain_DomainObject $object)
    {
        $query = 'INSERT INTO tbl_cm_campaign ' .
            '(id, created, cm_name, cm_id, total_recipients, processed, last_stats_import, stats_updated_opens, stats_updated_subscriberClick, tag_open_id, tag_click_id, filter_id) ' .
            'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $types = array('integer', 'date', 'text', 'text', 'integer', 'integer', 'date', 'integer', 'integer', 'integer', 'integer', 'integer');
        $this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

        $data = array($object->getId(), $object->getCreated(), $object->getCmName(),
            $object->getCmId(), $object->getTotalRecipients(), $object->getProcessed(),
            $object->getLastStatsImport(), $object->getStatsUpdatedOpens(), $object->getStatsUpdatedSubscriberClick(),
            $object->getTagOpenId(), $object->getTagClickId(), $object->getFilterId());
        $this->doStatement($this->insertStmt, $data);
    }

    /**
     * Update the existing database record for the object.
     * @param app_domain_DomainObject $object
     */
    function update(app_domain_DomainObject $object)
    {
        $query = 'UPDATE tbl_cm_campaign SET 
            created = ?, 
            cm_name = ?, 
            cm_id = ?,
            total_recipients = ?, 
            processed = ?, 
            last_stats_import = ?,
            stats_updated_opens = ?, 
            stats_updated_subscriberClick = ?,
            tag_open_id = ?, 
            tag_click_id = ?, 
            filter_id = ? 
            WHERE id = ?';

        $types = array(
            'date', 
            'text', 
            'text', 
            'integer', 
            'integer', 
            'date', 
            'integer', 
            'integer', 
            'integer', 
            'integer', 
            'integer'
        );
        $this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

        $data = array(
            $object->getCreated(), 
            $object->getCmName(),
            $object->getCmId(), 
            $object->getTotalRecipients(), 
            $object->getProcessed(),
            $object->getLastStatsImport(), 
            $object->getStatsUpdatedOpens(), 
            $object->getStatsUpdatedSubscriberClick(),
            $object->getTagOpenId(), 
            $object->getTagClickId(), 
            $object->getFilterId(),
            $object->getId(),
        );
        $this->doStatement($this->updateStmt, $data);
    }

    /**
     * Find the given cm_campaign.
     * @param integer $id cm_campaign ID
     * @return app_domain_CmCampaign
     * @see app_mapper_Mapper::load()
     */
    public function doFind($id)
    {
        $values = array($id);
        $result = $this->doStatement($this->selectStmt, $values);
        return $this->load($result);
    }

    /**
     * Find all cm_campaigns.
     * @return app_mapper_CmCampaignCollection collection of app_domain_CmCampaign objects
     */
    public function findAll()
    {
        $result = $this->doStatement($this->selectAllStmt, array());
        return new app_mapper_CmCampaignCollection($result, $this);
    }

    /**
     * Find the current cm campaign by post ID.
     * @param string $cm_id Campaign monitor id
     * @return app_domain_CmCampaign collection
     */
    public function findByCmId($cm_id)
    {
        $values = array($cm_id);
        $result = $this->doStatement($this->selectByCmIdStmt, $values);
        return $this->load($result);
    }

}

?>