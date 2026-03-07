<?php

/**
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */
require_once('app/ajax/domain/Ajax_JSON.class.php');
require_once('app/ajax/domain/ajaxResponse.class.php');
require_once('app/ajax/domain/ajaxWarning.class.php');
require_once('app/ajax/domain/ajaxNotice.class.php');

/**
 * @package framework
 */
abstract class app_command_AjaxCommandMultipleParameters extends app_Command_AjaxCommand
{

	protected function ProcessParameters($request)
	{
		foreach ($request as $item)
		{
			
		
			//$result[] = array('field' => $this->request->field, 'value' => $this->request->value, 'item_id' => $this->request->item_id);
			$result[] = array(key($item) => $this->request->key($item));
			
			array_push($this->response->data, $result);
		}
	}
	
	abstract public function execute();
		
}

?>