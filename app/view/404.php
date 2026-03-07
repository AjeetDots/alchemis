<?php

/**
 * Defines the app_view_404 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Framework
 */
class app_view_404 extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->display('404.tpl');
	}
}

?>