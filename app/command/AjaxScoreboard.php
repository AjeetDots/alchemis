<?php

/**
 * Defines the app_command_AjaxScoreboard class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Scoreboard.php');

/**
 * @package Alchemis
 */
class app_command_AjaxScoreboard extends app_command_AjaxCommand
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
			case 'get_home_scoreboard':
				$user_id = $_SESSION['auth_session']['user']['id'];
				$scoreboard = app_domain_Scoreboard::findByUserIdStartDateEndDate($user_id, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
				
				$this->request->communication_count = $scoreboard->getCommunicationCount();
				$this->request->effective_count = $scoreboard->getEffectiveCount();
				
				$this->request->success = true;
				break;
			
			default:
				// TODO
				//  - should throw/log an error of some sort?
				break;
		}
		
		$this->response->data[] = $this->request;
	}


}

?>