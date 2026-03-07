<?php

/**
 * Defines the app_view_PostInitiativeNote class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2012 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_PostInitiativeNote extends app_view_View
{
	protected function doExecute()
	{
		$note = $this->request->getObject('note');
		$this->smarty->assign('note', $note);
		$attachments = $this->request->getObject('attachments');
		$this->smarty->assign('attachments', $attachments);
		
// 		$this->smarty->assign('post_initiative_note_id', $this->request->getProperty('post_initiative_note_id'));
		$this->smarty->display('PostInitiativeNote.tpl');
	}
}

?>