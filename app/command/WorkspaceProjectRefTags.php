<?php

/**
 * Defines the app_command_WorkspaceProjectRefTags class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

//require_once('app/domain/Company.php');
//require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Tag.php');
require_once('app/mapper/TagMapper.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_WorkspaceProjectRefTags extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Get request parameters
		$parent_object_type = $request->getProperty('parent_object_type');
				
		$parent_object_id = $request->getProperty('parent_object_id');
		$request->setObject('parent_object_id', $parent_object_id);
		
		$category_id = $request->getProperty('category_id');
		$request->setObject('category_id', $category_id);
		
		// Get tags
//		$tags = app_domain_Tag::findByCompanyIdAndCategoryId($company_id, $category_id);
        $tags = app_domain_Tag::findByParentObjectIdAndCategoryId($parent_object_type, $parent_object_id, $category_id);
		$request->setObject('tags', $tags);
		
		// redefine parent_object_type to useful English
		switch ($parent_object_type)
		{
			case 'app_domain_Company':
				$parent_object_type = 'Company';
				break;
			case 'app_domain_Post':
				$parent_object_type = 'Post';
				break;
			case 'app_domain_PostInitiative':
				$parent_object_type = 'PostInitiative';
				break;
			default:
				throw new Exception('Unspecified parent_object_type');
				break;
		}
		
		$request->setObject('parent_object_type', $parent_object_type);
		
		$request->setProperty('category', app_domain_Tag::lookupCategoryById($category_id));

		// project refs available for current initiative
		if ($items = app_domain_Tag::findProjectRefByInitiativeId($request->getProperty('initiative_id')));
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['value']] = @C_String::htmlDisplay(ucfirst($item['value']));
			}
		}		
		$request->setObject('initiative_tags', $options);
		
		return self::statuses('CMD_OK');
	}
}

?>