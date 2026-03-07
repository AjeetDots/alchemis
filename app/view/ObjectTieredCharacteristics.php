<?php

/**
 * Defines the app_view_ObjectTieredCharacteristics class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_ObjectTieredCharacteristics extends app_view_View
{
	protected function doExecute()
	{
		// Root (top level, no parents) tiered characteristics
		$root_tiered_characteristics = $this->request->getObject('root_tiered_characteristics')->toRawArray();
		$this->smarty->assign('root_tiered_characteristics', $root_tiered_characteristics);
		
		// Parent options
		$parents = $this->request->getObject('parents');
		$this->smarty->assign('parents', $parents);
		
		// Get the tiered characteristics associated with this object (company). 
		$this->smarty->assign('company', $this->request->getObject('company'));
		$this->smarty->assign('object_tiered_characteristics', $this->request->getObject('object_tiered_characteristics'));
		$this->smarty->assign('parent_object_tiered_characteristics', $this->request->getObject('parent_object_tiered_characteristics'));
		
		// Get characteristics not yet associated with this object
		$available = $this->request->getObject('available');
		$this->smarty->assign('available', $available);
		
		$this->smarty->assign('unused_top_level_tiered_characteristics', $this->request->getObject('unused_top_level_tiered_characteristics'));
		
		// Set the object type (e.g. app_domain_Company)
		$this->smarty->assign('parent_object_type', $this->request->getObject('parent_object_type'));
		
		// Set the object ID (e.g. company ID)
		$this->smarty->assign('parent_object_id', $this->request->getObject('parent_object_id'));
		
		$this->smarty->assign('category_id', $this->request->getObject('category_id'));
		$this->smarty->assign('category', $this->request->getProperty('category'));
		$this->smarty->display('ObjectTieredCharacteristics.tpl');
	}
}

?>