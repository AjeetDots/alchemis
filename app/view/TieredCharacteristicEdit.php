<?php

/**
 * Defines the app_view_TieredCharacteristicEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_TieredCharacteristicEdit extends app_view_View
{
	protected function doExecute()
	{
		$characteristic = $this->request->getObject('characteristic');
		$this->smarty->assign('characteristic', $this->request->getObject('characteristic'));
		
		// Parent options
		$parents = $this->request->getObject('parents');
		$this->smarty->assign('parents', $parents);
		
		// Category options
		$categories = $this->request->getObject('categories');
		$this->smarty->assign('categories', $categories);
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		$this->smarty->display('TieredCharacteristicEdit.tpl');
	}
}

?>