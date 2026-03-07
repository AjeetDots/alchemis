<?php

/**
 * Defines the app_view_MeetingActionCreate class. 
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
class app_view_MeetingActionCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{
//		$fields = $this->request->getObject('fields');
//		echo '<pre>';
//		print_r($fields);
//		echo '</pre>';
		
		// Preset date
		$date = $this->request->getObject('date');
		$date = Utils::DateFormat($date, 'YYYY-MM-DD', 'DD/MM/YYYY');
		$this->smarty->assign('date', $date);

//		// only use 'id' and 'name' if save successful
//		$this->smarty->assign('id', $this->request->getProperty('id'));
//		$this->smarty->assign('name', $this->request->getProperty('name'));
		$this->smarty->assign('referrer_type', $this->request->getProperty('referrer_type'));
		$this->smarty->assign('app_domain_Action_post_initiative_id', $this->request->getProperty('post_initiative_id'));
		$this->smarty->assign('app_domain_Action_meeting_id', $this->request->getProperty('meeting_id'));
				
		$this->smarty->assign('type_options', $this->request->getObject('type_options'));
		$this->smarty->assign('communication_type_options', $this->request->getObject('communication_type_options'));
		$this->smarty->assign('resource_type_options', $this->request->getObject('resource_type_options'));
		
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
		
		$this->smarty->display('MeetingActionCreate.tpl');
	}
}

?>