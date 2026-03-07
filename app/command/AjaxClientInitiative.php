<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Tag.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Command class to handle Ajax operations on app_domain_Tag objects.
 * @package alchemis
 */
class app_command_AjaxClientInitiative extends app_command_AjaxCommand
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
					$project_ref_html = $this->makeProjectRefs();
					$this->request->project_ref_html = $project_ref_html;
					default:
				break;
		}
		
		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
		if ($this->request->item_id == null)
		{
			$this->request->item_id = $tag->getId();
		}

		array_push($this->response->data, $this->request);
		
	}
	
	protected function makeProjectRefs()
	{
		$project_refs = app_domain_Tag::findProjectRefByInitiativeId($this->request->item_id);
		
		$options = array();
		$options[0] = '-- select --';
		foreach ($project_refs as $item)
		{
			$options[$item['value']] = @C_String::htmlDisplay(ucfirst($item['value']));
		}
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('html_select_id', 'project_refs');
		$smarty->assign('html_select_name', 'project_refs');
		$smarty->assign('html_select_style', 'width: 200px; vertical-align: top;');
		
		$smarty->assign('html_select_options', $options);
		
		return $smarty->fetch('html_select.tpl');
		
	}

}







?>