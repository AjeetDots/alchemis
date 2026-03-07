<?php 

/**
 * Defines the app_view_CampaignDetailsEdit class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
 
require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_CampaignDetailsEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('id', $this->request->getProperty('id'));
		$this->smarty->assign('campaign', $this->request->getObject('campaign'));
		
		$this->smarty->assign('campaign_type_options', $this->request->getObject('campaign_type_options'));
		$this->smarty->assign('billing_terms_options', $this->request->getObject('billing_terms_options'));
		$this->smarty->assign('payment_terms_options', $this->request->getObject('payment_terms_options'));
		$this->smarty->assign('payment_method_options', $this->request->getObject('payment_method_options'));
		
		$this->smarty->assign('minimum_duration_options', $this->request->getObject('minimum_duration_options'));
		$this->smarty->assign('notice_period_options', $this->request->getObject('notice_period_options'));
				
		$this->smarty->assign('start_selected', $this->request->getProperty('start_selected'));
		$this->smarty->assign('end_selected', $this->request->getProperty('end_selected'));
				
		// Get any feedback
		$this->smarty->assign('success', $this->request->getProperty('success'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}

		$this->smarty->display('CampaignDetailsEdit.tpl');
	}
}
?>