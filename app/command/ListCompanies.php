<?php

require_once('app/domain/Company.php');

class app_command_ListCompanies extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$collection = app_domain_Company::findAll();
		$request->setObject('companies', $collection);
		return self::statuses('CMD_OK');
	}
}

?>