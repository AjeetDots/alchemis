<?php

/**
 * Defines the app_command_PostNoteCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/PostInitiativeNote.php');
require_once('app/mapper/PostInitiativeNoteMapper.php');

/**
 * @package Alchemis
 */
class app_command_PostInitiativeNoteCreate extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		$request->setProperty('post_id', $request->getProperty('post_id'));
		$request->setProperty('initiative_id', $request->getProperty('initiative_id'));
		$request->setProperty('post_initiative_id', $request->getProperty('post_initiative_id'));
		$request->setProperty('communication_id', $request->getProperty('communication_id'));

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
		
		$obj = new app_domain_PostInitiativeNote();
		$obj->setPostInitiativeId($request->getProperty('post_initiative_id'));
		$obj->setCreatedAt(date('Y-m-d H:i:s'));
		$obj->setCreatedBy($user['id']);
		$obj->setNote($request->getProperty('note'));
		$obj->commit();
		
		if ($request->getProperty('communication_id') != '')
		{
			$communication = app_domain_Communication::find($request->getProperty('communication_id'));
			$communication->setNoteId($obj->getId());
			$communication->commit();
		}
		
		return true;
	}

}

?>