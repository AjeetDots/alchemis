<?php

/**
 * Defines the app_view_RbacCommandView class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_RbacCommandView extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('command', $this->request->getObject('command'));
		$this->smarty->display('RbacCommandView.tpl');
	}
}

?>