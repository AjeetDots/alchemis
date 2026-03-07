<?php

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/Site.php');

require_once('include/EasySql/EasySql.class.php');

// Ensure the maximum execution time is at least 1200 seconds (20 minutes);
set_time_limit(0);
ini_set('memory_limit', '1280M');
ini_set('auto_detect_line_endings', '1');
/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportCompany1_4 extends app_command_ManipulationCommand
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
            $request->setObject('csv_data', $this->getRawUniqueCompanies());
            $this->setUpCompanyDedupeData();
            $this->findPossibleAlchemisCompanyMasterRecords();
            $request->setObject('company_selection', $this->showCompaniesForUserSelection());
    }

    private function setUpCompanyDedupeData()
    {

        $company_dedupe_name = '';
        $dedupe_company_match_1 = '';
        $dedupe_company_match_2 = '';
        $dedupe_company_match_3 = '';

        $company_patterns = array();
        $company_patterns[0] = '/the /i';
        $company_patterns[1] = '/ltd/i';
        $company_patterns[2] = '/limited/i';
        $company_patterns[3] = '/plc/i';
        $company_patterns[4] = '/lp/i';

        $company_patterns[5] = '/\W/i';

        $company_dedupe_short_postcode = '';
        $company_dedupe_company_telephone = '';
        $company_dedupe_town = '';

        $data = app_domain_ImportCompany::doFindRawCompanies();

        foreach($data as $row)
        {
            $company_dedupe_name = str_replace(' ', '', strtolower(preg_replace($company_patterns, '', $row[3])));
            $company_dedupe_name = substr($company_dedupe_name,0,round((strlen($company_dedupe_name)/(100/50)), 0));
            $company_dedupe_short_postcode = strtolower(substr(str_replace(' ', '', $row[11]), 0, 3));
            $company_dedupe_company_telephone = strtolower(substr($row[5], -4, 4));
            $company_dedupe_town = str_replace(' ', '', strtolower(preg_replace($company_patterns[5], '', $row[8])));

            if ($company_dedupe_name != '' && $company_dedupe_short_postcode != '')
            {
                $dedupe_company_match_1 = $company_dedupe_name . $company_dedupe_short_postcode;
            }

            if ($company_dedupe_name != '' && $company_dedupe_company_telephone != '')
            {
                $dedupe_company_match_2 = $company_dedupe_name . $company_dedupe_company_telephone;
            }

            if ($company_dedupe_name != '' && $company_dedupe_town != '')
            {
                $dedupe_company_match_3 = $company_dedupe_name . $company_dedupe_town;
            }

            $data = array(
                        'row_id'                    => $row[1],
                        'dedupe_company_name'       => $company_dedupe_name,
                        'dedupe_company_match_1'    => $dedupe_company_match_1,
                        'dedupe_company_match_2'    => $dedupe_company_match_2,
                        'dedupe_company_match_3'    => $dedupe_company_match_3,
            );

            app_domain_ImportCompany::insertCompanyDedupeData($data);

            $dedupe_company_match_1 = '';
            $dedupe_company_match_2 = '';
            $dedupe_company_match_3 = '';

        }

        return true;
    }


    private function getRawUniqueCompanies()
    {

        $master_dupliate_company_refs = array();

        $current_row_id = 0;
        $master_row_id = 0;

        $address = '';
        $address_previous_row = '';
        $company_previous_row = '';

        $data = app_domain_ImportCompany::doFindRawCompaniesByNameAndSite();

        foreach($data as $row)
        {


            $current_row_id = $row[1];
            $index = 6;
            $address_array = array(
                    'address_1'           => $row[$index++],
                    'address_2'           => $row[$index++],
                    'town'                => $row[$index++],
                    'city'                => $row[$index++],
                    'county'              => $row[$index++],
                    'postcode'            => $row[$index++],
                    'country'             => $row[$index++],
            );

            $address_string = app_domain_Site::formatAddress($address_array, 'string');

            if ($address_string !== '') {
                if ($address_string != $address_previous_row)
                {
                    $master_row_id = $row[1];
                }
                else
                {
                    $master_dupliate_company_refs[] = array(
                        'row_id'    =>  $current_row_id,
                        'master_row_id' =>  $master_row_id,
                    );
                }
            } else { // check if company name is identical
                if ($row[3] != $company_previous_row)
                    {
                        $master_row_id = $row[1];
                    }
                    else
                    {
                        $master_dupliate_company_refs[] = array(
                            'row_id'    =>  $current_row_id,
                            'master_row_id' =>  $master_row_id,
                        );
                    }
            }
            $address_previous_row = $address_string;
            $company_previous_row = $row[3];
        }


         app_domain_ImportCompany::updateRawDupliateCompanyIds($master_dupliate_company_refs);

        return true;
    }

    private function findPossibleAlchemisCompanyMasterRecords()
    {

        $data = app_domain_ImportCompany::doFindRawCompanyDedupeDataGroupByRowID();

//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';

        $masters = array();

        foreach($data as $row)
        {

//
//             echo '<pre>';
//         print_r($row);
//         echo '</pre>';

            $temp = app_domain_ImportCompany::doFindProbableAlchemisCompanyMasterRecords($row);


            foreach ($temp as $temp_row)
            {
//              if (!in_array($temp_row, $masters))
//              {
                    $masters[] =  $row[0] . ':' . $temp_row . ':probable';
//              }
            }

            $temp = app_domain_ImportCompany::doFindPossibleAlchemisCompanyMasterRecords($row[4]);
            foreach ($temp as $temp_row)
            {
                if (!in_array($row[0] . ':' . $temp_row . ':probable', $masters))
                {
                    $masters[] =  $row[0] . ':' . $temp_row . ':possible';
                }
            }


        }

//        echo '<pre>';
//        print_r($probable_masters);
//        echo '</pre>';

//         echo '<pre>';
//         print_r($masters);
//         echo '</pre>';

        app_domain_ImportCompany::insertCompanyMatchIds($masters);

        return true;
    }


    private function showCompaniesForUserSelection()
    {

        $import_address = '';
        $import_company_telephone = '';
        $import_company_website = '';

        $alchemis_address = '';
        $alchemis_company_telephone = '';
        $alchemis_company_website = '';


        $current_alchemis_company_id = 0;
        $previous_alchemis_company_id = 0;
        $current_import_row_id = 0;
        $previous_import_row_id = 0;

        $data = app_domain_ImportCompany::doFindAllFromTblImportCompanyMatches();

        foreach($data as $row)
        {
            $current_import_row_id = $row['import_row_id'];

            $import_company_data = app_domain_ImportCompany::getImportCompanyRecordsByRowId($row['import_row_id']);

            $import_company_name = $import_company_data[0][3];
            $import_company_telephone = $import_company_data[0][5];
            $import_company_website = $import_company_data[0][4];

            $index = 6;
            $address_array = array(
                    'address_1'           => $import_company_data[0][$index++],
                    'address_2'           => $import_company_data[0][$index++],
                    'town'                => $import_company_data[0][$index++],
                    'city'                => $import_company_data[0][$index++],
                    'county'              => $import_company_data[0][$index++],
                    'postcode'            => $import_company_data[0][$index++],
                    'country'             => $import_company_data[0][$index++],
            );

            $import_address = app_domain_Site::formatAddress($address_array, 'paragraph');

            // get address info for possible matches
            $alchemis_company_data = app_domain_ImportCompany::selectAlchemisCompanyById($row['alchemis_company_id']);

            $alchemis_company_name = $alchemis_company_data[0][1];
            $alchemis_company_telephone = $alchemis_company_data[0][3];
            $alchemis_company_website = $alchemis_company_data[0][2];

            $current_alchemis_company_id = $row['alchemis_company_id'];

            $index = 8;
            $address_array = array(
                    'address_1'           => $alchemis_company_data[0][$index++],
                    'address_2'           => $alchemis_company_data[0][$index++],
                    'town'                => $alchemis_company_data[0][$index++],
                    'city'                => $alchemis_company_data[0][$index++],
                    'county'              => $alchemis_company_data[0][$index++],
                    'postcode'            => $alchemis_company_data[0][$index++],
                    'country'             => $alchemis_company_data[0][$index++],
            );

            $alchemis_address = app_domain_Site::formatAddress($address_array, 'paragraph');

            // build final output array
            // if the import_row_id is the same as the previous then we place a null value for the
            // import_row_id and only populate the possible alchemis company match. This is so that we
            // have an easy to use array in the view

            if ($current_alchemis_company_id != $previous_alchemis_company_id)
            {
                if ($current_import_row_id == $previous_import_row_id)
                {
                  $output_array[] = array(
                    'import_row_id'         => $row['import_row_id'],
                    'import_company_name'   => null,
                    'import_company_telephone' => null,
                    'import_company_website' => null,
                    'import_address'        => null,
                    'alchemis_company_id'   => $row['alchemis_company_id'],
                    'alchemis_company_name' => $alchemis_company_name,
                    'alchemis_company_telephone' => $alchemis_company_telephone,
                    'alchemis_company_website' => $alchemis_company_website,
                    'alchemis_address'      => $alchemis_address,
                    );
                }
                else
                {
                      $output_array[] = array(
                    'import_row_id'         => $row['import_row_id'],
                    'import_company_name'   => $import_company_name,
                    'import_company_telephone' => $import_company_telephone,
                    'import_company_website' => $import_company_website,
                    'import_address'        => $import_address,
                    'alchemis_company_id'   => $row['alchemis_company_id'],
                    'alchemis_company_name' => $alchemis_company_name,
                    'alchemis_company_telephone' => $alchemis_company_telephone,
                    'alchemis_company_website' => $alchemis_company_website,
                    'alchemis_address'      => $alchemis_address,
                    );
                }
            }
            else
            {
                 $output_array[] = array(
                    'import_row_id'         => $row['import_row_id'],
                    'import_company_name'   => $import_company_name,
                    'import_company_telephone' => $import_company_telephone,
                    'import_company_website' => $import_company_website,
                    'import_address'        => $import_address,
                    'alchemis_company_id'   => $row['alchemis_company_id'],
                    'alchemis_company_name' => $alchemis_company_name,
                    'alchemis_company_telephone' => $alchemis_company_telephone,
                    'alchemis_company_website' => $alchemis_company_website,
                    'alchemis_address'      => $alchemis_address,
                );
            }

            $previous_alchemis_company_id = $current_alchemis_company_id;
            $previous_import_row_id = $current_import_row_id;
        }


//          echo '$output_array<pre>';
//          print_r($output_array);
//          echo '</pre>';

        return $output_array;
    }

    /**
     * Handles the processing of the form, trying to save each object. Assumes
     * any validation has already been performed.
     * @param app_controller_Request $request
     */
    protected function processForm(app_controller_Request $request)
    {
	    $properties = $request->getProperties();
	
	    // reset all alchemis company ids
	    app_domain_ImportCompany::resetAllAlchemisCompanyIds();
	
	    foreach ($properties as $key => $item)
	    {
	        $temp = strpos($key, 'chk_');
	
	        if ($temp !== false)
		    {
	            $import_row_id = trim(substr($key,4));
	            $alchemis_company_id = $item;
	            if (is_numeric($import_row_id) && is_numeric($alchemis_company_id))
	            {
	                // Update tbl_import_lines with selected alchemis_company_id
	                app_domain_ImportCompany::updateAlchemisCompanyIds($import_row_id, $alchemis_company_id);
	            }
	        }
	    }

	    $request->setProperty('processed', true);	
    	return true;
    }
}
?>
