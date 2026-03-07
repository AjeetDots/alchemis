<?php

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/Site.php');

require_once('include/EasySql/EasySql.class.php');

// Ensure the maximum execution time is at least 1200 seconds (20 minutes);
set_time_limit(1800);
ini_set('memory_limit', '1280M');
ini_set('auto_detect_line_endings', '1');
/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportCompany1_2 extends app_command_ManipulationCommand
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
        $this->clearExistingImportData();
        $request->setObject('csv_data', $this->importCsv('data/Coverdale Data Import_17May12.csv'));
    }

    private function clearExistingImportData()
    {
       if (app_domain_ImportCompany::clearExistingImportData())
       {
         echo "Data cleared";
       }
    }

    private function importCsv($file)
    {
        // Open the file handle
        $handle = fopen($file, 'r');

        $length = 0;
        $delimiter = ',';

        $row = 1; // ignore first line contain column headers

        while (($data = fgetcsv($handle, $length, $delimiter)) !== false)
        {
            if ($row > 1)
            {
                // Trim all fields
                foreach ($data as &$field)
                {
                    $field = trim($field);
                }

                $index = 0;

                $pdata = array(
                      'row_id'              => $row-1,
                      'alchemis_company_id' => $data[$index++],
                      'company_name'        => $data[$index++],
                      'company_telephone'   => $data[$index++],
                      'company_website'     => $data[$index++],
                      'site_address_1'      => trim($data[$index++] . ' ' . $data[$index++]),
                      'site_address_2'      => $data[$index++],
                      'site_town'           => $data[$index++],
                      'site_city'           => $data[$index++],
                      'site_county'         => $data[$index++],
                      'site_postcode'       => $data[$index++],
                      'site_country'        => $data[$index++],
                      'alchemis_post_id'    => $data[$index++],
                      'post_job_title'      => $data[$index++],
                      'post_telephone'      => $data[$index++],
                      'contact_title'       => $data[$index++],
                      'contact_first_name'  => $data[$index++],
                      'contact_surname'     => $data[$index++],
                      'contact_email'       => $data[$index++],
                      'brand'               => $data[$index++],
                      'sub_category'        => $data[$index++],
                      'sub_category_1'      => $data[$index++],
                      'company_tag'         => $data[$index++],
                      'post_tag'            => $data[$index++],
                      'project_ref'         => $data[$index++],
                      'client'              => $data[$index++],
                      'client_note'         => $data[$index++],
                );

                 $output_array[] = $pdata;
            }

            $row++;
        }

        // Close the file handle
        fclose($handle);

//        echo '----------------<pre>';
//        print_r($output_array);
//        echo '</pre>';

        return app_domain_ImportCompany::insertLines($output_array);

//        print_r($counts);

        return $counts;
    }


}
?>
