<?php 

/**
 * Defines the app_view_SiteCreate class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_SiteCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{

		$this->smarty->assign('company_id',   $this->request->getProperty('company_id'));
		$this->smarty->assign('company_name',   $this->request->getProperty('company_name'));
				
		$this->smarty->assign('site_counties_options', $this->request->getObject('site_counties_options'));
		$this->smarty->assign('site_countries_options', $this->request->getObject('site_countries_options'));
		$this->smarty->assign('site_counties_selected', $this->request->getObject('site_counties_selected'));
		$this->smarty->assign('site_countries_selected', $this->request->getObject('site_countries_selected'));
		
		// only use 'id' and 'name' if save successful
		$this->smarty->assign('id', $this->request->getProperty('id'));

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
		
		$this->smarty->display('SiteCreate.tpl');
	}
}

?>