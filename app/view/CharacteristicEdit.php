<?php

/**
 * Defines the app_view_CharacteristicEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CharacteristicEdit extends app_view_View
{
	protected function doExecute()
	{
		$characteristic = $this->request->getObject('characteristic');
		$this->smarty->assign('characteristic', $this->request->getObject('characteristic'));
		
//		if ($characteristic->hasMultipleElements())
//		{
//			$elements = $characteristic->getElements();
//		}
		
		
//		$elements = $this->request->getObject('elements');
		$this->smarty->assign('elements', $this->request->getObject('elements'));


		$types = array('company', 'post', 'post initiative');
		$this->smarty->assign('types', $types);
		$this->smarty->assign('type', $characteristic->getType());
		
		$data_types = array('boolean', 'date', 'text');
		$this->smarty->assign('data_types', $data_types);
		$this->smarty->assign('data_type', $characteristic->getDataType());
		
//		$this->smarty->assign('characteristics', $this->request->getObject('characteristics'));
//		$this->smarty->assign('available_characteristics', $this->request->getObject('available_characteristics'));
////		$this->smarty->assign('parent_object_type', $this->request->getObject('parent_object_type'));
////		$this->smarty->assign('parent_object_id', $this->request->getObject('parent_object_id'));
////		$this->smarty->assign('category_id', $this->request->getObject('category_id'));
////		$this->smarty->assign('category', $this->request->getProperty('category'));
//		$this->smarty->assign('company_id',   $this->request->getObject('company_id'));
//		$this->smarty->assign('company_name', $this->request->getObject('company_name'));
		
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));

		$this->smarty->assign('client_initiatives', $this->request->getObject('client_initiatives'));

		$campaignCharacteristics = $this->request->getObject('campaignCharacteristics');
		$this->smarty->assign('campaignCharacteristics', $campaignCharacteristics);
		$this->smarty->assign('campaignList', implode(',', $campaignCharacteristics->lists('campaign_id')) . ',');
		
		$this->smarty->display('CharacteristicEdit.tpl');
	}
}

?>