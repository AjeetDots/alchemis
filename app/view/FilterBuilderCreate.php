<?php

/**
 * Defines the app_view_FilterBuilder class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_FilterBuilderCreate extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('group_options', $this->request->getObject('group_options'));
		$this->smarty->assign('field_options', $this->request->getObject('field_options'));
		$this->smarty->assign('results_format_values', $this->request->getObject('results_format_values'));
		$this->smarty->assign('results_format_output', $this->request->getObject('results_format_output'));
		$this->smarty->assign('results_format_selected', $this->request->getObject('results_format_selected'));
		
		$this->smarty->assign('type_values', $this->request->getObject('type_values'));
		$this->smarty->assign('type_output', $this->request->getObject('type_output'));
		$this->smarty->assign('type_selected', $this->request->getObject('type_selected'));
		
		$this->smarty->assign('campaigns', $this->request->getObject('campaigns'));
		
		$this->smarty->display('FilterBuilderCreate.tpl');
	}
}

?>