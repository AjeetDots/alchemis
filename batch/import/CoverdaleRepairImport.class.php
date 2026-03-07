<?php

require_once('batch/BatchProcess.class.php');
require_once('include/Zend/Log/Writer/Stream.php');
require_once('include/Zend/Log.php');

class batch_import_CoverdaleRepairImport extends batch_BatchProcess
{
	private $_companyData;
	private $_logger;
	private $_defaultCountryId;
	private $_defaultUserId;
	private $_defaultNextActionBy;
	private $_defaultStatusId;
	private $_companyIdsAdded;
		
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
		
		if (!file_exists($filename)) { 
			touch($filename);	
		} 
		
		if (file_exists($filename)) { 
			$stream = @fopen($filename, 'a', false);	
		} else {
			throw new Exception('Failed to open log file stream');
		}
		
		$writer = new Zend_Log_Writer_Stream($stream);
		$this->_logger = new Zend_Log($writer);
		
		$this->_logger->info('Log initialised');
		
		$this->_companyData = array();
		$this->_defaultStatusId = 7;
		$this->_defaultNextActionBy = 1;
		$this->_defaultUserId = 1;
		$this->_defaultCountryId = 9;
		
		$this->_companyData = $this->getAllImportData();
		
		$this->preLineCheck();
// 		exit();
		unset($this->_companyData);
		
		$this->_companyData = $this->getAllImportData();
		
		$rowCount = 0;
		$include_count = 0;
		$exclude_count = 0;
		foreach ($this->_companyData as $row) {
			if ($row['include_in_import'] == 1){
				$include_count ++;
			}
			if ($row['include_in_import'] == 0){
				$exclude_count ++;
			}
			$rowCount++;
		}
		
		Zend_Debug::dump($rowCount, '$rowCount');
		Zend_Debug::dump($include_count, '$include_count');
		Zend_Debug::dump($exclude_count, '$exclude_count');
		
// 		exit();
		
		$rowCount = 0;
		foreach ($this->_companyData as $row) {
			
			$rowCount++;
			Zend_Debug::dump('Processing row: ' . $rowCount);
			
			// company stuff
			$companyId = $this->importCompany($row);
			
			$this->importCompanyTieredCharacteristic($companyId, $row['sub_category_id']);
			$this->importCompanyTieredCharacteristic($companyId, $row['sub_category_1_id']);
			
			$this->addTag($row['brand'], 1, 'company', $companyId); 
			$this->addTag($row['company_tag'], 2, 'company', $companyId);
			
			$postId = $this->importPost($row, $companyId);
			if ($postId == 0) {
				$this->_logger->err('Could not completed importPost ' . $postId . ' (rowId ' . $row['row_id'] . ')');
				continue;
			} else {
				$this->_logger->info('Completed importPost ' . $postId . ' (rowId ' . $row['row_id'] . ')');
			}
			
			$this->addTag($row['post_tag'], 2, 'post', $postId);
			$this->_logger->info('Completed adding tag ' . $row['post_tag'] . ' for postId ' . $postId . ' (rowId ' . $row['row_id'] . ')');
						
			$postInitiativeId = $this->importPostInitiative($row, $postId, $this->_defaultStatusId, $this->_defaultNextActionBy);
			
			
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
		echo 'update tbl_object_tiered_characteristics_seq set sequence = (select max(id) from tbl_object_tiered_characteristics);<br />';
		echo 'alter table tbl_object_tiered_characteristics_seq auto_increment = <!-- set to value of sequence field +1 -->';
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
		} elseif (array_key_exists($companyData['row_id'], $this->_companyIdsAdded)) {
			// we've already added this company as a new record so look it up)
			$this->_logger->info('Found newly added company_id ' . $this->_companyIdsAdded[$companyData['row_id']] . ' (rowId ' . $companyData['row_id'] . ')');
			$id = $this->_companyIdsAdded[$companyData['row_id']];
		} elseif (!array_key_exists($companyData['row_id'], $this->_companyIdsAdded)) { 
			// add new company and grab ID
			$id = $this->getNextId('tbl_companies');
			$data['id'] = $id;
				
			if ($this->db->insert('tbl_companies', $data) == 1) {
				$this->_companyIdsAdded[$companyData['row_id']] = $id;
				$this->_logger->info('Added new companyId ' . $data['id'] . ' (rowId ' . $companyData['row_id'] . ')');
			} else {
				$this->_logger->err('Could not add new companyId ' . $data['id'] . ' (rowId ' . $companyData['row_id'] . ')');
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
		if (is_null($companyData['site_county'])) {
			$county_id = null;
		} elseif ($companyData['site_county_id'] == 0) {
			$county_id = null;
		}
		
		if (is_null($companyData['site_country'])) {
			$country_id = $this->_defaultCountryId;
		} elseif ($companyData['site_country_id'] == 0) {
			$country_id = $this->_defaultCountryId;
		}
		
		$data = array(
			'company_id'	=> $companyId,
			'address_1' 	=> $companyData['site_address_1'],
			'address_2' 	=> $companyData['site_address_2'],
			'town' 			=> $companyData['site_town'],
			'city' 			=> $companyData['site_city'],
			'county_id' 	=> $county_id,
			'postcode' 		=> $companyData['site_postcode'],
			'country_id' 	=> $country_id,
		);
	
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
		
		if (is_null($companyData['contact_first_name']) || is_null($companyData['contact_surname'])) {
			$this->_logger->err('ERROR: Exiting with message: Could not find postId for null contact or surname (rowId ' . $companyData['row_id'] . ')');
			return 0;
		} else {
			$postId = $this->getPostIdByContactNameAndCompanyId($companyData['contact_first_name'], $companyData['contact_surname'], $companyId);
		}
		
		// if alchemis_post_id is not empty then don't create a post
		if ($companyData['alchemis_post_id'] != '') {
			$id = $companyData['alchemis_post_id'];
		} elseif (!is_null($postId) && $postId > 0) { 
			//if we already have a contact with this name at this company_id then don't insert a post - just get the id
			$id = $postId;
		} elseif (!is_null($postId) && $postId > 0) {
			$this->_logger->err('ERROR: Exiting with message: Could not find postId for companyId ' . $companyId .' and contact ' .  $companyData['contact_first_name'] . ' ' . $companyData['contact_surname'] . '(rowId ' . $companyData['row_id'] . ')');
			throw new exception('ERROR: Exiting with message: Could not find postId for companyId ' . $companyId .' and contact ' .  $companyData['contact_first_name'] . ' ' . $companyData['contact_surname'] . '(rowId ' . $companyData['row_id'] . ')');
		} else {
			// add new post
			$id = $this->getNextId('tbl_posts');
			$data['id'] = $id;
			
			if ($this->db->insert('tbl_posts', $data) == 1) {
				$this->_logger->info('Added new postId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
				$this->importContact($companyData, $id);
			} else {
				$this->_logger->err('Could not add new postId ' . $data['id'] . ' for companyId ' . $companyId . ' (rowId ' . $companyData['row_id'] . ')');
				$id = 0;
			}
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
			Zend_Debug::dump($postInitiativeId);
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
			$this->_logger->info('Added post initiative note ' . $id . ' for postId ' . $postId);
		} else {
			$this->_logger->err('Could not add post initiative note for postId ' . $postId);
		}
	}
	
	function getImportData() 
	{
		$select = $this->db->select();
		$select->from(array('il' => 'tbl_import_lines'));
		$select->where('include_in_import = 1');
		$select->order('id');
		
		return $this->db->fetchAll($select);
	}
	

	function getAllImportData()
	{
		$select = $this->db->select();
		$select->from(array('il' => 'tbl_import_lines'));
		$select->order('id');
	
		return $this->db->fetchAll($select);
	}
	
	function preLineCheck() 
	{
		$data = array('include_in_import' => 0);
		$this->db->update('tbl_import_lines', $data);
// 		exit();
		// designed to check if a row has already been used 
		$rowCount = 0;
		foreach ($this->_companyData as &$row) {
			$rowCount++;
			Zend_Debug::dump('Pre-check - processing row: ' . $rowCount);
			Zend_Debug::dump($row);
			
			$companyId = 0;
			$postId = 0;
			$postInitiativeId = 0;
			$commCount = 0;
			
			if (is_null($row['alchemis_company_id'])) {
				
				if (is_null($row['company_name'])) {
					$row['include_in_import'] = 1;
					$data = array('include_in_import' => 1);
					$this->db->update('tbl_import_lines', $data, 'id = ' . $row['id']);
					$this->_logger->info('No company name found. Row id ' . $row['id'] . ' marked for include in import');
					continue;
				}
				
				$select = $this->db->select();
				$select->from(array('c' => 'tbl_companies'), array('id'));
				$select->joinLeft(array('s' => 'tbl_sites'), 's.company_id = c.id' , array());
				$select->where('c.name = ?', $row['company_name']);
				if (!is_null($row['site_address_1'])) {
					$select->where('s.address_1 = ?', $row['site_address_1']);
				}
				
				if (!is_null($row['site_address_2'])) {
					$select->where('s.address_2 = ?', $row['site_address_2']);
				}
				
				if (!is_null($row['site_postcode'])) {
					$select->where('s.postcode = ?', $row['site_postcode']);
				}
				
				$companyId = $this->db->fetchOne($select);
			} else {
				$companyId = $row['alchemis_company_id'];
			}
			
			if  ($companyId == 0) {
				$row['include_in_import'] = 1;
				$data = array('include_in_import' => 1);
				$this->db->update('tbl_import_lines', $data, 'id = ' . $row['id']);
				$this->_logger->info('CompanyId = 0 therefore marked company `' . $row['company_name'] . '` row id ' . $row['id'] . ' for include in import');
				continue;
// 				throw new Exception('Error: $companyId is zero');
			}
			
			Zend_Debug::dump($companyId, 'Company id');
			
			if (is_null($row['alchemis_post_id'])) {
				
				if (is_null($row['contact_first_name']) || is_null($row['contact_surname'])) {
					$row['include_in_import'] = 1;
					$data = array('include_in_import' => 1);
					$this->db->update('tbl_import_lines', $data, 'id = ' . $row['id']);
					$this->_logger->info('First name or Surname not found. Marked row id ' . $row['id'] . ' for include in import');
					continue;
				}
				
				$select = $this->db->select();
				$select->from(array('p' => 'tbl_posts'), array('id'));
				$select->joinLeft(array('con' => 'tbl_contacts'), 'con.post_id = p.id' , array());
				$select->where('con.first_name = ?', $row['contact_first_name']);
				$select->where('con.surname = ?', $row['contact_surname']);
				$select->where('p.company_id = ?', $companyId);
				
				
				$postId = $this->db->fetchOne($select);
			} else {
				$postId = $row['alchemis_post_id'];
			}
			
			if  ($postId == 0) {
				Zend_Debug::dump($select->__toString());
				$row['include_in_import'] = 1;
				$data = array('include_in_import' => 1);
				$this->db->update('tbl_import_lines', $data, 'id = ' . $row['id']);
				$this->_logger->info('PostId = 0 therefore marked row id ' . $row['id'] . ' for include in import');
				continue;
// 				throw new Exception('Error: $postId is zero');
			}
			
			$select = $this->db->select();
			$select->from('tbl_post_initiatives', array('id'));
			$select->where('post_id = ?', $postId);
			$select->where('initiative_id = ?', $row['client_initiative_id']);
			$postInitiativeId = $this->db->fetchOne($select);
				
			if  ($postInitiativeId == 0) {
				//throw new Exception('Error: $postInitiativeId is zero');
				$data = array('include_in_import' => 1);
				$this->db->update('tbl_import_lines', $data, 'id = ' . $row['id']);
				$this->_logger->info('Could not find post_initiative_id for specified company id of ' . $companyId . ' and post id of ' . $postId . ' (row Id ' . $row['id'] . ') - Marked for import');
				continue;
			} else {
				
				$this->_logger->info('Found existing post_initiative_id ' . $postInitiativeId . ' for specified company id of ' . $companyId . ' and post id of ' . $postId . ' (row Id ' . $row['id'] . '). NOT INCLUDED IN IMPORT');
				
// 				$select = $this->db->select();
// 				$select->from('tbl_communications', array('count(id)'));
// 				$select->where('post_initiative_id = ?', $postInitiativeId);
// 				$commCount = $this->db->fetchOne($select);
// 				Zend_Debug::dump($commCount, '$commCount');
				
// 				if ($commCount == 0) {
// 					$row['include_in_import'] = 1;
// 					$data = array('include_in_import' => 1);
// 					$this->db->update('tbl_import_lines', $data, 'id = ' . $row['id']);
// 					$this->_logger->info('No communications found therefore marked id ' . $row['id'] . ' for include in import');
// 				} else {
// 					$this->_logger->info('Found communications for post_initiative_id ' . $postInitiativeId . '. Marked as NOT INCLUDED in import');
// 				}
				
			}
			
			
		}
		
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
			
			if ($company_parent_id == 0) {
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
			
			if ($company_parent_id == 0) {
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
			
			if ($parentTagId == 0) 
			{
				$this->db->insert($table, $data);
				$id = $this->db->lastInsertId();
				
				$this->_logger->info('Added new ' . $objectType . ' tag id ' . $id . ' for tag_id: ' . $tagId);
			} else {
				$this->_logger->info('Found existing ' . $objectType . ' tag id ' . $parentTagId . ' for tag_id: ' . $tagId);
			}
		//return $id;
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
	
		return $this->db->fetchOne($select);
	}
	
}

?>