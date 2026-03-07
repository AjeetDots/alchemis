<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/FilterMapper.php');
require_once('app/domain/Filter.php');

class app_command_FilterList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
		require_once('Auth/Session.php');
		$session = Auth_Session::singleton();
		$request->setObject('user', $session->getSessionUser());
		
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}
	
	
	protected function init(app_controller_Request $request)
	{
		$user = $request->getObject('user');

		$delete_restore_permission = $this->session_user->hasPermission('permission_deleted_restored_filters');
		$request->setProperty('delete_restore_permission', $delete_restore_permission);
		
		$filters_personal = app_domain_Filter::findPersonalByUserId($user['id']);
		$request->setObject('filters_personal', $filters_personal);
		$request->setProperty('filters_personal_count', count($filters_personal->toRawArray()));
				
		$filters_campaign = app_domain_Filter::findCampaignFiltersByUserId($user['id']);
		$request->setObject('filters_campaign', $filters_campaign);
		$request->setProperty('filters_campaign_count', count($filters_campaign->toRawArray()));
		
		$filters_global = app_domain_Filter::findGlobalFilters();
		$request->setObject('filters_global', $filters_global);
		$request->setProperty('filters_global_count', count($filters_global->toRawArray()));
		
		if ($this->session_user->hasPermission('permission_admin_users'))
		{
			$request->setProperty('can_export',true); 
		}
	}
	
}

?>