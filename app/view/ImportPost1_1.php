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
class app_view_ImportPost1_1 extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('post_selection', $this->request->getObject('post_selection'));
    	$this->smarty->display('ImportPost1_1.tpl');
	}
}

?>