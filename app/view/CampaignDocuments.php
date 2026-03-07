<?php

/**
 * Defines the app_view_CampaignDocuments class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CampaignDocuments extends app_view_View
{
	protected function doExecute()
	{
		$campaign_id = $this->request->getObject('campaign_id');
		$this->smarty->assign('campaign_id', $campaign_id);
		
		$documents = $this->request->getObject('documents');
		$this->smarty->assign('documents', $documents);

//		$types = array('company', 'post', 'post initiative');
//		$this->smarty->assign('types', $types);
//		
//		$data_types = array('boolean', 'date', 'text');
//		$this->smarty->assign('data_types', $data_types);
//		
//		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('CampaignDocuments.tpl');
	}
}

?>