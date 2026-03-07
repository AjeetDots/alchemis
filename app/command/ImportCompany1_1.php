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
class app_command_ImportCompany1_1 extends app_command_ManipulationCommand
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
        $this->setUpAlchemisCompanyDedupeData();
    }

    private function setUpAlchemisCompanyDedupeData()
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
        $company_patterns[4] = '/plc/i';
        $company_patterns[5] = '/\W/i';

        $company_dedupe_short_postcode = '';
        $company_dedupe_company_telephone = '';
        $company_dedupe_town = '';

        $data = app_domain_ImportCompany::doFindAlchemisCompanies();

        $index = 0;

//         echo '<pre>';
//              print_r($data);
//              echo '</pre>';
//exit();

        foreach($data as $row)
        {
            echo $index++ . '<br />';

//          echo $row[3] . '<br />';
            $company_dedupe_name = str_replace(' ', '', strtolower(preg_replace($company_patterns, '', $row[1])));
//          echo $company_dedupe_name . '<br />';
//          echo strlen($company_dedupe_name) .  '<br />';
//          echo round((strlen($company_dedupe_name)/(100/50)), 0) .  '<br />';
            $company_dedupe_name = substr($company_dedupe_name,0,round((strlen($company_dedupe_name)/(100/50)), 0));
//            echo $company_dedupe_name . '<br />';

            $company_dedupe_short_postcode = strtolower(substr(str_replace(' ', '', $row[4]), 0, 3));
//          echo $company_dedupe_short_postcode . '<br />';

//          echo $row[10] . '<br />';

            $company_dedupe_company_telephone = strtolower(substr($row[2], -4, 4));
//            echo $company_dedupe_company_telephone . '<br />';

            $company_dedupe_town = str_replace(' ', '', strtolower(preg_replace($company_patterns[5], '', $row[3])));
//            echo $company_dedupe_town . '<br /><br />';

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

//            echo $dedupe_company_match_1 . ':' . $dedupe_company_match_2 . ':' . $dedupe_company_match_3 . '<br />';

            $data = array(
                        'id'                        => $row[0],
                        'dedupe_company_name'       => $company_dedupe_name,
                        'dedupe_company_match_1'    => $dedupe_company_match_1,
                        'dedupe_company_match_2'    => $dedupe_company_match_2,
                        'dedupe_company_match_3'    => $dedupe_company_match_3,
            );

            app_domain_ImportCompany::insertAlchemisCompanyDedupeData($data);

            // reset variables for next loop
            $dedupe_company_match_1 = '';
            $dedupe_company_match_2 = '';
            $dedupe_company_match_3 = '';
        }

        return true;
    }


}
?>
