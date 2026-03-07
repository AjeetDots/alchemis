<?php

/**
 * Defines the app_view_PostNotes class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_PostNotes extends app_view_View
{
	protected function doExecute()
	{
		$notes = $this->request->getObject('notes');
		$this->smarty->assign('notes', $notes->toRawArray());
		$this->smarty->assign('post_id', $this->request->getProperty('post_id'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('PostNotes.tpl');
	}
}

?>

