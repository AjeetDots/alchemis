<?php 

/**
 * Defines the app_view_SiteEdit class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */
 
require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_SiteEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('company_id', $this->request->getProperty('company_id'));
		$this->smarty->assign('company_name', $this->request->getProperty('company_name'));
		$this->smarty->assign('site', $this->request->getObject('site'));
		$company_id = $this->request->getProperty('company_id');
		$company = app_model_Company::find($company_id);
		$parent_company = $company->parent_company()->first();
		$this->smarty->assign('company', $company);
		$this->smarty->assign('parent_company', $parent_company);
		
		$this->smarty->assign('site_counties_options',   $this->request->getObject('site_counties_options'));
		$this->smarty->assign('site_countries_options',  $this->request->getObject('site_countries_options'));
		$this->smarty->assign('site_counties_selected',  $this->request->getObject('site_counties_selected'));
		$this->smarty->assign('site_countries_selected', $this->request->getObject('site_countries_selected'));
		
		$this->smarty->assign('app_domain_Site_address_1',   $this->request->getObject('app_domain_Site_address_1'));
		$this->smarty->assign('app_domain_Site_address_2',   $this->request->getObject('app_domain_Site_address_2'));
		$this->smarty->assign('app_domain_Site_town',        $this->request->getObject('app_domain_Site_town'));
		$this->smarty->assign('app_domain_Site_city',        $this->request->getObject('app_domain_Site_city'));
		$this->smarty->assign('app_domain_Site_county_id',   $this->request->getObject('app_domain_Site_county_id'));
		$this->smarty->assign('app_domain_Site_postcode',    $this->request->getObject('app_domain_Site_postcode'));
		$this->smarty->assign('app_domain_Site_country_id',  $this->request->getObject('app_domain_Site_country_id'));
		
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

		$this->smarty->display('SiteEdit.tpl');
	}
}
?>