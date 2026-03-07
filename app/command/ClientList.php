<?php

require_once('app/domain/Client.php');
//require_once('app/domain/PaginateHelper.php');

class app_command_ClientList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$paginate = new app_domain_PaginateHelper($request);
		$paginate->setTotal(app_domain_Client::count());
		$collection = app_domain_Client::findSet($paginate->getLimit(), $paginate->getOffset());
		$request->setObject('clients', $collection);
		$request->setObject('paginate', $paginate);
		return self::statuses('CMD_OK');
	}
}

?>