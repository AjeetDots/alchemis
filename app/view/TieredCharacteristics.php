<?php

/**
 * Defines the app_view_TieredCharacteristics class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_TieredCharacteristics extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('tiered_characteristics', $this->request->getObject('tiered_characteristics'));
		$this->smarty->assign('unused_top_level_tiered_characteristics', $this->request->getObject('unused_top_level_tiered_characteristics'));
		$this->smarty->assign('parent_object_type', $this->request->getObject('parent_object_type'));
		$this->smarty->assign('parent_object_id', $this->request->getObject('parent_object_id'));
		$this->smarty->assign('category_id', $this->request->getObject('category_id'));
		$this->smarty->assign('category', $this->request->getProperty('category'));
		$this->smarty->display('TieredCharacteristics.tpl');
	}
}

?>