<?php

/**
 * Defines the app_mapper_SiteMapper class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_SiteMapper extends app_mapper_ShadowMapper implements app_domain_SiteFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select single
//		$query = 'SELECT * FROM tbl_sites WHERE id = ?';
//		$types = array('integer');
//		$this->selectStmt = self::$DB->prepare($query, $types);
		
		// Select by company
//		$query = 'SELECT * FROM tbl_sites WHERE company_id = ?';
//		$types = array('integer');
//		$this->selectByCompanyStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Site($array['id']);
		$obj->setCompanyId($array['company_id']);
		$obj->setName($array['name']);
		$obj->setAddress1($array['address_1']);
		$obj->setAddress2($array['address_2']);
		$obj->setTown($array['town']);
		$obj->setCity($array['city']);
		$obj->setPostcode($array['postcode']);
		$obj->setTelephone($array['telephone']);
		$obj->setCountyId($array['county_id']);
		$obj->setCountryId($array['country_id']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_sites');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		if (!isset($this->insertStmt))
		{
			
			if ((is_null($object->getCountyId()) || $object->getCountyId() == 0) && (is_null($object->getCountryId()) || $object->getCountryId() == 0))
			{
				$query = 'INSERT INTO tbl_sites (id, company_id, address_1, address_2, ' .
						'town, city, postcode, county_id, country_id) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?, NULL, NULL)';
			
				$types = array('integer', 'integer', 'text', 'text', 'text', 'text', 
								'text');
			
				$this->insertStmt = self::$DB->prepare($query, $types);
			
				$data = array($object->getId(), $object->getCompanyId(), $object->getAddress1(), 
						$object->getAddress2(), $object->getTown(), $object->getCity(), 
						$object->getPostcode());	
			}
			elseif ((is_null($object->getCountyId()) || $object->getCountyId() == 0) && !(is_null($object->getCountryId()) || $object->getCountryId() == 0))
			{
				$query = 'INSERT INTO tbl_sites (id, company_id, address_1, address_2, ' .
						'town, city, postcode, county_id, country_id) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?, NULL, ?)';
			
				$types = array('integer', 'integer', 'text', 'text', 'text', 'text', 
								'text', 'integer');
			
				$this->insertStmt = self::$DB->prepare($query, $types);
			
				$data = array($object->getId(), $object->getCompanyId(), $object->getAddress1(), 
						$object->getAddress2(), $object->getTown(), $object->getCity(), 
						$object->getPostcode(), $object->getCountryId());	
			}
			elseif (!(is_null($object->getCountyId()) || $object->getCountyId() == 0) && (is_null($object->getCountryId()) || $object->getCountryId() == 0))
			{
				$query = 'INSERT INTO tbl_sites (id, company_id, address_1, address_2, ' .
						'town, city, postcode, county_id, country_id) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)';
			
				$types = array('integer', 'integer', 'text', 'text', 'text', 'text', 
								'text', 'integer');
			
				$this->insertStmt = self::$DB->prepare($query, $types);
			
				$data = array($object->getId(), $object->getCompanyId(), $object->getAddress1(), 
						$object->getAddress2(), $object->getTown(), $object->getCity(), 
						$object->getPostcode(), $object->getCountyId());	
			}
			else
			{
				$query = 'INSERT INTO tbl_sites (id, company_id, address_1, address_2, ' .
						'town, city, postcode, county_id, country_id) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
			
				$types = array('integer', 'integer', 'text', 'text', 'text', 'text', 
								'text', 'integer', 'integer');
			
				$this->insertStmt = self::$DB->prepare($query, $types);
				
				$data = array($object->getId(), $object->getCompanyId(), $object->getAddress1(), 
						$object->getAddress2(), $object->getTown(), $object->getCity(), 
						$object->getPostcode(), $object->getCountyId(), $object->getCountryId());
			}
		}
		
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		if ((is_null($object->getCountyId()) || $object->getCountyId() == 0) && (is_null($object->getCountryId()) || $object->getCountryId() == 0))
		{
			$query = 'UPDATE tbl_sites SET company_id = ?, address_1 = ?, address_2 = ?, ' .
						'town = ?, city = ?, postcode = ?, county_id = null, country_id = null ' .
						'WHERE id = ?';
			$types = array('integer', 'text', 'text', 'text', 'text', 'text', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types);
			
			$data = array($object->getCompanyId(), $object->getAddress1(), $object->getAddress2(), 
							$object->getTown(), $object->getCity(), $object->getPostcode(), 
							$object->getId());
		}
		elseif ((is_null($object->getCountyId()) || $object->getCountyId() == 0) && !(is_null($object->getCountryId()) || $object->getCountryId() == 0))
		{
			$query = 'UPDATE tbl_sites SET company_id = ?, address_1 = ?, address_2 = ?, ' .
						'town = ?, city = ?, postcode = ?, county_id = null, country_id = ? ' .
						'WHERE id = ?';
			$types = array('integer', 'text', 'text', 'text', 'text', 'text', 'integer', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types);
			
			$data = array($object->getCompanyId(), $object->getAddress1(), $object->getAddress2(), 
							$object->getTown(), $object->getCity(), $object->getPostcode(), 
							$object->getCountryId(), $object->getId());
		}
		elseif (!(is_null($object->getCountyId()) || $object->getCountyId() == 0) && (is_null($object->getCountryId()) || $object->getCountryId() == 0))
		{
			$query = 'UPDATE tbl_sites SET company_id = ?, address_1 = ?, address_2 = ?, ' .
						'town = ?, city = ?, postcode = ?, county_id = ?, country_id = null ' .
						'WHERE id = ?';
			$types = array('integer', 'text', 'text', 'text', 'text', 'text', 'integer', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types);
			
			$data = array($object->getCompanyId(), $object->getAddress1(), $object->getAddress2(), 
							$object->getTown(), $object->getCity(), $object->getPostcode(), 
							$object->getCountyId(), $object->getId());
		}
		else
		{
			$query = 'UPDATE tbl_sites SET company_id = ?, address_1 = ?, address_2 = ?, ' .
						'town = ?, city = ?, postcode = ?, county_id = ?, country_id = ? ' .
						'WHERE id = ?';
			$types = array('integer', 'text', 'text', 'text', 'text', 'text', 'integer', 'integer', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types);
			
			$data = array($object->getCompanyId(), $object->getAddress1(), $object->getAddress2(), 
							$object->getTown(), $object->getCity(), $object->getPostcode(), 
							$object->getCountyId(), $object->getCountryId(), $object->getId());
		}
		
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Return a given site.
	 * @param integer $id
	 * @return app_domain_Site
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT * FROM tbl_sites WHERE id = ?';
		$types = array('integer');
		$selectStmt = self::$DB->prepare($query, $types);
		
		$values = array($id);
		$result = $this->doStatement($selectStmt, $values);
		return $this->load($result);
	}

	/**
	 * Find all sites for a given company.
	 * @param string $company_id
	 * @return app_mapper_SiteCollection collection of app_domain_Site objects
	 */
	public function findByCompanyId($company_id)
	{
		$query = 'SELECT * FROM tbl_sites WHERE company_id = ?';
		$types = array('integer');
		$selectByCompanyStmt = self::$DB->prepare($query, $types);
		
		$values = array($company_id);
		$result = $this->doStatement($selectByCompanyStmt, $values);
		return new app_mapper_SiteCollection($result, $this);
	}

	/**
	 * Find all counties.
	 * @return array 
	 */
	public function getCountiesAll()
	{
		$query = 'SELECT * FROM tbl_lkp_counties ORDER BY name'; 
		$result = $this->doStatement(self::$DB->prepare($query), array());
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find all countries.
	 * @return array 
	 */
	public function getCountriesAll()
	{
		$query = 'SELECT * FROM tbl_lkp_countries ORDER BY name'; 
		$result = $this->doStatement(self::$DB->prepare($query), array());
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find county by county id
	 * @param integer $id
	 * @return raw data - single item
	 */
	public function lookupCountyById($id)
	{
		if ($id)
		{
			$query = 'SELECT name FROM tbl_lkp_counties WHERE id = ' . self::$DB->quote($id, 'integer');
			return self::$DB->queryOne($query);
		}
		else
		{
			return null;
		}
	}
	
	
	/**
	 * Find country by country id
	 * @param integer $id
	 * @return raw data - single item
	 */
	public function lookupCountryById($id)
	{
		if ($id)
		{
			$query = 'SELECT name FROM tbl_lkp_countries WHERE id = ' . self::$DB->quote($id, 'integer');
			return self::$DB->queryOne($query);
		}
		else
		{
			return null;
		}
	}
	

	/**
	 * Find all regions.
	 * @return array 
	 */
	public function getRegionsAll()
	{
		$query = 'SELECT * FROM tbl_lkp_regions ORDER BY name'; 
		$result = $this->doStatement(self::$DB->prepare($query), array());
		return self::mdb2ResultToArray($result);
	}


	/**
	 * Find postcode by postcode
	 * @param integer $id
	 * @return raw data - single item
	 */
	public function countPostcodeByPostcode($postcode)
	{
		if ($postcode)
		{
			$query = 'SELECT count(id) FROM tbl_lkp_postcodes WHERE postcode = ' . self::$DB->quote($postcode, 'text');
			return self::$DB->queryOne($query);
		}
		else
		{
			return 0;
		}
	}
	
}

?>