<?php

require_once('app/mapper/FilterBuilderMapper.php');
require_once('app/domain/FilterBuilder.php');

class app_command_Filter extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
//		require_once('Auth/Session.php');
//		$session = Auth_Session::singleton();
//		$request->setObject('user', $session->getSessionUser());
		
		
//		$t = new app_domain_FilterBuilder();
		$id = $request->getProperty('id');
		$request->setProperty('id',$id);
		
		
		return self::statuses('CMD_OK');
	}
}

?>