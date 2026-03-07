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
class app_mapper_ImportPostMapper extends app_mapper_Mapper implements app_domain_ImportPostFinder
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
    public static function doFindRawPosts()
    {
        $query = 'SELECT id, row_id, alchemis_post_id, ' .
			    'post_job_title, ' .
			    'post_telephone, ' .
			    'contact_title, ' .
			    'contact_first_name, ' .
			    'contact_surname ' .
			    'FROM tbl_import_lines ' .
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
    public static function doFindAlchemisPosts($lower_id, $upper_id)
    {
        $query = 'SELECT id, first_name, surname FROM vw_posts_contacts ' .
                'WHERE id > ' . $lower_id . ' ' .
                'AND id <= ' . $upper_id . ' ' .
                'AND (first_name is not null OR first_name != \'\') ' .
                'AND (surname is not null OR surname != \'\') ' .
                'ORDER BY id';
//        echo $query;

        $results = self::$DB->queryAll($query);
//        self::$DB->free();
        return $results;
    }

/**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindCountAlchemisPosts()
    {
        $query = 'SELECT max(id) FROM vw_posts_contacts';

//        echo $query;

        return self::$DB->queryOne($query);
    }

    public static function countPostIdByContactsNameAndCompanyId($first_name, $surname, $company_id)
    {
        $query = 'SELECT count(id) FROM vw_posts_contacts ' .
                'WHERE first_name = ' . self::$DB->quote($first_name, 'text') . ' ' .
                'AND surname = ' . self::$DB->quote($surname, 'text') . ' ' .
                'AND company_id = ' . self::$DB->quote($company_id, 'integer');
        return self::$DB->queryOne($query);
    }

    public static function lkpPostIdByContactNameAndCompanyId($first_name, $surname, $company_id)
    {
        $query = 'SELECT max(id) FROM vw_posts_contacts ' .
                'WHERE first_name = ' . self::$DB->quote($first_name, 'text') . ' ' .
                'AND surname = ' . self::$DB->quote($surname, 'text') . ' ' .
                'AND company_id = ' . self::$DB->quote($company_id, 'integer');
        return self::$DB->queryOne($query);
    }

   /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function doFindProbableAlchemisPostMasterRecords($data)
    {
    	$output_array = array();


    	        $query = 'SELECT post_id ' .
                    'FROM tbl_contacts con ' .
    	            'JOIN tbl_posts p on p.id = con.post_id ' .
                    'WHERE p.deleted = 0 ' .
    	            'AND con.deleted = 0 ' .
                    'AND dedupe_contact_first_name = ' . self::$DB->quote($data[3], 'text') . ' ' .
    	            'AND dedupe_contact_surname = ' . self::$DB->quote($data[4], 'text') . ' ' .
    	            'AND p.company_id = ' . self::$DB->quote($data[2], 'integer') . ' ' .
                    'ORDER BY p.id';
//                echo $query;

    		$results = self::$DB->queryCol($query);

        return $results;
    }



    /* Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function insertPostMatchIds($data)
    {
    	// delete any existing records
    	$query = 'DELETE FROM tbl_import_post_matches';

//    	echo $query . '<br />';

                self::$DB->exec($query);

        $output_array = array();

        foreach ($data as $row)
        {
        	$temp = explode(':',$row);

            $query = 'INSERT INTO tbl_import_post_matches ' .
                    '(import_id, alchemis_post_id, match_type) ' .
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

//        echo '<pre>';
//        print_r($output_array);
//        echo '</pre>';

        return $output_array;
    }

    /* Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function updateContactNames($id, $data)
    {
    	
    		// if three elements in $data then assume they are title, first_name, surname
    		if (count($data) == 3) {  
            	$query = 'UPDATE tbl_import_lines ' .
                    'SET contact_title =  ' . self::$DB->quote($data[0], 'text') . ', ' .
                    'contact_first_name =  ' . self::$DB->quote($data[1], 'text') . ', ' .
                    'contact_surname =  ' . self::$DB->quote($data[2], 'text') . ' ' .
                    'WHERE row_id = ' . self::$DB->quote($id, 'integer') . ';';
    		} elseif (count($data) == 2) { // if ttwo elements in $data then assume they are first_name, surname
    			$query = 'UPDATE tbl_import_lines ' .
						 'SET contact_title = null, ' . 
						 'contact_first_name =  ' . self::$DB->quote($data[0], 'text') . ', ' .
    			         'contact_surname =  ' . self::$DB->quote($data[1], 'text') . ' ' .
    			         'WHERE row_id = ' . self::$DB->quote($id, 'integer') . ';';
    		}
    		
//                 echo $query . '<br />';

                self::$DB->exec($query);

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
    public static function doFindRawPostDedupeData()
    {
        $query = 'SELECT id, row_id, alchemis_company_id, dedupe_contact_first_name, dedupe_contact_surname ' .
                'FROM tbl_import_lines ' .
                'WHERE alchemis_company_id is not null ' .
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
    public static function getImportPostRecordsByAlchemisCompanyId($alchemis_company_id)
    {
    	$query = 'SELECT * ' .
                'FROM tbl_import_lines il ' .
                'WHERE alchemis_company_id = ' . self::$DB->quote($alchemis_company_id, 'integer');
//        echo $query;

        return self::$DB->queryAll($query);
    }


       /**
     * Responsible for constructing and running any queries that are needed.
     * @param integer $id
     * @return app_domain_DomainObject
     * @see app_mapper_Mapper::load()
     */
    public static function getImportPostRecordsById($id)
    {
        $query = 'SELECT * ' .
                'FROM tbl_import_lines il ' .
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
    public static function selectAlchemisPostById($id)
    {
        $query = 'SELECT * ' .
                'FROM vw_posts_contacts cs ' .
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
    public static function doFindAllFromTblImportPostMatches()
    {
        $query = 'SELECT ipm.*, il.alchemis_company_id ' .
                'FROM tbl_import_post_matches ipm ' .
                'JOIN tbl_import_lines il on ipm.import_id = il.id ' .
                'ORDER BY ipm.id';
//        echo $query;

        return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
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
	               'contact_email) ' .
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
	               self::$DB->quote($line_item['contact_email'], 'text') . ')';

//	               echo $query . '<br />';

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

    	return $counts;
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

//                  echo $query . '<br />';

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


    public static function insertPostDedupeData($data)
    {
    	 $query = 'UPDATE tbl_import_lines SET ' .
    	       'dedupe_contact_first_name = ' . self::$DB->quote($data['post_dedupe_contact_first_name'], 'text') . ',' .
               'dedupe_contact_surname = ' . self::$DB->quote($data['post_dedupe_contact_surname'], 'text') . ' ' .
               'where id = '. self::$DB->quote($data['id'], 'integer');

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

//     /**
//     * Gets an open DB connection object.
//     * @return resource an open database connection ready for use.
//     * @access protected
//     * @static
//     */
//     protected static function getDbConnection()
//     {
//     	require_once('app/base/Registry.php');
//     	$dsn = app_base_ApplicationRegistry::getDSN();
//     	$username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
//     	$password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
//     	$database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
//     	$hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
//     	return array(
//             				'username' => $username, 
//             				'password' => $password,
//             				'database' => $database,
//             				'hostname' => $hostname,
//     	);
//     }
    
//     public static function insertAlchemisPostDedupeData( $data)
//     {


// //     	define('DB_HOST',     'localhost');
// //         define('DB_NAME',     'alchemis');
// //         define('DB_USER',     'alchemis');
// //         define('DB_PASSWORD', 'rYT4maP7');

// //         $db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

//     	$connection = self::getDbConnection();
//     	$db = new EasySql($connection['username'], $connection['password'], $connection['database'], $connection['hostname']);
    	
//         $db->debug_all = false;

//          $query = 'UPDATE tbl_contacts con JOIN tbl_posts p ON p.id = con.post_id SET ' .
//                'con.dedupe_contact_first_name = ' . self::$DB->quote($data['post_dedupe_contact_first_name'], 'text') . ',' .
//                'con.dedupe_contact_surname = ' . self::$DB->quote($data['post_dedupe_contact_surname'], 'text') . ' ' .
//                'where con.post_id = '. self::$DB->quote($data['id'], 'integer') . ' ' .
//                'AND p.deleted = 0';

// //        echo $query . '<br />';

//         if ($db->query($query))
//         {
// //        	self::$DB->free();
//             return true;
//         }
//         else
//         {
//             return false;
//         }
//     }

    public static function updateAlchemisPostIds($import_id, $alchemis_post_id)
    {
         $query = 'UPDATE tbl_import_lines SET ' .
               'alchemis_post_id = ' . self::$DB->quote($alchemis_post_id, 'integer') . ' ' .
               'where id = '. self::$DB->quote($import_id, 'integer');

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

    public static function resetAllAlchemisPostIds()
    {
         $query = 'UPDATE tbl_import_lines SET ' .
               'alchemis_post_id = null';

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


}

?>