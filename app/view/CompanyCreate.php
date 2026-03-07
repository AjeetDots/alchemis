<?php

/**
 * Defines the app_view_CompanyCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_CompanyCreate extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('site_counties_options', $this->request->getObject('site_counties_options'));
		$this->smarty->assign('site_countries_options', $this->request->getObject('site_countries_options'));
		$this->smarty->assign('app_domain_Site_country_id', $this->request->getObject('app_domain_Site_country_id'));
        $this->smarty->assign('sub_categories', $this->request->getObject('sub_categories'));
        $this->smarty->assign('post_data_source_options', $this->request->getObject('post_data_source_options'));
        $this->smarty->assign('app_domain_Post_data_source_id', $this->request->getProperty('app_domain_Post_data_source_id'));
		
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
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}

		$this->smarty->display('CompanyCreate.tpl');
	}
}

?>