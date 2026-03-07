<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/domain/Mailer.php');
require_once('app/mapper/MailerMapper.php');
require_once('app/domain/MailerItem.php');
require_once('app/mapper/MailerItemMapper.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

// Ensure the maximum execution time is at least 1200 seconds (20 minutes);
set_time_limit(1200);

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_MailerItemCreateBatch extends app_command_ManipulationCommand
{
	protected $mailer_items_added_count;
	
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			if ($mailer_item_count = $this->processForm($request))
			{
//				header('Location: index.php?cmd=MailerItemList&mailer_id=' . $request->getProperty('mailer_id') . '&feedback=' . $this->mailer_items_added_count . ' mailer item(s) added successfully');
//				exit();
			}
			else
			{
				return self::statuses('CMD_ERROR');
			}
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}

	/**
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		$initiative_id = $request->getProperty('initiative_id');
		if ($initiative_id == '')
		{
			throw new Exception('Invalid initiative_id supplied');
		}
		
		$mailer_id = $request->getProperty('mailer_id');
		if ($mailer_id == '')
		{
			throw new Exception('Invalid mailer_id supplied');
		}
		
		// variable to keep count of number of mailer items added/not added
		$mailer_items_added_count = 0;
		$mailer_items_not_added_count = 0;
		
		$properties = $request->getProperties();
		foreach ($properties as $key => $item)
		{
			$temp = strpos($key, 'chk_post_id_');
			
			if ($temp !== false)
			{
				$post_id = trim(substr($key,12));
				if (is_numeric($post_id))
				{
					// Create mailer item
					
					// lookup post_initiative_id for the post/initiative_id combo. If none, create a blank one
					if ($post_initiative = app_domain_PostInitiative::findByPostAndInitiative($post_id, $initiative_id))
					{
						// existing post_initiative 
						// check if this post_initiative_id has already been added to the selected mailer
						$item_exists = app_domain_MailerItem::lookupIdByPostInitiativeIdByMailer($post_initiative->getId(), $mailer_id);
						
						if (is_null($item_exists))
						{
							$item_exists = false;
						}
						else
						{
							$item_exists = true;
						}
					}
					else
					// create new post initiative
					{
						$post_initiative = new app_domain_PostInitiative();
						$post_initiative->setPostId($post_id);
						$post_initiative->setInitiativeId($initiative_id);
						$post_initiative->setStatusId(7); // fresh lead
						$post_initiative->setLeadSourceId(7);
						$post_initiative->setNextActionBy(1); // Alchemis
						$post_initiative->commit();
						
					}
					
					$post_initiative_id = $post_initiative->getId();
						
					if (!$item_exists)
					{				
						$mailer_item = new app_domain_MailerItem();
						$mailer_item->setPostInitiativeId($post_initiative_id);
						$mailer_item->setMailerId($mailer_id);
						$mailer_item->commit();
					
						$mailer_items_added_count ++;
					}
				}
				else
				{
					// TODO: create a list of posts not added/found
					$mailer_items_not_added_count ++;
				}
			}
		}
		
		$this->mailer_items_added_count = $mailer_items_added_count;
		return true;
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('mailer_id', $request->getProperty('mailer_id'));
		$request->setProperty('initiative_id', $request->getProperty('initiative_id'));
		
		// Get lookup information
		if ($items = app_domain_Mailer::findAvailableFiltersByUserId($_SESSION['auth_session']['user']['id']))
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
			}
			$request->setObject('filters', $options);
		}
		
	
	}	
}
?>
