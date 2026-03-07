<?php

/**
 * Defines the app_view_Communication class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Communication extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('company_id', $this->request->getObject('company_id'));
		$this->smarty->assign('post_id', $this->request->getObject('post_id'));
		$this->smarty->assign('post_initiative_id', $this->request->getObject('post_initiative_id'));
		$this->smarty->assign('initiative_id', $this->request->getObject('initiative_id'));
		$this->smarty->assign('source_tab', $this->request->getProperty('source_tab'));
		$this->smarty->assign('tab', 'Communication');
		$this->smarty->display('Communication.tpl');
	}
}

?>