<?php

/**
 * Defines the app_domain_ImportPost class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_ImportPost extends app_domain_DomainObject
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

//    /** Inserts dedupe field data in tbl_import_lines
//   * @return boolean
//   */
  public static function insertPostDedupeData($array)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->insertPostDedupeData($array);
  }

// //    /** Inserts dedupe field data in tbl_companies
// //   * @return boolean
// //   */
//   public static function insertAlchemisPostDedupeData($array)
//   {
//       $finder = self::getFinder(__CLASS__);
//       return $finder->insertAlchemisPostDedupeData($array);
//   }

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
  public static function insertPostMatchIds($data)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->insertPostMatchIds($data);
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
  public static function doFindRawPosts()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindRawPosts();
  }


   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindAlchemisPosts($lower_id, $upper_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindAlchemisPosts($lower_id, $upper_id);
  }

   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindCountAlchemisPosts()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindCountAlchemisPosts();
  }

 /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function countPostIdByContactsNameAndCompanyId($surname, $first_name, $company_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->countPostIdByContactsNameAndCompanyId($surname, $first_name, $company_id);
  }

 /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function lkpPostIdByContactNameAndCompanyId($surname, $first_name, $company_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->lkpPostIdByContactNameAndCompanyId($surname, $first_name, $company_id);
  }

    /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindProbableAlchemisPostMasterRecords($data)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindProbableAlchemisPostMasterRecords($data);
  }


   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindPossibleAlchemisPostMasterRecords($data)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindPossibleAlchemisPostMasterRecords($data);
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
  public static function doFindRawPostDedupeData()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindRawPostDedupeData();
  }


     /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function doFindAllFromTblImportPostMatches()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->doFindAllFromTblImportPostMatches();
  }


     /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function getImportPostRecordsByAlchemisCompanyId($row_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->getImportPostRecordsByAlchemisCompanyId($row_id);
  }



     /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function getImportPostRecordsById($row_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->getImportPostRecordsById($row_id);
  }


   /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateContactNames($id, $data)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateContactNames($id, $data);
  }






     /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function selectAlchemisPostById($id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->selectAlchemisPostById($id);
  }


       /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function updateAlchemisPostIds($import_id, $alchemis_post_id)
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->updateAlchemisPostIds($import_id, $alchemis_post_id);
  }

         /** Find each all data in doFindAlchemisCompanies
   * @return raw array
   */
  public static function resetAllAlchemisPostIds()
  {
      $finder = self::getFinder(__CLASS__);
      return $finder->resetAllAlchemisPostIds();
  }

}

?>
