<?php

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/Site.php');
require_once('app/domain/Company.php');

/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportCompany3 extends app_command_ManipulationCommand
{
    protected $records;
    protected $success;
    protected $failure;

    public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');

		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			$this->processForm($request);
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
	private function init(app_controller_Request $request)
	{
		// update the country ids where we do have a match
		app_domain_ImportCompany::updateKnownCountry();
		
        $request->setObject('country_data', $this->getCountryData());
        $request->setObject('country_lkp_data', $this->getCountryLkpData());
	}



    private function getCountryData()
    {
        return app_domain_ImportCompany::doFindImportCountriesNotInLookupData();

    }


    private function getCountryLkpData()
    {
        // country_id
        if ($items = app_domain_Site::getCountriesAll())
        {
            $options = array();
            $options[0] = '-- select if required--';
            foreach ($items as $item)
            {
                $options[$item['id']] = @C_String::htmlDisplay(ucfirst($item['name']));
            }
            return $options;
        }
    }


    /**
     * Handles the processing of the form, trying to save each object. Assumes
     * any validation has already been performed.
     * @param app_controller_Request $request
     */
    protected function processForm(app_controller_Request $request)
    {
      $properties = $request->getProperties();

      foreach ($properties as $key => $item)
      {
          $temp = strpos($key, 'new_country_');

          if ($temp !== false)
          {
              $unknown_country = str_replace('_', ' ', trim(substr($key,12)));
              $country_id = $item;
              if ($unknown_country != '' && $country_id != '')
              {
                  // Update tbl_import_lines with selected alchemis_company_id
                  app_domain_ImportCompany::updateUnknownCountry($unknown_country, $country_id);
              }
          }
      }

      // update the country ids where we do have a match
      app_domain_ImportCompany::updateKnownCountry();
      
      echo '<p>Unknown countries updated</p>';
      return true;
    }
}
?>
