<?php

/**
 * Defines the app_view_ListCompanies class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_ListCompanies extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('companies');
		$this->smarty->assign('companies', $collection->getArray());
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('ListCompanies.tpl');
	}
}

?>