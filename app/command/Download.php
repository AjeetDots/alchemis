<?php

/**
 * Defines the app_command_Download class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('HTTP/Download.php');
require_once('app/domain/Document.php');

/**
 * @package Framework
 */
class app_command_Download extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$document = app_domain_Document::find($request->getProperty('document_id'));
		$document->download();
	}
}

?>