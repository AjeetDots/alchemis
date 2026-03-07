<?php

/**
 * Defines the app_command_CompanyCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
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
require_once('app/base/ValidationError.php');


/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_CompanyCreate extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
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
		$errors['app_domain_Company_name'] = app_domain_Company::validate($request->getProperty('app_domain_Company_name'), app_domain_Company::getFieldSpec('name'));
		$errors['app_domain_Company_telephone'] = app_domain_Company::validate($request->getProperty('app_domain_Company_telephone'), app_domain_Company::getFieldSpec('telephone'));
		$errors['app_domain_Company_website'] = app_domain_Company::validate($request->getProperty('app_domain_Company_website'), app_domain_Company::getFieldSpec('website'));
		$errors['app_domain_Company_parent_company'] = app_domain_Company::validate($request->getProperty('app_domain_Company_parent_company'), app_domain_Company::getFieldSpec('parent_company'));
		$errors['app_domain_Company_subcategory_id'] = app_domain_Company::validate($request->getProperty('tiered_characteristic_id'), app_domain_Company::getFieldSpec('subcategory_id'));
		
		if (app_base_ApplicationRegistry::getItem('company_on_create_site_address_required')) {
			if ($request->getProperty('chk_display_site'))
			{
				$errors = array_merge($errors, $this->_doAddressValidation($request));
			} else {
				$errors['app_domain_Site_address_required'] = new app_base_ValidationError('At least one address field must be entered');
			}
		} else {
			if ($request->getProperty('chk_display_site'))
			{
				$errors = array_merge($errors, $this->_doAddressValidation($request));
			}
		}
		
		if ($request->getProperty('chk_display_post'))
		{
			$errors['app_domain_Post_job_title'] = app_domain_Post::validate($request->getProperty('app_domain_Post_job_title'), app_domain_Post::getFieldSpec('job_title'));
			$errors['app_domain_Post_telephone_1'] = app_domain_Post::validate($request->getProperty('app_domain_Post_telephone_1'), app_domain_Post::getFieldSpec('telephone_1'));
			$errors['app_domain_Post_telephone_2'] = app_domain_Post::validate($request->getProperty('app_domain_Post_telephone_2'), app_domain_Post::getFieldSpec('telephone_2'));
			$errors['app_domain_Post_telephone_switchboard'] = app_domain_Post::validate($request->getProperty('app_domain_Post_telephone_switchboard'), app_domain_Post::getFieldSpec('telephone_switchboard'));
			$errors['app_domain_Post_telephone_fax'] = app_domain_Post::validate($request->getProperty('app_domain_telephone_Post_fax'), app_domain_Post::getFieldSpec('telephone_fax'));
		}
		
		if ($request->getProperty('chk_display_contact'))
		{
			$errors['app_domain_Contact_title'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_title'), app_domain_Contact::getFieldSpec('title'));
			$errors['app_domain_Contact_first_name'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_first_name'), app_domain_Contact::getFieldSpec('first_name'));
			$errors['app_domain_Contact_surname'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_surname'), app_domain_Contact::getFieldSpec('surname'));
			$errors['app_domain_Contact_telephone_mobile'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_telephone_mobile'), app_domain_Contact::getFieldSpec('telephone_mobile'));
			$errors['app_domain_Contact_email'] = app_domain_Contact::validate($request->getProperty('app_domain_Contact_email'), app_domain_Contact::getFieldSpec('email'));
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
	
	private function _doAddressValidation(app_controller_Request $request)
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
		
		//echo $request->getProperty('app_domain_Site_country_id');
		//die();
		// check for country and postcode validation
		if ($request->getProperty('app_domain_Site_country_id') != '0')
		{
			if (app_base_ApplicationRegistry::getItem('site_postcode_required')) {
				$postcode = app_domain_Site::isValidPostcode($request->getProperty('app_domain_Site_postcode'), $request->getProperty('app_domain_Site_country_id'));
				if (!empty($postcode))
				{
					$errors['app_domain_Site_postcode'] = $postcode;
				} 
			} else {
				if ($request->getProperty('app_domain_Site_postcode') != '') {
					$postcode = app_domain_Site::isValidPostcode($request->getProperty('app_domain_Site_postcode'), 		$request->getProperty('app_domain_Site_country_id'));
					if (!empty($postcode))
					{
						$errors['app_domain_Site_postcode'] = $postcode;
					} 
				}
			}
		} else {
			$errors['app_domain_Site_country_id'] = new app_base_ValidationError('Country must be specified');
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
		// Company
		$company = new app_domain_Company();
		$company->setName($request->getProperty('app_domain_Company_name'));
		$company->setTelephone($request->getProperty('app_domain_Company_telephone'));
		$company->setWebsite($request->getProperty('app_domain_Company_website'));
		$company->setParentCompany($request->getProperty('app_domain_Company_parent_company'));
		$company->commit();
		

		// If site checked
		if ($display_site = $request->getProperty('chk_display_site'))
		{
			$site = new app_domain_Site();
			$site->setCompanyId($company->getId());
			$site->setAddress1($request->getProperty('app_domain_Site_address_1'));
			$site->setAddress2($request->getProperty('app_domain_Site_address_2'));
			$site->setTown($request->getProperty('app_domain_Site_town'));
			$site->setCity($request->getProperty('app_domain_Site_city'));
			$site->setCountyId($request->getProperty('app_domain_Site_county_id'));
			$site->setPostcode($request->getProperty('app_domain_Site_postcode'));
			$site->setCountryId($request->getProperty('app_domain_Site_country_id'));
			$site->commit();
		}
		
		// If post checked
		if ($display_post = $request->getProperty('chk_display_post'))
		{
			$post = new app_domain_Post();
			$post->setCompanyId($company->getId());
			$post->setJobTitle($request->getProperty('app_domain_Post_job_title'));
			$post->setTelephone1($request->getProperty('app_domain_Post_telephone_1'));
			$post->setTelephone2($request->getProperty('app_domain_Post_telephone_2'));
			$post->setTelephoneSwitchboard($request->getProperty('app_domain_Post_telephone_switchboard'));
            $post->setTelephoneFax($request->getProperty('app_domain_Post_telephone_fax'));
            $post->setDataSourceId($request->getProperty('app_domain_Post_data_source_id'));
            $post->setDataSourceChangedDate(Utils::getTimestamp());
			$post->commit();
		}
		
		// If contact checked
		if ($display_contact = $request->getProperty('chk_display_contact'))
		{
			$contact = new app_domain_Contact();
			$contact->setPostId($post->getId());
			$contact->setTitle($request->getProperty('app_domain_Contact_title'));
			$contact->setFirstName($request->getProperty('app_domain_Contact_first_name'));
			$contact->setSurname($request->getProperty('app_domain_Contact_surname'));
			$contact->setTelephoneMobile($request->getProperty('app_domain_Contact_telephone_mobile'));
			$contact->setEmail($request->getProperty('app_domain_Contact_email'));
			$contact->commit();
		}
		
		
		// Instantiate the tiered characteristic selected
		$tiered_characteristic = app_domain_TieredCharacteristic::find($request->getProperty('tiered_characteristic_id'));
		 
		// Add the parent first before the select tiered characteristic can be added 
		if ($tiered_characteristic->hasParent())
		{
			// Parent category needs to be associated first
			$obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
			$obj->setParentObjectId($company->getId());
			$obj->setParentObjectType('app_domain_Company');
			$obj->setTieredCharacteristicId($tiered_characteristic->getParentId());
			$obj->setTier(0);
			$obj->commit();
		}
		
		$obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
		$obj->setParentObjectId($company->getId());
		$obj->setParentObjectType('app_domain_Company');
		$obj->setTieredCharacteristicId($request->getProperty('tiered_characteristic_id'));
		$obj->setTier($request->getProperty('tier'));
		$obj->commit();

		
		if (app_base_ApplicationRegistry::getItem('company_on_create_add_cleaned_date_characteristic')) {
			//now add company cleaned characteristic and populate with current date
			$object_characteristic = new app_domain_ObjectCharacteristicDate();
			$object_characteristic->setParentObjectId($company->getId());
			$object_characteristic->setParentObjectType('app_domain_Company');
			$object_characteristic->setCharacteristicId(13);
			$object_characteristic->setValue(date('Y-m-d'));
			$object_characteristic->commit();
		}
		
		$defaultTieredCharacteristic = app_base_ApplicationRegistry::getItem('company_on_create_default_top_level_tiered_characteristic');
		if (app_base_ApplicationRegistry::getItem('company_on_create_default_top_level_tiered_characteristic')) {
			$doAddDefaultTieredCharacteristic = false; //default position
			// only add default top level characteristic if not already addded manually by user as part of adding a sub-cat
			if ($display_sub_category) {
				if ($tiered_characteristic->hasParent()) {
					if ($tiered_characteristic->getParentId() == $defaultTieredCharacteristic) {
						// match - do nothing
					} else {
						$doAddDefaultTieredCharacteristic = true;
					}
				}
			} else {
				$doAddDefaultTieredCharacteristic = true;
			}
			
			if ($doAddDefaultTieredCharacteristic) {
				$obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
				$obj->setParentObjectId($company->getId());
				$obj->setParentObjectType('app_domain_Company');
				$obj->setTieredCharacteristicId($defaultTieredCharacteristic);
				$obj->setTier(0);
				$obj->commit();
			}
			
		}
		
		// set the request properties 'id' and 'name' so we can redirect to this new company
		$request->setProperty('id', $company->getId());
		$request->setProperty('name', $company->getName());
		
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
		// Company fields
		$sticky_fields = array(
			'app_domain_Company_name',
			'app_domain_Company_telephone',
			'app_domain_Company_website',
			'app_domain_Company_parent_company',
			'parent_company'
		);
		
		// Site fields
		if ($request->getProperty('chk_display_site'))
		{
			$sticky_fields = array_merge($sticky_fields, array(	'chk_display_site', 
																'app_domain_Site_address_1',
																'app_domain_Site_address_2',
																'app_domain_Site_town',
																'app_domain_Site_city',
																'app_domain_Site_county_id',
																'app_domain_Site_postcode',
																'app_domain_Site_country_id'));
		}
		
		// Post fields
		if ($request->getProperty('chk_display_post'))
		{
			$sticky_fields = array_merge($sticky_fields, array(	'chk_display_post', 
																'app_domain_Post_job_title',
																'app_domain_Post_telephone_1',
																'app_domain_Post_telephone_2',
																'app_domain_Post_telephone_switchboard',
																'app_domain_Post_telephone_fax'));
		}
		
		// Contact fields
		if ($request->getProperty('chk_display_contact'))
		{
			$sticky_fields = array_merge($sticky_fields, array(	'chk_display_contact', 
																'app_domain_Contact_title',
																'app_domain_Contact_first_name',
																'app_domain_Contact_surname',
																'app_domain_Contact_telephone_mobile',
																'app_domain_Contact_email'));
		}
		
		// Sub category fields
		if ($request->getProperty('chk_display_sub_category'))
		{
			$sticky_fields = array_merge($sticky_fields, array( 'chk_display_sub_category',
																'tiered_characteristic_id',
																'tier'));
		}
		
		$this->doHandleStickyFields($request, $sticky_fields);
	}

	/**
	 * Initialises data needed by drop-downs by assigning them to the request
	 * @param app_controller_Request $request 
	 */
	protected function init(app_controller_Request $request)
	{
		
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
		
        $request->setObject('app_domain_Site_country_id', 9); // default to UK
        
        $items = app_domain_Post::lookupDataSourcesAll();
        $options = array();
        foreach ($items as $item) {
            $options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
        }
        $request->setObject('post_data_source_options', $options);
        $suggested = app_model_DataSource::where('description', 'Colleague suggested')->first();
        $request->setProperty('app_domain_Post_data_source_id', $suggested->id);
		
		// sub category list
		$sub_categories = app_domain_TieredCharacteristic::selectAllSubCategoriesForDropdown();
		$request->setObject('sub_categories', $sub_categories);
	}
	
}

?>