<?php

/**
 * Defines the app_view_CampaignList class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CampaignList extends app_view_View
{
	protected function doExecute()
	{
		$this->collection = $this->request->getObject('campaigns');
//		$myArray = array();
//		for ($i = 0; $i < $collection->count(); $i++)
//		{
//			$item = $collection->current();
//			$myArray[] = $item;
//			$collection->next();
//		}
//		$this->smarty->assign('campaigns', $myArray);
		$this->smarty->assign('campaigns', $collection->toArray());
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('CampaignList.tpl');
	}
}

?>