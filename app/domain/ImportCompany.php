<?php

/**
 * Defines the app_domain_ImportCompany class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_ImportCompany extends app_domain_DomainObject
{

//	protected $mailer_id;
//	protected $post_initiative_id;
//	protected $despatched_date;
//	protected $despatched_communication_id;
//	protected $response_date;
//	protected $response_communication_id;
//	protected $note;
//
//	function __construct($id = null)
//	{
//		parent::__construct($id);
//	}
//

//    /** Updates any lines in tbl_import_lines where the company name address match
//   * @return boolean
//   */
  public static function updateRawDupliateCompanyIds($array)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateRawDupliateCompanyIds($array);
  }

//    /** Inserts dedupe field data in tbl_import_lines
//   * @return boolean
//   */
  public static function insertCompanyDedupeData($array)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->insertCompanyDedupeData($array);
  }

//    /** Inserts dedupe field data in tbl_companies
//   * @return boolean
//   */
  public static function insertAlchemisCompanyDedupeData($array)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->insertAlchemisCompanyDedupeData($array);
  }

//    /** Insert a number of lines into tbl_import_lines
//   * @return boolean
//   */
  public static function insertLines($line_items)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->insertLines($line_items);
  }

//    /** Insert a number of lines into tbl_import_company_matches
//   * @return boolean
//   */
  public static function insertCompanyMatchIds($data)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->insertCompanyMatchIds($data);
  }

    /** Find each occurence of a company in tbl_import_lines based on company_name and full address
   * @return raw array
   */
  public static function doFindUniqueCompanies()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindUniqueCompanies();
  }


   /** Find each all data in tbl_import_lines
   * @return raw array
   */
  public static function doFindRawCompanies()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindRawCompanies();
  }

   /** Find each all data in tbl_import_lines
   * @return raw array
   */
  public static function doFindRawCompaniesByNameAndSite()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindRawCompaniesByNameAndSite();
  }




   /** Find each all data in tbl_import_lines
   * @return raw array
   */
  public static function doFindRawCompaniesWithColumnHeaders()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindRawCompaniesWithColumnHeaders();
  }


   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindAlchemisCompanies()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindAlchemisCompanies();
  }


    /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindProbableAlchemisCompanyMasterRecords($data)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindProbableAlchemisCompanyMasterRecords($data);
  }


   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindPossibleAlchemisCompanyMasterRecords($data)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindPossibleAlchemisCompanyMasterRecords($data);
  }




   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindRawCompanyDedupeData()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindRawCompanyDedupeData();
  }

   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindRawCompanyDedupeDataGroupByRowID()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindRawCompanyDedupeDataGroupByRowID();
  }


     /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindAllFromTblImportCompanyMatches()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindAllFromTblImportCompanyMatches();
  }


       /** Find each all data in doFindImportAddress1Data
   * @return raw array
   */
  public static function doFindImportAddress1Data()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindImportAddress1Data();
  }

     /** updates a specified field in tbl_Import_Lines for a specific row_id
   * @return raw array
   */
  public static function updateSingleAddressFieldInImportLines($row_id, $field_name, $value)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateSingleAddressFieldInImportLines($row_id, $field_name, $value);
  }



       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function getImportCompanyRecordsByRowId($row_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->getImportCompanyRecordsByRowId($row_id);
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function lkpImportCompanyNameByRowId($row_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->lkpImportCompanyNameByRowId($row_id);
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindImportSubCategoriesNotInLookupData()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindImportSubCategoriesNotInLookupData();
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindImportSubCategories_1NotInLookupData()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindImportSubCategories_1NotInLookupData();
  }

       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function clearExistingImportData()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->clearExistingImportData();
  }



     /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function selectAlchemisCompanyById($id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->selectAlchemisCompanyById($id);
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindImportCountiesNotInLookupData()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindImportCountiesNotInLookupData();
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindImportCountriesNotInLookupData()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindImportCountriesNotInLookupData();
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateUnknownCounty($unknown_county, $county_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateUnknownCounty($unknown_county, $county_id);
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateKnownCounty()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateKnownCounty();
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateUnknownCountry($unknown_country, $country_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateUnknownCountry($unknown_country, $country_id);
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateKnownCountry()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateKnownCountry();
  }

       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateUnknownSubCategory($unknown_sub_category, $sub_category_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateUnknownSubCategory($unknown_sub_category, $sub_category_id);
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateKnownSubCategory()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateKnownSubCategory();
  }

   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateUnknownSubCategory_1($unknown_sub_category, $sub_category_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateUnknownSubCategory_1($unknown_sub_category, $sub_category_id);
  }


   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateKnownSubCategory_1()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateKnownSubCategory_1();
  }
       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateClientInitiativeId($client_initiative_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateClientInitiativeId($client_initiative_id);
  }



       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateAlchemisCompanyIds($import_row_id, $alchemis_company_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateAlchemisCompanyIds($import_row_id, $alchemis_company_id);
  }

         /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function resetAllAlchemisCompanyIds()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->resetAllAlchemisCompanyIds();
  }


         /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function insertTblImportLinesArchive()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->insertTblImportLinesArchive();
  }

}

?>
