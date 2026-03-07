<?php

/**
 * Defines the app_view_ClientView class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_ClientView extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('client', $this->request->getObject('client'));
		$this->smarty->display('ClientView.tpl');
	}
}

?>