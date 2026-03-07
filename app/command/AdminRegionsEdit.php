<?php

/**
 * Defines the app_command_AdminRegionsEdit class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/RegionMapper.php');
require_once('app/domain/Region.php');

class app_command_AdminRegionsEdit extends app_command_Command
{
		public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
/**
//			if ($errors = $this->getFormErrors($request))
//			{
//				// Ensure validation errors are passed in to the view
//				$this->handleValidationErrors($request, $errors);
//				
//				// Ensure field values are made sticky
//				$this->handleStickyFields($request);
//				
//				$this->init($request);
//				return self::statuses('CMD_VALIDATION_ERROR');
//			}
//			elseif ($this->processForm($request))
 * 
 */
			if ($this->processForm($request))
			{
				$this->init($request);
				$request->setProperty('success', true);
				$request->addFeedback('Save Successful');
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
	 * Handles the processing of the form, trying to save each object. Assumes 
	 * any validation has already been performed. 
	 * @param app_controller_Request $request
	 */
	protected function processForm(app_controller_Request $request)
	{
		
		
		$region = app_domain_Region::find($request->getProperty('id'));
		
		$region->setName($request->getProperty('app_domain_Region_name'));
		$region->setDescription($request->getProperty('app_domain_Region_description'));
		$region->commit();
		
		return true;
	}



	protected function init(app_controller_Request $request)
	{
		$region = app_domain_Region::find($request->getProperty('id'));
		$request->setObject('region', $region);
	}
	
}


?>