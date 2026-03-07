<?php

/**
 * Defines the app_view_MailerItemCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_MailerItemCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('filters', $this->request->getObject('filters'));
		$this->smarty->assign('mailer_id', $this->request->getProperty('mailer_id'));
		$this->smarty->assign('initiative_id', $this->request->getProperty('initiative_id'));
//		$this->smarty->assign('source_tab', $this->request->getObject('source_tab'));
		
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

		$this->smarty->display('MailerItemCreate.tpl');
	}
}

?>