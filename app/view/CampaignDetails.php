<?php 

/**
 * Defines the app_view_CampaignDetails class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
 
/**
 * @package Alchemis
 */
class app_view_CampaignDetails extends app_view_View
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('campaign_id', $this->request->getProperty('campaign_id'));
		$this->smarty->assign('campaign', $this->request->getObject('campaign'));

		$this->smarty->display('CampaignDetails.tpl');
	}
}
?>