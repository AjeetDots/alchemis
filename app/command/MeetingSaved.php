<?php

class app_command_MeetingSaved extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$request->setProperty('source_tab', $request->getProperty('source_tab'));
		$request->setProperty('post_initiative_id', $request->getProperty('post_initiative_id'));
		$request->setProperty('company_id', $request->getProperty('company_id'));
		return self::statuses('CMD_OK');
	}
}

?>