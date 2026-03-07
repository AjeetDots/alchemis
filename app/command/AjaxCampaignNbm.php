<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/CampaignNbm.php');
require_once('app/mapper/CampaignNbmMapper.php');
require_once('app/domain/CampaignNbmTarget.php');
require_once('app/mapper/CampaignNbmTargetMapper.php');
require_once('include/Utils/Utils.class.php');

/**
 * Command class to handle Ajax operations on app_domain_CampaignNbm objects.
 * @package alchemis
 */
class app_command_AjaxCampaignNbm extends app_command_AjaxCommand
{

	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);

		switch ($this->request->cmd_action)
		{
			case 'add_nbm':
				if (app_domain_CampaignNbm::findCountByUserIdAndCampaignId($this->request->user_id, $this->request->campaign_id) == 0)
				{
					$campaign_nbm = new app_domain_CampaignNbm();
					$campaign_nbm->setCampaignId($this->request->campaign_id);
					$campaign_nbm->setUserId($this->request->user_id);
					if (app_domain_CampaignNbm::findCountByCampaignId($this->request->campaign_id) == 0)
					{
						$campaign_nbm->setIsLeadNbm(1);
					}
					else
					{
						$campaign_nbm->setIsLeadNbm(0);
					}
					$campaign_nbm->setDeactivatedDate('0000-00-00');
					$campaign_nbm->setName($this->request->user_name);
					$campaign_nbm->setUserAlias($this->request->user_alias);
					$campaign_nbm->setUserEmail($this->request->user_email);
					$campaign_nbm->commit();

					// now add targets
					$this->addNbmCampaignTargets();
					$this->request->campaign_nbm_id = $campaign_nbm->getId();
					$this->request->row_html = $this->getCampaignNbmLineHtml($campaign_nbm);
					$this->request->is_valid = true;
				}
				else
				{
					$this->request->is_valid = false;
				}
				break;
			case 'delete_nbm':
				$campaign_nbm = app_domain_CampaignNbm::find($this->request->item_id);
				$campaign_nbm->setDeactivatedDate(date('Y-m-d'));
				$campaign_nbm->commit();
				$this->request->row_html = $this->getCampaignNbmLineHtml($campaign_nbm);
				break;
			case 'reinstate_nbm':
				$campaign_nbm = app_domain_CampaignNbm::find($this->request->item_id);
				$campaign_nbm->setDeactivatedDate('0000-00-00');
				$campaign_nbm->commit();
				$this->request->row_html = $this->getCampaignNbmLineHtml($campaign_nbm);
				break;
			case 'make_lead_nbm':
				// get new lead nbm object - needed to so we can fetch the campaign_id
				$campaign_nbm = app_domain_CampaignNbm::find($this->request->item_id);

				$campaign_nbm_lead_old = app_domain_CampaignNbm::findLeadNbmByCampaignId($campaign_nbm->getCampaignId());
				if (is_object($campaign_nbm_lead_old))
				{
					$campaign_nbm_lead_old->setIsLeadNbm(0);
					$campaign_nbm_lead_old->commit();
					$this->request->item_id_old = $campaign_nbm_lead_old->getId();
					$this->request->row_html_old = $this->getCampaignNbmLineHtml($campaign_nbm_lead_old);
				}
				$campaign_nbm->setIsLeadNbm(1);
				$campaign_nbm->commit();

				$this->request->row_html_new = $this->getCampaignNbmLineHtml($campaign_nbm);
				break;
			case 'update_nbm_call_name':
				$campaign_nbm = app_domain_CampaignNbm::find($this->request->item_id);
				$campaign_nbm->setUserAlias($this->request->user_alias);
				$campaign_nbm->commit();
				break;
			case 'update_nbm_email':
				$campaign_nbm = app_domain_CampaignNbm::find($this->request->item_id);
				$campaign_nbm->setUserEmail($this->request->user_email);
				$campaign_nbm->commit();
				break;
			case 'update_nbm_deactivated_date':
                $campaign_nbm = app_domain_CampaignNbm::find($this->request->item_id);
                $campaign_nbm->setDeactivatedDate($this->request->deactivated_date);
                $campaign_nbm->commit();
                break;
			default:
				break;
		}

		// Return result data
		array_push($this->response->data, $this->request);

	}

	protected function getCampaignNbmLineHtml($campaign_nbm)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('nbm', $campaign_nbm);
		return $smarty->fetch('html_CampaignNbmLine.tpl');
	}

	protected function addNbmCampaignTargets()
	{
		//TODO: remove any existing campaign target periods for this nbm before adding new targets
		// Need to do this because if re-instating an nbm then that nbm may already have existing campaign targets assigned.

		$latest_year_month = app_domain_Campaign::findLatestTargetPeriodByCampaignId($this->request->campaign_id);
		$latest_year = substr($latest_year_month, 0,4);
		$latest_month = substr($latest_year_month, 4,2);

		// get the diffence in number of months between the start of the current month and
		// the latest date for the campaign targets.
		// We need to create this number of nbm campaign target entries
		$current_month = date('n');
		$current_year = date('Y');
		$month_count = Utils::dateDiff('m', date('Y-m-d'), $latest_year . '-'. $latest_month.'-01');

//		return $month_count;

		for ($i = 1; $i <= $month_count+1; $i++)
		{

			if ($current_month > 12)
			{
				$current_year ++;
				$current_month = 1;
			}
			$month_pad = $current_year . str_pad($current_month,2,'0',STR_PAD_LEFT);
			$current_month ++;

			$nbm_campaign_target = new app_domain_CampaignNbmTarget();
			$nbm_campaign_target->setUserId($this->request->user_id);
			$nbm_campaign_target->setCampaignId($this->request->campaign_id);
			$nbm_campaign_target->setYearMonth($month_pad);
			$nbm_campaign_target->setPlannedDays(0);
			$nbm_campaign_target->setProjectManagementDays(0);
			$nbm_campaign_target->setEffectives(0);
			$nbm_campaign_target->setMeetingsSet(0);
			$nbm_campaign_target->setMeetingsSetImperative(0);
			$nbm_campaign_target->setMeetingsAttended(0);

//			throw new Exception(print_r($nbm_campaign_target));

			$nbm_campaign_target->commit();

		}


	}


}

?>