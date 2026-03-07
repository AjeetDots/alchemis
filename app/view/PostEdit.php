<?php

/**
 * Defines the app_view_PostEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_PostEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('company_id', $this->request->getProperty('company_id'));
		$this->smarty->assign('company_name', $this->request->getProperty('company_name'));
		$this->smarty->assign('post', $this->request->getObject('post'));
        $this->smarty->assign('contact', $this->request->getObject('contact'));
        $this->smarty->assign('post_data_source_options', $this->request->getObject('post_data_source_options'));
		
		// Get any feedback
		$this->smarty->assign('success', $this->request->getProperty('success'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}
		
		$this->smarty->display('PostEdit.tpl');
	}
}

?>