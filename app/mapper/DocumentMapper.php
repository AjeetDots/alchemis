<?php

/**
 * Defines the the friendly MIME name. class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package Framework
 */
class app_mapper_DocumentMapper extends app_mapper_Mapper implements app_domain_DocumentFinder
{

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Document($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setFilename($array['filename']);
		$obj->setDescription($array['description']);
		$obj->setSize($array['size']);
		$obj->setMimeId($array['mime_id']);
		$obj->setCreatedBy($array['created_by']);
		$obj->setCreatedByName($array['created_by_name']);
		$obj->setCreated($array['created']);
		$obj->setDeleted($array['deleted']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_documents');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
//		echo '<p><b>app_mapper_DocumentMapper::doInsert(' . get_class($object) . ')</b></p>';
		if (is_null($object->getMimeId()))
		{
			$query = 'INSERT INTO tbl_documents (id, campaign_id, filename, description, size, mime_id, created_by, created) ' .
						'VALUES (?, ?, ?, ?, ?, NULL, ?, ?)';
			$types = array('integer', 'integer', 'text', 'text', 'integer', 'integer', 'date');
			$data = array($object->getId(), $object->getCampaignId(), $object->getFilename(), $object->getDescription(), 
							$object->getSize(), $object->getCreatedBy(), $object->getCreated());
		}
		else
		{
			$query = 'INSERT INTO tbl_documents (id, campaign_id, filename, description, size, mime_id, created_by, created) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
			$types = array('integer', 'integer', 'text', 'text', 'integer', 'integer', 'integer', 'date');
			$data = array($object->getId(), $object->getCampaignId(), $object->getFilename(), $object->getDescription(), 
							$object->getSize(), $object->getMimeId(), $object->getCreatedBy(), $object->getCreated());
		}
		$stmt = self::$DB->prepare($query, $types);
		$this->doStatement($stmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
//		echo '<p><b>app_mapper_DocumentMapper::update(' . get_class($object) . ')</b></p>';
	}

	/**
	 * Deletes the database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
//		echo '<p><b>app_mapper_DocumentMapper::delete(' . get_class($object) . ')</b></p>';
		$document_id = $object->getId();
		$query = 'UPDATE tbl_documents SET deleted = 1 WHERE id = ?';
		$types = array('integer');
		$data = array($document_id);
		$stmt = self::$DB->prepare($query, $types);
		$this->doStatement($stmt, $data);
		
//		// Delete the actual file
//		$this->deleteFile($document_id);
	}

//	/**
//	 * Deletes the actual file.
//	 * @param integer $document_id
//	 */
//	protected function deleteFile($document_id)
//	{
//		$file = '.' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . $document_id;
//		unlink($file);
//	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
//		echo "<p><b>app_mapper_DocumentMapper::doFind($id)</b></p>";
		$query = 'SELECT d.*, m.file_type, m.mime_type, m.friendly_name AS mime_friendly_name, ' .
					'u.name AS created_by_name ' .
					'FROM tbl_documents AS d ' .
					'LEFT JOIN tbl_mime_types AS m ON d.mime_id = m.id ' .
					'INNER JOIN tbl_rbac_users AS u ON d.created_by = u.id ' .
					'WHERE d.id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/**
	 * Find all docuemnts.
	 * @return array of app_domain_Document objects
	 */
	public function findAll()
	{
//		echo "<p><b>app_mapper_DocumentMapper::findAll()</b></p>";
		$query = 'SELECT d.*, m.file_type, m.mime_type, m.friendly_name AS mime_friendly_name, ' .
					'u.name AS created_by_name ' .
					'FROM tbl_documents AS d ' .
					'INNER JOIN tbl_mime_types AS m ON d.mime_id = m.id ' .
					'INNER JOIN tbl_rbac_users AS u ON d.created_by = u.id ' .
					'ORDER BY d.created desc, d.filename';
		$result = self::$DB->query($query);
		$collection = new app_mapper_DocumentCollection($result, $this);
		return $collection->toArray();
	}

	/**
	 * Returns the number of documents.
	 * @param integer
	 */
	public function count()
	{
		$query = 'SELECT COUNT(*) FROM tbl_documents';
		$result = self::$DB->query($query);
		return $result->fetchOne(0, 0);
	}

	/**
	 * Detemine whether a file already exist with this name within the same campaign. 
	 * @param integer $campaign_id
	 * @param integer $file_id
	 * @param string $filename
	 * @return boolean
	 */
	public function filenameAlreadyExists($campaign_id, $file_id, $filename)
	{
		// Check whether the document name already exists in the folder
		$query = 'SELECT COUNT(d.id) FROM tbl_documents AS d ' .
					'WHERE d.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
					' AND d.filename = ' . self::$DB->quote($filename, 'text') . ' ' .
					'AND d.id != ' . self::$DB->quote($file_id, 'integer');
		$result = self::$DB->query($query);
		$count = $result->fetchOne(0, 0);
		return $count > 0; 
	}

	/**
	 * Find documents for a given campaign.
	 * @return integer $campaign_id
	 * @return app_mapper_DocumentCollection
	 */
	public function findByCampaignId($campaign_id)
	{
		$query = 'SELECT d.*, ' .
					'u.name AS created_by_name ' .
					'FROM tbl_documents AS d ' .
					'INNER JOIN tbl_rbac_users AS u ON d.created_by = u.id ' .
					'WHERE d.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
					'AND d.deleted = false ' .
					'ORDER BY d.created desc, d.filename';
		$result = self::$DB->query($query);
		$collection = new app_mapper_DocumentCollection($result, $this);
		return $collection->toArray();
	}

	/**
	 * Lookup the ID of a given MIME type.
	 * @return string $mime_type
	 * @return integer
	 */
	public function lookupMimeTypeId($mime_type)
	{
		if (!isset($this->statements['lookup_mime_type_id']))
		{
			$query = 'SELECT id FROM tbl_mime_types WHERE mimetypes = ?';
			$types = array('text');
			$this->statements['lookup_mime_type_id'] = self::$DB->prepare($query, $types);
		}
		$data = array($mime_type);
		$result = $this->doStatement($this->statements['lookup_mime_type_id'], $data);
		return $result->fetchOne(0, 0);
	}

	/**
	 * Insert an new MIME type record and returns its ID.
	 * @return string $extension the extension
	 * @return string $mime_type the mime type
	 * @return integer
	 */
	public function insertMimeType($extension, $mime_type)
	{
		$mime_id = self::$DB->nextID('tbl_mime_types');
		$query = "INSERT INTO tbl_mime_types (id, file_type, mime_type, icon_path, friendly_name) VALUES (?, ?, ?, NULL, '')";
		$types = array('integer', 'text', 'text');
		$data = array($mime_id, $extension, $mime_type);
		$stmt = self::$DB->prepare($query, $types);
		$this->doStatement($stmt, $data);
		return $mime_id;
	}

	/**
	 * Lookup the friendly name of a given MIME type.
	 * @return string $mime_type
	 * @return string
	 */
	public function lookupMimeFriendlyName($mime_type)
	{
		if (!isset($this->statements['lookup_mime_friendly_name']))
		{
			$query = 'SELECT friendly_name FROM tbl_mime_types WHERE mimetypes = ?';
			$types = array('text');
			$this->statements['lookup_mime_friendly_name'] = self::$DB->prepare($query, $types);
		}
		$data = array($mime_type);
		$result = $this->doStatement($this->statements['lookup_mime_friendly_name'], $data);
		return $result->fetchOne(0, 0);
	}

	/**
	 * Returns the ID for a file extension / mime type combination. 
	 * @param string $extension
	 * @param string $mime_type
	 * @return integer
	 */
	public static function lookupMimeId($extension, $mime_type)
	{
		$query = 'SELECT id FROM tbl_mime_types ' .
					'WHERE file_type = ' . self::$DB->quote($extension) . ' ' .
					'AND mime_type = ' . self::$DB->quote($mime_type);
		return self::$DB->queryOne($query);
	}

}

?>