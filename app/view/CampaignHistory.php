<?php

/**
 * Defines the app_view_CampaignHistory class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CampaignHistory extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('campaigns');
//		for ($i = 0; $i < $collection->count(); $i++)
//		{
//			$item = $collection->getRaw($i);
//			$myArray[] = $item;
//		}
//		$this->smarty->assign('campaigns', $myArray);
		$this->smarty->assign('campaigns', $collection->getRawArray());
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('CampaignCreate.tpl');
		$this->smarty->display('CampaignHistory.tpl');
	}
}

?>