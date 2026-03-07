<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_PostDisciplines extends app_command_ManipulationCommand
{
	protected $source_tab;
	
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
				$request->addFeedback('Changes successful');
				$this->init($request);
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
		$this->post_id = $request->getProperty('post_id');
		// process the discipline information
		$discipline_ids = array();
		$properties = $request->getProperties();
		foreach ($properties as $key => $item)
		{
			$discipline_id = 0;
			if (strpos($key, 'decision_maker_confirmed_') !== false)
			{
				$discipline_id = trim(substr($key,25));
			} 
			elseif (strpos($key, 'agency_user_confirmed_') !== false)
			{
				$discipline_id = trim(substr($key,22));
			} 
			elseif (strpos($key, 'review_date_confirmed_') !== false)
			{
				$discipline_id = trim(substr($key,22));
			}

			if ($discipline_id != 0 && !in_array($discipline_id, $discipline_ids))
			{
				$discipline_ids[] = $discipline_id;	
			}
			
		}
		
		foreach ($discipline_ids as $discipline_id)
		{
			// set each variable that will hold info - so we can check later on whether we need to update
			// that bit of information 
			$decision_maker_type_id = null;
			$agency_user_type_id = null;
			$review_date_year = null;
			$review_date_month = null;
			
			// check each checkbox has been ticked. If so, process the info in the associated data field
			// decision maker type id
			if ($request->getProperty("decision_maker_confirmed_" . $discipline_id) == true)
			{
				$decision_maker_type_id = $request->getProperty("decision_maker_type_id_" . $discipline_id);
			}
			
			// agency user type id
			if ($request->getProperty("agency_user_confirmed_" . $discipline_id) == true)
			{
				$agency_user_type_id = $request->getProperty("agency_user_type_id_" . $discipline_id);
			}
			
			// agency review date
			if ($request->getProperty("review_date_confirmed_" . $discipline_id) == true)
			{
				$review_date_year = $request->getProperty($discipline_id . 'Year');
				$review_date_month = $request->getProperty($discipline_id . 'Month');
			}
			
			// process DM record
			if (!is_null($decision_maker_type_id))
			{
				$dm_record = app_domain_PostDecisionMaker::findbyPostIdAndDisciplineId($this->post_id, $discipline_id);
				
				if (!is_object($dm_record))
				{
					$dm_record = new app_domain_PostDecisionMaker();
					$dm_record->setPostId($this->post_id);
					$dm_record->setDisciplineId($discipline_id);
				}
				
				$dm_record->setTypeId($decision_maker_type_id);
				$dm_record->setLastUpdatedBy($_SESSION['auth_session']['user']['id']);
				$dm_record->setLastUpdatedAt(date('Y-m-d H:i:s'));
				$dm_record->commit();
			}
			
			// process agency user record
			if (!is_null($agency_user_type_id))
			{
				$au_record = app_domain_PostAgencyUser::findbyPostIdAndDisciplineId($this->post_id, $discipline_id);
				
				if (!is_object($au_record))
				{
					$au_record = new app_domain_PostAgencyUser();
					$au_record->setPostId($this->post_id);
					$au_record->setDisciplineId($discipline_id);
				}
				
				$au_record->setTypeId($agency_user_type_id);
				$au_record->setLastUpdatedBy($_SESSION['auth_session']['user']['id']);
				$au_record->setLastUpdatedAt(date('Y-m-d H:i:s'));
				$au_record->commit();
			}
			
			// process agency review date
			if (!is_null($review_date_year))
			{
				$rd_record = app_domain_PostDisciplineReviewDate::findbyPostIdAndDisciplineId($this->post_id, $discipline_id);
				
				if (!is_object($rd_record))
				{
					$rd_record = new app_domain_PostDisciplineReviewDate();
					$rd_record->setPostId($this->post_id);
					$rd_record->setDisciplineId($discipline_id);
				}
				
				$rd_record->setYearMonth($review_date_year . $review_date_month);
				$rd_record->setLastUpdatedBy($_SESSION['auth_session']['user']['id']);
				$rd_record->setLastUpdatedAt(date('Y-m-d H:i:s'));
				$rd_record->commit();
			}
		}
		
		return true;
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('post_id', $request->getProperty('post_id'));

		// -- Start of marketing disciplines grid --
		// get list of discplines assigned to this initiative
		$disciplines_grid = app_domain_Post::findDisciplinesGridByPostId($request->getProperty('post_id'));

		$request->setObject('disciplines_grid', $disciplines_grid);
		
		// decision maker options
		$decison_maker_options = app_domain_Communication::lookupDecisonMakerOptions();
		$request->setObject('decison_maker_options', $decison_maker_options);
		
		// agency user options
		$agency_user_options = app_domain_Communication::lookupAgencyUserOptions();
		$request->setObject('agency_user_options', $agency_user_options);
		
		// available discipline options - ie those not already shown in the disciplines grid
		$disciplines = app_domain_Communication::lookupDisciplineOptions();
		$options = array();
		$found = false;
			
		foreach ($disciplines as $discipline)
		{
			foreach ($disciplines_grid as $item)
			{
				if ($item['discipline_id'] == $discipline['id'])
				{
					$found = true;
					break;
				}
			}
			if (!$found)
			{
				$options[$discipline['id']] = @C_String::htmlDisplay(ucfirst($discipline['description']));
			}
			$found = false;
		}
				
		$request->setObject('available_disciplines', $options);

			
	}	
}
?>
