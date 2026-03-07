<?php

/**
 * Defines the app_view_TeamDelete.php class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_TeamDelete extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('team_id', $this->request->getObject('team_id'));
		$this->smarty->assign('team',    $this->request->getObject('team'));
		$this->smarty->assign('success',    $this->request->getProperty('success'));
		
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

		$this->smarty->display('TeamDelete.tpl');
	}
}

?>