<?php

/**
 * Defines the app_command_CompanyNoteCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Company.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/CompanyNote.php');
require_once('app/mapper/CompanyNoteMapper.php');

/**
 * @package Alchemis
 */
class app_command_CompanyNoteCreate extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$request->setObject('company_id', $request->getProperty('company_id'));

		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			if (trim($request->getProperty('note')) == '')
			{
				$request->addFeedback('Please enter a note');
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
				$request->addFeedback('Save Successful');
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
		
		$obj = new app_domain_CompanyNote();
		$obj->setCompanyId($request->getProperty('company_id'));
		$obj->setCreatedAt(date('Y-m-d H:i:s'));
		$obj->setCreatedBy($user['id']);
		$obj->setNote($request->getProperty('note'));
		$obj->commit();
		return true;
	}

}

?>