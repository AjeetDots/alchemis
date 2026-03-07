<?php

require_once('batch/BatchProcess.class.php');
require_once('include/Zend/Log/Writer/Stream.php');
require_once('include/Zend/Log.php');

class batch_import_LeadLineRepairYImport extends batch_BatchProcess
{
	private $_companyData;
	private $_logger;
	private $_defaultCountryId;
	private $_defaultUserId;
	private $_projectRefId;
	private $_defaultInitiativeId;
		
	function __construct($filename) {
		$connection = self::getDbConnection();
		$config = new Zend_Config(
			array (
				'database' => array(
					'adapter' => 'Mysqli',
					'params'  => array(
						'host'     => $connection['hostname'],
						'dbname'   => $connection['database'],
						'username' => $connection['username'],
						'password' => $connection['password'],
					)
				)
			)
		);
	
		$this->db = Zend_Db::factory($config->database);
		$this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
		
		$this->init($filename);
	}
	
	protected function init($filename) {
		
		
		$stream = @fopen('/var/www/html/batch/import/LeadlineRepairlog.txt', 'a', false);
		if (! $stream) {
			throw new Exception('Failed to open log file stream');
		}
		
		$writer = new Zend_Log_Writer_Stream($stream);
		$this->_logger = new Zend_Log($writer);
		
		$this->_logger->info('Log initialised');
		
		$this->_companyData = array();
		$this->_defaultInitiativeId = 1;
		$this->_defaultUserId = 1;
		$this->_defaultCountryId = 9;
		
// 		$this->_projectRefId = $this->addProjectRefTag('Leadline Cleaned May 2012', 3);
		
		$this->importCsv($filename);
		
		$rowCount = 0;
		foreach ($this->_companyData as $row) {
// 			if ($row['row_id'] >= 405) {
				//Zend_Debug::dump($row);
				$companyId = $this->importCompany($row);
// 				$this->importSite($row, $companyId);
				$postId = $this->importPost($row, $companyId);
				$this->importContact($row, $postId);
			
				Zend_Debug::dump('Processing row: ' . $rowCount);
// 			}
			
			$rowCount++;
// 			if ($rowCount >= 1030) {
// 				exit();
// 			}	
		}
		
		echo 'Now run the following:<br />';
		echo 'update tbl_tags_seq set sequence = (select max(id) from tbl_tags);<br />';
		echo 'alter table tbl_tags_seq auto_increment = <!-- set to value of sequence field +1 -->';
		echo 'update tbl_object_characteristics_date_seq set sequence = (select max(id) from tbl_object_characteristics_date);<br />';
		echo 'alter table tbl_object_characteristics_date_seq auto_increment = <!-- set to value of sequence field +1 -->';
		echo 'update tbl_post_initiative_tags_seq set sequence = (select max(id) from tbl_post_initiative_tags);<br />';
		echo 'alter table tbl_post_initiative_tags_seq auto_increment = <!-- set to value of sequence field +1 -->';
		echo 'update tbl_post_initiative_notes_seq set sequence = (select max(id) from tbl_post_initiative_notes);<br />';
		echo 'alter table tbl_post_initiative_notes_seq auto_increment = <!-- set to value of sequence field +1 -->';
	}
	
	protected function importCsv($file)
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
	                      'company_id' 			=> $data[$index++],
	                      'company_name'        => $data[$index++],
	                      'company_website'     => $data[$index++],
	                      'company_telephone'   => $data[$index++],
						  'site_id'			    => $data[$index++],
	                      'site_address_1'      => $data[$index++],
	                      'site_address_2'      => $data[$index++],
	                      'site_town'           => $data[$index++],
	                      'site_city'           => $data[$index++],
	                      'site_postcode'       => $data[$index++],
	                      'site_county'         => $data[$index++],
	                      'site_country'        => $data[$index++],
	                      'post_id'			    => $data[$index++],
	                      'post_job_title'      => $data[$index++],
	                      'post_telephone_1'    => $data[$index++],
						  'post_telephone_2'    => $data[$index++],
						  'post_telephone_switchboard' => $data[$index++],
						  'post_telephone_fax'  => $data[$index++],
						  'contact_id'		    => $data[$index++],
	                      'contact_title'       => $data[$index++],
	                      'contact_first_name'  => $data[$index++],
	                      'contact_surname'     => $data[$index++],
	                      'contact_email'       => $data[$index++],
	                      'contact_telephone'   => $data[$index++],
	                      'project_ref'         => $data[$index++],
	                      'left_company'        => $data[$index++],
				);
	
				foreach ($pdata as &$item) {
					$item == '' ? $item = null : null;
				}
				
// 				Zend_Debug::dump($pdata, '$pdata: ');
				
				$this->_companyData[] = $pdata;
			}
	
			$row++;
		}
	
		// Close the file handle
		fclose($handle);
	
		$this->_logger->info('Completed read of ' . $file . '. Rows found: ' . $row);
		
		// Zend_Debug::dump($this->_companyData[0]);

	}
	
	protected function importCompany($companyData) 
	{
// 		$data = array(
// 			'name' 		=> $companyData['company_name'],
// 			'website' 	=> $companyData['company_website'],
// 			'telephone' => $companyData['company_telephone'],
// 		);
		
// 		if ($companyData['company_id'] == '') {
// 			// add new company and grab ID
// 			$id = $this->getNextId('tbl_companies');
// 			$data['id'] = $id;
			
// 			if ($this->db->insert('tbl_companies', $data) == 1) {
// 				$this->_logger->info('Added new companyId ' . $data['id'] . ' (rowId ' . $companyData['row_id'] . ')');
// 			} else {
// 				$this->_logger->err('Could not add new companyId ' . $data['id'] . ' (rowId ' . $companyData['row_id'] . ')');
// 			}
			
// 			$id = $data['id'];
			
// 		} else {
			// update company
			// Zend_Debug::dump($this->db->update('tbl_companies', $data, 'id = ' . $companyData['company_id']));
// 			if ($this->db->update('tbl_companies', $data, 'id = ' . $companyData['company_id']) == 1) {
// 				$this->_logger->info('Updated companyId ' . $companyData['company_id'] . ' (rowId ' . $companyData['row_id'] . ')');
// 			} else {
// 				$this->_logger->err('No changes to companyId ' . $companyData['company_id'] . ' (rowId ' . $companyData['row_id'] . ')');
// 			}
			
			$id = $companyData['company_id'];
// 		}
		
		//$this->updateCompanyCharacteristic($id, 13, 'date', '2012-05-16');
		
		return $id;
		
	}
	
	protected function updateCompanyCharacteristic ($companyId, $characteristicId, $type, $value) 
	{
		// look for existing characteristic
		$select = $this->db->select();
		$select->from(array('c' => 'tbl_object_characteristics_' . $type), array('id'));
		$select->where('company_id = ?', $companyId);
		$select->where('characteristic_id = ?', $characteristicId);
		// Zend_Debug::dump($select->__toString());
		$result = $this->db->fetchOne($select);
		
		// Zend_Debug::dump($result, 'Existing id from updateCompanyCharacteristic');
		$data = array(
			'value'	=> $value,
		);
		
		if ($result != null) {
			$this->db->update('tbl_object_characteristics_' . $type, $data, 'id = ' . $result);
			$this->_logger->info('Updated ' . $type . ' characteristicId ' . $result . ' (' . $value . ') for companyId ' . $companyId);
		} else {
			$data['company_id'] = $companyId;
			$data['characteristic_id'] = $characteristicId;
			$this->db->insert('tbl_object_characteristics_' . $type, $data);
			$id = $this->db->lastInsertId();
			$this->_logger->info('Added ' . $type . ' characteristicId ' . $id . ' (' . $value . ') for companyId ' . $companyId);
		}
	}
	
	protected function importSite($companyData, $companyId) 
	{
		if ($companyData['site_county'] != '') {
			$county_id = $this->findCounty($companyData['site_county']);
			!$county_id ? $county_id = null : null;
		} else {
			$county_id = null;
		}
		
		$data = array(
			'company_id'	=> $companyId,
			'address_1' 	=> $companyData['site_address_1'],
			'address_2' 	=> $companyData['site_address_2'],
			'town' 			=> $companyData['site_town'],
			'city' 			=> $companyData['site_city'],
			'county_id' 	=> $county_id,
			'postcode' 		=> $companyData['site_postcode'],
			'country_id' 	=> $this->_defaultCountryId,
		);
	
		if ($companyData['site_id'] == '') {
			// add new site and grab ID
			$id = $this->getNextId('tbl_sites');
			$data['id'] = $id;
				
			if ($this->db->insert('tbl_sites', $data) == 1) {
				$this->_logger->info('Added new siteId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
			} else {
				$this->_logger->err('Could not add new siteId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
			}
			
			return $data['id'];
		} else {
			// update site
// 			Zend_Debug::dump($data, '$data');
// 			Zend_Debug::dump($companyData, '$$companyData');
			if ($this->db->update('tbl_sites', $data, 'id = ' . $companyData['site_id']) == 1) {
				$this->_logger->info('Updated siteId ' . $companyData['site_id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
			} else {
				$this->_logger->err('No changes to siteId ' . $companyData['site_id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
			}
			
			return $companyData['site_id'];
		}
	}
	
	protected function importPost($companyData, $companyId) 
	{
// 		$postNotFound = false;
		$id = null;
// 		$data = array(
// 				'company_id'			=> $companyId,
// 				'job_title' 			=> $companyData['post_job_title'],
// 				'telephone_1' 			=> $companyData['post_telephone_1'],
// 				'telephone_2'   		=> $companyData['post_telephone_2'],
// 				'telephone_switchboard'	=> $companyData['post_telephone_switchboard'],
// 				'telephone_fax' 		=> $companyData['post_telephone_fax'],
// 		);
		
		
		
// 		// Need to do this check as its possible there may be a post id in the import data which no longer exists in the database - so need to reinsert it
// 		if ($companyData['post_id'] != '') {
// 			$id = $companyData['post_id'];
// 			// Zend_Debug::dump($this->getPost($companyData['post_id']), 'results of getPost');
// 			if (!$this->getPost($companyData['post_id'])) {
// 				$postNotFound = true;
// 			}
// 		} else {
// 			$postNotFound = true;
// 		}
		
		
// 		if ($postNotFound) {
			
// 			// Zend_Debug::dump($id, 'PostId: ');
			
// 			// add new post and grab ID
// 			if ($id == null) {
// 				$id = $this->getNextId('tbl_posts');
// 			}
// 			$data['id'] = $id;
	
// 			if ($this->db->insert('tbl_posts', $data) == 1) {
// 				$this->_logger->info('Added new postId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
// 			} else {
// 				$this->_logger->err('Could not add new postId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
// 			}
			
// //			$id = $data['id'];
// 		} else {
// 			// get existing data
// 			$post = $this->getPost($companyData['post_id']);
// 			$jobTitleBefore = $post['job_title'];
// 			$jobTitleAfter = $companyData['post_job_title'];
			
// 			// update post
// 			if ($this->db->update('tbl_posts', $data, 'id = ' . $companyData['post_id']) == 1) {
// 				$this->_logger->info('Updated postId ' . $companyData['post_id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
// 			} else {
// 				$this->_logger->err('No changes to postId ' . $companyData['post_id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
// 			}
			
			$id =  $companyData['post_id'];
// 		}
		
// 		$this->addPostInitiativeProjectRef($id, $this->_defaultInitiativeId);
		
// 		if (!$postNotFound) {
// 			// have to log this post initiative note AFTER we've added the post initiative project ref as that function will create the post initiative record
// 			if ($jobTitleBefore != $jobTitleAfter) {
// 				$this->addPostInitiativeNote($companyData['post_id'], 'Job title changed from ' . $jobTitleBefore . ' to ' . $jobTitleAfter . '.');
// 			}
// 		}
		
		return $id;
	}
	
	protected function importContact($companyData, $postId)
	{
		$data = array(
			'post_id'			=> $postId,
			'title' 			=> $companyData['contact_title'],
			'first_name' 		=> $companyData['contact_first_name'],
			'surname'   		=> $companyData['contact_surname'],
			'email'				=> $companyData['contact_email'],
			'telephone_mobile' 	=> $companyData['contact_telephone'],
		);
		
		if ($companyData['left_company'] == 'Y') {
			$data['deleted'] = 1;
		
		
	
 		if ($companyData['contact_id'] == '') {
// 			// add new contact and grab ID
// 			$id = $this->getNextId('tbl_contacts');
// 			$data['id'] = $id;
	
// 			if ($this->db->insert('tbl_contacts', $data) == 1) {
// 				$this->_logger->info('Added new contactId ' . $data['id'] . ' for postId ' . $postId . ' (rowId ' . $companyData['row_id'] . ')');
// 			} else {
// 				$this->_logger->err('Could not add new contactId ' . $data['id'] . ' for postId ' . $postId . ' (rowId ' . $companyData['row_id'] . ')');
// 			}
	
// 			return $data['id'];
 		} else {
			// update contact
			$contact = $this->getContact($companyData['contact_id']);
			$nameBefore = trim($contact['first_name'] . ' ' . $contact['surname']);
			
// 			if ($companyData['left_company'] == 'Y') {
				$nameAfter = 'POST VACANT';
// 			} else {
// 				$nameAfter = trim($companyData['contact_first_name'] . ' ' . $companyData['contact_surname']);
// 			}
			
			$data['full_name'] = $nameAfter;
			
			if ($this->db->update('tbl_contacts', $data, 'id = ' . $companyData['contact_id']) == 1) {
				$this->_logger->info('Updated contactId ' . $companyData['contact_id'] . ' for postId ' . $postId . ' (rowId ' . $companyData['row_id'] . ')');
			} else {
				$this->_logger->err('No changes to contactId ' . $companyData['contact_id'] . ' for postId ' . $postId . ' (rowId ' . $companyData['row_id'] . ')');
			}
	
// 			if ($companyData['left_company'] == 'Y') {
				$this->_logger->info('ContactId ' . $companyData['contact_id'] . ' for postId ' . $postId . ' has been archived. (rowId ' . $companyData['row_id'] . ')');
 //			}
				
			if ($nameBefore != $nameAfter) {
//				$this->_logger->info("Post holder changed from $nameBefore to $nameAfter.");
				$this->addPostInitiativeNote($postId, "Post holder changed from $nameBefore to $nameAfter.");
			}
			
			return $companyData['contact_id'];
 		}

		}
	}
	
	private function addProjectRefTag($value, $categoryId) {
		
		$select = $this->db->select();
		$select->from(array('t' => 'tbl_tags'), array('id'));
		$select->where('value = ?', $value);
		$select->where('category_id = ?', $categoryId);
		$result = $this->db->fetchOne($select);
		
		if ($result > 0) {
			$id = $result;	
		} else {
			$data = array(
				'value' 		=> $value,
				'category_id'	=> $categoryId
			);
			
			$this->db->insert('tbl_tags', $data);
			$id = $this->db->lastInsertId();
		}
		return $id;
	}
	
	private function addPostInitiativeProjectRef($postId, $initiativeId) 
	{
		$select = $this->db->select();
		$select->from(array('pi' => 'tbl_post_initiatives'), array('id'));
		$select->where('post_id = ?', $postId);
		$select->where('initiative_id = ?', $initiativeId);
		
		$result = $this->db->fetchOne($select);
		
		$data = array(
			'tag_id'	=> $this->_projectRefId,	
		);
		
		if ($result != null) {
			// there is an existing post initiative record
			$data['post_initiative_id'] = $result;
		} else {
			// there is no existing post initiative record so need to add one
			$postInitiativeId = $this->addPostInitiative($postId, $initiativeId, 7, 'Added as part of Leadline cleaning May 2012', 1);
			$data['post_initiative_id'] = $postInitiativeId;
		}
		
		// check if this combo of pi_id and tag_id already exists in tbl_post_initiative_tags
		$select = $this->db->select();
		$select->from(array('pi' => 'tbl_post_initiative_tags'), array('id'));
		$select->where('post_initiative_id = ?', $data['post_initiative_id']);
		$select->where('tag_id = ?', $this->_projectRefId);
		
		$result = $this->db->fetchOne($select);
		// Zend_Debug::dump($result, 'Check for existing post initiative tag:');
		if ($result == false) {
			$this->db->insert('tbl_post_initiative_tags', $data);
			$tagId = $this->db->lastInsertId();
			$this->_logger->info('Added tagId ' . $tagId . ' to postInitiativeId ' . $result);
		}
	}
	
	/**
	* Logs a new post initiative note.
	* @param integer $post_id
	* @param string $note
	*/
	protected function addPostInitiative($postId, $initiativeId, $statusId, $comment, $nextActionBy)
	{
		$id = $this->getNextId('tbl_post_initiatives');
		
		$data = array(
			'id'				=> $id,
			'post_id'			=> $postId,
			'initiative_id'		=> $initiativeId,
			'status_id'			=> $statusId,
			'comment'			=> $comment,
			'next_action_by'	=> $nextActionBy
		);
				
				
		//Zend_Debug::dump($data, 'From addPostInitiative');
		if ($this->db->insert('tbl_post_initiatives', $data) == 1) {
			$this->_logger->info('Added PostInitiativeId ' . $id . ' for postId ' . $postId . ' and initiativeId ' . $initiativeId);
		} else {
			$this->_logger->err('Could not add post initiative for postId ' . $postId . ' and initiativeId ' . $initiativeId);
		}
		
		return $id;
	}
	
	private function findCounty($county) {
		$select = $this->db->select();
		$select->from(array('c' => 'tbl_lkp_counties'), array('id'));
		$select->where('name = ?', $county);
		return $this->db->fetchOne($select);
	}
	
	/**
	* Logs a new post initiative note.
	* @param integer $post_id
	* @param string $note
	*/
	protected function addPostInitiativeNote($postId, $note)
	{
		$select = $this->db->select();
		$select->from(array('pi' => 'tbl_post_initiatives'), array('id'));
		$select->where('post_id = ?', $postId);
		
		$results = $this->db->fetchAll($select);
		
		foreach ($results as $result)
		{
			$data = array(
				'post_initiative_id'	=> $result['id'],
				'created_at'			=> date('Y-m-d H:i:s'),
				'created_by'			=> $this->_defaultUserId,
				'note'					=> $note
			);
			
			if ($this->db->insert('tbl_post_initiative_notes', $data) == 1) {
				$id = $this->db->lastInsertId();
				$this->_logger->info('Added post initiative note ' . $id . ' for postId ' . $postId);
			} else {
				$this->_logger->err('Could not add post initiative note for postId ' . $postId);
			}
		}
	}
	
	function getPost($postId)
	{
		$select = $this->db->select();
		$select->from(array('p' => 'tbl_posts'));
		$select->where('id = ?', $postId);
		
		$results = $this->db->fetchRow($select);
		// Zend_Debug::dump($results);
		return $results;
		
	} 

	function getContact($contactId)
	{
		$select = $this->db->select();
		$select->from(array('c' => 'tbl_contacts'));
		$select->where('id = ?', $contactId);
		return $this->db->fetchRow($select);
	}
	
}

?>