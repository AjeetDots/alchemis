<?php

/**
 * Defines the app_view_Calendar class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Calendar extends app_view_View
{
	protected function doExecute()
	{
		// Handle core params
		$this->smarty->assign('date',        $this->request->getObject('date'));
		$this->smarty->assign('nbm_id',      $this->request->getObject('nbm_id'));
		$this->smarty->assign('client_id',   $this->request->getObject('client_id'));
		$this->smarty->assign('nbm_name',    $this->request->getObject('nbm_name'));
		$this->smarty->assign('client_name', $this->request->getObject('client_name'));

		// Set display options
		$this->smarty->assign('display', $this->request->getObject('display'));
		
		// Get the month data
		$month_data = $this->request->getObject('month_data');
		$this->smarty->assign('month_data', $month_data);
		$this->smarty->assign('year',  $this->request->getObject('year'));
		$this->smarty->assign('month', $this->request->getObject('month'));
		
		$this->smarty->display('Calendar.tpl');
	}
}

?>