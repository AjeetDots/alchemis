<?php

/**
 * Defines the app_command_CampaignDocumentAdd class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/domain/Document.php');
require_once('include/pear/HTTP/Upload.php');

/**
 * @package Framework
 */
class app_command_CampaignDocumentAdd extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		if ($request->propertyExists('campaign_id'))
		{
			$campaign_id = $request->getProperty('campaign_id');
			$request->setObject('campaign_id', $campaign_id);
		}
		else
		{
			throw new Exception('Campaign ID was not supplied');
		}

		// Access the uploaded file
		$upload = new HTTP_Upload('en');
		$file = $upload->getFiles('file');
		
		if (PEAR::isError($file))
		{
			die ($file->getMessage());
		}

		if ($file->isValid())
		{
			// Instantiate document for testing		
			$document = new app_domain_Document();
			$document->setCampaignId($campaign_id);
			$document->setFilename($file->getProp('real'));
			
			// Set the new name of the uploaded file and where it is to be put
			$filename = $document->getId();
			$file->setName($filename);
			$dest_dir = '.' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . '/';
			
			if ($document->filenameAlreadyExists())
			{
				$request->setObject('feedback', 'Failed to add document. A document with the filename ' . $document->getFilename() . ' already exists for this campaign.');
			}
			else
			{
				$dest_name = $file->moveTo($dest_dir);
				// echo '<pre>';print_r($dest_name);die;
				if (PEAR::isError($dest_name))
				{
					die ($dest_name->getMessage());
				}
				$real = $file->getProp('real');
				$request->setObject('feedback', "Uploaded $real as $dest_name in $dest_dir");
				$request->addFeedback("Uploaded $real as $dest_name in $dest_dir");
				$request->setObject('success', true);
				
				// Record upload of file
//				echo "<p>Record upload of file</p>";

				// Get current user
				$session = Auth_Session::singleton();
				$user = $session->getSessionUser();
				
				// Finish off creating the document
				$document->setDescription($request->getProperty('document_description'));
				$document->setSize($file->getProp('size'));
				$document->setMimeType($file->getProp('type'));
				$document->setCreatedBy($user['id']);
				$document->setCreated(date('Y-m-d H:i:s'));
				$document->commit();
				$request->setObject('success', true);
			}
		}
		elseif ($file->isMissing())
		{
//			echo "No file selected\n";
		}
		elseif ($file->isError())
		{
			echo $file->errorMsg() . "\n";
		}
		
		return self::statuses('CMD_OK');
	}
}

?>