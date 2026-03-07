<?php

/**
 * Defines the app_command_WorkspaceInfoSites class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Company.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Site.php');
require_once('app/mapper/SiteMapper.php');
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');

/**
 * @package Alchemis
 */
class app_command_WorkspaceInfoSites extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
			
		// Get request parameters
		$company_id = $request->getProperty('company_id');
		
		//Get company name
		$company = app_domain_Company::find($company_id);
		$request->setObject('company', $company);
				
		// Get sites
		$sites = app_domain_Site::findByCompanyId($company_id);
		$request->setObject('sites', $sites);
		
		// Get posts
		$company_posts_first_name = app_domain_Company::findPostsOrderByFirstName($company_id);
		$request->setObject('company_posts_first_name', $company_posts_first_name);
				
//		echo "<pre>";
//		print_r($sites);
//		echo "</pre>";

		return self::statuses('CMD_OK');
	}
}

?>