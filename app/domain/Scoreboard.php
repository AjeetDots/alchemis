<?php

require_once('app/domain/DomainObject.php');

class app_domain_Scoreboard extends app_domain_DomainObject
{
    protected $communication_count = 0;
    protected $effective_count = 0;
    protected $non_effective_count = 0;
    protected $meeting_set_count = 0;
    protected $information_request_count = 0;
    protected $callback_count = 0;
    protected $priority_callback_count = 0;

    /**
     * Override constructor to skip newId() — Scoreboard is always loaded from DB, never inserted.
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    private static function getMapper()
    {
        if (!class_exists('app_mapper_ScoreboardMapper')) {
            require_once('app/mapper/ScoreboardMapper.php');
        }
        return new app_mapper_ScoreboardMapper();
    }

    public static function findByUserIdStartDateEndDate($userId, $startDate, $endDate)
    {
        return self::getMapper()->findByUserIdStartDateEndDate($userId, $startDate, $endDate);
    }

    public static function findEffectivesGroupedByInitiative($user_id, $start_date, $end_date)
    {
        return self::getMapper()->findEffectivesGroupedByInitiative($user_id, $start_date, $end_date);
    }

    public static function findNonEffectiveCountGroupedByInitiative($user_id, $start_date, $end_date)
    {
        return self::getMapper()->findNonEffectiveCountGroupedByInitiative($user_id, $start_date, $end_date);
    }

    public static function findMeetingsSetGroupedByInitiative($user_id, $start_date, $end_date)
    {
        return self::getMapper()->findMeetingsSetGroupedByInitiative($user_id, $start_date, $end_date);
    }

    public static function findInformationRequestGroupedByInitiative($user_id, $start_date, $end_date)
    {
        return self::getMapper()->findInformationRequestGroupedByInitiative($user_id, $start_date, $end_date);
    }

    public function setCommunicationCount($v)      { $this->communication_count = $v; }
    public function setEffectiveCount($v)          { $this->effective_count = $v; }
    public function setNonEffectiveCount($v)       { $this->non_effective_count = $v; }
    public function setMeetingSetCount($v)         { $this->meeting_set_count = $v; }
    public function setInformationRequestCount($v) { $this->information_request_count = $v; }
    public function setCallBackCount($v)           { $this->callback_count = $v; }
    public function setPriorityCallBackCount($v)   { $this->priority_callback_count = $v; }

    public function getCommunicationCount()        { return $this->communication_count; }
    public function getEffectiveCount()            { return $this->effective_count; }
    public function getNonEffectiveCount()         { return $this->non_effective_count; }
    public function getMeetingSetCount()           { return $this->meeting_set_count; }
    public function getInformationRequestCount()   { return $this->information_request_count; }
    public function getCallBackCount()             { return $this->callback_count; }
    public function getPriorityCallBackCount()     { return $this->priority_callback_count; }
}
