<?php

/**
 * Defines the app_view_CharacteristicList class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CharacteristicList extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('characteristics');
		$this->smarty->assign('characteristics', $collection->toArray());
		
		$types = array('company', 'post', 'post initiative');
		$this->smarty->assign('types', $types);
		
		$data_types = array('boolean', 'date', 'text');
		$this->smarty->assign('data_types', $data_types);
		
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->display('CharacteristicList.tpl');
	}
}

?>