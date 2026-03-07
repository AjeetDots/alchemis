<?php

/**
 * Defines the app_command_CharacteristicList class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Characteristic.php');

/**
 * @package Alchemis
 */
class app_command_CharacteristicList extends app_command_Command
{
	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		return $this->session_user->hasPermission('permission_admin_characteristics');
	}

	public function doExecute(app_controller_Request $request)
	{
		$collection = app_domain_Characteristic::findAll();
		$request->setObject('characteristics', $collection);
		return self::statuses('CMD_OK');
	}
}

?>