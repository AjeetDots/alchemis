<?php

/**
 * Defines the app_view_PostNoteCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_PostNoteCreate extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('post_id', $this->request->getObject('post_id'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('postNoteCreate.tpl');
	}
}

?>