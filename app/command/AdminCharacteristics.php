<?php

/**
 * Defines the app_command_AdminCharacteristics class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

//require_once('app/mapper/FilterBuilderMapper.php');
//require_once('app/domain/FilterBuilder.php');

class app_command_AdminCharacteristics extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		return self::statuses('CMD_OK');
	}
}

?>