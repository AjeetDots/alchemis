<?php

/**
 * Defines the app_view_DashboardMessages class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_DashboardMessages extends app_view_View
{
	protected function doExecute()
	{
		$messages = $this->request->getObject('messages');
		$messages = $messages->toArray();
//		echo '<pre>';
//		print_r($actions);
//		echo '</pre>';
		
//		$this->smarty->assign('actions', $actions->toArray());
		$this->smarty->assign('messages', $messages);
		$this->smarty->display('DashboardMessages.tpl');
	}
}

?>