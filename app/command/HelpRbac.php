<?php

class app_command_HelpRbac extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		return self::statuses('CMD_OK');
	}
}

?>