<?php

/**
 * Defines the app_command_CommunicationAttachments class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
require_once('app/domain/CommunicationAttachment.php');

/**
 * @package Alchemis
 */
class app_command_CommunicationAttachments extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$communication_id = $request->getProperty('communication_id');
		$request->setProperty('communication_id', $communication_id);
		
		$attachments = app_domain_CommunicationAttachment::findByCommunicationId($communication_id);
		foreach ($attachments as $attachment)
		{
			$attachment->setDocument();
		}
		$request->setObject('attachments', $attachments);
		
	}
}

?>