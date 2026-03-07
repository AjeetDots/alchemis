<?php

/**
 * Defines the app_view_BusinessIntelligenceHome class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_BusinessIntelligenceHome extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('BusinessIntelligenceHome.tpl');
	}
}

?>