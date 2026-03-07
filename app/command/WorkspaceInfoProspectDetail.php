<?php

/**
 * Defines the app_command_WorkspaceInfoProspectDetail class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

/**
 * @package Alchemis
 */
class app_command_WorkspaceInfoProspectDetail extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		return self::statuses('CMD_OK');
	}
}

?>