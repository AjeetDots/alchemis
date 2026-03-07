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
		error_reporting (E_ALL & ~E_NOTICE);
		
		$debug = false;
		if ($debug) echo "<pre>";
		if ($debug) print_r($this->request);
		if ($debug) echo "</pre>";
		
		// Instantiate the object
		$id = $this->request->item_id;
			
		switch ($this->request->cmd_action)
		{
			case 'add_user':
				$user = new app_domain_RbacUser();
				$user->setName($this->request->name);
				$user->setHandle($this->request->handle);
				$user->setPassword(md5($this->request->password));
				$user->setActive($this->request->active);
				$client_id = $this->request->client_id ? $this->request->client_id : null;
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