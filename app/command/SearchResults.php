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
	protected $search_results_truncated = false;
	protected $search_results_limit = 300;

	public function doExecute(app_controller_Request $request)
	{
		// Get request parameters
		$search_type = $request->getProperty('search_type');
		$search_param = trim($request->getProperty('search_param'));
		$search_param_1 = trim($request->getProperty('search_param_1'));
		
		$page = (int)$request->getProperty('page');
		if ($page < 1) { $page = 1; }
		$page_size = 500;
		$offset = ($page - 1) * $page_size;
		
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
					if ($search_param === '') { break; }
					$total_results = app_domain_Tag::countByProjectRefStart($search_param);
					$collection = app_domain_Tag::findByProjectRefStart($search_param, $page_size, $offset)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'project refs');
					$request->setObject('search_type_friendly', 'start with');
					$request->setObject('total_results', $total_results);
					break;			
				
				case 'project_ref_includes':
					if (strlen($search_param) < 3) { break; }
					$total_results = app_domain_Tag::countByProjectRefInclude($search_param);
					$collection = app_domain_Tag::findByProjectRefInclude($search_param, $page_size, $offset)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'project refs');
					$request->setObject('search_type_friendly', 'include');
					$request->setObject('total_results', $total_results);
					break;
//
				case 'project_ref_equal':
					if ($search_param === '') { break; }
					$total_results = app_domain_Tag::countByProjectRefEqual($search_param);
					$collection = app_domain_Tag::findByProjectRefEqual($search_param, $page_size, $offset)->toRawArray();
					$this->getExtraCompanyInfo($collection);
					$request->setObject('search_results', $collection);
					$request->setObject('object_type', 'project refs');
					$request->setObject('search_type_friendly', 'equal');
					$request->setObject('total_results', $total_results);
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
			$request->setObject('page', $page);
			$request->setObject('page_size', $page_size);
			if (isset($total_results)) {
				$request->setObject('total_pages', ceil($total_results / $page_size));
			}
			$request->setObject('search_results_truncated', $this->search_results_truncated);
			$request->setObject('search_results_limit', $this->search_results_limit);
		}
		
		return self::statuses('CMD_OK');
	}
	
	/**
	 * Adds the additional site and post information to the company results.
	 * @param array $companies 
	 */
	protected function getExtraCompanyInfo(&$companies)
	{
		if (!is_array($companies) || count($companies) == 0)
		{
			return;
		}

		if (count($companies) > $this->search_results_limit)
		{
			$companies = array_slice($companies, 0, $this->search_results_limit);
			$this->search_results_truncated = true;
		}

		$company_ids = array();
		foreach ($companies as $company)
		{
			if (isset($company['id']))
			{
				$company_ids[] = (int) $company['id'];
			}
		}
		$company_ids = array_values(array_unique($company_ids));
		if (count($company_ids) == 0)
		{
			return;
		}

		$db = app_controller_ApplicationHelper::instance()->DB();
		$id_list = implode(',', $company_ids);

		$site_map = array();
		$site_query = 'SELECT s.company_id, s.address_1, s.address_2, s.town, s.city, s.postcode ' .
					  'FROM vw_sites s ' .
					  'INNER JOIN (' .
					  '  SELECT company_id, MIN(id) AS id FROM vw_sites ' .
					  '  WHERE company_id IN (' . $id_list . ') GROUP BY company_id' .
					  ') first_site ON first_site.id = s.id';
		$site_rows = $db->queryAll($site_query, null, MDB2_FETCHMODE_ASSOC);
		if (is_array($site_rows))
		{
			foreach ($site_rows as $site)
			{
				$address = array(
					'address_1' => isset($site['address_1']) ? $site['address_1'] : '',
					'address_2' => isset($site['address_2']) ? $site['address_2'] : '',
					'town'      => isset($site['town']) ? $site['town'] : '',
					'city'      => isset($site['city']) ? $site['city'] : '',
					'postcode'  => isset($site['postcode']) ? $site['postcode'] : ''
				);
				$site_map[(int) $site['company_id']] = app_domain_Site::formatAddress($address, 'paragraph');
			}
		}

		$posts_map = array();
		$post_query = 'SELECT id, company_id, job_title, full_name, telephone_1, propensity ' .
					  'FROM vw_posts WHERE company_id IN (' . $id_list . ') ORDER BY company_id, job_title';
		$post_rows = $db->queryAll($post_query, null, MDB2_FETCHMODE_ASSOC);
		if (is_array($post_rows))
		{
			foreach ($post_rows as $post)
			{
				$cid = (int) $post['company_id'];
				if (!isset($posts_map[$cid]))
				{
					$posts_map[$cid] = array();
				}
				$posts_map[$cid][] = $post;
			}
		}

		foreach ($companies as &$company)
		{
			$cid = (int) $company['id'];
			$company['site_address'] = isset($site_map[$cid]) ? $site_map[$cid] : '';
			$company['posts'] = isset($posts_map[$cid]) ? $posts_map[$cid] : array();
		}
		unset($company);
	}
	
	/**
	 * Adds the additional site and post information to the company results.
	 * @param array $companies 
	 */
	protected function getExtraContactInfo(&$contacts)
	{
		if (!is_array($contacts) || count($contacts) == 0)
		{
			return;
		}
		if (count($contacts) > $this->search_results_limit)
		{
			$contacts = array_slice($contacts, 0, $this->search_results_limit);
			$this->search_results_truncated = true;
		}

		foreach ($contacts as &$contact)
		{
			$address = array(	'address_1' => $contact['address_1'],
								'address_2' => $contact['address_2'],
								'town'      => $contact['town'],
								'city'      => $contact['city'],
								'postcode'  => $contact['postcode']);
			$contact['site_address'] = app_domain_Site::formatAddress($address, 'paragraph');
		}
		unset($contact);
	}

}

?>