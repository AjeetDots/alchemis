<?php

/**
 * Defines the app_view_CampaignDocumentDelete class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Framework
 */
class app_view_CampaignDocumentDelete extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('campaign_id', $this->request->getObject('campaign_id'));
		$this->smarty->assign('document',    $this->request->getObject('document'));
		$this->smarty->assign('success',     $this->request->getObject('success'));
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		// Handle any validation errors
		if ($this->request->isValidationError())
		{
			// Ensure validation errors are assigned to smarty
			$this->handleValidationErrors();
			
			// Ensure field values are made sticky
			$this->handleStickyFields();
		}

		$this->smarty->display('CampaignDocumentDelete.tpl');
	}
}

?>