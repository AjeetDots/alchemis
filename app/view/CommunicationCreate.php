<?php

/**
 * Defines the app_view_CommunicationCreate class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CommunicationCreate extends app_view_View
{
	protected function doExecute()
	{
		
		$this->smarty->assign('source_tab', $this->request->getProperty('source_tab'));
		$this->smarty->assign('company', $this->request->getObject('company'));
		$this->smarty->assign('client', $this->request->getObject('client'));
		
		$this->smarty->assign('lead_source_options', $this->request->getObject('lead_source_options'));
        $this->smarty->assign('lead_source_selected', $this->request->getProperty('lead_source_selected'));
        
        $this->smarty->assign('data_source_options', $this->request->getObject('data_source_options'));
        $this->smarty->assign('data_source_current', $this->request->getProperty('data_source_current'));
        $this->smarty->assign('data_source_current_id', $this->request->getProperty('data_source_current_id'));
        $this->smarty->assign('data_source_spoken_id', $this->request->getProperty('data_source_spoken_id'));
        $this->smarty->assign('data_source_suggested_id', $this->request->getProperty('data_source_suggested_id'));
        $this->smarty->assign('global_data_source_options', $this->request->getObject('global_data_source_options'));
        $this->smarty->assign('global_data_source_selected', $this->request->getProperty('global_data_source_selected'));
		
		$this->smarty->assign('company_posts_first_name', $this->request->getObject('company_posts_first_name'));
//		$this->smarty->assign('post_id', $this->request->getObject('post_id'));
		$this->smarty->assign('post', $this->request->getObject('post'));
		$this->smarty->assign('post_initiative_id', $this->request->getObject('post_initiative_id'));
		$this->smarty->assign('post_initiative', $this->request->getObject('post_initiative'));
		$this->smarty->assign('status_id', $this->request->getProperty('status_id'));
		$this->smarty->assign('initiative_id', $this->request->getProperty('initiative_id'));
		$this->smarty->assign('last_communication', $this->request->getObject('last_communication'));
		$this->smarty->assign('next_communication_reasons', $this->request->getObject('next_communication_reasons'));
		$this->smarty->assign('status_options', $this->request->getObject('status_options'));
		$this->smarty->assign('status_is_auto_calculate', $this->request->getProperty('status_is_auto_calculate'));
		$this->smarty->assign('targeting', $this->request->getObject('targeting'));
		$this->smarty->assign('receptiveness', $this->request->getObject('receptiveness'));
		
		$this->smarty->assign('campaign_disciplines_grid', $this->request->getObject('campaign_disciplines_grid'));
		$this->smarty->assign('non_campaign_disciplines_grid', $this->request->getObject('non_campaign_disciplines_grid'));
		$this->smarty->assign('decison_maker_options', $this->request->getObject('decison_maker_options'));
		$this->smarty->assign('agency_user_options', $this->request->getObject('agency_user_options'));
		
		$this->smarty->assign('available_disciplines', $this->request->getObject('available_disciplines'));
		
		
		$this->smarty->assign('meetings', $this->request->getObject('meetings'));
		
		$this->smarty->assign('meeting', $this->request->getObject('meeting'));
		$this->smarty->assign('meeting_action_count', $this->request->getProperty('meeting_action_count'));
		$this->smarty->assign('actions', $this->request->getObject('actions'));
		$this->smarty->assign('overdue_actions', $this->request->getObject('overdue_actions'));
		
		$this->smarty->assign('meeting_location_options', $this->request->getObject('meeting_location_options'));
		$this->smarty->assign('meeting_location_selected', $this->request->getProperty('meeting_location_selected'));
		$this->smarty->assign('nbm_predicted_rating_options', $this->request->getObject('nbm_predicted_rating_options'));
		$this->smarty->assign('nbm_predicted_rating_selected', $this->request->getProperty('nbm_predicted_rating_selected'));
		
		$this->smarty->assign('information_request_count', $this->request->getProperty('information_request_count'));
//		$this->smarty->assign('information_requests', $this->request->getObject('information_requests'));
		
		$this->smarty->display('CommunicationCreate.tpl');
	}
}

?>