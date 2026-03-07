<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/domain/PostIncumbentAgency.php');
require_once('app/mapper/PostIncumbentAgencyMapper.php');
require_once('app/domain/TieredCharacteristic.php');
require_once('app/mapper/TieredCharacteristicMapper.php');

class app_command_PostIncumbentAgencies extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$this->init($request);
		return self::statuses('CMD_OK');
	}
		
	protected function init(app_controller_Request $request)
	{
		// Pass post
		$post_id = $request->getProperty('post_id');
		$post = app_domain_Post::find($post_id);
		$request->setObject('post', $post);
		
		// Pass discipline
		$discipline_id = $request->getProperty('discipline_id');
		$request->setProperty('discipline_id', $discipline_id);
		$request->setProperty('discipline', app_domain_TieredCharacteristic::lookupValue($discipline_id));
		
		// Get incumbent agencies
		$incumbents = app_domain_PostIncumbentAgency::findAllByPostIdAndDisciplineId($post_id, $discipline_id);
		$incumbents = $incumbents->toRawArray();
		
		foreach ($incumbents as &$incumbent)
		{
			$company = app_domain_Company::find($incumbent['agency_company_id']);
			$incumbent['name'] = $company->getName();
			$incumbent['address'] = $company->getSiteAddress(null, 'paragraph');
			 
		}
		
		$request->setObject('incumbents', $incumbents);
	}
}

?>