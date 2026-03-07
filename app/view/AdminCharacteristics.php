<?php

/**
 * Defines the app_view_AdminCharacteristics class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_AdminCharacteristics extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('tab', 'Administration');
		$this->smarty->display('AdminCharacteristics.tpl');
	}
}

?>