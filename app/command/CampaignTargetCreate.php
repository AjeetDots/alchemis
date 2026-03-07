<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/domain/Campaign.php');
require_once('app/mapper/CampaignMapper.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_CampaignTargetCreate extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			if ($this->processForm($request))
			{
				$request->addFeedback('Save Successful');
				$request->setProperty('success', true);
				return self::statuses('CMD_OK');
			}
			else
			{
				return self::statuses('CMD_ERROR');
			}
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		$campaign_id = $request->getProperty('campaign_id');
		if ($campaign_id == '')
		{
			throw new Exception('Invalid campaign_id supplied');
		}
		
		// set up an array of year_months to populate with set/attended/opps/wins
		$latest_year_month = app_domain_Campaign::findLatestTargetPeriodByCampaignId($request->getProperty('campaign_id'));
		
		if (empty($latest_year_month ))
		{
			$campaign = app_domain_Campaign::find($campaign_id);
			$latest_year_month = $campaign->getStartYearMonth();
			$latest_year = substr($latest_year_month, 0,4);
			$latest_month = substr($latest_year_month, 4,2);
			
			if ($latest_month == 1)
			{
				$latest_month = 12;
				$latest_year --;
			}
			else
			{
				$latest_month --;
			}
		}
		else
		{		
			$latest_year = substr($latest_year_month, 0,4);
			$latest_month = substr($latest_year_month, 4,2);
		}
		
		$months = array();
		
		for ($i = 1; $i <= 12; $i++) 
		{
			if ($latest_month + 1 > 12)
			{
				$latest_year ++;
				$latest_month = 0;
			}
			$latest_month ++;
			$month_pad = str_pad($latest_month,2,'0',STR_PAD_LEFT);
			
			//NOTE: the names in this array MUST match the target types used in the tpl - eg html form name for set items must be of the form target200801_set
			$months[] = array(	'year_month' => $latest_year . str_pad($latest_month,2,'0',STR_PAD_LEFT),
								'calls' => 0,
								'effectives' => 0,
								'set' => 0,
								'attended' => 0,
								'opportunities' => 0,
								'wins' => 0);
		}
						
		$properties = $request->getProperties();
		foreach ($properties as $key => $item)
		{
			$temp = strpos($key, 'target');
			
			if ($temp !== false)
			{
				$year_month = trim(substr($key,6,6));
				$target_type = trim(substr($key,13));
				
//				echo $year_month . ' - ' . $target_type . ' : ' . $request->getProperty($key);
//				echo '<br />';
				
				// Find relevant entry in month array and populate values
				foreach ($months as &$month)
				{
					if ($month['year_month'] == $year_month)
					{
						$month[$target_type] = $request->getProperty($key);
					}	
				}
			}
		}
		
		// Create campaign target objects
		foreach ($months as &$month)
		{
			$campaign_target = new app_domain_CampaignTarget();
			$campaign_target->setCampaignId($campaign_id);
			$campaign_target->setYearMonth($month['year_month']);
			$campaign_target->setCalls($month['calls']);
			$campaign_target->setEffectives($month['effectives']);
			$campaign_target->setMeetingsSet($month['set']);
			$campaign_target->setMeetingsAttended($month['attended']); 
			$campaign_target->setOpportunities($month['opportunities']); 
			$campaign_target->setWins($month['wins']);
			$campaign_target->commit();
		}

		// copy these new client targets into tbl_campaign_nbm_targets for each of the nbms currently assigned to this campaign
// 		app_domain_CampaignNbmTarget::copyCampaignTargetPeriodsToCampaignNbmTargets($campaign_id);
		$this->updateCampaignNbmTargets($campaign_id);
		
		return true;
	}
	
	
	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('campaign_id', $request->getProperty('campaign_id'));
		
		$campaign_id = $request->getProperty('campaign_id');
		
		$latest_year_month = app_domain_Campaign::findLatestTargetPeriodByCampaignId($campaign_id);
//		echo '$latest_year_month = ' . empty($latest_year_month);
		
		if (empty($latest_year_month ))
		{
			$campaign = app_domain_Campaign::find($campaign_id);
			$latest_year_month = $campaign->getStartYearMonth();
			$latest_year = substr($latest_year_month, 0,4);
			$latest_month = substr($latest_year_month, 4,2);
			
			if ($latest_month == 1)
			{
				$latest_month = 12;
				$latest_year --;
			}
			else
			{
				$latest_month --;
			}
		}
		else
		{		
			$latest_year = substr($latest_year_month, 0,4);
			$latest_month = substr($latest_year_month, 4,2);
		}
		
		$months = array();
		
		for ($i = 1; $i <= 12; $i++) 
		{
			if ($latest_month + 1 > 12)
			{
				$latest_year ++;
				$latest_month = 0;
			}
			$latest_month ++;
			$month_pad = str_pad($latest_month,2,'0',STR_PAD_LEFT);
			$months[] = array(	'year_month' => $latest_year . str_pad($latest_month,2,'0',STR_PAD_LEFT),
								'month_display' => $month_pad . '-' . $latest_year);
		}
		
		$request->setObject('months', $months);
	}	
	
	protected function updateCampaignNbmTargets($campaign_id)
	{
		//TODO: remove any existing campaign target periods for this nbm before adding new targets
		// Need to do this because if re-instating an nbm then that nbm may already have existing campaign targets assigned.
	
		
		$latest_year_month = app_domain_Campaign::findLatestTargetPeriodByCampaignId($campaign_id);
		if (!is_null($latest_year_month)) {
			$latest_year = substr($latest_year_month, 0,4);
			$latest_month = substr($latest_year_month, 4,2);
		} else {
			// no campaign targets exist
			return;
		}
		
		$user_ids = app_domain_CampaignNbm::findCurrentCampaignUserIdsByCampaign($campaign_id);
		
		if (count($user_ids) > 0) {
			foreach ($user_ids as $user) {
				$user_id = $user['user_id'];
				$latest_year_month_campaign_nbm = app_domain_CampaignNbmTarget::findLatestTargetPeriodByCampaignIdAndUserId($campaign_id, $user_id);
				
				if (!is_null($latest_year_month_campaign_nbm)) {
					$current_year = substr($latest_year_month_campaign_nbm, 0,4);
					$current_month = substr($latest_year_month_campaign_nbm, 4,2)+1;
					
					if ($current_month > 12)
					{
						$current_year ++;
						$current_month = 1;
					}
					
				} else { // if no targets then use the current month
					$current_year = date('Y');
					$current_month = date('n');
				}
				
				// get the diffence in number of months between the latest month of the campaign targets for this nbm
				// and the latest date for the campaign targets.
				// We need to create this number of nbm campaign target entries
				$month_count = Utils::dateDiff('m', $current_year . '-'. $current_month.'-01', $latest_year . '-'. $latest_month.'-01');
				
				if ($month_count > 0) {
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
						$nbm_campaign_target->setUserId($user_id);
						$nbm_campaign_target->setCampaignId($campaign_id);
						$nbm_campaign_target->setYearMonth($month_pad);
						$nbm_campaign_target->setPlannedDays(0);
						$nbm_campaign_target->setProjectManagementDays(0);
						$nbm_campaign_target->setEffectives(0);
						$nbm_campaign_target->setMeetingsSet(0);
						$nbm_campaign_target->setMeetingsSetImperative(0);
						$nbm_campaign_target->setMeetingsAttended(0);
						$nbm_campaign_target->commit();
					}
				}	
			}	
		}
	}
	
		
}
?>
