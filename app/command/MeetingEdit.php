<?php
require_once('app/command/ManipulationCommand.php');
require_once('app/domain/Meeting.php');
require_once('app/mapper/MeetingMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

class app_command_MeetingEdit extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{

		}
		elseif ($task == 'save')
		{
			if ($errors = $this->getFormErrors($request))
			{
				// Ensure validation errors are passed in to the view
				$this->handleValidationErrors($request, $errors);
				
				// Ensure field values are made sticky
				$this->handleStickyFields($request);
				
				$this->init($request);
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
				header('Location: index.php?cmd=MeetingSaved&post_initiative_id=' . $request->getProperty('post_initiative_id') . '&company_id=' . $request->getProperty('company_id') .'&source_tab=' . $request->getProperty('source_tab'));
				exit;
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
	 * Checks form fields against validation rules.
	 * @param app_controller_Request $request the object from which the form 
	 *        values can be accessed
	 * @return array an array of errors, empty if none found
	 */
	protected function getFormErrors(app_controller_Request $request)
	{
		$errors = array();
		
		foreach ($errors as $key => $error)
		{
			if (empty($error))
			{
				unset($errors[$key]);
			}
		}
		return $errors;
	}
	
	 /** Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		$meeting_id = $request->getProperty('id');
		
		if ($meeting_id)
		{
			//need to check the session in case this meeting is currently being edited through the communication.
			//NOTE: it is important that we check the session first so that we include any changes which may have been made in a previous edit and 
			//stored in the current session
			if (isset($_SESSION['auth_session']['communication'])) 
			{
				if (isset($_SESSION['auth_session']['communication']['meetings']))
				{
					foreach ($_SESSION['auth_session']['communication']['meetings'] as $session_meeting)
					{
						if ($session_meeting->getId() == $request->getProperty('id'))
						{
							$meeting = $session_meeting;
						}
					}
				}
			} 
			//if not in session then look in the database for the object
			if (!is_object($meeting))
			{
				$meeting = app_domain_Meeting::find($meeting_id);
			}
		}
		else
		{
			$meeting = new app_domain_Meeting();
		} 

		$meeting->setFeedbackRating($request->getProperty('feedback_rating'));
		if ($request->getProperty('feedback_decision_maker'))
		{
			$meeting->setFeedbackDecisionMaker(1);
		}
		else
		{
			$meeting->setFeedbackDecisionMaker(0);
		}
		if ($request->getProperty('feedback_agency_user'))
		{
			$meeting->setFeedbackAgencyUser(1);
		}
		else
		{
			$meeting->setFeedbackAgencyUser(0);
		}
		
		if ($request->getProperty('feedback_budget_available'))
		{
			$meeting->setFeedbackBudgetAvailable(1);
		}
		else
		{
			$meeting->setFeedbackBudgetAvailable(0);
		}
		
		if ($request->getProperty('feedback_receptive'))
		{
			$meeting->setFeedbackReceptive(1);
		}
		else
		{
			$meeting->setFeedbackReceptive(0);
		}
		
		if ($request->getProperty('feedback_targeting'))
		{
			$meeting->setFeedbackTargeting(1);
		}
		else
		{
			$meeting->setFeedbackTargeting(0);
		}

		$meeting->setFeedbackComments($request->getProperty('feedback_comments'));
		$meeting->setFeedbackNextSteps($request->getProperty('feedback_next_steps'));
		$meeting->commit();
		return true;
		
	}
	
	 /** Takes a list of the fields being used and re-assigns any values entered 
	 * to make sticky.
	 * @param app_controller_Request $request 
	 * @param array $field_spec associative array of fields in use, where the 
	 *        key is the field name
	 */
	protected function handleStickyFields(app_controller_Request $request)
	{
		// Company fields
		$sticky_fields = array(	'post_initiative_id',
								'company_id' ,
								'app_domain_Meeting_date', 
								'app_domain_Meeting_notes');
		
		$this->doHandleStickyFields($request, $sticky_fields);
	}
	
	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		$meeting_id = $request->getProperty('id');
		$meeting = '';
		
		if ($meeting_id)
		{
			//need to check the session in case this meeting is currently being edited through the communication.
			//NOTE: it is important that we check the session first so that we include any changes which may have been made in a previous edit and 
			//stored in the current session
			if (isset($_SESSION['auth_session']['communication'])) 
			{
				if (isset($_SESSION['auth_session']['communication']['meetings']))
				{
					foreach ($_SESSION['auth_session']['communication']['meetings'] as $session_meeting)
					{
						if ($session_meeting->getId() == $request->getProperty('id'))
						{
							$meeting = $session_meeting;
						}
					}
				}
			} 
			//if not in session then look in the database for the object
			if (!is_object($meeting))
			{
				$meeting = app_domain_Meeting::find($meeting_id);
			}
		}
		else
		{
			$meeting = new app_domain_Meeting();
		} 
		
		
		$request->setObject('meeting', $meeting);
		
		// Get company
		$company_id = $request->getProperty('company_id');
		$company = app_domain_Company::find($company_id);
		$request->setProperty('company_name', $company->getName());
		
		$post_initiative_id = $meeting->getPostInitiativeId();
		
		// Pass post_id
		$post = app_domain_Post::findByPostInitiativeId($post_initiative_id);
		$request->setObject('post', $post);
		
		// Pass post_initiative_id
		$request->setProperty('post_initiative_id', $post_initiative_id);
		
		// Pass source location - the page requesting the meeting edit
		$request->setProperty('source_tab', $request->getProperty('source_tab'));
		
		// Meeting feedback rating
		$options = array();
		for ($x=1;$x<=5;$x++)
		{
			$options[$x] = $x;
			if ($x == $meeting->getFeedbackRating())
			{
				$request->setProperty('feedback_rating_selected', $x);
			}		
		}
		$request->setObject('feedback_rating_options', $options);	
		
		// Meeting feedback meeting length
		$options = array();
		for ($x=5;$x<=65;$x=$x+5)
		{
			if ($x == 65)
			{
				$options[$x] = '>60';
			}
			else
			{
				$options[$x] = $x;
			}
			if ($x == $meeting->getFeedbackMeetingLength())
			{
				$request->setProperty('feedback_meeting_length_selected', $x);
			}		
		}
		$request->setObject('feedback_meeting_length_options', $options);	
		
		// Pass allow edit property - if false the edit page is overlaid with a div to make the 
		// fields inaccessible and the submit/cancel buttons are hidden
		if ($request->getProperty('allow_edit'))
		{
			$request->setProperty('allow_edit', false);
		}
		else
		{
			$request->setProperty('allow_edit', true);
		}

	}
}


?>