<?php

/**
 * Defines the app_command_Administration class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

/**
 * @package Alchemis
 */
class app_command_Administration extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
				
		return self::statuses('CMD_OK');
	}
}

?>