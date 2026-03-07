<?php

/**
 * Defines the app_view_EventDelete class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_EventDelete extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('event', $this->request->getObject('event'));
		$this->smarty->assign('success', $this->request->getProperty('success'));
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Assign referrer
		$this->smarty->assign('referrer', $this->request->getProperty('referrer'));
		$this->smarty->assign('referrer_date', $this->request->getProperty('referrer_date'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}

		$this->smarty->display('EventDelete.tpl');
	}
}

?>