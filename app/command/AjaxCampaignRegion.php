<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
//require_once('app/domain/CampaignNbm.php');
//require_once('app/mapper/CampaignNbmMapper.php');
//require_once('app/domain/NbmCampaignTarget.php');
//require_once('app/mapper/NbmCampaignTargetMapper.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_CampaignRegion objects.
 * @package alchemis
 */
class app_command_AjaxCampaignRegion extends app_command_AjaxCommand
{
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'add_region':
				$campaign_region = new app_domain_CampaignRegion();
				$campaign_region->setCampaignId($this->request->campaign_id);
				$campaign_region->setRegionId($this->request->region_id);
				$campaign_region->setName($this->request->region_name);
				$campaign_region->commit();
				
				$this->request->campaign_region_id = $campaign_region->getId();
				$this->request->row_html = $this->getCampaignRegionLineHtml($campaign_region);
				break;
			case 'delete_region':
				$campaign_nbm = app_domain_CampaignRegion::find($this->request->item_id);
				$campaign_nbm->markDeleted();
				$campaign_nbm->commit();
				break;
			default:
				break;
		}
		
		// Return result data
		array_push($this->response->data, $this->request);
		
	}
	
	protected function getCampaignRegionLineHtml($campaign_region)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('region', $campaign_region);
		return $smarty->fetch('html_CampaignRegionLine.tpl');
	}
}

?>