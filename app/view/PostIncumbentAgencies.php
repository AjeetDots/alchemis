<?php

/**
 * Defines the app_view_PostIncumbentAgencies class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_PostIncumbentAgencies extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		$this->smarty->assign('post', $this->request->getObject('post'));
		$this->smarty->assign('discipline_id', $this->request->getProperty('discipline_id'));
		$this->smarty->assign('discipline', $this->request->getProperty('discipline'));
		$this->smarty->assign('incumbents', $this->request->getObject('incumbents'));
//		echo '<pre>';
//		print_r($this->request->getObject('incumbents'));
//		echo '</pre>';
		
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
		
		$this->smarty->display('PostIncumbentAgencies.tpl');
	}
}


?>