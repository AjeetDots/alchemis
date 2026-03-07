<?php

require_once('batch/BatchProcess.class.php');
require_once('include/Zend/Log/Writer/Stream.php');
require_once('include/Zend/Log.php');

class batch_import_StandardImport extends batch_BatchProcess
{
	private $_doImportCompanies;
	private $_doImportPosts;
	private $_companyData;
	private $_logger;
	private $_defaultCountryId;
	private $_defaultUserId;
	private $_defaultNextActionBy;
	private $_defaultStatusId;
	private $_companyIdsAdded;
	private $_commitTransactions;
	private $_processed;
	private $_additions;
	private $_failures;
	private $_duplicates;
	private $_existing;
		
	function __construct($filename, $withRollback, $doImportCompanies = true, $doImportPosts = true) {
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
		
		$this->_doImportCompanies = $doImportCompanies;
		$this->_doImportPosts = $doImportPosts;
		
		// set following line to commit queries for each line. Set false to rollback after each line
		$this->_commitTransactions = !$withRollback;
		
		$this->init($filename);
	}
	
	public function getResult() 
	{
		return array(
			'processed' => $this->_processed,
			'additions' => $this->_additions,
			'duplicates' => $this->_duplicates,
			'existing' => $this->_existing,
			'failures' 	=> $this->_failures,
		);
	}
	
	protected function init($filename) {
		
		
		if (!file_exists($filename)) { 
			touch($filename);	
		} 
		
		if (file_exists($filename)) { 
			$stream = @fopen($filename, 'a', false);	
		} else {
			throw new Exception('Failed to open log file stream');
		}
		
// 		echo ($filename);
// 		die();
		
		$writer = new Zend_Log_Writer_Stream($stream);
		$this->_logger = new Zend_Log($writer);
		
		$this->_logger->info('Log initialised');
		
		
		
		
		
		$this->_companyData = array();
		$this->_defaultStatusId = 7;
		$this->_defaultNextActionBy = 1;
		$this->_defaultUserId = 1;
		$this->_defaultCountryId = 9;
		$this->_companyIdsAdded = array();
		$this->_processed = array(
			'companies' => 0,
			'posts' 	=> 0,
		);
		
		$this->_additions = array(
			'companies' => 0,
			'posts' 	=> 0,
		);
		
		$this->_duplicates = array(
			'companies' => 0,
			'posts' 	=> 0,
		);
		
		$this->_existing = array(
			'companies' => 0,
			'posts' 	=> 0,
		);
		
		$this->_failures = array(
			'companies' => 0,
			'posts' 	=> 0,
		);
		
		$this->_companyData = $this->getImportData();
		
		$rowCount = 1;
		$this->db->beginTransaction();
		$this->_logger->info('Started transaction');
		
		foreach ($this->_companyData as $row) {
// 			if ($rowCount <= 10) {
				Zend_Debug::dump('Processing row: ' . $rowCount);
				
				if ($this->_doImportCompanies) {
					// company stuff
					$companyId = $this->importCompany($row);
					$this->_processed['companies']++;
					
					$this->importCompanyTieredCharacteristic($companyId, $row['sub_category_id']);
					$this->importCompanyTieredCharacteristic($companyId, $row['sub_category_1_id']);
					
					$this->addTag($row['brand'], 1, 'company', $companyId); 
					$this->addTag($row['company_tag'], 2, 'company', $companyId);
				}
				
				if ($this->_doImportPosts) {
					$postId = $this->importPost($row, $companyId);
					$this->_processed['posts']++;
					
					$this->_logger->info('Completed importPost ' . $postId . ' (rowId ' . $row['row_id'] . ')');
					$this->addTag($row['post_tag'], 2, 'post', $postId);
					$this->_logger->info('Completed adding tag ' . $row['post_tag'] . ' for postId ' . $postId . ' (rowId ' . $row['row_id'] . ')');
								
					$postInitiativeId = $this->importPostInitiative($row, $postId, $this->_defaultStatusId, $this->_defaultNextActionBy);
				}
// 			}
			$rowCount++;
		}
		
		if ($this->_commitTransactions) {
			$this->db->commit();
			$this->_logger->info('Committed transactions');
		} else {
			$this->db->rollBack();
			$this->_logger->info('Rolled back transactions');
		}
		
		if ($this->_commitTransactions) {
			echo 'Updating sequence values:<br />';
				
			$sql = 'update tbl_tags_seq set sequence = (select max(id) from tbl_tags)';
			$this->db->query($sql);
			echo $sql . ';<br />';
			$sql = 'select max(id) from tbl_tags';
			$maxValue = $this->db->fetchOne($sql);
			$maxValue++;
			$sql = 'alter table tbl_tags_seq auto_increment = ' . $maxValue;
			echo $sql . ';<br />';
			$this->db->query($sql);
				
			$sql = 'update tbl_company_tags_seq set sequence = (select max(id) from tbl_company_tags)';
			$this->db->query($sql);
			echo $sql . ';<br />';
			$sql = 'select max(id) from tbl_company_tags';
			$maxValue = $this->db->fetchOne($sql);
			$maxValue++;
			$sql = 'alter table tbl_company_tags_seq auto_increment = ' . $maxValue;
			echo $sql . ';<br />';
			$this->db->query($sql);
				
			$sql = 'update tbl_post_initiative_tags_seq set sequence = (select max(id) from tbl_post_initiative_tags)';
			$this->db->query($sql);
			echo $sql . ';<br />';
			$sql = 'select max(id) from tbl_post_initiative_tags';
			$maxValue = $this->db->fetchOne($sql);
			$maxValue++;
			$sql = 'alter table tbl_post_initiative_tags_seq auto_increment = ' . $maxValue;
			echo $sql . ';<br />';
			$this->db->query($sql);
				
			$sql = 'update tbl_post_initiative_notes_seq set sequence = (select max(id) from tbl_post_initiative_notes)';
			$this->db->query($sql);
			echo $sql . ';<br />';
			$sql = 'select max(id) from tbl_post_initiative_notes';
			$maxValue = $this->db->fetchOne($sql);
			$maxValue++;
			$sql = 'alter table tbl_post_initiative_notes_seq auto_increment = ' . $maxValue;
			echo $sql . ';<br />';
			$this->db->query($sql);
			
			$sql = 'update tbl_object_tiered_characteristics_seq set sequence = (select max(id) from tbl_object_tiered_characteristics)';
			$this->db->query($sql);
			echo $sql . ';<br />';
			$sql = 'select max(id) from tbl_object_tiered_characteristics';
			$maxValue = $this->db->fetchOne($sql);
			$maxValue++;
			$sql = 'alter table tbl_object_tiered_characteristics_seq auto_increment = ' . $maxValue;
			echo $sql . ';<br />';
			$this->db->query($sql);
		}
		
		//echo 'COMPLETED IMPORT';
		
// 		echo 'Now run the following:<br />';
// 		echo 'update tbl_tags_seq set sequence = (select max(id) from tbl_tags);<br />';
// 		echo 'alter table tbl_tags_seq auto_increment = <!-- set to value of sequence field +1 -->';
// 		echo 'update tbl_object_characteristics_date_seq set sequence = (select max(id) from tbl_object_characteristics_date);<br />';
// 		echo 'alter table tbl_object_characteristics_date_seq auto_increment = <!-- set to value of sequence field +1 -->';
// 		echo 'update tbl_post_initiative_tags_seq set sequence = (select max(id) from tbl_post_initiative_tags);<br />';
// 		echo 'alter table tbl_post_initiative_tags_seq auto_increment = <!-- set to value of sequence field +1 -->';
// 		echo 'update tbl_post_initiative_notes_seq set sequence = (select max(id) from tbl_post_initiative_notes);<br />';
// 		echo 'alter table tbl_post_initiative_notes_seq auto_increment = <!-- set to value of sequence field +1 -->';
// 		echo 'update tbl_object_tiered_characteristics_seq set sequence = (select max(id) from tbl_object_tiered_characteristics);<br />';
// 		echo 'alter table tbl_object_tiered_characteristics_seq auto_increment = <!-- set to value of sequence field +1 -->';
	}
	
	protected function importCompany($companyData) 
	{
		$data = array(
			'name' 		=> $companyData['company_name'],
			'website' 	=> $companyData['company_website'],
			'telephone' => $companyData['company_telephone'],
		);
		
		if ($companyData['alchemis_company_id'] != '') {
			// if alchemis_company_id is not empty then don't create a company.
			$this->_logger->info('Found existing company_id ' . $companyData['alchemis_company_id'] . ' (rowId ' . $companyData['row_id'] . ')');
			$id = $companyData['alchemis_company_id'];
			$this->_existing['companies']++;
		} elseif (array_key_exists($companyData['row_id'], $this->_companyIdsAdded)) {
			// we've already added this company as a new record so look it up)
			$this->_logger->info('Found newly added company_id ' . $this->_companyIdsAdded[$companyData['row_id']] . ' (rowId ' . $companyData['row_id'] . ')');
			$id = $this->_companyIdsAdded[$companyData['row_id']];
			$this->_duplicates['companies']++;
		} elseif (!array_key_exists($companyData['row_id'], $this->_companyIdsAdded)) { 
			// add new company and grab ID
			$id = $this->getNextId('tbl_companies');
			$data['id'] = $id;
				
			if ($this->db->insert('tbl_companies', $data) == 1) {
				$this->_logger->info('Added new companyId ' . $data['id'] . ' (rowId ' . $companyData['row_id'] . ')');
				$this->_companyIdsAdded[$companyData['row_id']] = $id;
				$this->_additions['companies']++;
			} else {
				$this->_logger->err('Could not add new companyId ' . $data['id'] . ' (rowId ' . $companyData['row_id'] . ')');
				$this->_failures['companies']++;
			}	
			$this->importSite($companyData, $id);
		} else {
			$this->_logger->err('ERROR: Exiting with message: No valid company id found for existing company ' . $company_name . ' - import row ' . $companyData['row_id']);
			throw new exception('No valid company id found for existing company ' . $company_name . ' - import row ' . $companyData['row_id']);
		}
		
		return $id;
		
	}
	
	protected function importSite($companyData, $companyId) 
	{
		$data = array(
			'company_id'	=> $companyId,
			'address_1' 	=> $companyData['site_address_1'],
			'address_2' 	=> $companyData['site_address_2'],
			'town' 			=> $companyData['site_town'],
			'city' 			=> $companyData['site_city'],
			'county_id' 	=> $companyData['site_county_id'],
			'postcode' 		=> $companyData['site_postcode'],
			'country_id' 	=> $companyData['site_country_id']
		);
	
		if ($companyData['site_county_id'] == 0) {
			$data['county_id'] = null;
		}
		
		if ($companyData['site_country_id'] == 0) {
			$data['country_id'] = null;
		}

		$id = $this->getNextId('tbl_sites');
		$data['id'] = $id;
		
		if ($this->db->insert('tbl_sites', $data) == 1) {
			$this->_logger->info('Added new siteId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
		} else {
			$this->_logger->err('Could not add new siteId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
		}
			
		return $data['id'];
	}
	
	protected function importPost($companyData, $companyId) 
	{
		$postNotFound = false;
		$id = null;
		$data = array(
				'company_id'			=> $companyId,
				'job_title' 			=> $companyData['post_job_title'],
				'telephone_1' 			=> $companyData['post_telephone'],
		);
		
		$postId = $this->getPostIdByContactNameAndCompanyId($companyData['contact_first_name'], $companyData['contact_surname'], $companyId);
		
		// if alchemis_post_id is not empty then don't create a post
		if ($companyData['alchemis_post_id'] != '') {
			$id = $companyData['alchemis_post_id'];
			$this->_additions['existing']++;
		} elseif (!is_null($postId) && $postId > 0) { 
			//if we already have a contact with this name at this company_id then don't insert a post - just get the id
			$id = $postId;
			$this->_existing['existing']++;
		} elseif (!is_null($postId) && $postId > 0) {
			$this->_logger->err('ERROR: Existing with message: Could not find postId for companyId ' . $companyId .' and contact ' .  $companyData['contact_first_name'] . ' ' . $companyData['contact_surname'] . '(rowId ' . $companyData['row_id'] . ')');
			throw new exception('ERROR: Existing with message: Could not find postId for companyId ' . $companyId .' and contact ' .  $companyData['contact_first_name'] . ' ' . $companyData['contact_surname'] . '(rowId ' . $companyData['row_id'] . ')');
		} else {
			// add new post
			$id = $this->getNextId('tbl_posts');
			$data['id'] = $id;
			
			
			if ($this->db->insert('tbl_posts', $data) == 1) {
				$this->_logger->info('Added new postId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
				$this->_additions['posts']++;
			} else {
				$this->_logger->err('Could not add new postId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
				$this->_failures['posts']++;
			}
			
			$this->importContact($companyData, $id);
		}			
		
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
		);
		
		// add new contact and grab ID
		$id = $this->getNextId('tbl_contacts');
		$data['id'] = $id;

		if ($this->db->insert('tbl_contacts', $data) == 1) {
			$this->_logger->info('Added new contactId ' . $data['id'] . ' for postId ' . $postId . ' (rowId ' . $companyData['row_id'] . ')');
		} else {
			$this->_logger->err('Could not add new contactId ' . $data['id'] . ' for postId ' . $postId . ' (rowId ' . $companyData['row_id'] . ')');
		}

		return $data['id'];
	}
	
	
	
	/**
	* Logs a new post initiative note.
	* @param integer $post_id
	* @param string $note
	*/
	protected function importPostInitiative($companyData, $postId, $statusId, $nextActionBy)
	{
		if ($companyData['client_initiative_id'] != '' && $companyData['client_initiative_id'] > 0) {
		
			$postInitiativeId = $this->getPostInitiativeIdByPostIdAndInitiativeId($postId, $companyData['client_initiative_id']);
// 			Zend_Debug::dump($postInitiativeId);
//			exit();
			if ($postInitiativeId) {
				$this->_logger->info('Found PostInitiativeId ' . $postInitiativeId . ' for postId ' . $postId . ' and initiativeId ' . $companyData['client_initiative_id']);
				$id = $postInitiativeId;
			} else {
				$id = $this->getNextId('tbl_post_initiatives');
					
				$data = array(
					'id'				=> $id,
					'post_id'			=> $postId,
					'initiative_id'		=> $companyData['client_initiative_id'],
					'status_id'			=> $statusId,
					'next_action_by'	=> $nextActionBy
				);
					
					
				//Zend_Debug::dump($data, 'From addPostInitiative');
				if ($this->db->insert('tbl_post_initiatives', $data) == 1) {
					$this->_logger->info('Added PostInitiativeId ' . $id . ' for postId ' . $postId . ' and initiativeId ' . $initiativeId);
				} else {
					$this->_logger->err('Could not add post initiative for postId ' . $postId . ' and initiativeId ' . $initiativeId);
				}	
			}
			
			// add project ref
			$this->addTag($companyData['project_ref'], 3, 'postInitiative', $id);
			
			// add post initiative note
			if ($companyData['client_note'] != null) {
				$this->addPostInitiativeNote($id, $companyData['client_note']);
			}		
		}
	
		return $id;
	}
	
	/**
	* Logs a new post initiative note.
	* @param integer $post_id
	* @param string $note
	*/
	protected function addPostInitiativeNote($postInitiativeId, $note)
	{
		
		$data = array(
			'post_initiative_id'	=> $postInitiativeId,
			'created_at'			=> date('Y-m-d H:i:s'),
			'created_by'			=> $this->_defaultUserId,
			'note'					=> $note
		);
		
		if ($this->db->insert('tbl_post_initiative_notes', $data) == 1) {
			$id = $this->db->lastInsertId();
			$this->_logger->info('Added post initiative note ' . $id . ' for postId ' . $postInitiativeId);
		} else {
			$this->_logger->err('Could not add post initiative note for postId ' . $postInitiativeId);
		}
	}
	
	function getImportData() 
	{
		$select = $this->db->select();
		$select->from(array('il' => 'tbl_import_lines'));
		$select->order('id');
		
		return $this->db->fetchAll($select);
	}
	
	function importCompanyTieredCharacteristic($companyId, $subCategoryId) {
		// insert company category/sub-cat
		if ($subCategoryId != '' && $subCategoryId > 0)
		{
			// find parent of sub_category
			$select = $this->db->select();
			$select->from('tbl_tiered_characteristics', array('parent_id'));
			$select->where('id = ?', $subCategoryId);
			$parent_id = $this->db->fetchOne($select);
			
			if (is_null($parent_id)) {
				$this->_logger->info('Could not find parent id for specified sub_cat id of ' . $subCategoryId . '. (rowId ' . $companyData['row_id'] . ')');
				return -1;
			}

			// is this parent tiered characteristic associated with the company_id
			$select = $this->db->select();
			$select->from('tbl_object_tiered_characteristics', array('count(id)'));
			$select->where('company_id = ?', $companyId);
			$select->where('tiered_characteristic_id = ?', $parent_id);
			$company_parent_id = $this->db->fetchOne($select);
			
			if (is_null($company_parent_id)) {
				// Parent category needs to be associated first
				$data = array(
					'tiered_characteristic_id' 	=> $parent_id,
					'tier'						=> 0,
					'company_id'				=> $companyId
				);
				$id = $this->db->insert('tbl_object_tiered_characteristics', $data);
				$this->_logger->info('Add new company tiered characteristic id ' . $id . ' for companyId: ' . $companyId . 'and tiered_characteristic_id: ' . $parent_id);
			} else {
				$this->_logger->info('Found existing company tiered characteristic id ' . $id . ' for companyId: ' . $companyId . 'and tiered_characteristic_id: ' . $parent_id);
			}

			// is this sub cat tiered characteristic associated with the company_id
			$select = $this->db->select();
			$select->from('tbl_object_tiered_characteristics', array('count(id)'));
			$select->where('company_id = ?', $companyId);
			$select->where('tiered_characteristic_id = ?', $subCategoryId);
			$company_parent_id = $this->db->fetchOne($select);
			
			if (is_null($company_parent_id)) {
				$data = array(
					'tiered_characteristic_id' 	=> $subCategoryId,
					'tier'						=> 3,
					'company_id'				=> $companyId
				);
				
				$this->db->insert('tbl_object_tiered_characteristics', $data);
				$this->_logger->info('Add new company sub-cat tiered characteristic id ' . $id . ' for companyId: ' . $companyId . 'and tiered_characteristic_id: ' . $subCategoryId);
			} else {
				$this->_logger->info('Found existing company sub-cat tiered characteristic id ' . $id . ' for companyId: ' . $companyId . 'and tiered_characteristic_id: ' . $parent_id);
			}
		}
	}
	
	function addTag($value, $categoryId, $objectType, $objectId) 
	{
		if ($value == '' || is_null($value)) {
			return 0;
		} else {
		
			$select = $this->db->select();
			$select->from(array('t' => 'tbl_tags'), array('id'));
			$select->where('value = ?', $value);
			$select->where('category_id = ?', $categoryId);
			$result = $this->db->fetchOne($select);
		
			if ($result > 0) {
				$tagId = $result;
				$this->_logger->info('Found existing tag id ' . $tagId . ' for value: ' . $value);
			} else {
				$data = array(
						'value' 		=> $value,
						'category_id'	=> $categoryId
				);
					
				$this->db->insert('tbl_tags', $data);
				$tagId = $this->db->lastInsertId();
				$this->_logger->info('Added new tag id ' . $tagId . ' for value: ' . $value);
			}
			
			$data = array(
				'tag_id' => $tagId
			);
			
			switch ($objectType) {
				case 'company':
					$table = 'tbl_company_tags';
					$parentField = 'company_id';
					$data['company_id'] = $objectId;
					break;
				case 'post':
					$table = 'tbl_post_tags';
					$parentField = 'post_id';
					$data['post_id'] = $objectId;
					break;
				case 'postInitiative':
					$table = 'tbl_post_initiative_tags';
					$parentField = 'post_initiative_id';
					$data['post_initiative_id'] = $objectId;
					break;
			}
	
			// is this tag already associated with parent object?
			$select = $this->db->select();
			$select->from($table, array('id'));
			$select->where('tag_id = ?', $tagId);
			$select->where($parentField . ' = ?', $objectId);
			
			$parentTagId = $this->db->fetchOne($select);
			Zend_Debug::dump($parentTagId);
			
			if ($parentTagId <= 0)
			{
				$this->db->insert($table, $data);
				$id = $this->db->lastInsertId();
				
				$this->_logger->info('Added new ' . $objectType . ' tag id ' . $id . ' for tag_id: ' . $tagId);
			} else {
				$this->_logger->info('Found existing ' . $objectType . ' tag id ' . $parentTagId . ' for tag_id: ' . $tagId);
			}
		}
	}
	
	function getPostIdByContactNameAndCompanyId($first_name, $surname, $company_id) 
	{
		$select = $this->db->select();
		$select->from(array('t' => 'vw_posts_contacts'), array('id'));
		$select->where('first_name = ?', $first_name);
		$select->where('surname = ?', $surname);
		$select->where('company_id = ?', $company_id);
		
		return $this->db->fetchOne($select);
	}
	
	function getPostInitiativeIdByPostIdAndInitiativeId($postId, $initiativeId)
	{
		$select = $this->db->select();
		$select->from(array('t' => 'tbl_post_initiatives'), array('id'));
		$select->where('post_id = ?', $postId);
		$select->where('initiative_id = ?', $initiativeId);
	
//		Zend_Debug::dump($select->__toString());

	
		return $this->db->fetchOne($select);
	}
	
}

?>