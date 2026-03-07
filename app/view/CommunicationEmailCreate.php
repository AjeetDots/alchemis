<?php

/**
 * Defines the app_view_CommunicationEmailCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_CommunicationEmailCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{
	
		// Init
		$post = $this->request->getObject('post');
		if ($post)
		{
			$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
			$this->smarty->assign('post', $post);
			$this->smarty->assign('contact', $post->getContact());
			$this->smarty->assign('campaign_nbm', $this->request->getObject('campaign_nbm'));
			$this->smarty->assign('attachments', $this->request->getObject('attachments'));
			$this->smarty->assign('information_requests', $this->request->getObject('information_requests'));
		}
		
		// Get any feedback
		if ($this->request->getProperty('success'))
		{
			$this->smarty->assign('success', $this->request->getProperty('success'));
		}
		
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}
		
		$this->smarty->display('CommunicationEmailCreate.tpl');
		
	}

}

?>