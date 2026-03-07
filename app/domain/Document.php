<?php

/**
 * Defines the app_domain_Document class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/mapper/DocumentMapper.php');

if (!defined('BYTES_IN_GIGABYTE'))
{
	define('BYTES_IN_GIGABYTE', (float)1073741824);
}

if (!defined('BYTES_IN_MEGABYTE'))
{
	define('BYTES_IN_MEGABYTE', (float)1048576);
}

if (!defined('BYTES_IN_KILOBYTE'))
{
	define('BYTES_IN_KILOBYTE', (float)1024);
}

/**
 * @package Framework
 */
class app_domain_Document extends app_domain_DomainObject
{
	/**
	 * ID of the campaign.
	 * @var integer
	 */
	protected $campaign_id;

	/**
	 * The filename
	 * @var string
	 */
	protected $filename;

	/**
	 * The file description.
	 * @var string
	 */
	protected $description;

	/**
	 * The document size in bytes.
	 * @var integer
	 */
	protected $size;

	/**
	 * The MIME type.
	 * @var integer
	 */
	protected $mime_id;

	/**
	 * The ID of the user who created the document
	 * @var integer
	 */
	protected $created_by;

	/**
	 * The timestamp of when the document was created.
	 * @var string in the format 'YYYY-MM-DD HH:MM:SS'
	 */
	protected $created;

	/**
	 * Whether the file is deleted.
	 * @var boolean
	 */
	protected $deleted;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Commit any changes to the object to the database.
	 */
	public function commit()
	{
		if (is_null($this->created))
		{
			$this->created = date('Y-m-d H:i:s');
		}
		parent::commit();
	}

	/**
	 * Download the document.
	 */
	public function download()
	{
		require_once('HTTP/Download.php');
		$dl = new HTTP_Download();
		$dl->setFile('.' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . $this->getId());
		$dl->setContentDisposition(HTTP_DOWNLOAD_ATTACHMENT, $this->getFilename());
		$dl->send();
	}

	/**
	 * Set the campaign ID.
	 * @param integer $campaign_id
	 */
	public function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		$this->markDirty();
	}

	/**
	 * Return the campaign ID.
	 * @return integer
	 */
	public function getCampaignId()
	{
		return $this->campaign_id;
	}

	/**
	 * Set the filename.
	 * @param string $filename
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;
		$this->markDirty();
	}

	/**
	 * Return the filename.
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * Set the description.
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		$this->markDirty();
	}

	/**
	 * Return the description.
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set the filesize (in bytes).
	 * @param integer $size
	 */
	public function setSize($size)
	{
		$this->size = $size;
		$this->markDirty();
	}

	/**
	 * Get the filesize (in bytes).
	 * @return integer
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * Returns the firendly file size, i.e. in KB, MB or GB.
	 * @return string 
	 */
	public function getFriendlySize($unit = null, $precision = 1)
	{
		require_once('Utils/Utils.class.php');
		return Utils::convertBytes($this->size, $unit, $precision);
	}

	/**
	 * Set the date created
	 * @param string $created in the format 'YYYY-MM-DD HH:MM:SS'
	 */
	public function setCreated($created)
	{
		$this->created = $created;
		$this->markDirty();
	}

	/**
	 * Return the date created.
	 * @return string
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * Set the ID of the user who created the document. 
	 * @param integer $created_by
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;
		$this->markDirty();
	}

	/**
	 * Return the ID of the user who created the document. 
	 * @return integer
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}

	/**
	 * Set the name of the user who created the document. 
	 * @param string $created_by_name
	 */
	public function setCreatedByName($created_by_name)
	{
		$this->created_by_name = $created_by_name;
		$this->markDirty();
	}

	/**
	 * Return the name of the user who created the document. 
	 * @return integer
	 */
	public function getCreatedByName()
	{
		return $this->created_by_name;
	}
	
	/**
	 * Set the deleted flag. 
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted)
	{
		$this->deleted = $deleted;
		$this->markDirty();
	}

	/**
	 * Return the deleted flag 
	 * @return boolean
	 */
	public function getDeleted()
	{
		return $this->deleted;
	}
	
	/**
	 * Get the file extension.
	 * @return string
	 */
	public function getFilenameExtension()
	{
		$extension = substr($this->filename, strrpos($this->filename, '.') + 1);
		return $extension;
	}

	/**
	 * Detemine whether a file already exist with this name within the same campaign. 
	 * @return boolean
	 */
	public function filenameAlreadyExists()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->filenameAlreadyExists($this->campaign_id, $this->id, $this->filename);
	}

	/**
	 * Set the MIME ID. 
	 * @param integer $mime_id
	 */
	public function setMimeId($mime_id)
	{
		$this->mime_id = $mime_id;
		$this->markDirty();
	}

	/**
	 * Return the MIME ID.
	 * @return integer
	 */
	public function getMimeId()
	{
		if (is_null($this->mime_id))
		{
			// get file type
			$extension = $this->getFilenameExtension();
			$mime_type = $this->getMimeType();
			if ($mime_id = self::lookupMimeId($extension, $mime_type))
			{
				$this->mime_id = $mime_id;
			}
			else
			{
				// add a new mime record
				$this->mime_id = self::insertMimeType($extension, $mime_type);
			}
		}
		return $this->mime_id;
	}

	/**
	 * Set the file type.
	 * @param string $file_type
	 */
	public function setFileType($file_type)
	{
		$this->file_type = $file_type;
		$this->markDirty();
	}

	/**
	 * Get the MIME type.
	 * @return string
	 */
	public function getFileType()
	{
		return $this->file_type;
	}

	/**
	 * Set the file MIME type.
	 * @param string $mime_type
	 */
	public function setMimeType($mime_type)
	{
		$this->mime_type = $mime_type;
		$this->markDirty();
	}

	/**
	 * Get the MIME type.
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->mime_type;
	}

	/**
	 * Set the friendly MIME name.
	 * @param string $mime_friendly_name
	 */
	public function setMimeFriendlyName($mime_friendly_name)
	{
		$this->mime_friendly_name = $mime_friendly_name;
		$this->markDirty();
	}

	/**
	 * Get the friendly MIME name.
	 * @return string
	 */
	public function getMimeFriendlyName()
	{
		return $this->mime_friendly_name;
	}

	/**
	 * Find a given document.
	 * @param integer $id document ID
	 * @return app_domain_Document
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * Return all documents.
	 * @return app_mapper_DocumentCollection collection of app_domain_Document objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * Find documents for a given campaign.
	 * @return integer $campaign_id
	 * @return app_mapper_DocumentCollection
	 */
	public static function findByCampaignId($campaign_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCampaignId($campaign_id);
	}

	/**
	 * Lookup the ID of a given MIME type.
	 * @return string $mime_type
	 * @return integer
	 */
	public static function lookupMimeTypeId($mime_type)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupMimeTypeId($mime_type);
	}

	/**
	 * Returns the ID for a file extension / mime type combination. 
	 * @param string $extension
	 * @param string $mime_type
	 * @return integer
	 */
	public static function lookupMimeId($extension, $mime_type)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupMimeId($extension, $mime_type);
	}

	/**
	 * Insert an new MIME type record and returns its ID.
	 * @return string $extension the extension
	 * @return string $mime_type the mime type
	 * @return integer
	 */
	public static function insertMimeType($extension, $mime_type)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->insertMimeType($extension, $mime_type);
	}

}

?>