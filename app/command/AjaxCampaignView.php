<?php

/**
 * Defines the app_domain_CampaignTarget class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/CampaignTarget.php');
require_once('app/mapper/CampaignTargetMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_CampaignTarget objects.
 * @package Alchemis
 */
class app_command_AjaxCampaignView extends app_command_AjaxCommand
{
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'save_target_line':
				$form_data = $this->request->form_data;
				
				// array to hold incoming field data arrays
				$field_data = array();
				$date_data = array();
				
				foreach ($form_data as $item)
				{
					$field_data[] = explode('-', $item[0]);
				}
				$this->request->return_data = $this->saveLine($field_data);
				break;

			default:
				break;
		}
		
		// Return result data
		array_push($this->response->data, $this->request);
		
	}
	
	protected function saveLine($field_data)
	{
		$campaign_target = app_domain_CampaignTarget::find($this->request->target_id);
		foreach ($field_data as $field)
		{
			switch ($field[1])
			{
				case 'calls':
					$campaign_target->setCalls($field[2]);
					break;
				case 'effectives':
					$campaign_target->setEffectives($field[2]);
					break;
				case 'meets_set':
					$campaign_target->setMeetingsSet($field[2]);
					break;
				case 'meets_attended':
					$campaign_target->setMeetingsAttended($field[2]);
					break;
				case 'opportunities':
					$campaign_target->setOpportunities($field[2]);
					break;
				case 'wins':
					$campaign_target->setWins($field[2]);
					break;
			}
		}
		$campaign_target->commit();

		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();

		$smarty->assign('target', $campaign_target);
		
		return $smarty->fetch('html_CampaignTargetLine.tpl');
	}

}

?>