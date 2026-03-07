<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/MailerMapper.php');
require_once('app/domain/Mailer.php');
require_once('app/mapper/MailerItemMapper.php');
require_once('app/domain/MailerItem.php');
require_once('app/mapper/MailerItemResponseMapper.php');
require_once('app/domain/MailerItemResponse.php');
require_once('include/EasySql/EasySql.class.php');
require_once('include/Utils/Utils.class.php');

// Ensure the maximum execution time is unlimited
set_time_limit(0);
ini_set('memory_limit', '128M');

ob_flush();

class app_command_MailerItemList extends app_command_Command
{
	protected $mailer_items_removed_count;
	protected $mailer_items_despatched_count;
	
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
		
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			if ($this->processForm($request))
			{
				$request->addFeedback('Processing complete. ' .$this->mailer_items_removed_count . ' items were removed and ' .
						$this->mailer_items_despatched_count . ' items were despatched.');
				$this->init($request);
				return self::statuses('CMD_OK');
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
	 * Handles the processing of the form. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		$mailer_id = $request->getProperty('mailer_id');
		if ($mailer_id == '')
		{
			throw new Exception('Invalid mailer_id supplied');
		}
		else
		{
			$mailer = app_domain_Mailer::find($mailer_id);
		}
		
		// variable to keep count of number of mailer items despatched/not despatched
		$mailer_items_despatched_count = 0;
		$mailer_items_not_despatched_count = 0;
		
		// variable to keep count of number of mailer items removed/not removed
		$mailer_items_removed_count = 0;
		$mailer_items_not_removed_count = 0;
		
		// set a single despatched_date for all items being despatched
		$despatched_date = date('Y-m-d H:i:s');
		
		$communication_comments = 'Mailer \'' . $mailer->getName() . '\' despatched.';
		$communication_notes = 'Mailer type: ' . $mailer->getResponseGroupName() . '<br />';
		$communication_notes .= 'Despatch method: ' . $mailer->getTypeName() . '<br />';
		
		
		$properties = $request->getProperties();
		
		$dbConnection = self::getDbConnection();
		
		define('DB_HOST',     $dbConnection['hostname']);
		define('DB_NAME',     $dbConnection['database']);
		define('DB_USER',     $dbConnection['username']);
		define('DB_PASSWORD', $dbConnection['password']);
		
		$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
		$db->debug_all = false;

		$my_time = microtime(true);
		echo 'Processing... ';
		foreach ($properties as $key => $item)
		{
			if (substr($key, 0, 11) == 'chk_remove_')
			{
				$mailer_item_id = trim(substr($key,11));
				if (is_numeric($mailer_item_id))
				{
					// Remove mailer item
					$mailer_item = app_domain_MailerItem::find($mailer_item_id);
					$mailer_item->markDeleted();
					$mailer_item->commit();
				
					$mailer_items_removed_count ++;

				
				}			
				else
				{
					// TODO: create a list of posts not added/found
					$mailer_items_not_removed_count ++;
				}
			}
			elseif (substr($key, 0, 15) == 'chk_despatched_')
			{
				$mailer_item_id = trim(substr($key,15));
				if (is_numeric($mailer_item_id))
				{
					// Despatch mailer item
					if ($mailer_item = $this->getMailerItemPostInitiativeId($db, $mailer_item_id))
					{
						$communication_id = $this->addMailerCommunication(null, $mailer_item, $despatched_date, $communication_comments, $communication_notes);
					
						$sql = 'update tbl_mailer_items set despatched_date = \'' . $despatched_date . '\', ' .
								'despatched_communication_id = ' . $communication_id . ' ' . 
								'where id = ' . $mailer_item_id;
						$db->query($sql);

						$mailer_items_despatched_count ++;
						$tmp = fmod($mailer_items_despatched_count, 100);
						
						if ($tmp == 0)
						{
							echo $mailer_items_despatched_count . '< /br>';
							flush();
						}
					}
					else
					{
						throw new Exception('Mailer item id ' . $mailer_item_id . ' could not be found');
					}
				}			
				else
				{
					// TODO: create a list of posts not added/found
					$mailer_items_not_despatched_count ++;
				}
			}
			else
			{
				// do nothing
			}
			
			
		}
		
		echo 'Time taken (seconds) : ' . (microtime(true) - $my_time) . '<br />';
		
		$this->mailer_items_removed_count = $mailer_items_removed_count;
		$this->mailer_items_despatched_count = $mailer_items_despatched_count;
		return true;
	}
	
	protected function init(app_controller_Request $request)
	{
		$mailer = app_domain_Mailer::find($request->getProperty('mailer_id'));
		$request->setObject('mailer', $mailer);
		
		$mailer_items = app_domain_MailerItem::findByMailerId($request->getProperty('mailer_id'));
		$this->getResponseInfo($mailer_items);
		
		$request->setObject('mailer_items', $mailer_items);
	}
	
	
	/**
	 * Adds the response info to mailer items.
	 * @param array $mailer_items
	 */
	protected function getResponseInfo(&$mailer_items)
	{
		foreach ($mailer_items as &$mailer_item)
		{
			if ($mailer_item['response_date'] != '')
			{
				$finder = new app_mapper_MailerItemResponseMapper();
				$reponses = $finder->findByMailerItemId($mailer_item['id']);
				$mailer_responses = array();
				foreach ($reponses as $reponse)
				{
					$mailer_responses[] = $reponse['description'];	
				
				}
				$mailer_item['responses'] = $mailer_responses;
			}
		}
	}
	
	protected function addMailerCommunication($despatched_communicaton_id = null, $post_initiative_id, $despatched_date, $comments, $notes)
	{
		if (is_null($despatched_communicaton_id))
		{
			$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
			
			$communication = new app_domain_Communication();
			$communication->setPostInitiativeId($post_initiative_id);
			$communication->setUserId($_SESSION['auth_session']['user']['id']);
			$communication->setTypeId(5);
			$communication->setStatusId($post_initiative->getStatusId());
			$communication->setCommunicationDate($despatched_date);
			$communication->setDirection('out');
			$communication->setEffective('non-effective');
			$communication->setIsEffective(false);
			$communication->setNextActionBy(1); // Alchemis
		}
		else
		{
			$communication = app_domain_Communication::find($despatched_communicaton_id);
		}
		
		$communication->setComments($comments);
		$communication->setNotes($notes);
		$communication->commit();
		
		return $communication->getId();
		
	}
	
	// returns false if $mailer_item_id doesn't exist in table
	protected function getMailerItemPostInitiativeId($db, $mailer_item_id)
	{
		$sql = 'select post_initiative_id from tbl_mailer_items where id = ' . $mailer_item_id;
		if (is_null($tmp = $db->get_var($sql)))
		{
			return false;
		}
		else
		{
			return $tmp;
		}
		
	}
	
	protected static function getDbConnection()
	{
		require_once('app/base/Registry.php');
		$dsn = app_base_ApplicationRegistry::getDSN();
		$username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
		$password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
		$database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
		$hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
		return array(
					'username' => $username, 
					'password' => $password,
					'database' => $database,
					'hostname' => $hostname,
		);
	}
}

?>