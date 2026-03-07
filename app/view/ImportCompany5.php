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
class app_view_ImportCompany5 extends app_view_ManipulationView
{
	protected function doExecute()
	{

		// Init
		$this->smarty->assign('client_initiative_lkp_data', $this->request->getObject('client_initiative_lkp_data'));
		$this->smarty->display('ImportCompany5.tpl');
	}
}

?>