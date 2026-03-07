<?php

/**
 * Defines the app_command_SearchResults class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Company.php');
require_once('app/domain/Contact.php');
require_once('app/mapper/SearchMapper.php');

/**
 * @package Alchemis
 */
class app_command_SearchResults extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get request parameters
		$search_type = $request->getProperty('search_type');
		$search_param = trim($request->getProperty('search_param'));
		$search_param_1 = trim($request->getProperty('search_param_1'));
		
		if (!is_null($search_type) && !is_null($search_param))
		{
			switch ($search_type)
			{
				case 'company_start':
					$collection = app_domain_Company::findByNameStart($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'sites');
					$request->setObject('search_type_friendly', 'start with');
					break;

				case 'company_list_start':
					$collection = app_domain_Company::findByNameListStart($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'sites');
					$request->setObject('search_type_friendly', 'start with');
					break;

				case 'company_includes':
					$collection = app_domain_Company::findByNameIncludes($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'sites');
					$request->setObject('search_type_friendly', 'include');
					break;

				case 'company_equal':
					$collection = app_domain_Company::findByNameEqual($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'sites');
					$request->setObject('search_type_friendly', 'equal');
					break;

				case 'company_telephone_start':
					$collection = app_domain_Company::findByTelephoneStart($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site telephone');
					$request->setObject('search_type_friendly', 'starts with');
					break;
				
				case 'company_telephone_includes':
					$collection = app_domain_Company::findByTelephoneIncludes($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site telephone');
					$request->setObject('search_type_friendly', 'includes');
					break;
					
				case 'company_telephone_equal':
					$collection = app_domain_Company::findByTelephoneEqual($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site telephone');
					$request->setObject('search_type_friendly', 'equal');
					break;
					
				case 'postcode_start':
					$collection = app_domain_Company::findByPostcodeStart($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site postcode');
					$request->setObject('search_type_friendly', 'starts with');
					break;
					
				case 'postcode_includes':
					$collection = app_domain_Company::findByPostcodeIncludes($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site postcode');
					$request->setObject('search_type_friendly', 'includes');
					break;
					
				case 'postcode_equal':
					$collection = app_domain_Company::findByPostcodeEqual($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site postcode');
					$request->setObject('search_type_friendly', 'equals');
					break;
					
//				case 'brand_includes':
//					$collection = app_domain_Company::findByBrandIncludes($search_param)->toRawArray();
//					$request->setObject('search_results', $collection);
//					$request->setObject('object_type', 'company brand');
//					$request->setObject('search_type_friendly', 'includes');
//					break;

				case 'contact_surname_start':
					$collection = app_domain_Contact::findByContactSurnameStart($search_param)->toRawArray();
					$this->getExtraContactInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'contact surnames');
					$request->setObject('search_type_friendly', 'start with');
					break;

				case 'contact_fullname_start':
					$collection = app_domain_Contact::findByContactFullNameStart($search_param)->toRawArray();
					$this->getExtraContactInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'contact full names');
					$request->setObject('search_type_friendly', 'start with');
					break;
					
				case 'company_initiative':
					$collection = app_domain_Company::findByNameStartAndInitiativeId($search_param, $search_param_1)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site initiatives');
					$request->setObject('search_type_friendly', 'start with');
					break;				
									
				case 'project_ref_start':
					$collection = app_domain_Tag::findByProjectRefStart($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'project refs');
					$request->setObject('search_type_friendly', 'start with');
					break;			
				
				case 'project_ref_includes':
					$collection = app_domain_Tag::findByProjectRefIncludes($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'project refs');
					$request->setObject('search_type_friendly', 'include');
					break;

				case 'project_ref_equal':
					$collection = app_domain_Tag::findByProjectRefEqual($search_param)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'project refs');
					$request->setObject('search_type_friendly', 'equal');
					break;
					
				case 'company_brand_equal':
					$collection = app_domain_Tag::findByCompanyTagCategoryIdEqual($search_param, 1)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site brands');
					$request->setObject('search_type_friendly', 'equal');
					break;
					
				case 'company_brand_start':
					$collection = app_domain_Tag::findByCompanyTagCategoryIdStart($search_param, 1)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site brands');
					$request->setObject('search_type_friendly', 'equal');
					break;
					
				case 'company_brand_includes':
					$collection = app_domain_Tag::findByCompanyTagCategoryIdIncludes($search_param, 1)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'site brands');
					$request->setObject('search_type_friendly', 'equal');
					break;
					
//				case 'contact_telephone_start':
//					$collection = app_domain_Contact::findByTelephoneStart($search_param)->toRawArray();
//					$this->getExtraCompanyInfo($collection);
//					$request->setObject('search_results', $collection);
//					$request->setObject('object_type', 'contact telephone');
//					$request->setObject('search_type_friendly', 'starts with');
//					break;
					
				

				
					
				default:
					throw new Exception('Invalid search type');
					break;
			}
			
			// Pass on request params
			$request->setObject('search_type', $search_type);
			$request->setObject('search_param', $search_param);
		}
		
		return self::statuses('CMD_OK');
	}
	
	/**
	 * Adds the additional site and post information to the company results.
	 * @param array $companies 
	 */
	protected function getExtraCompanyInfo(&$companies)
	{
		foreach ($companies as &$company)
		{
			// Site
			$finder = new app_mapper_SiteMapper();
			$sites_collection = $finder->findByCompanyId($company['id']);
			$sites = $sites_collection->toRawArray();
			if (isset($sites[0]))
			{
				$address = array(	'address_1' => $sites[0]['address_1'],
									'address_2' => $sites[0]['address_2'],
									'town'      => $sites[0]['town'],
									'city'      => $sites[0]['city'],
									'postcode'  => $sites[0]['postcode']);
				$company['site_address'] = app_domain_Site::formatAddress($address, 'paragraph');
			}					

			// Posts
			$finder = new app_mapper_PostMapper();
			$posts_collection = $finder->findByCompanyId($company['id']);
			$company['posts'] = $posts_collection->toRawArray();
		}
	}
	
	/**
	 * Adds the additional site and post information to the company results.
	 * @param array $companies 
	 */
	protected function getExtraContactInfo(&$contacts)
	{
		foreach ($contacts as &$contact)
		{
			$address = array(	'address_1' => $contact['address_1'],
								'address_2' => $contact['address_2'],
								'town'      => $contact['town'],
								'city'      => $contact['city'],
								'postcode'  => $contact['postcode']);
			$contact['site_address'] = app_domain_Site::formatAddress($address, 'paragraph');
		}
	}

}

?>