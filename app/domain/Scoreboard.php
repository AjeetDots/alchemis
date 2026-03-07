<?php

class app_domain_Scoreboard {
    
    public static function findByUserIdStartDateEndDate($userId, $startDate, $endDate) {
        // Return a mock scoreboard object
        return new app_domain_Scoreboard();
    }
    
    public function getCommunicationCount() {
        return 0;
    }
    
    public function getEffectiveCount() {
        return 0;
    }
    
    public function getCallBackCount() {
        return 0;
    }
    
    public function getPriorityCallBackCount() {
        return 0;
    }
}