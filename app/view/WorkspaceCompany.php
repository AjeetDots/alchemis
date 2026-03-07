<?php

/**
 * Defines the app_view_WorkspaceCompany class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceCompany extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('company', $this->request->getObject('company'));
		$this->smarty->assign('company_posts_job_title', $this->request->getObject('company_posts_job_title'));
		$this->smarty->assign('company_posts_first_name', $this->request->getObject('company_posts_first_name'));
		
		$this->smarty->assign('post', $this->request->getObject('post'));
//		$this->smarty->assign('post_id', $this->request->getObject('post_id'));
		$this->smarty->assign('client_initiatives', $this->request->getObject('client_initiatives'));
		
		$this->smarty->display('WorkspaceCompany.tpl');
	}
}

?>