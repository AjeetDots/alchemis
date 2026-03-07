<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
//require_once('app/domain/CampaignCompanyDoNotCall.php');
//require_once('app/mapper/CampaignCompanyDoNotCallMapper.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_CampaignNbm objects.
 * @package alchemis
 */
class app_command_AjaxCampaignCompanyDoNotCall extends app_command_AjaxCommand
{
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'add_company_do_not_call':
				$campaign_company_dnc = new app_domain_CampaignCompanyDoNotCall();
				$campaign_company_dnc->setCampaignId($this->request->campaign_id);
				$campaign_company_dnc->setCompanyId($this->request->company_id);
				$campaign_company_dnc->setCreatedAt(date('Y-m-d H:i:s'));
				$campaign_company_dnc->setCreatedBy($_SESSION['auth_session']['user']['id']);
				$campaign_company_dnc->commit();
				
				break;
			case 'multiple_add_company_do_not_call':
				$form_data = $this->request->form_data;
				
				// array to hold incoming field data arrays
				$company_ids = array();
				
				foreach ($form_data as $key => $data_item)
				{
					$temp = array();
					$temp = explode('_', $key);
					array_push($company_ids, $temp[2]);
				}

				$count_items_added = 0;
				foreach ($company_ids as $company_id)
				{
					if ($this->addCampaignCompanyDoNotCall($this->request->campaign_id, $company_id))
					{
						$count_items_added ++;
					}
				}
				
				unset($form_data);
				unset($company_ids);
				
				$this->request->count_items_added = $count_items_added;
				break;	
			case 'delete_company_do_not_call':
				$campaign_company_dnc = app_domain_CampaignCompanyDoNotCall::find($this->request->item_id);
				$campaign_company_dnc->markDeleted();
				$campaign_company_dnc->commit();
				break;
			default:
				break;
		}
		
		// Return result data
		array_push($this->response->data, $this->request);
		
	}
	
	protected function addCampaignCompanyDoNotCall($campaign_id, $company_id)
	{
		// NOTE: the campaign_id variable is actually an initiative variable - so we need to lookup the parent campaign_id
		$initiative = app_domain_Initiative::find($campaign_id);		
	
	
		$campaign_company_dnc = new app_domain_CampaignCompanyDoNotCall();
//		$campaign_company_dnc->setCampaignId($campaign_id);
		$campaign_company_dnc->setCampaignId($initiative->getCampaignId());
		$campaign_company_dnc->setCompanyId($company_id);
		$campaign_company_dnc->setCreatedAt(date('Y-m-d H:i:s'));
		$campaign_company_dnc->setCreatedBy($_SESSION['auth_session']['user']['id']);
		$campaign_company_dnc->commit();
		
		return true;
	}
	
}

?>