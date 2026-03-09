<?php

/**
 * Defines the app_view_FilterResults class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_FilterResults extends app_view_View
{
	protected function doExecute()
	{
		if ($this->request->getObject('results') == '' || is_null($this->request->getObject('results')))
		{
			$this->smarty->assign('filter', null);
			$this->smarty->display('FilterResultsCompaniesPosts.tpl');
		}
		else
		{

			$collection = $this->request->getObject('results');
			$filter = $this->request->getObject('filter');

			switch ($filter->getResultsFormat())
			{
				case 'Mailer':
					$this->getExtraCompanyInfo($collection);
					break;
			}

			$this->smarty->assign('filter', $filter);
			$this->smarty->assign('client_name', $this->request->getProperty('client_name'));
			$this->smarty->assign('results', $collection);

			switch ($filter->getResultsFormat())
			{
				case 'Company':
				case 'Site':
					$this->smarty->display('FilterResultsCompanies.tpl');
					break;
				case 'Company and posts':
				case 'Site and posts':
					$this->smarty->display('FilterResultsCompaniesPosts.tpl');
					break;
				case 'Client initiative':
					$this->smarty->display('FilterResultsPostInitiatives.tpl');
					break;
				case 'Client initiative with last note':
					$this->smarty->display('FilterResultsPostInitiativesWithLastNote.tpl');
					break;
				case 'Mailer':
					$this->smarty->display('MailerItemCreateResults.tpl');
					break;
				case 'Meeting':
                    $this->smarty->display('FilterResultsMeetings.tpl');
                    break;
				default:
					echo 'Error: no results format supplied';
			}
		}
	}

	/**
	 * Adds the additional site and post information to the company results.
	 * @param array $companies
	 */
	protected function getExtraCompanyInfo(&$collection)
	{
		foreach ($collection as &$item)
		{
//			// Site
//			$finder = new app_mapper_SiteMapper();
//			$sites_collection = $finder->findByCompanyId($result['id']);
//			$sites = $sites_collection->toRawArray();
//			if (isset($sites[0]))
//			{
				$address = array(	'address_1' => $item['address_1'],
									'address_2' => $item['address_2'],
									'town'      => $item['town'],
									'city'      => $item['city'],
									'postcode'  => $item['postcode']);
				$item['site_address'] = app_domain_Site::formatAddress($address, 'paragraph');
//				echo "item[site_address] = " . $item['site_address'];
//			}
		}
	}

}

?>