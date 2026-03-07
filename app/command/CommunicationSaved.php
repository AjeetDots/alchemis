<?php

class app_command_CommunicationSaved extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$user_id = $_SESSION['auth_session']['user']['id'];
		$scoreboard = app_domain_Scoreboard::findByUserIdStartDateEndDate($user_id, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
		$request->setObject('scoreboard', $scoreboard);
		
		$request->setProperty('post_id', $request->getProperty('post_id'));
		$request->setProperty('initiative_id', $request->getProperty('initiative_id'));
		$request->setProperty('post_initiative_id', $request->getProperty('post_initiative_id'));
		$request->setProperty('source_tab', $request->getProperty('source_tab'));
		
		return self::statuses('CMD_OK');
	}
}

?>