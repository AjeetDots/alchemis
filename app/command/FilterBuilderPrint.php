<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/FilterBuilderMapper.php');
require_once('app/domain/FilterBuilder.php');
require_once('app/mapper/FilterMapper.php');
require_once('app/domain/Filter.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('include/Utils/String.class.php');

class app_command_FilterBuilderPrint extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
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
		
	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		$id = $request->getProperty('id');
		$filter = app_domain_Filter::find($id);
		$request->setObject('filter',$filter);
		
		require_once('Auth/Session.php');
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		 
		if ($user['id'] == $filter->getCreatedBy())
		{
			$is_owner = true;
		}
		else
		{
			$is_owner = false;
		}
		$request->setProperty('is_owner',$is_owner);
		
		$filter_lines = app_domain_Filter::findFilterLinesByFilterId($id);
		$request->setObject('filter_lines',$filter_lines);
	}
	
}

?>