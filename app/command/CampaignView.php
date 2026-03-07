<?php

/**
 * Defines the app_command_Calendar class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/ClientMapper.php');
require_once('app/domain/Client.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_CampaignView extends app_command_Command
{
	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		if ($this->session_user->hasPermission('permission_admin_client_campaigns') || $this->session_user->hasPermission('permission_admin_clients_nbm_admin')) {
			return true;
		} else {
			return false;
		}

	}

	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');

		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{

		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}

	protected function init(app_controller_Request $request)
	{
		$client_id = $request->getProperty('client_options');

		if ($items = app_domain_Client::findAll())
		{
			$items = $items->toRawArray();
			$selected_client = 0;
			$options = array();
			$options[0] = '-- select --';
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay($item['name']);
				if ($client_id == $item['id'])
				{
					$selected_client =$item['id'];
				}
			}
			$request->setObject('client_options', $options);

			$request->setProperty('client_selected', $selected_client);
		}

		if ($client_id != '' && $client_id > 0)
		{
			$client = app_domain_Client::find($client_id);

			$campaign = app_domain_Campaign::findByClientId($client_id);
			$request->setObject('campaign', $campaign);
			$request->setObject('client', $client);

			// campaign disciplines
			$campaign_disciplines = app_domain_CampaignDiscipline::findByCampaignId($campaign->getId());
			$request->setObject('campaign_disciplines', $campaign_disciplines);
			$campaign_disciplines_count = count($campaign_disciplines->toRawArray());
			$request->setProperty('campaign_disciplines_count', $campaign_disciplines_count);

			if ($items = app_domain_CampaignDiscipline::findAvailableDisciplinesByCampaignId($campaign->getId()))
			{
				$options = array();
				foreach ($items as $item)
				{
					$options[$item['id']] = @C_String::htmlDisplay($item['value']);
				}
				$request->setObject('discipline_options', $options);
			}


			$campaign_nbms = app_domain_CampaignNbm::findByCampaignId($campaign->getId());
			$request->setObject('campaign_nbms', $campaign_nbms);

			// Campaign targets
			$campaign_targets = app_domain_CampaignTarget::findByCampaignId($campaign->getId());
			$request->setObject('campaign_targets', $campaign_targets);

			// user_ids
			if ($items = app_domain_RbacUser::findAllActive())
			{
				$items = $items->toRawArray();
				$options = array();
				$options[0] = '-- select --';
				foreach ($items as $item)
				{
					$options[$item['id']] = @C_String::htmlDisplay($item['name']);
				}
				$request->setObject('user_options', $options);
			}

			// Campaign company do not call
			$campaign_companies_do_not_call = app_domain_CampaignCompanyDoNotCall::findByClientId($client_id)->toRawArray();
			$this->getExtraCompanyInfo($campaign_companies_do_not_call);
			$request->setObject('campaign_companies_do_not_call', $campaign_companies_do_not_call);

			// Campaign sectors
			$campaign_sectors = app_domain_CampaignSector::findByCampaignIdOrderByWeighting($campaign->getId());
			$request->setObject('campaign_sectors', $campaign_sectors);
			$campaign_sector_options = app_domain_TieredCharacteristic::findAllForDropdown();
			$request->setObject('campaign_sector_options',$campaign_sector_options);

			// Campaign regions
			$campaign_regions = app_domain_CampaignRegion::findByCampaignId($campaign->getId());
			$request->setObject('campaign_regions', $campaign_regions);
			if ($items = app_domain_Region::findAll())
			{
				$items = $items->toRawArray();
				$options = array();
				$options[0] = '-- select --';
				foreach ($items as $item)
				{
					$options[$item['id']] = @C_String::htmlDisplay($item['name']);
				}
				$request->setObject('region_options', $options);
			}

			// Campaign report summaries
			$campaign_report_summaries = app_domain_CampaignReportSummary::findByCampaignId($campaign->getId());
			$request->setObject('campaign_report_summaries', $campaign_report_summaries);

		}
	}


	/**
	 * Adds the additional site information to the company results.
	 * @param array $companies
	 */
	protected function getExtraCompanyInfo(&$companies)
	{
		foreach ($companies as &$company)
		{
			// Site
			$finder = new app_mapper_SiteMapper();
			$sites_collection = $finder->findByCompanyId($company['company_id']);
			$sites = $sites_collection->toRawArray();
			if (isset($sites[0]))
			{
				$address = array(	'address_1' => $sites[0]['address_1'],
									'address_2' => $sites[0]['address_2'],
									'town'      => $sites[0]['town'],
									'city'      => $sites[0]['city'],
									'postcode'  => $sites[0]['postcode']);
				$company['site_address'] = app_domain_Site::formatAddress($address, 'paragraph');
			}
		}
	}


}

?>
