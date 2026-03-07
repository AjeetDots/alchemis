<?php

/**
 * Defines the app_view_Filter class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Filter extends app_view_View
{
	protected function doExecute()
	{
//		$this->smarty->assign('user', $this->request->getObject('user'));
		$this->smarty->assign('tab', 'Filter');
		$this->smarty->display('Filter.tpl');
	}
}

?>