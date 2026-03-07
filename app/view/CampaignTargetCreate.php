<?php

/**
 * Defines the app_view_CampaignTargetCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_CampaignTargetCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('campaign_id', $this->request->getProperty('campaign_id'));
		$this->smarty->assign('months', $this->request->getObject('months'));
//		$this->smarty->assign('months_display', $this->request->getObject('months_display'));
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('success', $this->request->getProperty('success'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}

		$this->smarty->display('CampaignTargetCreate.tpl');
	}
}

?>