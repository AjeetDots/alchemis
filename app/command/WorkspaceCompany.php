<?php
require_once('app/domain/Company.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/CompanyNote.php');
require_once('app/mapper/CompanyNoteMapper.php');
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');

class app_command_WorkspaceCompany extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Get request parameters
		$company_id = $request->getProperty('id');
		
		// Get company
		$company = app_domain_Company::find($company_id);
		$request->setObject('company', $company);
		
		// Get post_id
		$post_id = $request->getProperty('post_id');
//		$request->setObject('post_id', $post_id);
		
//		echo "<pre>";
//		print_r($company);
//		echo "</pre>";
				
		// Get posts
		$company_posts_job_title = app_domain_Company::findPostsOrderByJobTitle($company_id);
		$request->setObject('company_posts_job_title', $company_posts_job_title);
		
		$company_posts_first_name = app_domain_Company::findPostsOrderByFirstName($company_id);
		$request->setObject('company_posts_first_name', $company_posts_first_name);
		
		$company_note_count = app_domain_CompanyNote::findCountByCompanyId($company_id);
		$request->setProperty('company_note_count', $company_note_count);
		
		
		// Get clients contacted for this post
		if (is_null($post_id) || $post_id == '' || $post_id == null || $post_id == 'null')
		{
			
			echo 'count($company_posts_job_title) = ' . count($company_posts_job_title);
			if (count($company_posts_job_title) > 0)
			{
				$post_id = $company_posts_job_title[0]['id'];
			}
			else
			{
				$post_id = null;
			}
		
		}
		
		
		if (!is_null($post_id))
		{
			$post = app_domain_Post::find($post_id);
			$request->setObject('post', $post);
			
			$client_initiatives = app_domain_Post::findPostInitiatives($post_id);
			$request->setObject('client_initiatives', $client_initiatives);
		}
		
//		echo "<pre>";
//		print_r($client_initiatives);
//		echo "</pre>";
		
		
		
		
		return self::statuses('CMD_OK');
	}
}

?>