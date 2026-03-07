<?php

/**
 * Defines the app_view_CharacteristicElements class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CharacteristicElements extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('elements', $this->request->getObject('elements'));
		
		$characteristic = $this->request->getObject('characteristic');
//		$this->smarty->assign('characteristic', $characteristic->getName());
		$this->smarty->assign('characteristic', $characteristic);

//		$this->smarty->assign('parent_object_type', $this->request->getObject('parent_object_type'));
//		$this->smarty->assign('parent_object_id', $this->request->getObject('parent_object_id'));
//		$this->smarty->assign('category_id', $this->request->getObject('category_id'));
//		$this->smarty->assign('category', $this->request->getProperty('category'));
		$this->smarty->display('CharacteristicElements.tpl');
	}
}

?>