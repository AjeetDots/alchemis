<?php

/**
 * Defines the app_view_CampaignReportSummaryEdit.php class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_CampaignReportSummaryEdit extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('id', $this->request->getProperty('id'));
		$this->smarty->assign('subject', $this->request->getProperty('summary'));
		$this->smarty->assign('note', $this->request->getProperty('note'));
		$this->smarty->assign('summary', $this->request->getObject('summary'));
		$this->smarty->assign('error', $this->request->getProperty('error'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('success', $this->request->getProperty('success'));
		$this->smarty->display('CampaignReportSummaryEdit.tpl');
	}
}

?>