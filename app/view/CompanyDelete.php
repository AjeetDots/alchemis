<?php

/**
 * Defines the app_view_CompanyDelete class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_CompanyDelete extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('company', $this->request->getObject('company'));
		$this->smarty->assign('id', $this->request->getProperty('id'));
		$this->smarty->assign('post_count', $this->request->getProperty('post_count'));
		$this->smarty->assign('data_source', $this->request->getProperty('data_source'));
		$this->smarty->assign('success', $this->request->getProperty('success'));
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}

		$this->smarty->display('CompanyDelete.tpl');
	}
}

?>