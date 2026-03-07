<?php

/**
 * Defines the app_mapper_ImportCompanyMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/Mapper.php');

/**
 * @package Alchemis
 */
class app_mapper_ImportCompanyMapper extends app_mapper_Mapper implements app_domain_ImportCompanyFinder
{
    public function __construct()
    {
        if (!self::$DB)
        {
            self::$DB = app_controller_ApplicationHelper::instance()->DB();
        }

        // Select all
        $this->selectAllStmt = self::$DB->prepare('SELECT * FROM vw_companies ORDER BY name');

    }

    /**
     * Load an object from an associative array.
     * @param array $array an associative array
     * @return app_domain_DomainObject
     */
    protected function doLoad($array)
    {
        $obj = new app_domain_Company($array['id']);
        $obj->setName($array['name']);
        $obj->setWebsite($array['website']);
        $obj->setTelephone($array['telephone']);
        $obj->setTelephoneTps($array['telephone_tps']);
//      $obj->setCharacteristics(app_domain_Characteristic::findByCompanyId($array['id']));
//      $obj->setNotes($this->findNotes($array['id']));
        $obj->markClean();
        return $obj;
    }

    /**
     * Get a new ID to use from the database.
     * @return integer
     */
    public function newId()
    {
        $this->id = self::$DB->nextID('tbl_companies');
        return $this->id;
    }

    /**
     * Insert a new database record for the object.
     * @param app_domain_DomainObject $object
     */
    function doInsert(app_domain_DomainObject $object)
    {
        $query = 'INSERT INTO tbl_companies (id, name, telephone, website) VALUES (?, ?, ?, ?)';
        $types = array('integer', 'text', 'text', 'text');
        $insertStmt = self::$DB->prepare($query, $types);
        $data = array($object->getId(), $object->getName(),
                        $object->getTelephone(), $object->getWebsite());
        $this->doStatement($insertStmt, $data);
    }

    /**
     * Update the existing database record for the object.
     * @param app_domain_DomainObject $object
     */
    function update(app_domain_DomainObject $object)
    {
        $query = 'UPDATE tbl_companies SET name = ?, website = ?, telephone = ?, telephone_tps = ? WHERE id = ?';
        $types = array('text', 'text', 'text', 'boolean', 'integer');
        $updateStmt = self::$DB->prepare($query, $types);
        $data = array($object->getName(), $object->getWebsite(), $object->getTelephone(), $object->getTelephoneTps(), $object->getId());
        $this->doStatement($updateStmt, $data);

        // Notes
        // NOTE: need to do check else foreach fails
        if ($object->getNotes())
        {
            foreach ($object->getNotes() as $note)
            {
                // TODO
            }
        }
    }

    /**
     * Update the existing database record for the object.
     * @param app_domain_DomainObject $object
     */
    function delete(app_domain_DomainObject $object)
    {
        $query = 'UPDATE tbl_companies SET deleted = 1 WHERE id = ?';
        $types = array('integer');
        $updateStmt = self::$DB->prepare($query, $types);
        $data = array($object->getId());
        $this->doStatement($updateStmt, $data);
    }

    /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public function doFind($id)
    {
        $query = 'SELECT * FROM tbl_companies WHERE id = ?';
        $types = array('integer');
        $selectStmt = self::$DB->prepare($query, $types);

        $data = array($id);
        $result = $this->doStatement($selectStmt, $id);
        return $this->load($result);
    }

   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindUniqueCompanies()
    {
        $query = 'SELECT company_name FROM tbl_import_lines ' .
                'GROUP BY company_name, site_address_1, site_address_2, site_town, site_city, site_county, site_postcode, site_country ' .
                'ORDER BY id';
//        echo $query;

        return self::$DB->queryAll($query);
    }

   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindRawCompanies()
    {
        $query = 'SELECT * FROM tbl_import_lines ' .
                'ORDER BY id';
//        echo $query;

        return self::$DB->queryAll($query);
    }

      /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindRawCompaniesByNameAndSite()
    {
        $query = 'SELECT * FROM tbl_import_lines ' .
                'ORDER BY company_name, site_address_1, site_address_2, site_town, site_city, site_county, site_postcode, site_country';
//        echo $query;

        return self::$DB->queryAll($query);
    }


   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindRawCompaniesWithColumnHeaders()
    {
        $query = 'SELECT * FROM tbl_import_lines ' .
                'ORDER BY id';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

    }

   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindAlchemisCompanies()
    {
        $query = 'SELECT id, name, telephone, town, postcode FROM vw_companies_sites ' .
                "ORDER BY id";
//                "WHERE name like '%puzzle%' ORDER BY id";
//        echo $query;

        return self::$DB->queryAll($query);
    }






   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindProbableAlchemisCompanyMasterRecords($data)
    {
        $output_array = array();

        for ($x= 1; $x<=3; $x++)
        {
            if (!is_null($data[$x]))
            {
                $query = 'SELECT id ' .
                    'FROM tbl_companies ' .
                    'WHERE deleted = 0 ' .
                    'AND dedupe_company_match_' . $x . ' = ' .
                    self::$DB->quote($data[$x], 'text') . ' ' .
                    'ORDER BY id';
//                 echo $query;

                $results = self::$DB->queryCol($query);

//                              echo '<pre>';
//                 print_r($results);
//                 echo '</pre>';

                    foreach ($results as $row)
                    {
                        if (!in_array($row, $output_array))
                        {
                            $output_array[] = $row;
                        }
                    }

            }




        }

//         echo '<pre>';
//         print_r($output_array);
//         echo '</pre>';

        return $output_array;
    }



    /* Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function insertCompanyMatchIds($data)
    {
        $query = 'INSERT INTO tbl_import_company_matches';
        self::$DB->exec($query);

        $output_array = array();

        foreach ($data as $row)
        {
            $temp = explode(':',$row);

            $query = 'INSERT INTO tbl_import_company_matches ' .
                    '(import_row_id, alchemis_company_id, match_type) ' .
                    'VALUES (' .
                    self::$DB->quote($temp[0], 'integer') . ', ' .
                    self::$DB->quote($temp[1], 'integer') . ', ' .
                    self::$DB->quote($temp[2], 'text') . ')';
//                 echo $query . '<br />';

                self::$DB->exec($query);
        }

    }


   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindPossibleAlchemisCompanyMasterRecords($data)
    {
        $output_array = array();

        if (!is_null($data))
        {
                    $query = 'SELECT id ' .
                        'FROM tbl_companies ' .
                        'WHERE deleted = 0 ' .
                        'AND dedupe_company_name = ' .
                        self::$DB->quote($data, 'text') . ' ' .
                        'ORDER BY id';
    //                echo $query . '<br />';

                $results = self::$DB->queryCol($query);

    //            echo '<pre>';
    //        print_r($results);
    //        echo '</pre>';

                foreach ($results as $row)
                {
                    if (!in_array($row, $output_array))
                    {
                        $output_array[] = $row;
                    }
                }
        }
//        echo '<pre>';
//        print_r($output_array);
//        echo '</pre>';

        return $output_array;
    }


   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindRawCompanyDedupeData()
    {
        $query = 'SELECT row_id, dedupe_company_match_1, dedupe_company_match_2, dedupe_company_match_3 ' .
                'FROM tbl_import_lines ' .
                'ORDER BY row_id';
//                "WHERE name like '%puzzle%' ORDER BY id";
//        echo $query;

        return self::$DB->queryAll($query);
    }


       /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindRawCompanyDedupeDataGroupByRowID()
    {
        $query = 'SELECT row_id, dedupe_company_match_1, dedupe_company_match_2, ' .
                'dedupe_company_match_3, dedupe_company_name ' .
                'FROM tbl_import_lines ' .
                'GROUP BY row_id, dedupe_company_match_1, dedupe_company_match_2, ' .
                'dedupe_company_match_3, dedupe_company_name ' .
                'ORDER BY row_id';
//                "WHERE name like '%puzzle%' ORDER BY id";
//         echo $query;

        return self::$DB->queryAll($query);
    }


   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function getImportCompanyRecordsByRowId($row_id)
    {
        // NOTE: we actually query on ID since row_id may have duplicate values (correctly)
        // and we only want to return one address per import company
        $query = 'SELECT * ' .
                'FROM tbl_import_lines il ' .
                'WHERE id = ' . self::$DB->quote($row_id, 'integer');
//        echo $query;

        return self::$DB->queryAll($query);
    }

    public static function lkpImportCompanyNameByRowId($row_id)
    {
        // NOTE: order by id since the proc which generates the row_id uses the first occurrence of id as the row_id
        $query = 'SELECT company_name ' .
                'FROM tbl_import_lines il ' .
                'WHERE row_id = ' . self::$DB->quote($row_id, 'integer') . ' ' .
                'ORDER BY id ';
//        echo $query;

        return self::$DB->queryOne($query);
    }


       /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function selectAlchemisCompanyById($id)
    {
        $query = 'SELECT * ' .
                'FROM vw_companies_sites cs ' .
                'WHERE id = ' . self::$DB->quote($id, 'integer');
//        echo $query;

        return self::$DB->queryAll($query);
    }


   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindAllFromTblImportCompanyMatches()
    {
        $query = 'SELECT * ' .
                'FROM tbl_import_company_matches icm ' .
//                'GROUP BY icm.import_row_id ' .
                'ORDER BY icm.id';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
    }


   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindImportCountiesNotInLookupData()
    {
        $query = 'SELECT il.site_county ' .
                'from tbl_import_lines il ' .
                'left join tbl_lkp_counties lkp_c on il.site_county = lkp_c.name ' .
                'where lkp_c.name is null ' .
                'and il.site_county is not null ' .
                'group by il.site_county ' .
                'order by il.site_county';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
    }


   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindImportCountriesNotInLookupData()
    {
        $query = 'SELECT il.site_country ' .
                'from tbl_import_lines il ' .
                'left join tbl_lkp_countries lkp_c on il.site_country = lkp_c.name ' .
                'where lkp_c.name is null ' .
                'and il.site_country is not null ' .
                'group by il.site_country ' .
                'order by il.site_country';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
    }

     /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindImportSubCategoriesNotInLookupData()
    {
        $query = 'SELECT il.sub_category ' .
                'from tbl_import_lines il ' .
                'left join tbl_tiered_characteristics lkp on il.sub_category = lkp.value ' .
                'where lkp.value is null ' .
                'and il.sub_category is not null ' .
                'group by il.sub_category ' .
                'order by il.sub_category';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
    }

     /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindImportSubCategories_1NotInLookupData()
    {
        $query = 'SELECT il.sub_category_1 ' .
                'from tbl_import_lines il ' .
                'left join tbl_tiered_characteristics lkp on il.sub_category_1 = lkp.value ' .
                'where lkp.value is null ' .
                'and il.sub_category_1 is not null ' .
                'group by il.sub_category_1 ' .
                'order by il.sub_category_1';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
    }

     /**
     * Returns data from address_1 - we assume that this is where address data is populated if the address is in one block (ie need to be separated out into individual address lines)
     * @return array
     */
    public static function doFindImportAddress1Data()
    {
        $query = 'SELECT il.company_name, il.site_address_1, row_id ' .
                'from tbl_import_lines il';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
    }

    public static function updateSingleAddressFieldInImportLines($row_id, $field_name, $value)
    {
        $query = 'UPDATE tbl_import_lines SET ' .
               $field_name . ' = ' . self::$DB->quote($value, 'text') .
               'where row_id = '. self::$DB->quote($row_id, 'integer');

//        echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }



    public static function clearExistingImportData()
    {

     $query = 'DROP TABLE IF EXISTS `tbl_import_lines`';
     self::$DB->exec($query);


     $query = "CREATE TABLE `tbl_import_lines` ( " .
    "`id` int(11) NOT NULL auto_increment, " .
    "`row_id` int(11) NOT NULL default '0', " .
    "`alchemis_company_id` int(11) default '0', " .
    "`company_name` varchar(255) default NULL, " .
    "`company_telephone` varchar(50) default NULL, " .
    "`company_website` varchar(255) NULL, " .
    "`site_address_1` varchar(255) default NULL, " .
    "`site_address_2` varchar(255) default NULL, " .
    "`site_town` varchar(50) default NULL, " .
    "`site_city` varchar(50) default NULL, " .
    "`site_postcode` varchar(25) default NULL, " .
    "`site_county`  varchar(50) default NULL, " .
    "`site_county_id` int(11) default '0', " .
    "`site_country`  varchar(50) default NULL, " .
    "`site_country_id` int(11) default '0', " .
    "`alchemis_post_id` int(11) NULL default '0', " .
    "`post_job_title` varchar(255) default NULL, " .
    "`post_telephone` varchar(50) default NULL, " .
    "`contact_title` varchar(25) default NULL, " .
    "`contact_first_name` varchar(50) default NULL, " .
    "`contact_surname` varchar(50) default NULL, " .
    "`contact_email` varchar(100) default NULL, " .
    "`brand` varchar(100) default NULL, " .
    "`sub_category` varchar(50) default NULL, " .
    "`sub_category_id` int(11) default '0', " .
    "`sub_category_1` varchar(50) default NULL, " .
    "`sub_category_1_id` int(11) default '0', " .
    "`company_tag` varchar(50) default NULL, " .
    "`post_tag` varchar(50) default NULL, " .
    "`project_ref` varchar(50) default NULL, " .
    "`client` varchar(100) default NULL, " .
    "`client_note` text default NULL, " .
    "`client_initiative_id` int(11) default '0', " .
    "`dedupe_company_name` varchar(255) default NULL, " .
    "`dedupe_company_match_1` varchar(150) default NULL, " .
    "`dedupe_company_match_2` varchar(150) default NULL, " .
    "`dedupe_company_match_3` varchar(150) default NULL, " .
    "`dedupe_contact_first_name` varchar(50) default NULL, " .
    "`dedupe_contact_surname` varchar(50) default NULL, " .
    "PRIMARY KEY  (`id`), " .
    "INDEX `ix_tbl_import_lines_dedupe_company_name` (`dedupe_company_name`), " .
    "INDEX `ix_tbl_import_lines_dedupe_company_match_1` (`dedupe_company_match_1`), " .
    "INDEX `ix_tbl_import_lines_dedupe_company_match_2` (`dedupe_company_match_2`), " .
    "INDEX `ix_tbl_import_lines_dedupe_company_match_3` (`dedupe_company_match_3`), " .
    "INDEX `ix_tbl_import_lines_dedupe_contact_first_name` (`dedupe_contact_first_name`), " .
    "INDEX `ix_tbl_import_lines_dedupe_contact_surname` (`dedupe_contact_surname`) " .
    ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

   self::$DB->exec($query);

    $query =  "DROP TABLE IF EXISTS `tbl_import_company_matches`";
    self::$DB->exec($query);

    $query = "CREATE TABLE `tbl_import_company_matches` ( " .
    "`id` int(11) NOT NULL auto_increment, " .
    "`import_row_id` int(11) NOT NULL default '0', " .
    "`alchemis_company_id` int(11) NOT NULL default '0', " .
    "`match_type` varchar(100) default NULL, " .
    "PRIMARY KEY  (`id`), " .
    "INDEX `ix_tbl_import_company_matches_import_row_id` (`import_row_id`), " .
    "INDEX `ix_tbl_import_company_matches_alchemis_company_id` (`alchemis_company_id`), " .
    "INDEX `ix_tbl_import_company_matches_match_type` (`match_type`) " .
    ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

   self::$DB->exec($query);

    $query =  "DROP TABLE IF EXISTS `tbl_import_post_matches`; ";
   self::$DB->exec($query);

    $query =  "CREATE TABLE `tbl_import_post_matches` ( " .
    "`id` int(11) NOT NULL auto_increment, " .
    "`import_id` int(11) NOT NULL default '0', " .
    "`alchemis_post_id` int(11) NOT NULL default '0', " .
    "`match_type` varchar(100) default NULL, " .
    "PRIMARY KEY  (`id`), " .
    "INDEX `ix_tbl_import_post_matches_import_id` (`import_id`), " .
    "INDEX `ix_tbl_import_post_matches_alchemis_post_id` (`alchemis_post_id`), " .
    "INDEX `ix_tbl_import_post_matches_match_type` (`match_type`) " .
    ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

     if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }

//    if (!self::$DB->exec($query))
//     {
//         throw new exception ('Error creating table tbl_import_company_matches');
//     }

    }

    /**
     * Find all companies.
     * @return app_mapper_CompanyCollection collection of app_domain_Company objects
     */
    public function findAll()
    {
        $result = $this->doStatement($this->selectAllStmt, array());
        return new app_mapper_CompanyCollection($result, $this);
    }

    public static function insertLines($line_items)
    {

    /**
    * Count of records, and successes and failures
    *
    * @var array
    */
    $counts = array(
        'records' => 0,
        'success' => 0,
        'failure' => 0,
    );
    
    $failures = array();

        foreach($line_items as $line_item)
        {
            try
            {
                $query = 'INSERT INTO tbl_import_lines (' .
                   'row_id, ' .
                   'alchemis_company_id, ' .
                   'company_name, ' .
                   'company_telephone, ' .
                   'company_website, ' .
                   'site_address_1, ' .
                   'site_address_2, ' .
                   'site_town, ' .
                   'site_city, ' .
                   'site_county, ' .
                   'site_postcode, ' .
                   'site_country, ' .
                   'alchemis_post_id, ' .
                   'post_job_title, ' .
                   'post_telephone, ' .
                   'contact_title, ' .
                   'contact_first_name, ' .
                   'contact_surname, ' .
                   'contact_email, ' .
                   'brand ,' .
                   'sub_category ,' .
                   'company_tag ,' .
                   'post_tag ,' .
                   'project_ref ,' .
                   'client, ' .
                   'client_note) ' .
                   'VALUES (' .
                   self::$DB->quote($line_item['row_id'], 'integer') . ',' .
                   self::$DB->quote($line_item['alchemis_company_id'], 'integer') . ',' .
                   self::$DB->quote($line_item['company_name'], 'text') . ',' .
                   self::$DB->quote($line_item['company_telephone'], 'text') . ',' .
                   self::$DB->quote($line_item['company_website'], 'text') . ',' .
                   self::$DB->quote($line_item['site_address_1'], 'text') . ',' .
                   self::$DB->quote($line_item['site_address_2'], 'text') . ',' .
                   self::$DB->quote($line_item['site_town'], 'text') . ',' .
                   self::$DB->quote($line_item['site_city'], 'text') . ',' .
                   self::$DB->quote($line_item['site_county'], 'text') . ',' .
                   self::$DB->quote($line_item['site_postcode'], 'text') . ',' .
                   self::$DB->quote($line_item['site_country'], 'text') . ',' .
                   self::$DB->quote($line_item['alchemis_post_id'], 'integer') . ',' .
                   self::$DB->quote($line_item['post_job_title'], 'text') . ',' .
                   self::$DB->quote($line_item['post_telephone'], 'text') . ',' .
                   self::$DB->quote($line_item['contact_title'], 'text') . ',' .
                   self::$DB->quote($line_item['contact_first_name'], 'text') . ',' .
                   self::$DB->quote($line_item['contact_surname'], 'text') . ',' .
                   self::$DB->quote($line_item['contact_email'], 'text') . ',' .
                   self::$DB->quote($line_item['brand'], 'text') . ',' .
                   self::$DB->quote($line_item['sub_category'], 'text') . ',' .
                   self::$DB->quote($line_item['company_tag'], 'text') . ',' .
                   self::$DB->quote($line_item['post_tag'], 'text') . ',' .
                   self::$DB->quote($line_item['project_ref'], 'text') . ',' .
                   self::$DB->quote($line_item['client'], 'text') . ',' .
                   self::$DB->quote($line_item['client_note'], 'text') . ')';

                   //echo $query . '<br />';

	                $t = self::$DB->exec($query);
                	
                	
                   if ($t === 1)
                   {
                       $counts['success']++;
                   }
                   else
                   {
                       $counts['failure']++;
                       $line_item['error'] = $t->userinfo;
                       $failures[$line_item['row_id']] = $line_item;
                   }
            }
            catch(Exception $e)
            {
                $counts['failure']++;
                $failures[$line_item['row_id']] = $line_item;;
            }

            $counts['records']++;
        }

        return array($counts, $failures);
    }

    public static function updateRawDupliateCompanyIds($array)
    {
        /**
        * Count of records, and successes and failures
        *
        * @var array
        */
        $counts = array(
            'records' => 0,
            'success' => 0,
            'failure' => 0,
        );

        $row_id = 1;

        foreach($array as $item)
        {
            try
            {
                $query = 'UPDATE tbl_import_lines SET ' .
                    'row_id = ' . $item['master_row_id'] . ' ' .
                    'where row_id = ' . $item['row_id'];

                if (self::$DB->exec($query))
                {
                    $counts['success']++;
                }
                else
                {
                  $counts['failure']++;
                }
            }
            catch(Exception $e)
            {
                $counts['failure']++;
            }

            $counts['records']++;
        }

        if (count($array) == $counts['success'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function insertCompanyDedupeData($data)
    {

//        $query = 'UPDATE tbl_import_lines SET ' .
//                "dedupe_site_postcode = LOWER(LEFT(site_postcode, LOCATE(' ', site_postcode))), " .
//                'dedupe_company_telephone = RIGHT(company_telephone, 4)';

         $query = 'UPDATE tbl_import_lines SET ' .
               'dedupe_company_name = ' . self::$DB->quote($data['dedupe_company_name'], 'text') . ',' .
               'dedupe_company_match_1 = ' . self::$DB->quote($data['dedupe_company_match_1'], 'text') . ',' .
               'dedupe_company_match_2 = ' . self::$DB->quote($data['dedupe_company_match_2'], 'text') . ',' .
               'dedupe_company_match_3 = ' . self::$DB->quote($data['dedupe_company_match_3'], 'text') . ' ' .
               'where row_id = '. self::$DB->quote($data['row_id'], 'integer');

//        echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
    * Gets an open DB connection object.
    * @return resource an open database connection ready for use.
    * @access protected
    * @static
    */
    protected static function getDbConnection()
    {
    	require_once('app/base/Registry.php');
    	$dsn = app_base_ApplicationRegistry::getDSN();
    	$username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
    	$password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
    	$database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
    	$hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
    	return array(
        				'username' => $username, 
        				'password' => $password,
        				'database' => $database,
        				'hostname' => $hostname,
    	);
    }
    
    public static function insertAlchemisCompanyDedupeData( $data)
    {

//         define('DB_HOST',     'localhost');
//         define('DB_NAME',     'alchemis');
//         define('DB_USER',     'alchemis');
//         define('DB_PASSWORD', 'rYT4maP7');
//   		$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
    	
    	$connection = self::getDbConnection();
    	$db = new EasySql($connection['username'], $connection['password'], $connection['database'], $connection['hostname']);
    	

        $db->debug_all = false;

         $query = 'UPDATE tbl_companies SET ' .
               'dedupe_company_name = ' . self::$DB->quote($data['dedupe_company_name'], 'text') . ',' .
               'dedupe_company_match_1 = ' . self::$DB->quote($data['dedupe_company_match_1'], 'text') . ',' .
               'dedupe_company_match_2 = ' . self::$DB->quote($data['dedupe_company_match_2'], 'text') . ',' .
               'dedupe_company_match_3 = ' . self::$DB->quote($data['dedupe_company_match_3'], 'text') . ' ' .
               'where id = '. self::$DB->quote($data['id'], 'integer');

        if ($db->query($query))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public static function updateAlchemisCompanyIds($import_row_id, $alchemis_company_id)
    {
         $query = 'UPDATE tbl_import_lines SET ' .
               'alchemis_company_id = ' . self::$DB->quote($alchemis_company_id, 'integer') . ' ' .
               'where row_id = '. self::$DB->quote($import_row_id, 'integer');

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function resetAllAlchemisCompanyIds()
    {
         $query = 'UPDATE tbl_import_lines SET ' .
               'alchemis_company_id = null';

//         echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function updateUnknownCounty($unknown_county, $county_id)
    {
        // lookup new county name so we can upadte tbl_import_lines.site_county
        $query = 'select name from tbl_lkp_counties where id = ' . self::$DB->quote($county_id, 'integer');
        $new_county_name = self::$DB->queryOne($query);

         $query = 'UPDATE tbl_import_lines SET ' .
               'site_county_id = ' . self::$DB->quote($county_id, 'integer') . ', ' .
                'site_county = ' . self::$DB->quote($new_county_name, 'text') . ' ' .
                'WHERE site_county = ' . self::$DB->quote($unknown_county, 'text');

//         echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function updateKnownCounty()
    {
         $query = 'UPDATE tbl_import_lines il ' .
                'JOIN tbl_lkp_counties lkp_c on lkp_c.name = il.site_county ' .
                'SET il.site_county_id = lkp_c.id';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function updateUnknownCountry($unknown_country, $country_id)
    {
        // lookup new country name so we can upadte tbl_import_lines.site_country
        $query = 'select name from tbl_lkp_countries where id = ' . self::$DB->quote($country_id, 'integer');
        $new_country_name = self::$DB->queryOne($query);

         $query = 'UPDATE tbl_import_lines SET ' .
               'site_country_id = ' . self::$DB->quote($country_id, 'integer') . ', ' .
                'site_country = ' . self::$DB->quote($new_country_name, 'text') . ' ' .
                'WHERE site_country = ' . self::$DB->quote($unknown_country, 'text');

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function updateKnownCountry()
    {
         $query = 'UPDATE tbl_import_lines il ' .
                'JOIN tbl_lkp_countries lkp_c on lkp_c.name = il.site_country ' .
                'SET il.site_country_id = lkp_c.id';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function updateUnknownSubCategory($unknown_sub_category, $sub_category_id)
    {
        // lookup new country name so we can upadte tbl_import_lines.site_country
        $query = 'select value from tbl_tiered_characteristics where id = ' . self::$DB->quote($sub_category_id, 'integer');
        $new_sub_category_name = self::$DB->queryOne($query);

//         echo $query . '<br />';

         $query = 'UPDATE tbl_import_lines SET ' .
               'sub_category_id = ' . self::$DB->quote($sub_category_id, 'integer') . ', ' .
                'sub_category = ' . self::$DB->quote($new_sub_category_name, 'text') . ' ' .
                'WHERE sub_category = ' . self::$DB->quote($unknown_sub_category, 'text');

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function updateKnownSubCategory()
    {
         $query = 'UPDATE tbl_import_lines il ' .
                'JOIN tbl_tiered_characteristics lkp on lkp.value = il.sub_category ' .
                'SET il.sub_category_id = lkp.id, il.sub_category = lkp.value';

//          echo $query . '<br />';
         
        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function updateUnknownSubCategory_1($unknown_sub_category, $sub_category_id)
    {
        // lookup new country name so we can upadte tbl_import_lines.site_country
        $query = 'select value from tbl_tiered_characteristics where id = ' . self::$DB->quote($sub_category_id, 'integer');
        $new_sub_category_name = self::$DB->queryOne($query);

//         echo $query . '<br />';

         $query = 'UPDATE tbl_import_lines SET ' .
               'sub_category_1_id = ' . self::$DB->quote($sub_category_id, 'integer') . ', ' .
                'sub_category_1 = ' . self::$DB->quote($new_sub_category_name, 'text') . ' ' .
                'WHERE sub_category_1 = ' . self::$DB->quote($unknown_sub_category, 'text');

//         echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function updateKnownSubCategory_1()
    {
         $query = 'UPDATE tbl_import_lines il ' .
                'JOIN tbl_tiered_characteristics lkp on lkp.value = il.sub_category_1 ' .
                'SET il.sub_category_1_id = lkp.id, il.sub_category_1 = lkp.value';

//         echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function updateClientInitiativeId($client_initiative_id)
    {
         $query = 'UPDATE tbl_import_lines SET ' .
               'client_initiative_id = ' . self::$DB->quote($client_initiative_id, 'integer');
//         echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function insertTblImportLinesArchive()
    {
    $query = 'INSERT INTO tbl_import_lines_archive ( ' .
'`id`, ' .
 '`row_id`, ' .
    '`alchemis_company_id` , ' .
    '`company_name`,  ' .
    '`company_website`, ' .
    '`company_telephone`, ' .
    '`site_address_1` , ' .
    '`site_address_2` , ' .
    '`site_town` , ' .
    '`site_city` , ' .
    '`site_postcode`, ' .
    '`site_county`  , ' .
    '`site_county_id`,  ' .
    '`site_country` ,  ' .
    '`site_country_id`, ' .
    '`alchemis_post_id`, ' .
    '`post_job_title`,  ' .
    '`post_telephone`,  ' .
    '`contact_title` , ' .
    '`contact_first_name` , ' .
    '`contact_surname` , ' .
    '`contact_email` , ' .
    '`brand` ,' .
    '`sub_category` ,' .
    '`sub_category_id` ,' .
    '`sub_category_1` ,' .
    '`sub_category_1_id` ,' .
    '`company_tag` ,' .
    '`post_tag` ,' .
    '`project_ref` ,' .
    '`client` ,' .
    '`client_note` ,' .
    '`client_initiative_id` ,' .
    '`dedupe_company_name` ,' .
    '`dedupe_company_match_1`, ' .
    '`dedupe_company_match_2` , ' .
    '`dedupe_company_match_3` , ' .
    '`dedupe_contact_first_name` , ' .
    '`dedupe_contact_surname`) ' .
'SELECT  ' .
'`id`,  ' .
 '`row_id`, ' .
    '`alchemis_company_id` , ' .
    '`company_name`,  ' .
    '`company_website`, ' .
    '`company_telephone`, ' .
    '`site_address_1` , ' .
    '`site_address_2` , ' .
    '`site_town` , ' .
    '`site_city` , ' .
    '`site_postcode`, ' .
    '`site_county`  , ' .
    '`site_county_id`,  ' .
    '`site_country` ,  ' .
    '`site_country_id`, ' .
    '`alchemis_post_id`, ' .
    '`post_job_title`,  ' .
    '`post_telephone`,  ' .
    '`contact_title` , ' .
    '`contact_first_name` , ' .
    '`contact_surname` , ' .
    '`contact_email` , ' .
    '`brand` ,' .
    '`sub_category` ,' .
    '`sub_category_id` ,' .
    '`sub_category_1` ,' .
    '`sub_category_1_id` ,' .
    '`company_tag` ,' .
    '`post_tag` ,' .
    '`project_ref` ,' .
    '`client` ,' .
    '`client_note` ,' .
    '`client_initiative_id` ,' .
    '`dedupe_company_name`, ' .
    '`dedupe_company_match_1`, ' .
    '`dedupe_company_match_2` , ' .
    '`dedupe_company_match_3` , ' .
    '`dedupe_contact_first_name` , ' .
    '`dedupe_contact_surname` ' .
'FROM tbl_import_lines  ' .
'ORDER BY ID';

//         echo $query . '<br />';

        if (self::$DB->exec($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }




}

?>