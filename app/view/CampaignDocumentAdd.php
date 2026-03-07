<?php

require_once('app/view/View.php');

class app_view_CampaignDocumentAdd extends app_view_View
{
	protected function doExecute()
	{
//		$folder = $this->request->getObject('folder');
//		$this->smarty->assign('folder', $folder);
		$campaign_id = $this->request->getObject('campaign_id');
		$this->smarty->assign('campaign_id', $campaign_id);
		
		$document = $this->request->getObject('document');
		$this->smarty->assign('document', $document);
		
		
		
		$feedback = $this->request->getObject('feedback');
		$this->smarty->assign('feedback', $feedback);
//echo $feedback;

		// 
//		$this->smarty->assign('feedback', $this->request->getFeedbackString());
//		$this->smarty->assign('warning',  $this->request->getWarningString());
//		$this->smarty->assign('error',    $this->request->getErrorString());
		
		$this->smarty->assign('success',  $this->request->getObject('success'));
	
		$this->smarty->display('CampaignDocumentAdd.tpl');
	}
}

?>