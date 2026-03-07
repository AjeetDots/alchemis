<?php

/**
 * Defines the app_command_CampaignReportSummaryCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

//require_once('app/domain/PostInitiative.php');
//require_once('app/mapper/PostInitiativeMapper.php');
//require_once('app/domain/PostInitiativeNote.php');
//require_once('app/mapper/PostInitiativeNoteMapper.php');

/**
 * @package Alchemis
 */
class app_command_CampaignReportSummaryCreate extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		$request->setProperty('campaign_id', $request->getProperty('campaign_id'));

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
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		
		$obj = new app_domain_CampaignReportSummary();
		$obj->setCampaignId($request->getProperty('campaign_id'));
		$obj->setSubject($request->getProperty('subject'));
		$obj->setNote($request->getProperty('note'));
		$obj->setUpdatedAt(date('Y-m-d H:i:s'));
		$obj->setUserId($user['id']);
		$obj->commit();
		return true;
	}

}

?>