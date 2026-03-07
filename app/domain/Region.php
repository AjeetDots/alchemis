<?php

/**
 * Defines the app_domain_Region class.
 *  
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Region extends app_domain_DomainObject
{
	/**
//	 * Field spec and validation rules.
//	 */
//	protected $spec;

	/**
	 * Array of associated postcodes
	 * @var array
	 */
	protected $postcodes;
	
	protected $name;
	protected $description;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
		
		if ($this->id)
		{
			
		}
	}

	/** Sets the name of the region
	 * @param string $name
	 */
	function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/** Gets the name of the region
	 * @return string $name - name of the region
	 */
	function getName()
	{
		return $this->name;
	}

	/** Sets the description of the region
	 * @param string $description
	 */
	function setDescription($description)
	{
		$this->description = $description;
		$this->markDirty();
	}

	/** Gets the description of the region
	 * @return string $description - name of the description
	 */
	function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Sets the postcodes collection.
	 * @param app_domain_PostcodeCollection $postcodes
	 */
	function setPostcodes()
	{
		$this->postcodes = $this->findPostcodes($this->id);
		$this->markDirty();
	}
	
	
	/**
	 * Returns the postcode collection.
	 * @return app_mapper_Collection the collection of app_domain_Postcode objects
	 */
	function getPostcodes()
	{
		return $this->postcodes;
	} 
	
	/**
	 * Returns boolean 
	 * @return boolean - whether postcode added successfully
	 */
	function addPostcode($postcode_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->addPostcode($this->id, $postcode_id);
	}
	
	/**
	 * Returns boolean.
	 * @return boolean - whether postcode removed successfully
	 */
	function deletePostcode($postcode_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->deletePostcode($this->id, $postcode_id);
	}
	
	/**
 	 * Find a regionby a given id
	 * @param integer $id region id
	 * @return app_mapper_RegionCollection collection of app_domain_Region objects
	 */
	public static function find($id)
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}	
	
	/**
 	 * Find all regions
	 * @return app_mapper_RegionCollection collection of app_domain_Region objects
	 */
	public static function findAll()
	{
		
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
	 * Find postcodes for a given region id
	 * @param integer $id - id of the region
	 * @return array of raw data
	 */
	public static function findPostcodes($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostcodes($id);
	}

	
	/**
	 * Find all postcodes from tbl_lkp_postcodes
	 * @return array of raw data
	 */
	public static function findPostcodesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostcodesAll();
	}
	
	
	/**
	 * Find all postcodes from tbl_lkp_postcodes starting with $search
	 * @param string $search - search string
	 * @return array of raw data
	 */
	public static function findPostcodesStartWith($search)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostcodesStartWith($search);
	}
	
}


?>