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
class app_view_ImportCompany4_1 extends app_view_ManipulationView
{
	protected function doExecute()
	{

		// Init
		$this->smarty->assign('tiered_characteristic_data', $this->request->getObject('tiered_characteristic_data'));
		$this->smarty->assign('tiered_characteristic_lkp_data', $this->request->getObject('tiered_characteristic_lkp_data'));
		$this->smarty->display('ImportCompany4_1.tpl');
	}
}

?>