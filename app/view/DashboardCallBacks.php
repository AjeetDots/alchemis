<?php

/**
 * Defines the app_view_DashboardCallBacks class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_DashboardCallBacks extends app_view_View
{
	protected function doExecute()
	{
		$start_datetime = $this->request->getObject('start_datetime');
		$end_datetime   = $this->request->getObject('end_datetime');
		
//		$this->smarty->assign('start_datetime', $start_datetime);
//		$this->smarty->assign('end_datetime',   $end_datetime);
//		$start_date = substr($start_datetime, 0, 10);
//		$end_date   = substr($start_datetime, 0, 10);

		// Call backs
		$call_backs = $this->request->getObject('call_backs');
		$timed_call_backs = array();
		$other_call_backs = array();
		foreach ($call_backs as $call_back)
		{
			if (preg_match('/00:00:00$/i', $call_back['next_communication_date']))
			{
				$other_call_backs[] = $call_back;
			}
			else
			{
				$timed_call_backs[] = $call_back;
			}
		}
		$this->smarty->assign('timed_call_backs', $timed_call_backs);
		$this->smarty->assign('other_call_backs', $other_call_backs);
		$this->smarty->assign('call_back_count',  count($timed_call_backs) + count($other_call_backs));
		
		$this->smarty->assign('tab', 'Dashboard');
		$this->smarty->display('DashboardCallBacks.tpl');
	}

}

?>