<?php
//require_once('app/domain/Company.php');
//require_once('app/mapper/CompanyMapper.php');

require_once('app/domain/Tag.php');
require_once('app/mapper/TagMapper.php');


class app_command_WorkspaceCompanyBrands extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Get request parameters
		$company_id = $request->getProperty('company_id');
		$request->setObject('company_id', $company_id);
		$category_id = $request->getProperty('category_id');
		$request->setObject('category_id', $category_id);
		
		// Get tags
		$tags = app_domain_Tag::findByCompanyIdAndCategoryId($company_id, $category_id);
		$request->setObject('tags', $tags);
		
//		echo "<pre>";
//		print_r($client_initiatives);
//		echo "</pre>";
		
		
		
		
		return self::statuses('CMD_OK');
	}
}

?>