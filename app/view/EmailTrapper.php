<?php

/**
 * Defines the app_view_EmailTrapper class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_EmailTrapper extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		
// 		$this->smarty->assign('filters_personal', $this->request->getObject('filters_personal'));
// 		$this->smarty->assign('filters_personal_count', $this->request->getProperty('filters_personal_count'));
		
		$this->smarty->assign('maxMessage', $this->request->getProperty('maxMessage'));
	
		$this->smarty->display('EmailTrapper.tpl');
	}
}

?>