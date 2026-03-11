<?php

/**
 * Defines the app_domain_CampaignNbm class.
 *
 * This represents the NBMs (users) assigned to a campaign.
 */

require_once('app/domain/DomainObject.php');

class app_domain_CampaignNbm extends app_domain_DomainObject
{
    protected $campaign_id;
    protected $user_id;
    protected $is_lead_nbm;
    protected $deactivated_date;
    protected $name;
    protected $user_alias;
    protected $user_email;

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function setCampaignId($campaign_id)
    {
        $this->campaign_id = $campaign_id;
        $this->markDirty();
    }

    public function getCampaignId()
    {
        return $this->campaign_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        $this->markDirty();
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setIsLeadNbm($is_lead_nbm)
    {
        $this->is_lead_nbm = $is_lead_nbm;
        $this->markDirty();
    }

    public function getIsLeadNbm()
    {
        return $this->is_lead_nbm;
    }

    public function setDeactivatedDate($deactivated_date)
    {
        $this->deactivated_date = $deactivated_date;
        $this->markDirty();
    }

    public function getDeactivatedDate()
    {
        return $this->deactivated_date;
    }

    public function setName($name)
    {
        $this->name = $name;
        $this->markDirty();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUserAlias($user_alias)
    {
        $this->user_alias = $user_alias;
        $this->markDirty();
    }

    public function getUserAlias()
    {
        return $this->user_alias;
    }

    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
        $this->markDirty();
    }

    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * Whether this campaign NBM assignment is active.
     *
     * The schema uses a special "zero" date to represent an active row:
     * deactivated_date = '0000-00-00'. Any other non-empty value means
     * the NBM has been deactivated.
     */
    public function isActive()
    {
        if ($this->deactivated_date === null) {
            return true;
        }

        $date = trim((string) $this->deactivated_date);

        if ($date === '' || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return true;
        }

        return false;
    }

    /**
     * Find a campaign NBM by id.
     */
    public static function find($id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->find($id);
    }

    /**
     * Find all campaign NBMs.
     */
    public static function findAll()
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findAll();
    }

    /**
     * Find all NBMs for a given campaign.
     */
    public static function findByCampaignId($campaign_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findByCampaignId($campaign_id);
    }

    /**
     * Get count of NBMs for a given campaign.
     */
    public static function findCountByCampaignId($campaign_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findCountByCampaignId($campaign_id);
    }

    /**
     * Find all NBMs by user id.
     */
    public static function findByUserId($user_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findByUserId($user_id);
    }

    /**
     * Find a specific NBM row by user and campaign.
     */
    public static function findByUserIdAndCampaignId($user_id, $campaign_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findByUserIdAndCampaignId($user_id, $campaign_id);
    }

    /**
     * Count NBMs matching user and campaign.
     */
    public static function findCountByUserIdAndCampaignId($user_id, $campaign_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findCountByUserIdAndCampaignId($user_id, $campaign_id);
    }

    /**
     * Get the lead NBM for a campaign.
     */
    public static function findLeadNbmByCampaignId($campaign_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findLeadNbmByCampaignId($campaign_id);
    }

    /**
     * Get current (active) campaign user IDs for a campaign.
     */
    public static function findCurrentCampaignUserIdsByCampaign($campaign_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findCurrentCampaignUserIdsByCampaign($campaign_id);
    }

    /**
     * List initiatives for a given user across their campaigns.
     */
    public static function findCampaignInitiativesByUserId($user_id)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findCampaignInitiativesByUserId($user_id);
    }

    /**
     * List initiatives for the current session user.
     */
    public static function findCampaignInitiativesByCurrentUser()
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->findCampaignInitiativesByCurrentUser();
    }

    /**
     * Persist changes to this object.
     */
    public function commit()
    {
        return parent::commit($this);
    }
}
