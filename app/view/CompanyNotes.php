<?php

/**
 * Defines the app_view_CompanyNotes class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CompanyNotes extends app_view_View
{
	protected function doExecute()
	{
		$notes = $this->request->getObject('notes');
		$this->smarty->assign('notes', $notes->toRawArray());
		$this->smarty->assign('company_id', $this->request->getProperty('company_id'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('CompanyNotes.tpl');
	}
}

?>