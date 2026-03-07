<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/domain/Company.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/PostDecisionMaker.php');
require_once('app/mapper/PostDecisionMakerMapper.php');
require_once('app/domain/PostDisciplineReviewDate.php');
require_once('app/mapper/PostDisciplineReviewDateMapper.php');
require_once('app/domain/Communication.php');
require_once('app/mapper/CommunicationMapper.php');
require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');
require_once('app/domain/InformationRequest.php');
require_once('app/mapper/InformationRequestMapper.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_CommunicationCreate extends app_command_ManipulationCommand
{
	protected $post_id;
	protected $initiative_id;
	protected $post_initiative_id;
	protected $source_tab;

	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');

		if ($task == 'cancel')
		{
			// destroy communication session
			$_SESSION['auth_session']['communication'] = null;
		}
		elseif ($task == 'save')
		{
			if ($this->processForm($request))
			{
				// destroy communication session
				$_SESSION['auth_session']['communication'] = null;
				header(	'Location: index.php?cmd=CommunicationSaved&post_id=' . $this->post_id .
						'&initiative_id=' . $this->initiative_id .
						'&post_initiative_id=' . $this->post_initiative_id .
						'&source_tab=' . $this->source_tab);
				exit;
			}
			else
			{
				return self::statuses('CMD_ERROR');
			}
		}
		else
		{
			// set up a session array to hold any communication related information - eg meetings, info reqs etc
			unset($_SESSION['auth_session']['communication']);
			$_SESSION['auth_session']['communication'] = array();

			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function processForm($request)
	{
		$this->source_tab = $request->getProperty('source_tab');
		$communication_id = $request->getProperty('communication_id');

		if ($communication_id)
		{
			$communication = app_domain_Communication::find($communication_id);
		}
		else
		{
			$communication = new app_domain_Communication();
		}

		if ($request->getProperty('post_initiative_id') != '')
		{
			$communication->setPostInitiativeId($request->getProperty('post_initiative_id'));
			$post_initiative = app_domain_PostInitiative::find($request->getProperty('post_initiative_id'));
		}

		else
		{
			// Send feedback that incorrect parameters supplied
			$request->setProperty('feedback', 'Incorrect parameters supplied');
			return;
		}

//		// need to know the initiative id later on
		$communication->setUserId($_SESSION['auth_session']['user']['id']);
		$communication->setLeadSourceId($request->getProperty('lead_source_id'));
		$communication->setTypeId(1);
		$communication->setCommunicationDate(date('Y-m-d H:i:s'));
		$communication->setDirection('out');
		$communication->setNextActionBy($request->getProperty('next_action_by'));

		$effective = $request->getProperty('effective');
		if (isset($effective))
		{
			$effective = 'effective';
			$communication_ote = $request->getProperty('ote');
			if (isset($communication_ote))
			{
				$ote = true;
			}
			else
			{
				$ote = false;
			}
			$communication->setOTE($ote);

			if ($request->getProperty('targeting') > 0) {
				$communication->setTargetingId($request->getProperty('targeting'));
			}
			
			if ($request->getProperty('receptiveness') > 0) {
				$communication->setReceptivenessId($request->getProperty('receptiveness'));
			}
			
			$communication->setIsEffective(true);

			// NOTE: 23/10/2007 - DM info now recorded as part of the discipline grid
			//$communication->setDecisionMakerTypeId($request->getProperty('decision_maker_type_id'));

			// NOTE: 14/05/2007 - not yet recording agency user info in the communication object, but needed for the status calculation
			//$agency_user = (bool)$request->getProperty('agency_user');
			$agency_user = 0;
		}
		else
		{
			$effective = 'non-effective';
			$ote = false;
			$communication->setIsEffective(false);
		}
		$communication->setEffective($effective);


		if ($request->getProperty('next_communication_date') != '')
		{
			$priorityCallBack = $request->getProperty('priority_callback');
			if (isset($priorityCallBack)) {
				$communication->setPriorityCallback(true);
				$post_initiative->setPriorityCallback(true);
			} else {
				$communication->setPriorityCallback(false);
				$post_initiative->setPriorityCallback(false);
			}
			
			$next_communication_date = Utils::DateFormat($request->getProperty('next_communication_date'), 'DD/MM/YYYY', 'YYYY-MM-DD') . ' ' . Utils::getFormTimeSmarty('next_communication_time');
			$communication->setNextCommunicationDate($next_communication_date);
			$post_initiative->setNextCommunicationDate($next_communication_date);
			$communication->setNextCommunicationDateReasonId($request->getProperty('next_communication_date_reason_id'));
			
			
		}
		else
		{
			$next_communication_date = null;
		}

		$communication->setComments($request->getProperty('comments'));
		$communication->setNotes($request->getProperty('notes'));

		// Process meetings
		// NOTE: we need $request->getProperty('communication_status_id') == 12 (meeting set) to make sure we process the first meeting created by the user
		if ($request->getProperty('meeting_id') != '' || $request->getProperty('communication_status_id') == 12 || $request->getProperty('communication_status_id') == 13)
		{
			$meeting_id = $request->getProperty('meeting_id');

			// If the user changes the status to 'Follow-up meeting' (status_id = 13) then we need to save the
			// existing meeting with the status 'Follow-up meeting' and create a new meeting with the status 'Meeting set'
			if ($meeting_id != '')
			{
				if ($request->getProperty('communication_status_id') != 13 && $request->getProperty('communication_status_id') >= 12)
				{
					$meeting = app_domain_Meeting::find($meeting_id);
					$meeting->setPostInitiativeId($post_initiative->getId());
					$meeting->setStatusId($request->getProperty('communication_status_id'));
					// As at 11/04/07 not using meeting type - set all meetings to 'initial'
					$meeting->setTypeId(1);
					$meeting->setLocationId($request->getProperty('meeting_location'));
					$meeting->setNbmPredictedRating($request->getProperty('nbm_predicted_rating'));
					$meeting_date_time = $request->getProperty('app_domain_Meeting_date') . ' ' . Utils::getFormTime($request->getProperty('meeting_time_Hour'), $request->getProperty('meeting_time_Minute'));
					$meeting->setDate(Utils::DateFormat($meeting_date_time, 'DD/MM/YYYY HH:MM:SS', 'YYYY-MM-DD HH:MM:SS'));
                    $meeting->setModifiedAt(date('Y-m-d H:i:s'));
                    $meeting->setModifiedBy($_SESSION['auth_session']['user']['id']);

					// For certain status' we want to mark the meeting as not current
					switch ($request->getProperty('communication_status_id'))
					{
						case 20: // meeting cancelled
						case 22: // meeting cancelled
						case 21: // follow-up meeting cancelled
						case 23: // follow-up meeting cancelled
						case 28: // brief received
						case 29: // proposal
						case 30: // win
						case 31: // gone-cold
							$meeting->setIsCurrent(0);
							break;
						default:
							break;
					}
				}
				elseif ($request->getProperty('communication_status_id') == 13)	//follow-up meeting set - need this option here as the user might in future be able to select meet attended -> followup meeting set
				{
					$meeting = app_domain_Meeting::find($meeting_id);

					// if the current meeting has a status of 13 then this is the CURRENT meeting so we don't want to create ANOTHER meeting
					if ($meeting->getStatusId() == 13)
					{
						$meeting->setPostInitiativeId($post_initiative->getId());
						$meeting->setStatusId($request->getProperty('communication_status_id'));
						// As at 11/04/07 not using meeting type - set all meetings to 'initial'
						$meeting->setTypeId(1);
						$meeting->setLocationId($request->getProperty('meeting_location'));
						$meeting->setNbmPredictedRating($request->getProperty('nbm_predicted_rating'));
						$meeting_date_time = $request->getProperty('app_domain_Meeting_date') . ' ' . Utils::getFormTime($request->getProperty('meeting_time_Hour'), $request->getProperty('meeting_time_Minute'));
						$meeting->setDate(Utils::DateFormat($meeting_date_time, 'DD/MM/YYYY HH:MM:SS', 'YYYY-MM-DD HH:MM:SS'));
                        $meeting->setModifiedAt(date('Y-m-d H:i:s'));
                        $meeting->setModifiedBy($_SESSION['auth_session']['user']['id']);

						// For certain status' we want to mark the meeting as not current
						switch ($request->getProperty('communication_status_id'))
						{
							case 20: // meeting cancelled
							case 22: // meeting cancelled
							case 21: // follow-up meeting cancelled
							case 23: // follow-up meeting cancelled
							case 28: // brief received
							case 29: // proposal
							case 30: // win
							case 31: // gone-cold
								$meeting->setIsCurrent(0);
								break;
							default:
								break;
						}
					}
					else
					{
						$meeting->setPostInitiativeId($post_initiative->getId());
						$meeting->setIsCurrent(0);
						// don't update the last modified by/at combo in this case

						$meeting_followup = new app_domain_Meeting();
						$meeting_followup->setPostInitiativeId($post_initiative->getId());
						$meeting_followup->setCommunicationId($communication->getId());
						$meeting_followup->setStatusId($request->getProperty('communication_status_id'));
						$meeting_followup->setIsCurrent(1);
						$meeting_followup->setCreatedAt(date('Y-m-d H:i:s'));
						$meeting_followup->setCreatedBy($_SESSION['auth_session']['user']['id']);
						$meeting_followup->setModifiedAt(date('Y-m-d H:i:s'));
                        $meeting_followup->setModifiedBy($_SESSION['auth_session']['user']['id']);
						// As at 11/04/07 not using meeting type - set all meetings to 'initial'
						$meeting_followup->setTypeId(1);
						$meeting_followup->setLocationId($request->getProperty('meeting_location'));
						$meeting_followup->setNbmPredictedRating($request->getProperty('nbm_predicted_rating'));
						$meeting_date_time = $request->getProperty('app_domain_Meeting_date') . ' ' . Utils::getFormTime($request->getProperty('meeting_time_Hour'), $request->getProperty('meeting_time_Minute'));
						$meeting_followup->setDate(Utils::DateFormat($meeting_date_time, 'DD/MM/YYYY HH:MM:SS', 'YYYY-MM-DD HH:MM:SS'));
					}
				}
				else
				{
					// do nothing - the status selected by the user is not a meeting status then the meeting should be marked as non-current
					if ($request->getProperty('communication_status_id') < 12)
					{
						// don't update the last modified by/at combo in this case

						$meeting = app_domain_Meeting::find($meeting_id);
						$meeting->setIsCurrent(0);
					}
				}
			}
			else
			{
				if ($request->getProperty('communication_status_id') == 12)
				{
					$meeting = new app_domain_Meeting();
					$meeting->setCommunicationId($communication->getId());
					$meeting->setIsCurrent(1);
					$meeting->setCreatedAt(date('Y-m-d H:i:s'));
					$meeting->setCreatedBy($_SESSION['auth_session']['user']['id']);
					$meeting->setModifiedAt(date('Y-m-d H:i:s'));
                    $meeting->setModifiedBy($_SESSION['auth_session']['user']['id']);
					$meeting->setPostInitiativeId($post_initiative->getId());
					$meeting->setStatusId($request->getProperty('communication_status_id'));
					// As at 11/04/07 not using meeting type - set all meetings to 'initial'
					$meeting->setTypeId(1);
					$meeting->setLocationId($request->getProperty('meeting_location'));
					$meeting->setNbmPredictedRating($request->getProperty('nbm_predicted_rating'));
					$meeting_date_time = $request->getProperty('app_domain_Meeting_date') . ' ' . Utils::getFormTime($request->getProperty('meeting_time_Hour'), $request->getProperty('meeting_time_Minute'));
					$meeting->setDate(Utils::DateFormat($meeting_date_time, 'DD/MM/YYYY HH:MM:SS', 'YYYY-MM-DD HH:MM:SS'));
				}
				elseif ($request->getProperty('communication_status_id') == 13)
				{
					$meeting_followup = new app_domain_Meeting();
					$meeting_followup->setPostInitiativeId($post_initiative->getId());
					$meeting_followup->setCommunicationId($communication->getId());
					$meeting_followup->setStatusId($request->getProperty('communication_status_id'));
					$meeting_followup->setIsCurrent(1);
					$meeting_followup->setCreatedAt(date('Y-m-d H:i:s'));
					$meeting_followup->setCreatedBy($_SESSION['auth_session']['user']['id']);
					$meeting_followup->setModifiedAt(date('Y-m-d H:i:s'));
                    $meeting_followup->setModifiedBy($_SESSION['auth_session']['user']['id']);
					// As at 11/04/07 not using meeting type - set all meetings to 'initial'
					$meeting_followup->setTypeId(1);
					$meeting_followup->setLocationId($request->getProperty('meeting_location'));
					$meeting_followup->setNbmPredictedRating($request->getProperty('nbm_predicted_rating'));
					$meeting_date_time = $request->getProperty('app_domain_Meeting_date') . ' ' . Utils::getFormTime($request->getProperty('meeting_time_Hour'), $request->getProperty('meeting_time_Minute'));
					$meeting_followup->setDate(Utils::DateFormat($meeting_date_time, 'DD/MM/YYYY HH:MM:SS', 'YYYY-MM-DD HH:MM:SS'));
				}
				else
				{
					// do nothing
				}
			}
		}

		// Need to check for scenario where a new post initiative record is being added. In this case $request->getProperty('communication_status_id') will
		// be blank
		if ($request->getProperty('communication_status_id') == '')
		{
			$status_id = $request->getProperty('communication_status_id_select');
		}
		else
		{
			$status_id = $request->getProperty('communication_status_id');
		}

		// If communication_status_id_select is blank then it means a disabled option was selected - so we need to recalculate as it may have changed depending
		// on the choices made by the user in other parts of the communication screen
		// Or, if communication_status_id == -1 then it means the user asked for the system to calculate the status
		if ($request->getProperty('communication_status_id_select') == '' || $request->getProperty('communication_status_id_select') == 0)
		{
			$status_id = app_domain_Communication::calculateStatus($request->getProperty('post_id'), $next_communication_date, $agency_user, $ote, $request->getProperty('next_communication_date_reason_id'),$request->getProperty('targeting'), $request->getProperty('receptiveness'));
		}

		$post_initiative->setStatusId($status_id);
        $post_initiative->setLeadSourceId($request->getProperty('lead_source_id'));

        if ($request->getProperty('data_source_id')) {
            if ($request->getProperty('data_source_id') != $post_initiative->getDataSourceId()) {
                $post_initiative->setDataSourceChangedDate(Utils::getTimestamp());
            }
            $post_initiative->setDataSourceId($request->getProperty('data_source_id'));
            $data_source = app_model_DataSource::where('id', $request->getProperty('data_source_id'))->first();
            $post_initiative_model = app_model_PostInitiative::where('id', $post_initiative->getId())->first();
            $current_tag = $post_initiative_model->tags()->wherePivot('data_source', true)->first();

            if ($current_tag) {
                $current_tag->value = $data_source->description;
                $current_tag->save();
            } else {
                $tag = new app_model_Tag(['value' => $data_source->description, 'category_id' => 3]);
                $post_initiative_model->tags()->save($tag, ['data_source' => true]);
            }
        }

		$post_initiative->setNextActionBy($request->getProperty('next_action_by'));
		$comment = $request->getProperty('comments');
		if ($comment != '')
		{
			$post_initiative->setComment($comment);
		}

		$communication->setStatusId($status_id);
		$communication->commit();

		// now we can update the last communication id - before this point we would violate foeign key constraints in the database
		$post_initiative->setLastCommunicationId($communication->getId());
		if ($effective == 'effective')
        {
        	$post_initiative->setLastEffectiveCommunicationId($communication->getId());
        }

        $post_initiative->commit();
        
        $post = app_domain_Post::find($post_initiative->getPostId());
        if ($request->getProperty('global_data_source_id') != $post->getDataSourceId()) {
            $post->setDataSourceChangedDate(Utils::getTimestamp());
        }
        $post->setDataSourceId($request->getProperty('global_data_source_id'));
        $post->commit();

		if ($request->getProperty('meeting_id') != ''|| $request->getProperty('communication_status_id') == 12 || $request->getProperty('communication_status_id') == 13) //meeting set
		{
			// Commit meeting at this point otherwise foreign key constraint on tbl_communications.id fails
			if (is_object($meeting))
			{
				//19/02/2010 - DMC - need to update the field attended_date
				// if status is >=24 (meeting/follow-up meeting attended) then set attended_date = date, else null
				if ($meeting->getStatusId() >= 24) {
					$meeting->setAttendedDate($meeting->getDate());
				}
				else {
					$meeting->setAttendedDate(null);
				}
				$meeting->commit();
			}

			if (is_object($meeting_followup))
			{
				//19/02/2010 - DMC - need to update the field attended_date
                // if status is >=24 then set attended_date = date, else null
                if ($meeting_followup->getStatusId() >= 24) {
                    $meeting_followup->setAttendedDate($meeting_followup->getDate());
                }
                else {
                    $meeting_followup->setAttendedDate(null);
                }
				$meeting_followup->commit();
			}

			// now save any meeting actions which are in the session
			if (isset($_SESSION['auth_session']['communication']['post_initiative_actions']))
			{
				foreach ($_SESSION['auth_session']['communication']['post_initiative_actions'] as $action)
				{

					if ($action->getMeetingId() == '' && $action->getTypeId() == 1)
					{
						// if an action does not have a meeting_id associated with it
						// and $meeting_followup is set then use the id of $meeting_followup.
						// Otherwise use $meeting.id
						// This is because it is not possible that a meeting AND a follow-up
						// meeting can be set in the same call - so it $meeting_followup exists
						// then use this in preference to $meeting
						if(is_object($meeting_followup))
						{
							$action->setMeetingId($meeting_followup->getId());
						}
						else
						{
							$action->setMeetingId($meeting->getId());
						}

						if ($action->getPostInitiativeId() == '')
						{
							$action->setPostInitiativeId($post_initiative->getId());
						}
						$action->commit();
					}
				}
			}
		}

		// now save any other post initiative actions
		if (isset($_SESSION['auth_session']['communication']['post_initiative_actions']))
		{
			foreach ($_SESSION['auth_session']['communication']['post_initiative_actions'] as $action)
			{
				$do_commit_action = false;
				if ($action->getTypeId() != 1)
				{
					if ($action->getPostInitiativeId() == '')
					{
						$action->setPostInitiativeId($post_initiative->getId());
					}
					$do_commit_action = true;
				}

				if ($action->getTypeId() == 2)
				{
					if ($action->getCommunicationId() == '')
					{
						$action->setCommunicationId($communication->getId());
					}
// 					print_r($action);
					$do_commit_action = true;
				}

				if ($do_commit_action)
				{
					$action->commit();
				}
			}
		}

		$this->post_id = $post_initiative->getPostId();
		$this->initiative_id = $post_initiative->getInitiativeId();
		$this->post_initiative_id = $post_initiative->getId();

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
				$dm_record->setCommunicationId($communication->getId());
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
				$au_record->setCommunicationId($communication->getId());
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
				$rd_record->setCommunicationId($communication->getId());
				$rd_record->commit();
			}
		}

		// check post incumbent agencies
		if (isset($_SESSION['auth_session']['communication']['post_incumbent_agencies']))
		{
			if (isset($_SESSION['auth_session']['communication']['post_incumbent_agencies']))
			{
				foreach ($_SESSION['auth_session']['communication']['post_incumbent_agencies'] as $post_incumbent_agency)
				{
					$post_incumbent_agency->setCommunicationId($communication->getId());
					$post_incumbent_agency->commit();
				}
			}
		}

//		if ($status_id < 12)
//		{
//			// create 'standard note' for this communication_id
//			$standard_note = $this->makeCommunicationStandardNote($communication, $this->initiative_id);
//
//			if ($standard_note != '')
//			{
//				if ($request->getProperty('notes') != '')
//				{
//					$standard_note .= "\n\n" . $request->getProperty('notes');
//				}
//
//				$communication->setNotes($standard_note);
//				$communication->commit();
//			}
//		}
		return true;
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('source_tab', $request->getProperty('source_tab'));

		// Get company
		$company_id = $request->getProperty('company_id');
		$company = app_domain_Company::find($company_id);
		$request->setObject('company', $company);

		// Pass post object
		$post = app_domain_Post::find($request->getProperty('post_id'));
		$request->setObject('post', $post);

		// If we have a post_initiative_id passed in....
		if ($request->getProperty('post_initiative_id') != '')
		{
			// Pass post_initiative_id
			$request->setObject('post_initiative_id', $request->getProperty('post_initiative_id'));

			// Get client name
			$client = app_domain_Client::findByPostInitiativeId($request->getProperty('post_initiative_id'));
			$request->setObject('client', $client);

			// Get status
			$post_initiative = app_domain_PostInitiative::find($request->getProperty('post_initiative_id'));
			$request->setObject('post_initiative', $post_initiative);

			$status_id = $post_initiative->getStatusId();
			$request->setProperty('status_id', $post_initiative->getStatusId());

			// Lead source
			$lead_source_id = $post_initiative->getLeadSourceId();
            $request->setProperty('lead_source_selected', $lead_source_id);
            
            // Data source
            $data_source_current = $post_initiative->getDataSource();
            $request->setProperty('data_source_current', $data_source_current);
            $data_source_current_id = $post_initiative->getDataSourceId();
            $request->setProperty('data_source_current_id', $data_source_current_id);
            $global_data_source_id = $post->getDataSourceId();
            $request->setProperty('global_data_source_selected', $global_data_source_id);
            
			// Get last communication
			$last_communication = app_domain_Communication::findLastByPostInitiativeId($request->getProperty('post_initiative_id'));
			$request->setObject('last_communication', $last_communication);

			//get meeting information
			$meetings = app_domain_Meeting::findByPostInitiativeId($request->getProperty('post_initiative_id'));
			$request->setObject('meetings', $meetings);

			// get current meeting - if one exists
			foreach ($meetings as $meeting)
			{
				// Assume we only ever have one current meeting
				if ($meeting->getIsCurrent())
				{

					// Meeting feedback rating - select default
					$request->setProperty('meeting_location_selected', $meeting->getLocationId());

					// Meeting feedback rating - select default
					$request->setProperty('nbm_predicted_rating_selected', $meeting->getNbmPredictedRating());

					$request->setObject('meeting', $meeting);
					break;
				}
			}

			//get meeting action information
			$meeting_action_count = app_domain_Action::findCurrentCountByPostInitiativeIdAndMultipleTypeIds($request->getProperty('post_initiative_id'), array(1,3,4));
			$request->setProperty('meeting_action_count', $meeting_action_count);

			//get actions information
			$actions = app_domain_Action::findCurrentCountByPostInitiativeId($request->getProperty('post_initiative_id'));
			$request->setObject('actions', $actions);

			//get overdue actions information
			$overdue_actions = app_domain_Action::findOverdueCountByPostInitiativeId($request->getProperty('post_initiative_id'));
			$request->setObject('overdue_actions', $overdue_actions);

			//get information request information
			$information_request_count = app_domain_Action::findCurrentCountByPostInitiativeIdAndTypeId($request->getProperty('post_initiative_id'), 2);
			$request->setProperty('information_request_count', $information_request_count);

//			$information_requests = app_domain_InformationRequest::findByPostInitiativeId($request->getProperty('post_initiative_id'));
//			$request->setObject('information_requests', $information_requests);
		}
		elseif ($request->getProperty('initiative_id') != '' && $request->getProperty('post_id') != '')
		{
			// Pass initiative_id
			$request->setProperty('initiative_id', $request->getProperty('initiative_id'));

			if ($post_initiative = app_domain_PostInitiative::findByPostAndInitiative($request->getProperty('post_id'), $request->getProperty('initiative_id')))
			{
				// do nothing
			}
			else
			// create new post initiative
			{
				// make new post initiative record - IS THIS EVER USED????
				$post_initiative = new app_domain_PostInitiative();
				$post_initiative->setPostId($request->getProperty('post_id'));
				$post_initiative->setInitiativeId($request->getProperty('initiative_id'));
				$post_initiative->setLeadSourceId(7);
			}

			$request->setProperty('post_initiative_id', $post_initiative->getId());

			// Get client name
			$client = app_domain_Client::findByInitiativeId($request->getProperty('initiative_id'));
			$request->setObject('client', $client);
		}
		else
		{
			// Send feedback that incorrect parameters supplied
			$request->setProperty('feedback', 'Incorrect parameters supplied');
			return;
		}

		// Get lookup information
		if ($items = app_domain_Communication::lookupNextCommunicationReasons())
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('next_communication_reasons', $options);
		}

		if (!isset($status_id))
		{
			$status_id = null;
		}

		if ($items = app_domain_PostInitiative::lookupLeadSourceAll());
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('lead_source_options', $options);
        }
        
        if ($items = app_domain_PostInitiative::lookupDataSourcesAll());
        {
            $options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
            $request->setObject('data_source_options', $options);

            $spoken = app_model_DataSource::where('description', "We've spoken before")->first();
            $spokenId = $spoken ? $spoken->id : '';
            $request->setProperty('data_source_spoken_id', $spokenId);

            $suggested = app_model_DataSource::where('description', 'Colleague suggested')->first();
            $suggestedId = $suggested ? $suggested->id : '';
            $request->setProperty('data_source_suggested_id', $suggestedId);
        }

        if ($items = app_domain_Post::lookupDataSourcesAll());
        {
            $options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
            $request->setObject('global_data_source_options', $options);
        }

		if ($items = app_domain_Meeting::lookupLocationAll());
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('meeting_location_options', $options);
		}

		$options = array();
		for ($x=1;$x<=5;$x++)
		{
			$options[$x] = $x;
		}
		$request->setObject('nbm_predicted_rating_options', $options);

		// -- Start of marketing disciplines grid --
		// get list of discplines assigned to this initiative
		$campaign_disciplines_grid = app_domain_Post::findDisciplinesGridByPostIdAndCampaignId($request->getProperty('post_id'), $request->getProperty('initiative_id'));
		$request->setObject('campaign_disciplines_grid', $campaign_disciplines_grid);

		$non_campaign_disciplines_grid = app_domain_Post::findNonCampaignDisciplinesGridByPostId($request->getProperty('post_id'), $request->getProperty('initiative_id'));
		$request->setObject('non_campaign_disciplines_grid', $non_campaign_disciplines_grid);

		// decision maker options
		$decison_maker_options = app_domain_Communication::lookupDecisonMakerOptions();
		$request->setObject('decison_maker_options', $decison_maker_options);

		// agency user options
		$agency_user_options = app_domain_Communication::lookupAgencyUserOptions();
		$request->setObject('agency_user_options', $agency_user_options);

		// available discipline options - ie those not already shown in the disciplines grid
		$disciplines = app_domain_Communication::lookupDisciplineOptions();
		$options = array();
		$options_1 = array();
		$found = false;

		foreach ($disciplines as $discipline)
		{
			foreach ($campaign_disciplines_grid as $item)
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

		foreach ($options as $key => $option)
		{
			foreach ($non_campaign_disciplines_grid as $item)
			{
				if ($item['discipline_id'] == $key)
				{
					$found = true;
					break;
				}
			}
			if (!$found)
			{
				$options_1[$key] = @C_String::htmlDisplay(ucfirst($option));

			}
			$found = false;
		}

		$request->setObject('available_disciplines', $options_1);

		$is_auto_calc = false;
		if ($items = app_domain_Communication::lookupStatusForHtmlSelect($status_id));
		{
			$options = array();
			foreach ($items as $item)
			{

				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
				if ($item['is_auto_calculate'])
				{
					$is_auto_calc = true;
				}
			}

			// can't add new element to top of array as array_unshift renumbers the whole array'
//			if ($is_auto_calc)
//			{
//				array_unshift($options, 'Other (system generated) ...');
//			}

			$request->setObject('status_options', $options);
			$request->setProperty('status_is_auto_calculate', $is_auto_calc);

		}

//		$request->setObject('status_html', $status_html);


		$targeting = app_domain_Communication::lookupCommunicationTargeting();
		$request->setObject('targeting', $targeting);
		$receptiveness = app_domain_Communication::lookupCommunicationReceptiveness();
		$request->setObject('receptiveness', $receptiveness);

	}

	function makeCommunicationStandardNote($communication, $initiative_id)
	{

		// callback
		if (!is_null($communication->getNextCommunicationDate()))
		{
			$note = 'We are due to make a call-back on ' . date('l jS F Y', strtotime($communication->getNextCommunicationDate())) . ".\n\n";
		}

		$post = app_domain_Post::findByPostInitiativeId($communication->getPostInitiativeId());

		if (is_object($post->getContact()))
		{
			$contact_first_name = ucfirst($post->getContact()->getFirstName());
		}
		else
		{
			$contact_first_name = 'the contact';
		}
		if ($communication->getIsEffective())
		{
			// match to offer
			// receptiveness
			$note .= ucfirst($contact_first_name) . ' was ' . strtolower($communication->getReceptivenessDescription()) .' to the call and the match to offer was ' .
			strtolower($communication->getTargetingDescription()) . ".\n\n";
		}

		// dm
		// agency user
		// review date
		$campaign_id = app_domain_Campaign::findCampaignIdByInitiativeId($initiative_id);
		$disciplines = app_domain_Communication::findCampaignDisciplineRecordsByCommunicationId($campaign_id, $communication->getId());
		$discipline_note = '';

		foreach ($disciplines as $discipline)
		{
			// DM /Agency user note
			$discipline_note = app_domain_Campaign::makeDecisionMakerAndAgencyUserStandardNote($discipline);

			// incumbents note
			$post_incumbent_agencies = app_domain_PostIncumbentAgency::findAllByDisciplineIdAndCommunicationId($discipline['tiered_characteristic_id'], $communication->getId());
			$discipline_note .= app_domain_Campaign::makeIncumbentAgencyStandardNote($post_incumbent_agencies, $contact_first_name);

			if ($discipline_note != '')
			{
				$note .= 'For ' . $discipline['discipline'] . ' ' .
						$contact_first_name .
						$discipline_note . "\n\n";
			}
		}

		return substr($note, 0, -2);
	}
}
?>
