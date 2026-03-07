<?php

/**
 * Defines the app_view_Reporting class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Reporting extends app_view_View
{
	protected function doExecute()
	{
		// Get MD5 of user ID
		$md5_user_id = $this->request->getObject('md5_user_id');
		$this->smarty->assign('md5_user_id', $md5_user_id);

		$user = $this->request->getObject('user');
		$this->smarty->assign('user', $user);
		
		$client_options = $this->request->getObject('client_options'); 
		$this->smarty->assign('client_options', $client_options);
		
		$client_selected = $this->request->getProperty('client_selected'); 
		$this->smarty->assign('client_selected', $client_selected);
		
		$this->smarty->display('Reporting.tpl');
	}
}

?>