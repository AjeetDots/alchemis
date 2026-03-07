<?php

/**
 * Defines the app_command_Communication class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

/**
 * @package Alchemis
 */
class app_command_Communication extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$request->setObject('company_id', $request->getProperty('company_id'));
		$request->setObject('post_id',$request->getProperty('post_id'));
		$request->setObject('post_initiative_id',$request->getProperty('post_initiative_id'));
		$request->setObject('initiative_id',$request->getProperty('initiative_id'));
		$request->setProperty('source_tab',$request->getProperty('source_tab'));
		return self::statuses('CMD_OK');
	}
}

?>