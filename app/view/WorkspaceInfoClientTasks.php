<?php

/**
 * Defines the app_view_WorkspaceInfoClientTasks class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceInfoClientTasks extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->display('WorkspaceInfoClientTasks.tpl');
	}
}

?>