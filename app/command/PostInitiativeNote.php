<?php

/**
 * Defines the app_command_PostInitiativeNote class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2012 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/PostInitiativeNote.php');
// require_once('app/mapper/PostNoteInitiativeMapper.php');

/**
 * @package Alchemis
 */


class app_command_PostInitiativeNote extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$note = app_domain_PostInitiativeNote::findByPostInitiativeNoteId($request->getProperty('post_initiative_note_id'));
		$request->setObject('note', $note->toRawArray());
		
		$attachments = app_domain_PostInitiativeNoteDocument::findByPostInitiativeNoteId($request->getProperty('post_initiative_note_id'));
		$request->setObject('attachments', $attachments);

		return self::statuses('CMD_OK');
	}
}

?>