<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_CampaignSector objects.
 * @package alchemis
 */
class app_command_AjaxCampaignSector extends app_command_AjaxCommand
{
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'add_sector':
				$campaign_sector = new app_domain_CampaignSector();
				$campaign_sector->setCampaignId($this->request->campaign_id);
				$campaign_sector->setSectorId($this->request->sector_id);
				$campaign_sector->setWeighting($this->request->weighting);
				$campaign_sector->commit();
				
				$campaign_sector_id = $campaign_sector->getId();
				app_domain_ObjectWatcher::remove($campaign_sector);
				$campaign_sector = app_domain_CampaignSector::find($campaign_sector_id);
				
				$this->request->item_id = $campaign_sector->getId();
				$this->request->row_html = $this->getCampaignSectorLineHtml($campaign_sector);
				break;
			case 'delete_sector':
				$campaign_sector = app_domain_CampaignSector::find($this->request->item_id);
				$campaign_sector->markDeleted();
				$campaign_sector->commit();
				break;
			case 'update_sector_weighting':
				$campaign_sector = app_domain_CampaignSector::find($this->request->item_id);
				$campaign_sector->setWeighting($this->request->weighting);
				$campaign_sector->commit();
				break;
				
			default:
				break;
		}
		
		// Return result data
		array_push($this->response->data, $this->request);
		
	}

	protected function getCampaignSectorLineHtml($campaign_sector)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('sector', $campaign_sector);
		return $smarty->fetch('html_CampaignSectorLine.tpl');
	}
		
}

?>