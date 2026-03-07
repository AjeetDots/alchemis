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
 * Command class to handle Ajax operations on app_domain_CampaignDiscipline objects.
 * @package alchemis
 */
class app_command_AjaxCampaignDiscipline extends app_command_AjaxCommand
{
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'add_discipline':
				$campaign_discipline = new app_domain_CampaignDiscipline();
				$campaign_discipline->setCampaignId($this->request->campaign_id);
				$campaign_discipline->setDisciplineId($this->request->discipline_id);
				$campaign_discipline->commit();
				
				$campaign_discipline_id = $campaign_discipline->getId();
				app_domain_ObjectWatcher::remove($campaign_discipline);
				$campaign_discipline = app_domain_CampaignDiscipline::find($campaign_discipline_id);
				
				$this->request->item_id = $campaign_discipline->getId();
				$this->request->row_html = $this->getCampaignDisciplineLineHtml($campaign_discipline);
				break;
			
			case 'delete_discipline':
				$campaign_discipline = app_domain_CampaignDiscipline::find($this->request->item_id);
				$campaign_discipline->markDeleted();
				$campaign_discipline->commit();
				break;
			default:
				break;
		}
		
		// Return result data
		array_push($this->response->data, $this->request);
		
	}

	protected function getCampaignDisciplineLineHtml($campaign_discipline)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('discipline', $campaign_discipline);
		return $smarty->fetch('html_CampaignDisciplineLine.tpl');
	}
		
}

?>