<?php

/**
 * Defines the app_command_Reporting class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_Reporting extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$session_user = $session->getSessionUser();

		// Pass through MD5 of the user ID using the session user object
		if ($this->session_user && method_exists($this->session_user, 'getId')) {
			$request->setObject('md5_user_id', md5($this->session_user->getId()));
		} else if (is_array($session_user) && isset($session_user['id'])) {
			// Fallback for legacy array-based user data
			$request->setObject('md5_user_id', md5($session_user['id']));
		} else {
			$request->setObject('md5_user_id', null);
		}

		$request->setObject('user', $this->session_user);

		if ($items = app_domain_Client::findByUserIdForDropdown($this->session_user->getId()))
		{
			$request->setObject('client_options', $items);
			// Default to the placeholder option (value 0) so that
			// JavaScript can trigger loading once a real client is chosen.
			$request->setProperty('client_selected', 0);
		}
		
		return self::statuses('CMD_OK');
	}
}

?>