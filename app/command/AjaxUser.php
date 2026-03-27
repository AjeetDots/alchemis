<?php

/**
 * Defines the app_command_AjaxUser class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/RbacUser.php');
require_once('app/mapper/RbacUserMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_RbacUser objects.
 * @package Alchemis
 */
class app_command_AjaxUser extends app_command_AjaxCommand
{
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);
		
		$debug = false;
		if ($debug) echo "<pre>";
		if ($debug) print_r($this->request);
		if ($debug) echo "</pre>";
		
		// Instantiate the object
		$id = isset($this->request->item_id) ? $this->request->item_id : null;
			
		switch (isset($this->request->cmd_action) ? $this->request->cmd_action : null)
		{
			case 'add_user':
				$user = new app_domain_RbacUser();
				$user->setName(isset($this->request->name) ? $this->request->name : null);
				$user->setHandle(isset($this->request->handle) ? $this->request->handle : null);
				$user->setPassword(md5(isset($this->request->password) ? $this->request->password : ''));
				$user->setActive(isset($this->request->active) ? $this->request->active : 0);
				$client_id = (isset($this->request->client_id) && $this->request->client_id) ? $this->request->client_id : null;
				$user->setClientId($client_id);
				$user->commit();
				$this->request->line_html = $this->getUserListLine($user);
				$this->request->success = true;
				break;

			default:
				// TODO
				//  - should throw/log an error of some sort?
				break;
		}
		
		$this->response->data[] = $this->request;
	}

	/**
	 * @param app_domain_RbacUser $user
	 */
	protected function getUserListLine(app_domain_RbacUser $user)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('user', $user);
		return $smarty->fetch('html_UserListLine.tpl');
	}

}

?>