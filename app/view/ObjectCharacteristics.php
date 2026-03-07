<?php

/**
 * Defines the app_view_ObjectCharacteristics class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_ObjectCharacteristics extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('parent_object_id',   $this->request->getObject('parent_object_id'));
		$this->smarty->assign('parent_object_type', $this->request->getObject('parent_object_type'));
		$this->smarty->assign('initiative_id', $this->request->getObject('initiative_id'));
		
		// The associated characteristics as a collection
//		$characteristics = $this->request->getObject('characteristics');
//		$this->smarty->assign('characteristics', $characteristics);
		
		// The associated characteristics as an array
		$characteristic_array = $this->request->getObject('characteristic_array');
		$this->smarty->assign('characteristic_array', $characteristic_array);
		
		// Get characteristics not yet associated with this object
		$available = $this->request->getObject('available');
		$this->smarty->assign('available', $available->toArray());
		
		$available_for_selection = array();
		foreach ($available as $c)
		{
			$available_for_selection[$c->getId()] = $c->getName();
		}
		$this->smarty->assign('available_for_selection', $available_for_selection);
		
		// Get object type
		$type = $this->request->getObject('type');
		$this->smarty->assign('type', $type);
		
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('ObjectCharacteristics.tpl');
	}
}

?>