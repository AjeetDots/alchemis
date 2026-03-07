<?php

require_once('app/domain/Company.php');

class app_command_WorkspaceCompanyView extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
//		$collection = app_domain_Company::findAll();
//		$request->setObject('companies', $collection);

		// Parameters
		$company_id = $request->getProperty('id');
		
		$request->setObject('company_id', $company_id);
		
		return self::statuses('CMD_OK');
	}
}

?>