<?php

/**
 * Defines the app_view_UserEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_UserEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Get user
		$user = $this->request->getObject('user');
		$this->smarty->assign('user', $this->request->getObject('user'));
		$this->smarty->assign('clients', $this->request->getObject('clients'));
		
		// Do fields
		$this->smarty->assign('app_domain_RbacUser_handle',    $this->request->getObject('app_domain_RbacUser_handle'));
		$this->smarty->assign('app_domain_RbacUser_name',      $this->request->getObject('app_domain_RbacUser_name'));
		$this->smarty->assign('app_domain_RbacUser_email',      $this->request->getObject('app_domain_RbacUser_email'));
		$this->smarty->assign('app_domain_RbacUser_is_active', $this->request->getObject('app_domain_RbacUser_is_active'));
		$this->smarty->assign('app_domain_RbacUser_client_id', $this->request->getObject('app_domain_RbacUser_client_id'));
		
		// Get any feedback
		$this->smarty->assign('success', $this->request->getObject('success'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}
		
		$this->smarty->display('UserEdit.tpl');
	}
}

?>