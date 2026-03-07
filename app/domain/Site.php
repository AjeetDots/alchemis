<?php

/**
 * Defines the app_domain_Site class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Site extends app_domain_DomainObject
{
	protected $name;
	protected $sites;
	protected $posts;
	protected $company_id;
	protected $address_1;
	protected $address_2;
	protected $town;
	protected $city;
	protected $postcode;
	protected $telephone;
	protected $county_id;
	protected $country_id;

	/**
	 * @param integer $id
	 * @param string $name 
	 */
	public function __construct($id = null, $name = null)
	{
		$this->name  = $name;
		parent::__construct($id);
		
		if ($this->id)
		{
		}
		else
		{
			echo "<br />skip load";
		}
	}

	/**
	 * Returns an array of field validation rules.
	 * @see app_base_RuleValidator
	 */
	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['address_1'] 	= array(	'alias'      => 'Address 1',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 255);
		
		$spec['address_2'] 	= array(	'alias'      => 'Address 2',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 255);
		
		$spec['town'] 	= array(		'alias'      => 'Town',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 50);
		
		$spec['city'] 	= array(		'alias'      => 'City',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 50);
		
		$spec['county_id'] 	= array(	'alias'      => 'County',
										'type'       => 'integer',
										'mandatory'  => false,
										'in_array'   => app_domain_Site::findCountyIds());
		
		$spec['county_id_mandatory'] 	= array(	'alias'      => 'County',
										'type'       => 'integer',
										'mandatory'  => true,
										'in_array'   => app_domain_Site::findCountyIds());
		
		$spec['postcode'] 	= array(	'alias'      => 'Postcode',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 50);
		
		$spec['country_id'] 	= array('alias'      => 'Country',
										'type'       => 'integer',
										'mandatory'  => false,
										'in_array'   => app_domain_Site::findCountryIds());
		if (!is_null($field))
		{
			return $spec[$field];
		}
		else
		{
			return $spec;
		}
	}

	/**
	 * Set the site company id.
	 * @param string $company_id the site company_id
	 */
	public function setCompanyId($company_id)
	{
		$this->company_id = $company_id;
		$this->markDirty();
	}

	/**
	 * Return the site company id.
	 * @return string the site company id.
	 */
	public function getCompanyId()
	{
		return $this->company_id;
	}
	
	/**
	 * Sets the sites collection.
	 * @param app_domain_SiteCollection $sites
	 */
	function setSites(app_domain_SiteCollection $sites)
	{
		$this->sites = $sites;
	}
	
	/**
	 * Returns the sites collection.
	 * @return app_mapper_Collection the collection of app_domain_Site objects
	 */
	function getSites()
	{
		return $this->sites;
	} 

	/**
	 * Add a site to the sites collection, and set the site's parent company to self.
	 * @param app_domain_Site $site the site to add
	 */
	function addSite(app_domain_Site $site)
	{
		$this->site->add($site);
		$site->setCompany($this);
	}

	/**
	 * Set the site name.
	 * @param string $name the site name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}
	
	/**
	 * Return the site name.
	 * @return string the site name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the site address1.
	 * @param string $name the site address1
	 */
	public function setAddress1($address)
	{
		$this->address_1 = $address;
		$this->markDirty();
	}

	/**
	* Return the site address1.
	* @param string the site address1
	*/
	public function getAddress1()
	{
		return $this->address_1;
	}

	/**
	 * Set the site address1.
	 * @param string $name the site address1
	 */
	public function setAddress2($address)
	{
		$this->address_2 = $address;
		$this->markDirty();
	}

	/**
	* TODO - stub
	* Return the site address2.
	* @param string the site address2
	*/
	public function getAddress2()
	{
		return $this->address_2;
	}

	/**
	 * Set the site town name.
	 * @param string $name the site town
	 */
	public function setTown($town)
	{
		$this->town = $town;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the site town .
	 * @return string the site town
	 */
	public function getTown()
	{
		return $this->town;
	}

	/**
	 * Set the site city.
	 * @param string $name the site city
	 */
	public function setCity($city)
	{
		$this->city = $city;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the site city.
	 * @return string the site city.
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Set the site postcode.
	 * @param string $name the site postcode
	 */
	public function setPostcode($postcode)
	{
		$this->postcode = $postcode;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the site postcode.
	 * @return string the site postcode
	 */
	public function getPostcode()
	{
		return $this->postcode;
	}

	/**
	 * Set the site telephone.
	 * @param string $name the site telephone
	 */
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the site telephone.
	 * @return string the site telephone
	 */
	public function getTelephone()
	{
		return $this->telephone;
	}

	/**
	 * Set the site county id.
	 * @param string $county_id the site county_id
	 */
	public function setCountyId($county_id)
	{
		$this->county_id = $county_id;
		$this->markDirty();
	}

	/**
	 * Return the site county_id.
	 * @return string the site county_id
	 */
	public function getCountyId()
	{
		return $this->county_id;
	}
	
	/**
	 * Set the site country id.
	 * @param string $country_id the site country_id
	 */
	public function setCountryId($country_id)
	{
		$this->country_id = $country_id;
		$this->markDirty();
	}

	/**
	 * Return the site country_id.
	 * @return string the site county_id
	 */
	public function getCountryId()
	{
		return $this->country_id;
	}

	/**
	 * Return a formatted string of the address.
	 * @return string formated address 
	 */
	public function getAddress($format = null)
	{
		$address = array(	'address_1' => $this->address_1,
							'address_2' => $this->address_2,
							'town'      => $this->town,
							'city'      => $this->city,
							'county'  	=> self::lookupCountyById($this->county_id),
							'postcode'  => $this->postcode,
							'country'  	=> self::lookupCountryById($this->country_id));
							
		return self::formatAddress($address, $format);
	}

	/**
	 * Return a formatted address.
	 * @param array $array
	 * @param string $format
	 */
	public static function formatAddress($array, $format = null)
	{
		$str = '';
		
		switch ($format)
		{
			case 'paragraph':
				foreach ($array as $item)
				{
					if ($item != 'NULL' && $item != '')
					{
						$str .= $item . '<br />';
					}
				}
				break;
			
			case 'string':
			default:
				$str = implode(', ', array_filter($array));
				break;
		}
		return $str;
	}

	/**
	 * Return a formatted address.
	 * @param string $postcode
	 * @param integer $country_id
	 */
	public static function isValidPostcode($postcode, $country_id)
	{
		switch ($country_id)
		{
			case 9:
				$postcode = trim($postcode);
				preg_match('/^[A-Z]{1,2}[0-9][0-9A-Z]?/i', $postcode, $matches);
				if (!isset($matches[0]) || is_null($matches[0]))
				{
					return new app_base_ValidationError('Postcode format invalid');
				}
				else
				{
					$postcode_count = self::countPostcodeByPostcode($matches[0]); 
					if ($postcode_count == 0)
					{
						return new app_base_ValidationError('First part of postcode is not a valid Royal Mail entry');
					}
					else
					{
						return ; // OK 
					}
				}
				break;

			default:
				$postcode = trim($postcode);
				preg_match('/^[A-Z]{1,2}[0-9][0-9A-Z]?/i', $postcode, $matches);
				
				if (isset($matches[0]) || !is_null($matches[0]))
				{
					$postcode_count = self::countPostcodeByPostcode($matches[0]); 
					if ($postcode_count >= 1)
					{
						return new app_base_ValidationError('UK Postcode format cannot be specified for a country outside the UK');
					}
					else
					{
						return ; // OK 
					}
				}
				break;	
		}
	}

	

				
	/**
	 * 
	 * @return app_mapper_SiteMapper
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_SiteMapper
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_SiteCollection collection of app_domain_Site objects
	 */
	public static function findByCompanyId($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyId($company_id);
	}

	/**
	 * 
	 * @return app_mapper_SiteMapper raw array
	 */
	public static function getCountiesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getCountiesAll();
	}

	/**
	 * 
	 * @return app_mapper_SiteMapper raw array
	 */
	public static function getCountriesAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getCountriesAll();
	}

	/**
	 * Get an array of county IDs
	 * @return array
	 */
	public static function findCountyIds()
	{
		$ids = array();
		$counties = self::getCountiesAll();
		foreach ($counties as $county)
		{
			$ids[] = $county['id'];
		}
		return $ids;
	}
	
	/**
	 * Get an array of county IDs
	 * @return array
	 */
	public static function findCountryIds()
	{
		$ids = array();
		$countries = self::getCountriesAll();
		foreach ($countries as $country)
		{
			$ids[] = $country['id'];
		}
		return $ids;
	}

	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupCountyById($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCountyById($id);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupCountryById($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCountryById($id);
	}
	
	/**
	 * 
	 * @return app_mapper_SiteMapper raw array
	 */
	public static function getRegionsAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getRegionsAll();
	}
	
	/**
	 * Looks for a postcode in tbl_lkp_postcode
	 * @param string $postcode
	 * @return app_mapper_SiteMapper raw array
	 */
	public static function countPostcodeByPostcode($postcode)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countPostcodeByPostcode($postcode);
	}

}

?>