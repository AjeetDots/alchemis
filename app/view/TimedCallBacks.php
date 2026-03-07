<?php

/**
 * Defines the app_view_TimedCallBacks class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_TimedCallBacks extends app_view_View
{
	protected function doExecute()
	{
		$start_datetime = $this->request->getObject('start_datetime');
		$end_datetime   = $this->request->getObject('end_datetime');

		// Call backs
		$call_backs = $this->request->getObject('call_backs');

		$this->smarty->assign('call_backs', $call_backs);
		$this->smarty->assign('call_back_count',  count($call_backs));
		$this->smarty->assign('success', $this->request->getProperty('success'));
		
		if ($this->request->getProperty('success')) {
			$this->smarty->assign('scoreboard', $this->request->getObject('scoreboard'));
		}
		
		$this->smarty->display('TimedCallBacks.tpl');
	}

}

?>