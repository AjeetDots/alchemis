<?php
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');

class app_command_WorkspaceCompanyInitiatives extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Get request parameters
		$company_id = $request->getProperty('company_id');
		$initiative_id = $request->getProperty('initiative_id');
		$post_id = $request->getProperty('post_id');
		
		$request->setObject('post_id', $post_id);
		
		// Get company
//		$post_initiatives = app_domain_Company::findCompanyPostInitiatives($company_id, $initiative_id);
//		$request->setObject('post_initiatives', $post_initiatives);

		$posts = app_domain_Post::findPostsByCompanyAndInitiative($company_id, $initiative_id, $post_id);
		$request->setObject('posts', $posts);
		
		
		
				
//		echo "<pre>";
//		echo $post_id;
//		echo "</pre>";

		return self::statuses('CMD_OK');
	}
}

?>