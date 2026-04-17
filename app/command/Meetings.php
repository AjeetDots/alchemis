<?php

require_once('app/domain/Meeting.php');

class app_command_Meetings extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$post_initiative_id = $request->getProperty('post_initiative_id');
		$request->setProperty('post_initiative_id', $post_initiative_id);
		
		if ($post_initiative_id == '')
		{
			$request->addFeedback('No post initiative id supplied');
			return self::statuses('CMD_OK');
		}

		$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
		if ($post_initiative === null)
		{
			$request->addFeedback('Post initiative not found');
			return self::statuses('CMD_ERROR');
		}

		$post = $post_initiative->getPost();
		if ($post === null)
		{
			$request->addFeedback('Post not found for this initiative');
			return self::statuses('CMD_ERROR');
		}
		$request->setObject('post', $post);
		$request->setProperty('company_id', $post->getCompanyId());

        $initiative = $post_initiative->getInitiative();
        $campaign_id = $initiative->getCampaignId();
        $campaign = app_domain_Campaign::find($campaign_id);
        $initiative_name = $campaign->getClientName() . ': ' . $initiative->getName();

		$request->setProperty('initiative_name', $initiative_name);
		$request->setProperty('referrer_type', $request->getProperty('referrer_type'));

		$meetings = app_domain_Meeting::findByPostInitiativeId($post_initiative_id);
		$request->setObject('meetings', $meetings);
		return self::statuses('CMD_OK');
	}
}

?>