<?php

class app_command_WorkspaceClient extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		return self::statuses('CMD_OK');
	}
}

?>