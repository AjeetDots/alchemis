<?php

/**
 * Defines the app_view_DashboardEvents class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_DashboardEvents extends app_view_View
{
	protected function doExecute()
	{
		$events = $this->request->getObject('events');
		$events = $events->toArray();
//		echo '<pre>';
//		print_r($events);
//		echo '</pre>';
		
		$this->smarty->assign('events', $events);
		$this->smarty->display('DashboardEvents.tpl');
	}
}

?>