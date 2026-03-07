<?php

/**
 * Defines the app_view_PostInitiativeActionCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');
require_once('include/Utils/Utils.class.php');

/**
 * @package Alchemis
 */
class app_view_PostInitiativeActionEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
//		// Preset date
//		$date = $this->request->getObject('date');
//		$date = Utils::DateFormat($date, 'YYYY-MM-DD', 'DD/MM/YYYY');
//		$this->smarty->assign('date', $date);

		$this->smarty->assign('referrer_type', $this->request->getProperty('referrer_type'));
		$this->smarty->assign('type_options', $this->request->getObject('type_options'));
		$this->smarty->assign('communication_type_options', $this->request->getObject('communication_type_options'));
		$this->smarty->assign('communication_type_default', app_base_ApplicationRegistry::getItem('action_default_communication_type'));
		$this->smarty->assign('resource_type_options', $this->request->getObject('resource_type_options'));
		
		$this->smarty->assign('post', $this->request->getObject('post'));
		$this->smarty->assign('initiative_name', $this->request->getProperty('initiative_name'));
		$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
		
		// Get any feedback
		$this->smarty->assign('success', $this->request->getProperty('success'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Assign referrer
		$this->smarty->assign('referrer', $this->request->getProperty('referrer'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}
		else
		{
			// Ensure field values are made sticky
			if ($fields = $this->request->getObject('fields'))
			{
				// Ensure fields are sticky
				if ($fields = $this->request->getObject('fields'))
				{
					foreach ($fields as $key => $value)
					{
						$this->smarty->assign($key, $value);
					}
				}
			}
		}
		
		$this->smarty->display('PostInitiativeActionEdit.tpl');
	}
}

?>