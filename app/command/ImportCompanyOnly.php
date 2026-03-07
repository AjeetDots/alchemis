<?php

require_once('app/command/ManipulationCommand.php');
// require_once('include/Utils/Utils.class.php');
// require_once('include/Utils/String.class.php');
// require_once('app/domain/Site.php');
// require_once('app/domain/ImportCompany.php');
// require_once('app/domain/TieredCharacteristic.php');

require_once('batch/import/StandardImport.class.php');
require_once('include/Zend/Debug.php');

/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportCompanyOnly extends app_command_ManipulationCommand
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
        $request->setProperty('action', 'init');

    }


    private function processForm(app_controller_Request $request)
    {
    	$withRollBack = $request->getProperty('with_rollback');
    	$withRollBack == 'on' ? $withRollBack = true : $withRollBack = false;
    	
    	$logFile = '/var/import-logs/import_' . date('Ymd') . '_' . date('His') . '.txt';
    	
    	$import = new batch_import_StandardImport(APP_DIRECTORY . $logFile, $withRollBack, true, false);
    	 
    	$result = $import->getResult();
    	 
    	$request->setProperty('action', 'completed');
    	$request->setProperty('company_processed_count',$result['processed']['companies'] );
    	$request->setProperty('company_added_count',$result['additions']['companies'] );
    	$request->setProperty('company_existing_count',$result['existing']['companies'] );
    	$request->setProperty('company_duplicates_count',$result['duplicates']['companies'] );
    	$request->setProperty('company_added_count',$result['additions']['companies'] );
    	$request->setProperty('company_failure_count',$result['failures']['companies'] );
    	$request->setProperty('log_file_path',$logFile);
    	
//         $company_added_count = 0;

//         $company_ids_added = array();

//         // get all records from tbl_import_lines and walk through...

//         $data = app_domain_ImportCompany::doFindRawCompaniesWithColumnHeaders();

//         foreach ($data as $row)
//         {
//         	$company_id = 0;


//             // 1. If alchemis_company_id is not empty then don't create a company.
//             if ($row['alchemis_company_id'] != '')
//             {
//                $company_id = $row['alchemis_company_id'];
//                $company = app_domain_Company::find($company_id);
//             }
//             elseif (!array_key_exists($row['row_id'], $company_ids_added)) // create a new company
//             {
//                $company = new app_domain_Company();
//                // set properties and save
//                $company->setName($row['company_name']);
//                $company->setTelephone($row['company_telephone']);
//                $company->setWebsite($row['company_website']);
//                $company->commit();

//                $site = new app_domain_Site();
//                $site->setCompanyId($company->getId());
//                $site->setAddress1($row['site_address_1']);
//                $site->setAddress2($row['site_address_2']);
//                $site->setTown($row['site_town']);
//                $site->setCity($row['site_city']);
//                $site->setCountyId($row['site_county_id']);
//                $site->setPostcode($row['site_postcode']);
//                $site->setCountryId($row['site_country_id']);
//                $site->commit();

//                // get new company id
//                $company_id = $company->getId();

//                // add new company id to an array so we can check if we've already added this company id
//                // to avoid duplicates.
//                $company_ids_added[$row['row_id']] = $company_id;

//                $company_added_count++;
//             }
//             elseif (array_key_exists($row['row_id'], $company_ids_added)) // we've already added this company as a new record so look it up
//             {
//                   $company_id = $company_ids_added[$row['row_id']];
//                   $company = app_domain_Company::find($company_id);
//             }
//             else
//             {
//             	throw new exception('No valid company id found for existing company ' . $company_name . ' - import row ' . $row['row_id']);
//             }

//             // insert company category/sub-cat
//             if ($row['sub_category_id'] != '' && $row['sub_category_id'] > 0)
//             {
//                // Instantiate the tiered characteristic selected
//                $tiered_characteristic = app_domain_TieredCharacteristic::find($row['sub_category_id']);

//                if ($tiered_characteristic->hasParent() && !app_domain_ObjectTieredCharacteristicHelper::isAssociated($company_id, $tiered_characteristic->getParentId()))
//                {
//                     // Parent category needs to be associated first
//                     $obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
//                     $obj->setParentObjectId($company_id);
//                     $obj->setParentObjectType('app_domain_Company');
//                     $obj->setTieredCharacteristicId($tiered_characteristic->getParentId());
//                     $obj->setTier(0);
//                     $obj->commit();
//                 }

//                 if (!app_domain_ObjectTieredCharacteristicHelper::isAssociated($company_id, $row['sub_category_id']))
//                 {
//                     $obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
//                     $obj->setParentObjectId($company_id);
//                     $obj->setParentObjectType('app_domain_Company');
//                     $obj->setTieredCharacteristicId($row['sub_category_id']);
//                     $obj->setTier(3);
//                     $obj->commit();
//                 }
//             }

//             // insert company category/sub-cat_1
//             if ($row['sub_category_1_id'] != '' && $row['sub_category_1_id'] > 0)
//             {
//                 // Instantiate the tiered characteristic selected
//                 $tiered_characteristic = app_domain_TieredCharacteristic::find($row['sub_category_1_id']);

//                 if ($tiered_characteristic->hasParent() && !app_domain_ObjectTieredCharacteristicHelper::isAssociated($company_id, $tiered_characteristic->getParentId()))
//                 {
//                     // Parent category needs to be associated first
//                     $obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
//                     $obj->setParentObjectId($company_id);
//                     $obj->setParentObjectType('app_domain_Company');
//                     $obj->setTieredCharacteristicId($tiered_characteristic->getParentId());
//                     $obj->setTier(0);
//                     $obj->commit();
//                 }

//                 if (!app_domain_ObjectTieredCharacteristicHelper::isAssociated($company_id, $row['sub_category_1_id']))
//                 {
//                     $obj = app_domain_ObjectTieredCharacteristicHelper::factory(null, null);
//                     $obj->setParentObjectId($company_id);
//                     $obj->setParentObjectType('app_domain_Company');
//                     $obj->setTieredCharacteristicId($row['sub_category_1_id']);
//                     $obj->setTier(3);
//                     $obj->commit();
//                 }
//             }

//             // insert brand
//             if ($row['brand'] != '')
//             {
//                 $tag = new app_domain_Tag();
//                 $tag->setValue($row['brand']);
//                 $tag->setCategoryId(1);
//                 $tag->setParentDomainObject($company);
//                 $tag->commit();
//             }

//             // insert company tag
//             if ($row['company_tag'] != '')
//             {
//                 $tag = new app_domain_Tag();
//                 $tag->setValue($row['company_tag']);
//                 $tag->setCategoryId(2);
//                 $tag->setParentDomainObject($company);
//                 $tag->commit();
//             }

//         }

//         // archive tbl_import_lines
//         app_domain_ImportCompany::insertTblImportLinesArchive();

//         echo '<pre>';
//         print_r($company_ids_added);
//         echo '</pre>';

//         $request->setProperty('action', 'completed');
//         $request->setProperty('company_added_count',$company_added_count );

    }
}
?>
