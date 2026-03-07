<?php

/**
 * Defines the app_view_ImportCompanyOnly class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_ImportCompanyOnly extends app_view_ManipulationView
{
	protected function doExecute()
	{

		// Init
		$this->smarty->assign('action', $this->request->getProperty('action'));
		$this->smarty->assign('company_processed_count', $this->request->getProperty('company_processed_count'));
		$this->smarty->assign('company_added_count', $this->request->getProperty('company_added_count'));
		$this->smarty->assign('company_existing_count', $this->request->getProperty('company_existing_count'));
		$this->smarty->assign('company_duplicates_count', $this->request->getProperty('company_duplicates_count'));
		$this->smarty->assign('company_failure_count', $this->request->getProperty('company_failure_count'));
		$this->smarty->assign('log_file_path', $this->request->getProperty('log_file_path'));
		$this->smarty->display('ImportCompanyOnly.tpl');
	}
}

?>