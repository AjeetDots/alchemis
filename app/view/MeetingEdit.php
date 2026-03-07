<?php

/**
 * Defines the app_view_MeetingEdit class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_MeetingEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
		$this->smarty->assign('company_id', $this->request->getProperty('company_id'));
		$this->smarty->assign('company_name', $this->request->getProperty('company_name'));
		$this->smarty->assign('post', $this->request->getObject('post'));
		$this->smarty->assign('meeting', $this->request->getObject('meeting'));
		
		$this->smarty->assign('feedback_rating_options', $this->request->getObject('feedback_rating_options'));
		$this->smarty->assign('feedback_rating_selected', $this->request->getProperty('feedback_rating_selected'));
		$this->smarty->assign('feedback_meeting_length_options', $this->request->getObject('feedback_meeting_length_options'));
		$this->smarty->assign('feedback_meeting_length_selected', $this->request->getProperty('feedback_meeting_length_selected'));

		$this->smarty->assign('source_tab', $this->request->getProperty('source_tab'));
		$this->smarty->assign('allow_edit', $this->request->getProperty('allow_edit'));
		
		
//		echo '<pre>';
//		echo 'Meeting: ' . print_r($this->request->getObject('meeting'));
//		echo '</pre>';
		
//		$this->smarty->assign('meeting_status_values',   $this->request->getObject('meeting_status_values'));
//		$this->smarty->assign('meeting_status_output',   $this->request->getObject('meeting_status_output'));
//		$this->smarty->assign('meeting_status_selected', $this->request->getObject('meeting_status_selected'));
		
//		$this->smarty->assign('meeting_types_values',   $this->request->getObject('meeting_types_values'));
//		$this->smarty->assign('meeting_types_output',   $this->request->getObject('meeting_types_output'));
//		$this->smarty->assign('meeting_types_selected', $this->request->getObject('meeting_types_selected'));
				
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

	
	$this->smarty->display('MeetingEdit.tpl');
	}
}

?>