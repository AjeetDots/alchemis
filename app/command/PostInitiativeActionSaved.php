<?php

class app_command_PostInitiativeActionSaved extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
			
		echo 'meeting action saved';
		app_domain_objectWatcher::showObjectWatcher();
		
//		$request->setProperty('source_tab', $request->getProperty('source_tab'));
//		$request->setProperty('post_initiative_id', $request->getProperty('post_initiative_id'));
//		$request->setProperty('company_id', $request->getProperty('company_id'));
//		$request->setProperty('post_id', $request->getProperty('post_id'));
		
		return self::statuses('CMD_OK');
	}
}

?>