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
class app_command_ImportCompany1_3 extends app_command_ManipulationCommand
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
        $request->setObject('address_data', $this->getAddressBlockData());
	}


    // This function processes an address which is in a single string block and attempts to split in into the correct database fields
    // It does this by
    // 1. starting at the end and assigning the most likely fields (eg postcode, city). These are specified in the
    private function getAddressBlockData()
    {
        $data = app_domain_ImportCompany::doFindImportAddress1Data();
        print_r($data);

//        exit();
        $delimiter = ',';
        $addressMapColumnPriorities = array ( // starting from last element
            0 => 'postcode',
            1 => 'county',
            2 => 'town',
        );

        $addressMapRemainingColumns = array ( // starting from first element
            0 => 'address_1',
            1 => 'address_2',
//            2 => 'town',
        );

        $defaultAddressField = 'address_1'; // field to which any additional address information should be appended

        foreach($data as $address) {
        	$t = array();
        	$addressArray = array_reverse(explode($delimiter, $address['site_address_1']));
        	echo '<pre>';
        	print_r($addressArray);
        	echo '</pre>';
        	$i = 0;
        	foreach ($addressArray as $item) {
        		echo $i . ': ' . $item . '<br />';
        		if (array_key_exists($i, $addressMapColumnPriorities)) { // first create the elements we are pretty sure are right
        			$t[$addressMapColumnPriorities[$i]] = $item;
        		}
        		$i++;
        	}

        	// put the output array into the right order for viewing
        	$t = array_reverse($t);

        	// now reverse the array and start from the beginning again, but this time looking for the columns specified in $addressMapRemainingColumns
        	$addressArray = array_reverse($addressArray);
        	$t1 = array();
        	$t1['row_id'] = $address['row_id'];
        	echo '<pre>';
            print_r($addressArray);
            echo '</pre>';
        	foreach ($addressArray as $key => $item) {
                foreach ($addressMapRemainingColumns as $mapping) {
                	// check if we already have an element of this name in the current output array (t1) AND whether we have already used the value in the previous output array (t)
                	if (!array_key_exists($mapping, $t1) && !(in_array($item, $t))) {
                		$t1[$mapping] = $item;
                		break;
                	}
                }
            }



            $outputAddressArray[] = array_merge($t1, $t);
        }

        echo '<br />---------------<br />';
        print_r($outputAddressArray);
        echo '<br>===============<br /><br />';

        return $outputAddressArray;
    }


    private function getCountyLkpData()
    {
        // county_id
        if ($items = app_domain_Site::getCountiesAll())
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

      $count = 0;

      foreach ($properties as $key => $item)
      {
      	 $delimiterLocation = strpos($key, '_');

      	 $row_id = substr($key, 0, $delimiterLocation);
      	 $field_name = substr($key, $delimiterLocation+1);
//       	 echo $row_id . ': ' . $field_name . '<br />';
      	     if (app_domain_ImportCompany::updateSingleAddressFieldInImportLines($row_id, $field_name, $item)) {
      	     	$count++;
      	     }
      }
      echo $count . ' data items updated';
      return true;
    }
}
?>
