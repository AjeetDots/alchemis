<?php

/**
 * Defines the app_view_CommunicationAttachments class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CommunicationAttachments extends app_view_View
{
	protected function doExecute()
	{
		
		$this->smarty->assign('communication_id', $this->request->getProperty('communication_id'));
		$this->smarty->assign('attachments', $this->request->getObject('attachments'));
		$this->smarty->display('CommunicationAttachments.tpl');
	}
}

?>