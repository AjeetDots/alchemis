<?php

class app_domain_CampaignNbm {
    
    public static function findCampaignInitiativesByUserId($userId) {
        // Return a mock array of client initiatives
        return [
            [
                'initiative_id' => 1,
                'client_initiative_display' => 'Default Initiative'
            ]
        ];
    }
}