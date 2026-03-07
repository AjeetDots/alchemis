<?php

/**
 * Defines the app_command_CampaignReportSummaryEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

/**
 * @package Alchemis
 */
class app_command_CampaignReportSummaryEdit extends app_command_Command
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
			if (trim($request->getProperty('subject')) == '' || trim($request->getProperty('note')) == '')
			{
				$request->setProperty('subject', $request->getProperty('subject'));
				$request->setProperty('note', $request->getProperty('note'));		
				$request->setProperty('error', true);
				$request->addFeedback('Please enter a subject and a note');
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
				$this->init($request);
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
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('id', $request->getProperty('id'));
				
		// Get summary
		$summary = app_domain_CampaignReportSummary::find($request->getProperty('id'));
		$request->setObject('summary', $summary);
		
	}	
	
	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		
		$obj = app_domain_CampaignReportSummary::find($request->getProperty('id'));
		$obj->setSubject($request->getProperty('subject'));
		$obj->setNote($request->getProperty('note'));
		$obj->setUpdatedAt(date('Y-m-d H:i:s'));
		$obj->setUserId($user['id']);
		$obj->commit();
		return true;
	}

}

?>