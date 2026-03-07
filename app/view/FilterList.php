<?php

/**
 * Defines the app_view_FilterList class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_FilterList extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		$this->smarty->assign('user', $this->request->getObject('user'));
		$this->smarty->assign('delete_restore_permission', $this->request->getProperty('delete_restore_permission'));
		
		$this->smarty->assign('filters_personal', $this->request->getObject('filters_personal'));
		$this->smarty->assign('filters_personal_count', $this->request->getProperty('filters_personal_count'));
		
		$this->smarty->assign('filters_campaign', $this->request->getObject('filters_campaign'));
		$this->smarty->assign('filters_campaign_count', $this->request->getProperty('filters_campaign_count'));
		
		$this->smarty->assign('filters_global', $this->request->getObject('filters_global'));
		$this->smarty->assign('filters_global_count', $this->request->getProperty('filters_global_count'));
				
		$this->smarty->assign('can_export', $this->request->getProperty('can_export'));
		
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
		
		$this->smarty->assign('refresh_screen', false);
	
	$this->smarty->display('FilterList.tpl');
	}
}

?>