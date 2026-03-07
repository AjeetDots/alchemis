<?php

/**
 * Defines the app_view_ImportCompany class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_ImportCompany1_4 extends app_view_ManipulationView
{
	protected function doExecute()
	{

		// Init
		$this->smarty->assign('company_selection', $this->request->getObject('company_selection'));
		$this->smarty->assign('processed', $this->request->getProperty('processed'));

		$this->smarty->display('ImportCompany1_4.tpl');
	}
}

?>