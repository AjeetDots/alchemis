<?php

require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_CampaignDetailsEdit extends app_command_ManipulationCommand
{
	
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			
		}
		elseif ($task == 'save')
		{
			if ($this->processForm($request))
			{
				$request->addFeedback('Save Successful');
				$request->setProperty('success', true);
				$request->setProperty('id',$request->getProperty('id'));
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
	 * @param app_controller_Request $request
	 */
	private function processForm($request)
	{
		$campaign = app_domain_Campaign::find($request->getProperty('id'));
		
		$campaign->setTypeId($request->getProperty('campaign_type'));
		
		if ($request->getProperty('start_Year') != '')
		{
			$campaign->setStartYearMonth($request->getProperty('start_Year') . $request->getProperty('start_Month'));
		}
		
		$campaign->setBillingTermsId($request->getProperty('billing_terms'));
		$campaign->setPaymentTermsId($request->getProperty('payment_terms'));
		$campaign->setPaymentMethodId($request->getProperty('payment_method'));
		
		$campaign->setContractSentDate(Utils::DateFormat($request->getProperty('contract_sent_date'), 'DD/MM/YYYY', 'YYYY-MM-DD'));
		$campaign->setContractReceivedDate(Utils::DateFormat($request->getProperty('contract_received_date'), 'DD/MM/YYYY', 'YYYY-MM-DD'));
		$campaign->setSoFormReceivedDate(Utils::DateFormat($request->getProperty('so_form_received_date'), 'DD/MM/YYYY', 'YYYY-MM-DD'));
		
		$campaign->setInitialFee($request->getProperty('initial_fee'));
		$campaign->setCurrentFee($request->getProperty('current_fee'));
				
		$campaign->setMinimumDuration($request->getProperty('minimum_duration'));
		
		$additional_terms = $request->getProperty('additional_terms');
		if (isset($additional_terms))
		{
			$campaign->setAdditionalTermsExist(true);
		}
		else
		{
			$campaign->setAdditionalTermsExist(false);
		}
		
		$campaign->setNoticePeriod($request->getProperty('notice_period'));
		
		$campaign->setNoticeDate(Utils::DateFormat($request->getProperty('notice_date'), 'DD/MM/YYYY', 'YYYY-MM-DD'));
		
		if ($request->getProperty('end_Year') != '')
		{
			$campaign->setEndYearMonth($request->getProperty('end_Year') . $request->getProperty('end_Month'));
		}
						
		$campaign->commit();
		
		return true;
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('id', $request->getProperty('id'));
		
		// Get campaign
		$campaign_id = $request->getProperty('id');
		$campaign = app_domain_Campaign::find($campaign_id);
		$request->setObject('campaign', $campaign);
		
		// get lookup options
		if ($items = app_domain_Campaign::lookupCampaignTypeOptions());
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('campaign_type_options', $options);
		}
		
		if ($items = app_domain_Campaign::lookupBillingTermsOptions());
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('billing_terms_options', $options);
		}
		
		if ($items = app_domain_Campaign::lookupPaymentTermsOptions());
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('payment_terms_options', $options);
		}
		
		if ($items = app_domain_Campaign::lookupPaymentMethodsOptions());
		{
			$options = array();
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['description']));
			}
			$request->setObject('payment_method_options', $options);
		}
		
		// minimum duration
		$options = array();
		for ($x=1;$x<=6;$x++)
		{
			if ($x == 1)
			{
				$options[$x] = $x . ' month';
			}
			else
			{
				$options[$x] = $x . ' months';
			}
		}
		$request->setObject('minimum_duration_options', $options);
		
		// notice period
		$options = array();
		for ($x=1;$x<=6;$x++)
		{
			if ($x == 1)
			{
				$options[$x] = $x . ' month';
			}
			else
			{
				$options[$x] = $x . ' months';
			}
		}
		$request->setObject('notice_period_options', $options);
		
	
		// campaign start year month values
		$start_year_month = $campaign->getStartYearMonth();
		if (!empty($start_year_month))
		{
			$start_year = substr($start_year_month, 0,4);
			$start_month = substr($start_year_month, 4,2);
			$request->setProperty('start_selected', $start_year . '-' . $start_month . '-01');
		}	
			
		// campaign end year month values
		$end_year_month = $campaign->getEndYearMonth();
		if (!empty($end_year_month))
		{
			$end_year = substr($end_year_month, 0,4);
			$end_month = substr($end_year_month, 4,2);
			$request->setProperty('end_selected', $end_year . '-' . $end_month . '-01');
		}	
	}	
	
}
?>
