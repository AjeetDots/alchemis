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
class app_view_ImportCompany2 extends app_view_ManipulationView
{
	protected function doExecute()
	{

		// Init
		$this->smarty->assign('county_data', $this->request->getObject('county_data'));

		$this->smarty->assign('county_lkp_data', $this->request->getObject('county_lkp_data'));
		$this->smarty->assign('country_data', $this->request->getObject('country_data'));

		$this->smarty->display('ImportCompany2.tpl');
	}
}

?>