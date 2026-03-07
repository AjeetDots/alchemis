<?php

/**
 * Defines the app_view_MailerCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_MailerCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('client_initiatives', $this->request->getObject('client_initiatives'));
		$this->smarty->assign('mailer_types', $this->request->getObject('mailer_types'));
		$this->smarty->assign('mailer_response_groups', $this->request->getObject('mailer_response_groups'));
		
		$this->smarty->assign('new_mailer', $this->request->getObject('new_mailer'));
		
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

		$this->smarty->display('MailerCreate.tpl');
	}
}

?>