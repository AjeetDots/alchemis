<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Company.php');
require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/Tag.php');
require_once('app/mapper/TagMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Command class to handle Ajax operations on app_domain_Tag objects.
 * @package alchemis
 */
class app_command_AjaxSearch extends app_command_AjaxCommand
{
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		$debug = false;
		if ($debug)
		{
			echo "<pre>";
			echo print_r($this->request);
			echo "</pre>";
		}
		
		switch ($this->request->cmd_action)
		{
			case 'get_project_ref_tags':
				$project_ref_html = $this->getProjectRefsByClientInitiativeId();
				$this->request->project_ref_html = $project_ref_html;
				break;
			case 'make_project_ref_tags':
				$this->request->project_refs_added = $this->makeProjectRefs();
				break;
			case 'add_multiple_company_tags':
				$form_data = $this->request->form_data;
				$tag_value = $this->request->tag_value;
			
				if ($tag_value == '') {
					array_push($this->response->warnings, 'No valid tag value supplied.');
					exit();
				}
			
				// array to hold incoming field data arrays
				$company_ids = array();
					
				foreach ($form_data as $key => $data_item)
				{
					$temp = array();
					$temp = explode('_', $key);
					array_push($company_ids, $temp[2]);
				}
					
				$count_items_added = 0;
				foreach ($company_ids as $company_id)
				{
					$tag = new app_domain_Tag();
					$tag->setValue($tag_value);
					$tag->setCategoryId(2);
					$company = app_domain_Company::find($company_id);
					$tag->setParentDomainObject($company);
					$tag->commit();
					$count_items_added ++;
				}
					
				unset($form_data);
				unset($company_ids);
					
				$this->request->count_items_added = $count_items_added;
				break;
			default:
				break;
		}
		
		array_push($this->response->data, $this->request);
		
	}
	
	protected function getProjectRefsByClientInitiativeId()
	{
		$project_refs = app_domain_Tag::findProjectRefByClientInitiativeId($this->request->item_id);
		
		$options = array();
		$options[0] = '-- select --';
		foreach ($project_refs as $item)
		{
			$options[$item->geValue()] = @C_String::htmlDisplay(ucfirst($item->getValue()));
		}
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('html_select_id', 'project_refs');
		$smarty->assign('html_select_name', 'project_refs');
		$smarty->assign('html_select_style', 'width: 300px; vertical-align: top;');
		
		$smarty->assign('html_select_options', $options);
		
		return $smarty->fetch('html_select.tpl');
		
	}

	protected function makeProjectRefs()
	{
		$checkboxes = explode('&', $this->request->checkboxes);
		$client_initiative_id = $this->request->client_initiative_id;
		$project_ref = $this->request->project_ref;
		
		$checkboxes = str_replace('chk_', '', $checkboxes);
		$checkboxes = str_replace('=on', '', $checkboxes);
		
		$new_post_initiative_count = 0;
		$new_project_ref_count = 0;
		
		foreach ($checkboxes as $checkbox)
		{
			$checkbox = trim($checkbox);
			$add_project_ref = false;
			if (is_numeric($checkbox))
			{
				$post_id = $checkbox;
				if ($post_initiative_id = app_domain_PostInitiative::findByPostAndInitiative($post_id, $client_initiative_id))
				{
					// check if this project ref already exists for this post_initiative_id
					if (app_domain_Tag::countOfTagValueByParentObjectIdAndCategoryId('app_domain_PostInitiative', $post_initiative_id, 3, $project_ref) == 0)
					{
						$add_project_ref = true;
					}
				}
				else
				{
					// create a post_initiative_id and then create a project ref
					$post_initiative_id = new app_domain_PostInitiative();
					$post_initiative_id->setPostId($post_id);
					$post_initiative_id->setInitiativeId($client_initiative_id);
					$post_initiative_id->setStatusId(6); // Fresh Lead
					$post_initiative_id->setLeadSourceId(7);
					$post_initiative_id->commit();
					$add_project_ref = true;
					$new_post_initiative_count ++;		
				}
				
				if ($add_project_ref)
				{
					// create a project ref
					$tag = new app_domain_Tag();
					$tag->setValue($project_ref);
					$tag->setCategoryId(3);
					$tag->setParentDomainObject($post_initiative_id);
					$tag->commit();
					$new_project_ref_count ++;		
				}
			}
			
			
		}
		$return_data = new stdClass();
		
		$return_data->new_post_initiative_count = $new_post_initiative_count;
		$return_data->new_project_ref_count = $new_project_ref_count;
		
		
		return $return_data;
		
	}
}







?>