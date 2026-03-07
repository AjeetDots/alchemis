<?php

/**
 * Defines the app_view_CompanyCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_PostEditLocation extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		$this->smarty->assign('company', $this->request->getObject('company'));
		$this->smarty->assign('post', $this->request->getObject('post'));
		
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
		
		$this->smarty->display('PostEditLocation.tpl');
	}
}


?>