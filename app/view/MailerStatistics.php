<?php

/**
 * Defines the app_view_MailerStatistics class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_MailerStatistics extends app_view_ManipulationView
{
	protected function doExecute()
	{
		
		// Init
		$this->smarty->assign('mailer', $this->request->getObject('mailer'));
		$this->smarty->assign('graph1_item_count', $this->request->getProperty('graph1_item_count'));
		$this->smarty->assign('graph1_despatched_count', $this->request->getProperty('graph1_despatched_count'));
		$this->smarty->assign('graph1_despatched_count_perc', $this->request->getProperty('graph1_despatched_count_perc'));
		$this->smarty->assign('graph1_not_despatched_count', $this->request->getProperty('graph1_not_despatched_count'));
		$this->smarty->assign('graph1_not_despatched_count_perc', $this->request->getProperty('graph1_not_despatched_count_perc'));
		
		$this->smarty->assign('graph2_response_count', $this->request->getProperty('graph2_response_count'));
		$this->smarty->assign('graph2_despatched_count', $this->request->getProperty('graph2_despatched_count'));
		$this->smarty->assign('graph2_response_count_perc', $this->request->getProperty('graph2_response_count_perc'));
		$this->smarty->assign('graph2_no_response_count', $this->request->getProperty('graph2_no_response_count'));
		$this->smarty->assign('graph2_no_response_count_perc', $this->request->getProperty('graph2_no_response_count_perc'));
		
		$this->smarty->assign('graph3_data', $this->request->getProperty('graph3_data'));
		$this->smarty->assign('graph3_total_count', $this->request->getProperty('graph3_total_count'));
		
		$this->smarty->assign('responses', $this->request->getProperty('responses'));
		
		$this->smarty->display('MailerStatistics.tpl');
	}
}

?>