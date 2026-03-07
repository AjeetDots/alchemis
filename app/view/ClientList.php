<?php

/**
 * Defines the app_view_ClientList class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_ClientList extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));

		$collection = $this->request->getObject('clients');
		$this->smarty->assign('clients', $collection->toArray());
		
//		// Pagination
//		$paginate = $this->request->getObject('paginate');
//		$this->smarty->assign('paginate', $paginate);
//		
//		require('Smarty/SmartyPaginate.class.php');
//		
//		// required connect
//		SmartyPaginate::connect();
//		
//		// set items per page
//		SmartyPaginate::setCurrentItem($paginate->getOffset() + 1);
//		SmartyPaginate::setLimit($paginate->getLimit());
//		SmartyPaginate::setTotal($paginate->getTotal());
//		
//		// Set the default number of page groupings displayed in the {paginate_middle} function.
//		SmartyPaginate::setPageLimit(5);
//		SmartyPaginate::setFirstText('First');
//		SmartyPaginate::setPrevText('&lt;&lt;');
//		SmartyPaginate::setNextText('&gt;&gt;');
//		SmartyPaginate::setLastText('Last');
//		
//		// assign your db results to the template
//		SmartyPaginate::setUrl($_SERVER['PHP_SELF'] . '?cmd=ClientList' . '&limit=' . $paginate->getLimit());
//		$this->smarty->assign('results', $collection);
//		
//		// assign {$paginate} var
//		SmartyPaginate::assign($this->smarty);
		
		$this->smarty->assign('tab', 'Clients');
		$this->smarty->display('ClientList.tpl');
	}
}

?>