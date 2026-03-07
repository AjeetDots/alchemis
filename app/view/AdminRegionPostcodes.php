<?php

/**
 * Defines the app_view_AdminRegionPostcodes class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_AdminRegionPostcodes extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('region', $this->request->getObject('region'));
		
		
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
		
		$this->smarty->display('AdminRegionPostcodes.tpl');
	}
}

?>