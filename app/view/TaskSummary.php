<?php

/**
 * Defines the app_view_TaskSummary class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_TaskSummary extends app_view_View
{
	protected function doExecute()
	{
		$tasks = array(	array(	'date'      => '2006-12-01',
								'task'      => 'Contact Client #1',
								'priority'  => 'high',
								'completed' => true),
						array(	'date'      => '2006-12-02',
								'task'      => 'Contact Client #2',
								'priority'  => 'medium',
								'completed' => true),
						array(	'date'      => '2006-12-04',
								'task'      => 'Contact Client #3',
								'priority'  => 'medium',
								'completed' => false),
						array(	'date'      => '2006-12-05',
								'task'      => 'Contact Client #4',
								'priority'  => 'low',
								'completed' => false));
		$this->smarty->assign('tasks', $tasks);
		$this->smarty->display('TaskSummary.tpl');
	}
}

?>