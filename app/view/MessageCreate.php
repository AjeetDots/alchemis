<?php

/**
 * Defines the app_view_MessageCreate class. 
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
class app_view_MessageCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Preset date
		$date = $this->request->getObject('date');
		$date = Utils::DateFormat($date, 'YYYY-MM-DD', 'DD/MM/YYYY');
		$this->smarty->assign('date', $date);

		// Init
		$this->smarty->assign('site_counties_options', $this->request->getObject('site_counties_options'));
		$this->smarty->assign('site_countries_options', $this->request->getObject('site_countries_options'));
		
		// only use 'id' and 'name' if save successful
		$this->smarty->assign('id', $this->request->getProperty('id'));
		$this->smarty->assign('name', $this->request->getProperty('name'));
		
		// Get any feedback
		$this->smarty->assign('success', $this->request->getProperty('success'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
//			// Ensure field values are made sticky
//			$this->handleStickyFields();
		}


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


		$this->smarty->display('MessageCreate.tpl');
	}
}

?>