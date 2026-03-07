<?php

/**
 * Defines the app_view_MeetingHistory class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_MeetingHistory extends app_view_View
{
	protected function doExecute()
	{
		// Get feedback
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		
		// Get history
		$collection = $this->request->getObject('history');
//		$this->smarty->assign('history', $collection->toArray());
		$this->smarty->assign('history', $collection);
//		echo '<pre>';
//		print_r($collection);
//		echo '</pre>';
		
		$this->smarty->display('MeetingHistory.tpl');
	}

//	protected function doExecute()
//	{
//		// Get feedback
//		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
//		
//		// Get products
//		$collection = $this->request->getObject('products');
//		$this->smarty->assign('products', $collection->toArray());
//		
//		// Pagination
//		$paginate = $this->request->getObject('paginate');
//		$this->smarty->assign('paginate', $paginate);
//		
//		// Get library
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
//		SmartyPaginate::setUrl($_SERVER['PHP_SELF'] . '?cmd=ProductListList' . '&limit=' . $paginate->getLimit()); 
//		$this->smarty->assign('results', $collection);
//		
//		// assign {$paginate} var
//		SmartyPaginate::assign($this->smarty);
//		
//		// Breadcrumbs
//		$breadcrumbs = array(	'DASHBOARD'                       => 'index.php?cmd=Dashboard',
//								'UNDERSTANDING_YOUR_ORGANISATION' => '',
//								'PRODUCTS'                        => null);
//		$this->smarty->assign('breadcrumbs', $breadcrumbs);
//		
//		$this->smarty->display('ProductList.tpl');
//	}
}

?>