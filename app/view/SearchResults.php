<?php

/**
 * Defines the app_view_SearchResults class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_SearchResults extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('search_results');
		$this->smarty->assign('search_type', $this->request->getObject('search_type'));
		$this->smarty->assign('search_param', $this->request->getObject('search_param'));
		$this->smarty->assign('object_type', $this->request->getObject('object_type'));
		$this->smarty->assign('search_type_friendly', $this->request->getObject('search_type_friendly'));
		$this->smarty->assign('tab', 'Search');
		
		$this->smarty->assign('search_results', $collection);
		
		switch ($this->request->getObject('object_type'))
		{
			// TODO: change to pattern match on companies, contacts etc
			case 'sites':
			case 'site postcode':
			case 'site telephone':
			case 'site brands':
				$this->smarty->display('SearchResults.tpl');
				break; 
			
			case 'contact surnames':
			case 'contact full names':
				$this->smarty->display('SearchResults_contacts.tpl');
				break;
			
			case 'project refs':
			case 'site initiatives':
				$this->smarty->display('SearchResults_postInitiatives.tpl');
				break;
			
			default:
				break;
		}
	}

}

?>