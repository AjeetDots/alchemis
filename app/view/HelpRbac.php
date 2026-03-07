<?php

/**
 * Defines the app_view_HelpRbac class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_HelpRbac extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->display('HelpRbac.tpl');
	}
}

?>