<?php

/**
 * Defines the ImportCompanySingleAddressBlock class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_ImportCompany1_3 extends app_view_ManipulationView
{
	protected function doExecute()
	{

		// Init
		$this->smarty->assign('address_data', $this->request->getObject('address_data'));

//		  echo '$$data<pre>';
//        print_r($this->request->getObject('address_data'));
//        echo '</pre>';

//		$this->smarty->assign('county_lkp_data', $this->request->getObject('county_lkp_data'));
//		$this->smarty->assign('country_data', $this->request->getObject('country_data'));
//		$this->smarty->assign('mailer_items', $this->request->getObject('mailer_items'));
//
//		// Get any feedback
//		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
//
//		// Handle any validation errors
//		if ($this->request->isValidationError())
//		{
//			// Ensure validation errors are assigned to smarty
//			$this->handleValidationErrors();
//
//			// Ensure field values are made sticky
//			$this->handleStickyFields();
//		}
//
//		$this->smarty->assign('refresh_screen', false);

	$this->smarty->display('ImportCompany1_3.tpl');
	}
}

?>