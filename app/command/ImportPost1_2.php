<?php

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/Site.php');
require_once('app/domain/ImportCompany.php');

require_once('include/EasySql/EasySql.class.php');

// Ensure the maximum execution time is at least 1200 seconds (20 minutes);
set_time_limit(0);
ini_set('memory_limit', '1280M');
/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportPost1_2 extends app_command_ManipulationCommand
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
		$this->setUpPostDedupeData();
		$this->setUpAlchemisPostDedupeData();
	    $this->findPossibleAlchemisPostMasterRecords();
        $request->setObject('post_selection', $this->showPostsForUserSelection());
	}

    private function setUpPostDedupeData()
    {

    	$post_dedupe_contact_first_name = '';
    	$post_dedupe_contact_surname = '';

    	$post_patterns = array();

    	$post_patterns[0] = '/\W/i';

        $data = app_domain_ImportPost::doFindRawPosts();

        foreach($data as $row)
        {
        	$post_dedupe_contact_first_name = str_replace(' ', '', strtolower(preg_replace($post_patterns, '', $row[6])));

        	$post_dedupe_contact_first_name = substr($post_dedupe_contact_first_name,0,round((strlen($post_dedupe_contact_first_name)/(100/50)), 0));
        	$post_dedupe_contact_surname = str_replace(' ', '', strtolower(preg_replace($post_patterns, '', $row[7])));
            $post_dedupe_contact_surname = substr($post_dedupe_contact_surname,0,round((strlen($post_dedupe_contact_surname)/(100/50)), 0));

            $data = array(
                        'id'                                => $row[0],
                        'post_dedupe_contact_first_name'    => $post_dedupe_contact_first_name,
                        'post_dedupe_contact_surname'       => $post_dedupe_contact_surname,
            );

            app_domain_ImportPost::insertPostDedupeData($data);

             // reset variables for next loop
            $post_dedupe_contact_first_name = '';
            $post_dedupe_contact_surname = '';

        }

        return true;
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

    private function setUpAlchemisPostDedupeData()
    {

        $post_dedupe_contact_first_name = '';
        $post_dedupe_contact_surname = '';

        $post_patterns = array();

        $post_patterns[0] = '/\W/i';

        $records = app_domain_ImportPost::doFindCountAlchemisPosts();
        $block = 10000;

        $count = floor(($records/$block)) + 1 . '<br />';
        $index = 0;

        $connection = self::getDbConnection();
        
//         define('DB_HOST',     'localhost');
//         define('DB_NAME',     'alchemis');
//         define('DB_USER',     'alchemis');
//         define('DB_PASSWORD', 'rYT4maP7');

        $db = new EasySql($connection['username'], $connection['password'], $connection['database'], $connection['hostname']);
//         $db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
        $db->debug_all = false;

        for ($x=0; $x<=$records; $x = $x + $block)
        {
             $data = app_domain_ImportPost::doFindAlchemisPosts($x, $x + $block);

	        foreach($data as $row)
	        {
	            $post_dedupe_contact_first_name = str_replace(' ', '', strtolower(preg_replace($post_patterns, '', $row[1])));

	            $post_dedupe_contact_first_name = substr($post_dedupe_contact_first_name,0,round((strlen($post_dedupe_contact_first_name)/(100/50)), 0));

	            $post_dedupe_contact_surname = str_replace(' ', '', strtolower(preg_replace($post_patterns, '', $row[2])));

	            $post_dedupe_contact_surname = substr($post_dedupe_contact_surname,0,round((strlen($post_dedupe_contact_surname)/(100/50)), 0));

	            $data = array(
	                        'id'                               => $row[0],
	                        'post_dedupe_contact_first_name'    => $post_dedupe_contact_first_name,
	                        'post_dedupe_contact_surname'       => $post_dedupe_contact_surname,
	            );

		         $query = 'UPDATE tbl_contacts con JOIN tbl_posts p ON p.id = con.post_id SET ' .
		               "con.dedupe_contact_first_name = '" . $data['post_dedupe_contact_first_name'] . "', " .
		               "con.dedupe_contact_surname = '" . $data['post_dedupe_contact_surname'] . "' " .
		               "where con.post_id = " . $data['id'] . " " .
		               'AND p.deleted = 0';
		        $db->query($query);


	            // reset variables for next loop
	            $post_dedupe_contact_first_name = '';
	            $post_dedupe_contact_surname = '';
	        }

	        unset($data);

        }

        return true;
    }


    private function findPossibleAlchemisPostMasterRecords()
    {

        $data = app_domain_ImportPost::doFindRawPostDedupeData();
        $masters = array();

//         echo '<pre>';
//         print_r($data);
//         echo '</pre>';
//         die();
        foreach($data as $row)
        {
        	$temp = app_domain_ImportPost::doFindProbableAlchemisPostMasterRecords($row);
        	foreach ($temp as $temp_row)
        	{
                    $masters[] =  $row[0] . ':' . $temp_row . ':probable';
                    //echo $row[0] . ':' . $temp_row . ':probable <br />';
        	}
        }

        app_domain_ImportPost::insertPostMatchIds($masters);

        return true;
    }


    private function showPostsForUserSelection()
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

        $data = app_domain_ImportPost::doFindAllFromTblImportPostMatches();

        foreach($data as $row)
        {
            $current_import_row_id = $row['alchemis_company_id'];

        	$import_company_data = app_domain_ImportPost::getImportPostRecordsByAlchemisCompanyId($row['alchemis_company_id']);

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

            // -------- Contact info
            $import_post_data = app_domain_ImportPost::getImportPostRecordsById($row['import_id']);
            $import_post_name       = $import_post_data[0][19] . ' ' . $import_post_data[0][20];
            $import_post_job_title   = $import_post_data[0][16];
            $import_post_telephone   = $import_post_data[0][17];
            $import_post_email       = $import_post_data[0][21];

            // get contact info for possible matches
            $alchemis_post_data = app_domain_ImportPost::selectAlchemisPostById($row['alchemis_post_id']);

            $alchemis_post_name = $alchemis_post_data[0][11];
            $alchemis_post_job_title = $alchemis_post_data[0][2];
            $alchemis_post_telephone   = $alchemis_post_data[0][4];
            $alchemis_post_email       = $alchemis_post_data[0][13];



            // build final output array
            // if the import_row_id is the same as the previous then we place a null value for the
            // import_row_id and only populate the possible alchemis company match. This is so that we
            // have an easy to use array in the view

            if ($current_alchemis_company_id != $previous_alchemis_company_id)
            {
				$output_array[] = array(
            		'import_id'         => $row['import_id'],
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
            		'alchemis_post_id'   => $row['alchemis_post_id'],
            		'alchemis_post_name'    => $alchemis_post_name,
            		'alchemis_post_job_title'     => $alchemis_post_job_title,
            		'alchemis_post_telephone'     => $alchemis_post_telephone,
            		'alchemis_post_email'         => $alchemis_post_email,
            		'import_post_name'      => $import_post_name,
            		'import_post_job_title'     => $import_post_job_title,
            		'import_post_telephone'     => $import_post_telephone,
                    'import_post_email'         => $import_post_email,
                );
            }
            else
            {
				$output_array[] = array(
                   	'import_id'         => $row['import_id'],
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
                    'alchemis_post_id'   => $row['alchemis_post_id'],
                    'alchemis_post_name'    => $alchemis_post_name,
                    'alchemis_post_job_title'     => $alchemis_post_job_title,
                    'alchemis_post_telephone'     => $alchemis_post_telephone,
                    'alchemis_post_email'         => $alchemis_post_email,
                    'import_post_name'      => $import_post_name,
                    'import_post_job_title'     => $import_post_job_title,
                    'import_post_telephone'     => $import_post_telephone,
                    'import_post_email'         => $import_post_email,
                );
            }

            $previous_alchemis_company_id = $current_alchemis_company_id;
        }

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
      app_domain_ImportPost::resetAllAlchemisPostIds();


      foreach ($properties as $key => $item)
      {

          	$temp = strpos($key, 'chk_');


          if ($temp !== false)
          {
              $import_id = trim(substr($key,4));
              $alchemis_post_id = $item;

              if (is_numeric($import_id) && is_numeric($alchemis_post_id))
              {
                  // Update tbl_import_lines with selected alchemis_company_id
                  app_domain_ImportPost::updateAlchemisPostIds($import_id, $alchemis_post_id);
              }
          }
      }
      
      return true;
    }

}
?>
