<?php

/**
 * Defines the app_view_CampaignView class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_CampaignView extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		
		$this->smarty->assign('client_options', $this->request->getObject('client_options'));
		$this->smarty->assign('client_selected', $this->request->getProperty('client_selected'));
		
		if ($this->request->getProperty('client_selected') != '')
		{
			$this->smarty->assign('client', $this->request->getObject('client'));
			$this->smarty->assign('campaign', $this->request->getObject('campaign'));
			$this->smarty->assign('start_selected', $this->request->getProperty('start_selected'));

			$this->smarty->assign('campaign_disciplines', $this->request->getObject('campaign_disciplines'));
			$this->smarty->assign('discipline_options', $this->request->getObject('discipline_options'));
			$this->smarty->assign('campaign_disciplines_count', $this->request->getProperty('campaign_disciplines_count'));
					
			$this->smarty->assign('duration_options', $this->request->getObject('duration_options'));
			$this->smarty->assign('duration_selected', $this->request->getProperty('duration_selected'));
			
			$this->smarty->assign('campaign_nbms', $this->request->getObject('campaign_nbms'));
			$this->smarty->assign('campaign_targets', $this->request->getObject('campaign_targets'));
			$this->smarty->assign('campaign_sectors', $this->request->getObject('campaign_sectors'));
			$this->smarty->assign('campaign_companies_do_not_call', $this->request->getObject('campaign_companies_do_not_call'));
			
			$this->smarty->assign('user_options', $this->request->getObject('user_options'));
			$this->smarty->assign('campaign_sector_options', $this->request->getObject('campaign_sector_options'));
			$this->smarty->assign('region_options', $this->request->getObject('region_options'));
			
			$this->smarty->assign('campaign_regions', $this->request->getObject('campaign_regions'));
			
			$this->smarty->assign('campaign_report_summaries', $this->request->getObject('campaign_report_summaries'));
		}
				
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
		
	$this->smarty->display('CampaignView.tpl');
	}
}

?>