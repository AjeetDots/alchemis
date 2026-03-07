<?php

/**
 * Defines the app_view_MailerItemList class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_MailerItemList extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		$this->smarty->assign('mailer', $this->request->getObject('mailer'));
		$this->smarty->assign('mailer_items', $this->request->getObject('mailer_items'));
				
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}
		
		$this->smarty->assign('refresh_screen', false);
		
	$this->smarty->display('MailerItemList.tpl');
	}
}

?>