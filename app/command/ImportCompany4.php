<?php

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/TieredCharacteristic.php');
require_once('app/domain/Company.php');

/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportCompany4 extends app_command_ManipulationCommand
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

		// update the sub cat ids where we do have a match
		app_domain_ImportCompany::updateKnownSubCategory();
		
        $request->setObject('tiered_characteristic_data', $this->getTieredCharacteristicData());
        $request->setObject('tiered_characteristic_lkp_data', $this->getTieredCharacteristicLkpData());

	}



    private function getTieredCharacteristicData()
    {
        return app_domain_ImportCompany::doFindImportSubCategoriesNotInLookupData();
    }

    private function getTieredCharacteristicLkpData()
    {
        return app_domain_TieredCharacteristic::selectAvailableByCompanyIdForDropdown($parent_object_id);
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
          $temp = strpos($key, 'new_sub_category_');

          if ($temp !== false)
          {
              $unknown_sub_category = str_replace('_', ' ', trim(substr($key,17)));
              $sub_category_id = $item;
              if ($unknown_sub_category != '' && $sub_category_id != '')
              {
                  app_domain_ImportCompany::updateUnknownSubCategory($unknown_sub_category, $sub_category_id);
              }
          }
      }

      // now update the remaining sub cat ids where we do have a match
       app_domain_ImportCompany::updateKnownSubCategory();

      echo '<p>Field sub_category updated</p>';
      return true;
    }
}
?>
