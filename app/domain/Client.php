<?php

require_once('app/domain/DomainObject.php');
//require_once('app/domain/Campaign.php');

/**
 * @package alchemis
 */
class app_domain_Client extends app_domain_DomainObject
{
	private $name;
	private $is_current;

	protected $address_1;
	protected $address_2;
	protected $address_3;
	protected $town;
	protected $postcode;
	protected $county_id;
	protected $country_id;
	protected $telephone;
	protected $fax;
	protected $website;
	protected $financial_year_start;
	protected $primary_contact_name;
	protected $primary_contact_job_title;
	protected $primary_contact_telephone;
	protected $primary_contact_email;
	protected $secondary_contact_name;
	protected $secondary_contact_job_title;
	protected $secondary_contact_telephone;
	protected $secondary_contact_email;
	protected $publish_diary;
	
	/**
	 * @param integer $id
	 * @param string $name 
	 */
	public function __construct($id = null, $name = null)
	{
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
	 * Set the client name.
	 * @param string $name the client name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/**
	 * Return the client name.
	 * @return string the client name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Return the id of the associated campaign.
	 * @return integer the id of the campaign
	 */
	public function getCampaignId()
	{
		return app_domain_Campaign::findIdByClientId($this->id);
	}
	
	/**
	 * Set the client is deleted flag.
	 * @param boolean $is_current the client is deleted flag
	 */
	public function setIsCurrent($is_current)
	{
		$this->is_current = $is_current;
		$this->markDirty();
	}

	/**
	 * Return the client is deleted flag.
	 * @return boolean the is deleted flag
	 */
	public function getIsCurrent()
	{
		return $this->is_current;
	}
	
	/**
	 * Set the client address1.
	 * @param string $name the client address1
	 */
	public function setAddress1($address)
	{
		$this->address_1 = $address;
		$this->markDirty();
	}

	/**
	* Return the client address1.
	* @param string the client address1
	*/
	public function getAddress1()
	{
		return $this->address_1;
	}

	/**
	 * Set the client address2.
	 * @param string $name the client address1
	 */
	public function setAddress2($address)
	{
		$this->address_2 = $address;
		$this->markDirty();
	}

	/**
	* TODO - stub
	* Return the client address2.
	* @param string the client address2
	*/
	public function getAddress2()
	{
		return $this->address_2;
	}

	/**
	 * Set the client address3.
	 * @param string $address3 the client address3
	 */
	public function setAddress3($address)
	{
		$this->address_3 = $address;
		$this->markDirty();
	}

	/**
	* TODO - stub
	* Return the client address3.
	* @param string the client address3
	*/
	public function getAddress3()
	{
		return $this->address_3;
	}


	/**
	 * Set the client town.
	 * @param string $name the client town
	 */
	public function setTown($town)
	{
		$this->town = $town;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client town.
	 * @return string the client town.
	 */
	public function getTown()
	{
		return $this->town;
	}

	/**
	 * Set the client postcode.
	 * @param string $name the client postcode
	 */
	public function setPostcode($postcode)
	{
		$this->postcode = $postcode;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client postcode.
	 * @return string the client postcode
	 */
	public function getPostcode()
	{
		return $this->postcode;
	}

	/**
	 * Set the client county id.
	 * @param number $county_id the client county_id
	 */
	public function setCountyId($county_id)
	{
		$this->county_id = $county_id;
		$this->markDirty();
	}

	/**
	 * Return the client county_id.
	 * @return string the client county_id
	 */
	public function getCountyId()
	{
		return $this->county_id;
	}
	
	/**
	 * Set the client country id.
	 * @param string $country_id the client country_id
	 */
	public function setCountryId($country_id)
	{
		$this->country_id = $country_id;
		$this->markDirty();
	}

	/**
	 * Return the client country_id.
	 * @return string the client county_id
	 */
	public function getCountryId()
	{
		return $this->country_id;
	}

	/**
	 * Set the client telephone.
	 * @param string $telephone the client telephone
	 */
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client telephone.
	 * @return string the client telephone
	 */
	public function getTelephone()
	{
		return $this->telephone;
	}

	/**
	 * Set the client fax.
	 * @param string $fax the client fax
	 */
	public function setFax($fax)
	{
		$this->fax = $fax;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client fax.
	 * @return string the client fax
	 */
	public function getFax()
	{
		return $this->fax;
	}	
		
	
	/**
	 * Set the client website.
	 * @param string $website the client website
	 */
	public function setWebsite($website)
	{
		$this->website = $website;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client website.
	 * @return string the client website
	 */
	public function getWebsite()
	{
		return $this->website;
	}	
	
	
	/**
	 * Set the client financial year start.
	 * @param string $financial_year_start the client financial_year_start
	 */
	public function setFinancialYearStart($financial_year_start)
	{
		$this->financial_year_start = $financial_year_start;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client website.
	 * @return string the client website
	 */
	public function getFinancialYearStart()
	{
		return $this->financial_year_start;
	}	
		
	
	/**
	 * Set the client primary contact name.
	 * @param string $primary_contact_name the client primary contact name
	 */
	public function setPrimaryContactName($primary_contact_name)
	{
		$this->primary_contact_name = $primary_contact_name;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client primary contact name.
	 * @return string the client primary contact name
	 */
	public function getPrimaryContactName()
	{
		return $this->primary_contact_name;
	}	
	
	
	/**
	 * Set the client primary contact job title.
	 * @param string $primary_contact_job_title the client primary contact job title
	 */
	public function setPrimaryContactJobTitle($primary_contact_job_title)
	{
		$this->primary_contact_job_title = $primary_contact_job_title;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client primary contact job title.
	 * @return string the client primary contact job title
	 */
	public function getPrimaryContactJobTitle()
	{
		return $this->primary_contact_job_title;
	}	
		
	
	/**
	 * Set the client primary contact telephone.
	 * @param string $primary_contact_telephone the client primary contact telephone
	 */
	public function setPrimaryContactTelephone($primary_contact_telephone)
	{
		$this->primary_contact_telephone = $primary_contact_telephone;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client primary contact telephone.
	 * @return string the client primary contact telephone
	 */
	public function getPrimaryContactTelephone()
	{
		return $this->primary_contact_telephone;
	}	
	
	
	/**
	 * Set the client primary contact email.
	 * @param string $primary_contact_email the client primary contact email
	 */
	public function setPrimaryContactEmail($primary_contact_email)
	{
		$this->primary_contact_email = $primary_contact_email;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client primary contact email.
	 * @return string the client primary contact email
	 */
	public function getPrimaryContactEmail()
	{
		return $this->primary_contact_email;
	}	
		
		
	
	/**
	 * Set the client secondary contact name.
	 * @param string $secondary_contact_name the client secondary contact name
	 */
	public function setSecondaryContactName($secondary_contact_name)
	{
		$this->secondary_contact_name = $secondary_contact_name;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client secondary contact name.
	 * @return string the client secondary contact name
	 */
	public function getSecondaryContactName()
	{
		return $this->secondary_contact_name;
	}	
	
	
	/**
	 * Set the client secondary contact job title.
	 * @param string $secondary_contact_job_title the client secondary contact job title
	 */
	public function setSecondaryContactJobTitle($secondary_contact_job_title)
	{
		$this->secondary_contact_job_title = $secondary_contact_job_title;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client secondary contact job title.
	 * @return string the client secondary contact job title
	 */
	public function getSecondaryContactJobTitle()
	{
		return $this->secondary_contact_job_title;
	}	
		
	
	/**
	 * Set the client secondary contact telephone.
	 * @param string $secondary_contact_telephone the client secondary contact telephone
	 */
	public function setSecondaryContactTelephone($secondary_contact_telephone)
	{
		$this->secondary_contact_telephone = $secondary_contact_telephone;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client secondary contact telephone.
	 * @return string the client secondary contact telephone
	 */
	public function getSecondaryContactTelephone()
	{
		return $this->secondary_contact_telephone;
	}
	
	/**
	 * Set the client secondary contact email.
	 * @param string $secondary_contact_email the client secondary contact email
	 */
	public function setSecondaryContactEmail($secondary_contact_email)
	{
		$this->secondary_contact_email = $secondary_contact_email;
		$this->markDirty();
	}

	/**
	 * TODO - stub
	 * Return the client secondary contact email.
	 * @return string the client secondary contact email
	 */
	public function getSecondaryContactEmail()
	{
		return $this->secondary_contact_email;
	}

	
	/**
	* Set the client publish_diary.
	* @param string $publish_diary the client publish_diary
	*/
	public function setPublishDiary($publish_diary)
	{
		$this->publish_diary = $publish_diary;
		$this->markDirty();
	}
	
	/**
	 * TODO - stub
	 * Return the client publish_diary.
	 * @return string the client publish_diary
	 */
	public function getPublishDiary()
	{
		return $this->publish_diary;
	}
	
	/**
	 * Return a formatted string of the address.
	 * @return string formated address 
	 */
	public function getAddress($format = null)
	{
		$address = array(	'address_1' => $this->address_1,
							'address_2' => $this->address_2,
							'address_3' => $this->address_3,
							'town'      => $this->town,
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
		
		// Remove any null / empty items
		foreach ($array as $key => &$item)
		{
			if (is_null($item) || trim($item) == '')
			{
				unset($array[$key]);
			}
		}
		
		$str = '';
		switch ($format)
		{
			case 'paragraph':
//				echo '<pre>';
//				print_r($array);
//				echo '</pre>';
				foreach ($array as &$item)
				{
//					echo $item . '<br />';
					if ($item != 'NULL' && $item != '')
					{
						$str .= $item . '<br />';
					}
				}
//				echo $str;
				break;
			
			case 'string':
			default:
				$str = implode(', ', $array);
				break;
		}
		return $str;
	}

	/**
	 * 
	 * @return app_domain_ClientCollection
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * 
	 * @return app_domain_ClientCollection
	 */
	public static function findAllActive()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAllActive();
	}

	/**
	 * Find all clients limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public static function findSet($limit = 15, $offset = 0)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findSet($limit, $offset);
	}

	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_VenueMapper
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * 
	 * @param integer $id
	 * @return app_mapper_VenueMapper
	 */
	public static function count()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->count();
	}
	
	/**
	 * Finds all the client initiative records.
	 * @return array of raw post mapper data
	 */
	public static function findAllClientInitiatives()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAllClientInitiatives();
	}
			
	/**
	 * Find initiatives for a given client.
	 * @param integer $client_id
	 * @return array of raw post mapper data
	 */
	public static function findClientInitiatives($client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findClientInitiatives($client_id);
	}

	/**
	 * Finds a client by initiative id
	 * @param $initiative_id
	 * @return client domain object
	 */
	public static function findByInitiativeId($initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByInitiativeId($initiative_id);
	}
	
	/**
	 * Finds a client by post initiative id 
	 * @param $post_initiative_id
	 * @return client domain object
	 */
	public static function findByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeId($post_initiative_id);
	}
	
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupCountyById($id)
	{
		$finder = self::getFinder('app_mapper_Site');
		return $finder->lookupCountyById($id);
	}
	
	/**
	 * 
	 * @return raw data - single item
	 */
	public static function lookupCountryById($id)
	{
		$finder = self::getFinder('app_mapper_Site');
		return $finder->lookupCountryById($id);
	}

	/**
	 * Find clients associated with a given user.
	 * @param integer $user_id
	 * @return array
	 */
	public static function findByUserId($user_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByUserId($user_id);
	}

	/**
	 * Find clients associated with a given user for drop-down.
	 * @param integer $user_id
	 * @return array
	 */
	public static function findByUserIdForDropdown($user_id)
	{
		$options = array();
		if ($items = self::findByUserId($user_id))
		{
			$options[0] = '-- select --';
			foreach ($items as $item)
			{
				$options[$item['client_id']] = $item['client_name'];
			}
		}
		return $options;
	}

	/**
	 * Lookup top line summary statistics
	 * @param integer $client_id
	 * @param string $year_month
	 * @return array
	 */
	public static function findTargetsByClientIdAndYearmonth($client_id, $year_month = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findTargetsByClientIdAndYearmonth($client_id, $year_month);
	}

	/**
	 * Lookup top line summary statistics
	 * @param integer $client_id
	 * @param string $year_month
	 * @return array
	 */
	public static function findActualsByClientIdAndYearmonth($client_id, $year_month = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findActualsByClientIdAndYearmonth($client_id, $year_month);
	}

	/**
	 * Return a client name for a given ID.
	 * @param integer $client_id
	 * @return string
	 */
	public static function lookupClientNameById($client_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupClientNameById($client_id);
	}

	/**
	* Return client ids where publish_dairy = 1
	* @return string
	*/
	public static function findClientIdsByPublishDiary()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findClientIdsByPublishDiary();
	}
	
	/**
	 * Find clients in a form suitable for a drop-down box.
	 * @return array
	 */
	public static function findForDropdown()
	{
		$items = self::findAllActive()->toRawArray();
		foreach ($items as $item)
		{
			$res[$item['id']] = $item['name'];
		}
		return $res;
	}

}

?>
