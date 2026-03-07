<?php

/**
 * Defines the app_command_SiteEdit class. 
 * @author    David Cartery <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/CompanyMapper.php');
require_once('app/domain/Company.php');
require_once('app/mapper/SiteMapper.php');
require_once('app/domain/Site.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * @package Alchemis
 */
class app_command_SiteEdit extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{

		}
		elseif ($task == 'save')
		{
			if ($errors = $this->getFormErrors($request))
			{
				// Ensure validation errors are passed in to the view
				$this->handleValidationErrors($request, $errors);
				
				// Ensure field values are made sticky
				$this->handleStickyFields($request);
				
				$this->init($request);
				return self::statuses('CMD_VALIDATION_ERROR');
			}
			elseif ($this->processForm($request))
			{
				$request->addFeedback('Save Successful');
				$request->setProperty('success', true);
				return self::statuses('CMD_OK');
			}
			else
			{
				return self::statuses('CMD_ERROR');
			}
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}
	
	
	/**
	 * Checks form fields against validation rules.
	 * @param app_controller_Request $request the object from which the form 
	 *        values can be accessed
	 * @return array an array of errors, empty if none found
	 */
	protected function getFormErrors(app_controller_Request $request)
	{
		$errors = array();
		
		$errors['app_domain_Site_address_1'] = app_domain_Site::validate($request->getProperty('app_domain_Site_address_1'), app_domain_Site::getFieldSpec('address_1'));
		$errors['app_domain_Site_address_2'] = app_domain_Site::validate($request->getProperty('app_domain_Site_address_2'), app_domain_Site::getFieldSpec('address_2'));
		$errors['app_domain_Site_town'] = app_domain_Site::validate($request->getProperty('app_domain_Site_town'), app_domain_Site::getFieldSpec('town'));
		$errors['app_domain_Site_city'] = app_domain_Site::validate($request->getProperty('app_domain_Site_city'), app_domain_Site::getFieldSpec('city'));

		if (app_base_ApplicationRegistry::getItem('site_county_required')) {
			$errors['app_domain_Site_county_id'] = app_domain_Site::validate($request->getProperty('app_domain_Site_county_id'), app_domain_Site::getFieldSpec('county_id_mandatory'));
		} else {
			$errors['app_domain_Site_county_id'] = app_domain_Site::validate($request->getProperty('app_domain_Site_county_id'), app_domain_Site::getFieldSpec('county_id'));
		}

		$errors['app_domain_Site_country_id'] = app_domain_Site::validate($request->getProperty('app_domain_Site_country_id'), app_domain_Site::getFieldSpec('country_id'));

		// check for country and postcode validation
		if ($request->getProperty('app_domain_Site_country_id') != '0')
		{
			if (app_base_ApplicationRegistry::getItem('site_postcode_required')) {
				$postcode = app_domain_Site::isValidPostcode($request->getProperty('app_domain_Site_postcode'), $request->getProperty('app_domain_Site_country_id'));
				if (!empty($postcode))
				{
					$errors['app_domain_Site_postcode'] = $postcode;
				}
			}
		} else {
			$errors['app_domain_Site_country_id'] = new app_base_ValidationError('Country must be specified');
		}
		
		foreach ($errors as $key => $error)
		{
			if (empty($error))
			{
				unset($errors[$key]);
			}
		}
		return $errors;
	}
	
	
	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		$site = app_domain_Site::find($request->getProperty('id'));
		
		$site->setAddress1($request->getProperty('app_domain_Site_address_1'));
		$site->setAddress2($request->getProperty('app_domain_Site_address_2'));
		$site->setTown($request->getProperty('app_domain_Site_town'));
		$site->setCity($request->getProperty('app_domain_Site_city'));
		$site->setPostcode($request->getProperty('app_domain_Site_postcode'));
		$site->setCountyId($request->getProperty('app_domain_Site_county_id'));
		$site->setCountryId($request->getProperty('app_domain_Site_country_id'));

		$site->commit();
		return true;
	}
		
	
	/**
	 * Takes a list of the fields being used and re-assigns any values entered 
	 * to make sticky.
	 * @param app_controller_Request $request 
	 * @param array $field_spec associative array of fields in use, where the 
	 *        key is the field name
	 */
	protected function handleStickyFields(app_controller_Request $request)
	{
		// Site fields
		$sticky_fields = array(	'chk_display_site', 
								'app_domain_Site_address_1',
								'app_domain_Site_address_2',
								'app_domain_Site_town',
								'app_domain_Site_city',
								'app_domain_Site_county_id',
								'app_domain_Site_postcode',
								'app_domain_Site_country_id');
				
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		// Get site
		$site_id = $request->getProperty('id');
		$site = app_domain_Site::find($site_id);
		$request->setObject('site', $site);

		// county_id
		if ($items = app_domain_Site::getCountiesAll())
		{
			$options = array();
			$options[0] = '-- select if required--';
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
			}
			$request->setObject('site_counties_options', $options);
		}
//		$request->setObject('site_counties_selected', $site->getCountyId());
		$request->setObject('app_domain_Site_county_id', $site->getCountyId());
		
		// country_id
		if ($items = app_domain_Site::getCountriesAll())
		{
			$options = array();
			$options[0] = '-- select if required--';
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
			}
			$request->setObject('site_countries_options', $options);
		}
//		$request->setObject('site_countries_selected', $site->getCountryId());
		$request->setObject('app_domain_Site_country_id', $site->getCountryId());
		
		
		// Get company name
		$company_id = $request->getProperty('company_id');
		$company = app_domain_Company::find($company_id);
		$request->setProperty('company_id', $company->getId());
		$request->setProperty('company_name', $company->getName());
		
//		$request->setObject('chk_display_site',           $site->getName());	
		$request->setObject('app_domain_Site_address_1',  $site->getAddress1());
		$request->setObject('app_domain_Site_address_2',  $site->getAddress2());
		$request->setObject('app_domain_Site_town',       $site->getTown());
		$request->setObject('app_domain_Site_city',       $site->getCity());
		$request->setObject('app_domain_Site_county_id',  $site->getCountyId());
//		$request->setObject('site_counties_selected',     $site->getCountyId());
		$request->setObject('app_domain_Site_postcode',   $site->getPostcode());
		$request->setObject('app_domain_Site_country_id', $site->getCountryId());
//		$request->setObject('site_countries_selected',    $site->getCountryId());
		
		if ($feeback = $request->getProperty('feedback'))
		{
			$request->addFeedback($feeback);
		}
	}
		
}

?>