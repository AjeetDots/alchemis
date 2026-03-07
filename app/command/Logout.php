<?php

/**
 * Defines the app_command_Logout class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('Auth/Session.php');
require_once('app/ajax/domain/Ajax_JSON.class.php');

/**
 * @package Framework
 */
class app_command_Logout extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$session = Auth_Session::singleton();
		$location = 'index.php?cmd=Login';
		
		if (!is_null($session->getRedirect())) {
			$json = new Services_JSON();
			$location .= '&redirect=' . $json->encode($session->getRedirect());
		}
		$session->logout();
		header('Location: ' . $location);
		exit;
	}
}

?>