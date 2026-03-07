<?php

require_once('app/domain/Meeting.php');

class app_command_Meetings extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$post_initiative_id = $request->getProperty('post_initiative_id');
		$request->setProperty('post_initiative_id', $post_initiative_id);
		
		$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
		$post = $post_initiative->getPost();
		
		$request->setObject('post', $post_initiative->getPost());
		$request->setProperty('company_id', $post->getCompanyId());	
		
		
        // $initiative_name = $post_initiative->getInitiative()->getClientName() . ': ' . $post_initiative->getInitiative()->getName();

        $initiative = $post_initiative->getInitiative();
        $campaign_id = $initiative->getCampaignId();
        $campaign = app_domain_Campaign::find($campaign_id);
        $initiative_name = $campaign->getClientName()  . ': ' .  $campaign->getClientName();

		$request->setProperty('initiative_name', $initiative_name);		
		
		$request->setProperty('referrer_type', $request->getProperty('referrer_type'));
		
		if ($post_initiative_id != '')
		{
			//get meeting information
			$meetings = app_domain_Meeting::findByPostInitiativeId($request->getProperty('post_initiative_id'));
			$request->setObject('meetings', $meetings);
			return self::statuses('CMD_OK');
		}
		else
		{
			$request->addFeedback('No post initiative id supplied');
			return self::statuses('CMD_OK');
		}
	}
}

?>