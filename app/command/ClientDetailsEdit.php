<?php

require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

/**
 * Extends the base Command object by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ClientDetailsEdit extends app_command_ManipulationCommand
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
		$client = app_domain_Client::find($request->getProperty('id'));
		
		$client->setName($request->getProperty('client_name'));
		$is_current = $request->getProperty('is_current');
		if (isset($is_current))
		{
			$client->setIsCurrent(true);
		}
		else
		{
			$client->setIsCurrent(false);
		}
		$client->setAddress1($request->getProperty('address_1'));
		$client->setAddress2($request->getProperty('address_2'));
		$client->setAddress3($request->getProperty('address_3'));
		$client->setTown($request->getProperty('town'));
		$client->setCountyId($request->getProperty('county_id'));
		$client->setPostcode($request->getProperty('postcode'));
		$client->setCountryId($request->getProperty('country_id'));
		$client->setTelephone($request->getProperty('telephone'));
		$client->setFax($request->getProperty('fax'));
		$client->setWebsite($request->getProperty('website'));
		$client->setFinancialYearStart(Utils::DateFormat($request->getProperty('financial_year_start_date'), 'DD/MM/YYYY', 'YYYY-MM-DD'));
		$client->setPrimaryContactName($request->getProperty('primary_contact_name'));
		$client->setPrimaryContactJobTitle($request->getProperty('primary_contact_job_title'));
		$client->setPrimaryContactTelephone($request->getProperty('primary_contact_telephone'));
		$client->setPrimaryContactEmail($request->getProperty('primary_contact_email'));
		$client->setSecondaryContactName($request->getProperty('secondary_contact_name'));
		$client->setSecondaryContactJobTitle($request->getProperty('secondary_contact_job_title'));
		$client->setSecondaryContactTelephone($request->getProperty('secondary_contact_telephone'));
		$client->setSecondaryContactEmail($request->getProperty('secondary_contact_email'));
		$client->commit();
		
		return true;
	}

	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{
		// Pass-through parameters
		$request->setProperty('id', $request->getProperty('id'));
		
		// Get client
		$client_id = $request->getProperty('id');
		$client = app_domain_Client::find($client_id);
		$request->setObject('client', $client);
		
		// county_id
		if ($items = app_domain_Site::getCountiesAll())
		{
			$options = array();
			$options[0] = '-- Select --';
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
			}
			$request->setObject('counties_options', $options);
		}
		
		// country_id
		if ($items = app_domain_Site::getCountriesAll())
		{
			$options = array();
			$options[0] = '-- Select --';
			foreach ($items as $item)
			{
				$options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
			}
			$request->setObject('countries_options', $options);
		}
		
	}	
	
}
?>
