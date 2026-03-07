<?php 

/**
 * Defines the app_view_ClientDetails class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
 
/**
 * @package Alchemis
 */
class app_view_ClientDetails extends app_view_View
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('client_id', $this->request->getProperty('client_id'));
		$this->smarty->assign('client', $this->request->getObject('client'));

		$this->smarty->display('ClientDetails.tpl');
	}
}
?>