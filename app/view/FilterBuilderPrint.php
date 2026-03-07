<?php

/**
 * Defines the app_view_FilterBuilderPrint class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_FilterBuilderPrint extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('filter', $this->request->getObject('filter'));
		$this->smarty->assign('filter_lines', $this->request->getObject('filter_lines'));
		$this->smarty->display('FilterBuilderPrint.tpl');
	}
}

?>