<?php 

/**
 * Defines the app_view_ClientDetailsEdit class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
 
require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_ClientDetailsEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('id', $this->request->getProperty('id'));
		$this->smarty->assign('client', $this->request->getObject('client'));
		$this->smarty->assign('counties_options', $this->request->getObject('counties_options'));
		$this->smarty->assign('countries_options', $this->request->getObject('countries_options'));
		
		// Get any feedback
		$this->smarty->assign('success', $this->request->getProperty('success'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		$this->smarty->display('ClientDetailsEdit.tpl');
	}
}
?>