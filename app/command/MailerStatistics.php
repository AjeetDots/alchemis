<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/MailerMapper.php');
require_once('app/domain/Mailer.php');
require_once('app/mapper/MailerItemMapper.php');
require_once('app/domain/MailerItem.php');
require_once('app/mapper/MailerItemResponseMapper.php');
require_once('app/domain/MailerItemResponse.php');

class app_command_MailerStatistics extends app_command_Command
{
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
			
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}
	
	
	protected function init(app_controller_Request $request)
	{
		
		$mailer_id = $request->getProperty('id');
		$mailer = app_domain_Mailer::find($mailer_id);
		$request->setObject('mailer', $mailer);
				
		// MarketingStatisticsGraph1
		$item_count 	= app_domain_MailerItem::countByMailerId($mailer_id);
		$despatch_count  = app_domain_MailerItem::countDespatchedDateByMailerId($mailer_id);
		
		$request->setProperty('graph1_item_count', $item_count);
		$request->setProperty('graph1_despatched_count', $despatch_count);
		
		if ($item_count == 0)
		{
			$request->setProperty('graph1_despatched_count_perc',0);
			$request->setProperty('graph1_not_despatched_count', 0);
			$request->setProperty('graph1_not_despatched_count_perc', 0);
		}
		else
		{
			$request->setProperty('graph1_despatched_count_perc', round(($despatch_count/$item_count)*100,2));	
			$request->setProperty('graph1_not_despatched_count', $item_count - $despatch_count);
			$request->setProperty('graph1_not_despatched_count_perc', round((($item_count - $despatch_count)/$item_count)*100,2));
		}

		// MarketingStatisticsGraph2
		$despatch_count = app_domain_MailerItem::countDespatchedDateByMailerId($mailer_id);
		$response_count = app_domain_MailerItem::countResponseDateByMailerId($mailer_id);
		
		$request->setProperty('graph2_despatched_count', $despatch_count);
		$request->setProperty('graph2_response_count', $response_count);
		
		if ($despatch_count == 0)
		{
			$request->setProperty('graph2_response_count_perc',0);
			$request->setProperty('graph2_no_response_count', 0);
			$request->setProperty('graph2_no_response_count_perc', 0);
		}
		else
		{
			$request->setProperty('graph2_response_count_perc', round(($response_count/$despatch_count)*100,2));
			$request->setProperty('graph2_no_response_count', $despatch_count - $response_count);
			$request->setProperty('graph2_no_response_count_perc', round((($despatch_count - $response_count)/$despatch_count)*100,2));
		}
		
		// MarketingStatisticsGraph3
		$data = array();
		$total_count = 0;
		if ($results = app_domain_MailerItemResponse::findCountAndDescriptionByMailerId($mailer_id))
		{
			
			// first loop to get total no of items so we can work out percentages in second loop
			$total_count = 0;
			foreach ($results as $result)
			{
				$total_count += $result['count'];
			}
			
			foreach ($results as $result)
			{
				$data[]   = array(	'count' 	=> $result['count'],
									'count_perc'=> round(($result['count']/$total_count)*100,2),
									'response' 	=> $result['description']);
			}
		}
		$request->setProperty('graph3_data', $data);
		$request->setProperty('graph3_total_count', $total_count);
		
		// Responses
		$request->setProperty('responses', $this->getMailerResponses($mailer_id));
		
		
	}
	
	protected function getMailerResponses($mailer_id)
	{
		$data = array();

		if ($results = app_domain_MailerItemResponse::findByMailerId($mailer_id))
		{
			foreach ($results as $result)
			{
				$data[] = array(
					'company_id'    => $result['company_id'],
					'company_name'  => $result['company_name'],
					'post_id'       => $result['post_id'],
					'post'          => $result['job_title'],
					'contact'       => $result['contact'],
					'response'      => $result['description'],
					'response_date' => $result['response_date'],
					'response_note' => $result['response_note']
				);
			}
		}

		return $data;
	}

}

?>