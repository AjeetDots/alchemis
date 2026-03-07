<?php

/**
 * Defines the app_view_PostDisciplines class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_PostDisciplines extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('post_id', $this->request->getProperty('post_id'));
		$this->smarty->assign('disciplines_grid', $this->request->getObject('disciplines_grid'));
		$this->smarty->assign('decison_maker_options', $this->request->getObject('decison_maker_options'));
		$this->smarty->assign('agency_user_options', $this->request->getObject('agency_user_options'));
		$this->smarty->assign('available_disciplines', $this->request->getObject('available_disciplines'));
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
//		// Handle any validation errors
//		if ($this->request->isValidationError())
//		{
//			// Ensure validation errors are assigned to smarty
//			$this->handleValidationErrors();
//			
//			// Ensure field values are made sticky
//			$this->handleStickyFields();
//		}

		$this->smarty->display('PostDisciplines.tpl');
	}
}

?>