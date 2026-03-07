<?php

/**
 * Defines the app_domain_Company class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/domain/Site.php');
require_once('app/mapper/SiteMapper.php');
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');
require_once('app/domain/Tags.php');

/**
 * @package Alchemis
 */
class app_domain_Company extends app_domain_DomainObject implements Taggable
{
	/**
	 * Field spec and validation rules.
	 */
	protected $spec;

	protected $name;
	protected $website;
	protected $parent_company_id;
	protected $telephone;
	protected $telephone_tps;
	protected $additional_info;

	/**
	 * Collection of site objects
	 * @var app_mapper_SiteCollection collection of app_domain_Site objects
	 */
	protected $sites;

	/**
	 * Collection of post objects
	 * @var app_mapper_PostCollection collection of app_domain_Post objects
	 */
	protected $posts;
	protected $posts_loaded = false;
//	protected $post_count;

	/**
	 * Collection of tag objects for this company
	 * @var app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	protected $tags;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);

		if ($this->id)
		{
			$finder = self::getFinder('app_domain_Site');
			$this->setSites($finder->findByCompanyId($this->id));
		}

	}

	/**
	 * Returns an array of field validation rules.
	 * @param string $field optional field name
	 * @return spec
	 * @see app_base_RuleValidator
	 */
	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['name'] 		= array(	'alias'      => 'Name',
										'type'       => 'text',
										'mandatory'  => true,
										'max_length' => 255);
		$spec['website'] 	= array(	'alias'      => 'Website',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 255);
		$spec['telephone'] 	= array(	'alias'      => 'Telephone',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 50);
		$spec['additional_info'] 	= array(	'alias'      => 'Additional Info',
										'type'       => 'text',
										'mandatory'  => false,
										'max_length' => 255);
		$spec['parent_company'] = array('alias'      => 'Parent Company',
										'type'       => 'integer',
										'mandatory'  => true);
		$spec['category_id'] 	= array('alias'      => 'Category Id ',
										'type'       => 'integer',
										'mandatory'  => false);
		$spec['subcategory_id'] = array('alias'      => 'Subcategory Id ',
										'type'       => 'integer',
										'mandatory'  => true);
		$spec['deleted'] 	= array(	'alias'      => 'Deleted',
										'type'       => 'boolean',
										'mandatory'  => true,
										'max_length' => 50);

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
	 * Sets the sites collection.
	 * @param app_domain_SiteCollection $sites
	 */
	function setSites(app_domain_SiteCollection $sites)
	{
		$this->sites = $sites;
		$this->markDirty();
	}

	/**
	 * Returns the sites collection.
	 * @return app_mapper_Collection the collection of app_domain_Site objects
	 */
	public function getSites()
	{
		return $this->sites;
	}

	/**
	 * TODO - assumes at least one item in collection
	 * Get a site in the collection
	 * @param integer $key item to get from collection
	 */
	public function getSiteAddress($key = null, $format = null)
	{
		if ($this->sites->current())
		{
			return $this->sites->current()->getAddress($format);
		}
		else
		{
			return null;
		}
	}

	/** TODO - assumes at least one item in collection
	 * Get a site in the collection
	 * @param integer $key item to get from collection
	 */
	public function getSiteId($key = null)
	{
		if ($this->sites->current())
		{
			return $this->sites->current()->getId();
		}
		else
		{
			return null;
		}
	}

	/**
	 * Add a site to the sites collection, and set the site's parent company to self.
	 * @param app_domain_Site $site the site to add
	 */
	public function addSite(app_domain_Site $site)
	{
		$this->sites->add($site);
		$site->setCompany($this);
		$this->markDirty();
	}

	/**
	 * Sets the posts collection.
	 * @param app_domain_PostCollection $posts
	 */
	function setPosts(app_domain_PostCollection $posts)
	{

		$this->posts = $posts;
		$this->markDirty();
	}

	/**
	 * Returns the posts collection.
	 * @return app_mapper_Collection the collection of app_domain_Post objects
	 */
	public function getPosts()
	{
		if (!$this->posts_loaded)
		{
			$finder = self::getFinder('app_domain_Post');
			$this->setPosts($finder->findByCompanyId($this->id));
			$this->posts_loaded = true;
		}
		return $this->posts;
	}

	/**
	 * Returns the number of posts at this company
	 * @return app_mapper_Collection the collection of app_domain_Post objects
	 */
	public function getPostCount()
	{
		return self::findPostCount($this->getId());
	}

//	/**
//	 * TODO - assumes at least on item in collection
//	 * Get a site in the collection
//	 * @param integer $key item to get from collection
//	 */
//	public function getPost($key = null)
//	{
//		return $this->posts->current();
//	}

	/**
	 * Sets the tags collection.
	 * @param app_domain_TagCollection $tags
	 */
	function setTags(app_domain_TagCollection $tags)
	{
		$this->tags = $tags;
		$this->markDirty();
	}

	/**
	 * Returns the tags collection.
	 * @return app_mapper_Collection the collection of app_domain_Tag objects
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * Add a tag to the tags collection, and set the tags parent domain object to self.
	 * @param app_domain_Tag $tag the tag to add
	 */
	public function addTag(app_domain_Tag $tag)
	{
		$this->tags->add($tag);
		$tag->setParentDomainObject($this);
		$this->markDirty();
	}

	/**
	 * Sets the characteristics collection.
	 * @param app_domain_CharacteristicCollection $characteristics
	 */
	function setCharacteristics(app_domain_CharacteristicCollection $characteristics)
	{
		$this->characteristics = $characteristics;
		$this->markDirty();
	}

	/**
	 * Returns the characteristics collection.
	 * @return app_domain_CharacteristicCollection the collection of app_domain_Characteristic objects
	 */
	public function getCharacteristics()
	{
		if (!isset($this->characteristics))
		{
			require_once('app/domain/Characteristic.php');
			$this->setCharacteristics(app_domain_Characteristic::findByCompanyId($this->id));
		}
		return $this->characteristics;
	}

	/**
	 * Add a characteristic to the characteristics collection, and set the
	 * characteristic's parent domain object to self.
	 * @param app_domain_Characteristic $characteristic the characteristic to add
	 */
	public function addCharacteristic(app_domain_Characteristic $characteristic)
	{
		$this->characteristics->add($characteristic);
		$tag->setParentDomainObject($this);
		$this->markDirty();
	}

	/**
	 * Set the company name.
	 * @param string $name the company name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/**
	 * Return the company name.
	 * @return string the company name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the company website.
	 * @param string $website the company website
	 */
	public function setWebsite($website)
	{
		$this->website = $website;
		$this->markDirty();
	}

	/**
	 * Return the company website.
	 * @return string the company website
	 */
	public function getWebsite()
	{
		if ((!empty($this->website) || $this->website != '') && substr($this->website, 0, 7) != 'http://')
		{
			return 'http://' . $this->website;
		}
		else
		{
			return $this->website;
		}
	}

	public function setParentCompany($parent_company)
	{
		$this->parent_company_id = $parent_company;
		$this->markDirty();
	}

	public function getParentCompany()
	{
		return $this->parent_company_id;
	}

	/**
	 * Set the company telephone.
	 * @param string $telephone the company telephone
	 */
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
		$this->markDirty();
	}

	/**
	 * Return the company telephone.
	 * @return string the company telephone
	 */
	public function getTelephone()
	{
		return $this->telephone;
	}

	/**
	 * Set the company telephone tps flag.
	 * @param boolean $telephone the company telephone tps flag
	 */
	public function setTelephoneTps($telephone_tps)
	{
		$this->telephone_tps = $telephone_tps;
		$this->markDirty();
	}

	/**
	 * Return the company telephone tps flag.
	 * @return boolean the company telephone tps
	 */
	public function getTelephoneTps()
	{
		return $this->telephone_tps;
	}

	/**
	 * Sets the sites collection.
	 * @param app_domain_SiteCollection $sites
	 */
	function setNotes($notes)
	{
		$this->notes = $notes;
		$this->markDirty();
	}

	/**
	 * Returns the sites collection.
	 * @return app_mapper_Collection the collection of app_domain_Site objects
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * Add a site to the sites collection, and set the site's parent company to self.
	 * @param app_domain_Site $site the site to add
	 */
	public function addNote($note)
	{
		$this->notes[] = ($note);
		$this->markDirty();
	}

	   /**
     * Find companies whose names start with the query string
     * @param string $name query string
     * @return app_mapper_CompanyCollection collection of app_domain_Company objects
     */
    public static function doFindByName($name)
    {
        $finder = self::getFinder(__CLASS__);
        return $finder->doFindByName($name);
    }


	/**
	 * Find companies whose names start with the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByNameStart($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByNameStart($name);
	}


	/**
	 * Find companies whose names start with the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByNameListStart($name_list)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByNameListStart($name_list);
	}


	/**
	 * Find companies whose names include the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByNameIncludes($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByNameIncludes($name);
	}

	/**
	 * Find companies whose name is equal to the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByNameEqual($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByNameEqual($name);
	}

 	/** Find all initiative records for a given company name starting with $name.
	 * @param string $name - company name being searched
	 * @param integer $initiative_id - initiative being search
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByNameStartAndInitiativeId($name, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByNameStartAndInitiativeId($name, $initiative_id);
	}

 	/**
 	 * Find companies whose telephone starts with the query string
	 * @param string $telephone query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByTelephoneStart($telephone)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByTelephoneStart($telephone);
	}

	 	/**
 	 * Find companies whose telephone includes with the query string
	 * @param string $telephone query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByTelephoneIncludes($telephone)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByTelephoneIncludes($telephone);
	}

	 	/**
 	 * Find companies whose telephone equals the query string
	 * @param string $telephone query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByTelephoneEqual($telephone)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByTelephoneEqual($telephone);
	}

	/** Find companies whose postcode starts with the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByPostcodeStart($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostcodeStart($name);
	}

 	/**
 	 * Find companies whose postcode includes the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByPostcodeIncludes($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostcodeIncludes($name);
	}

 	/**
 	 * Find companies whose postcode equals the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByPostcodeEqual($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostcodeEqual($name);
	}




 	/**
 	 * Find companies whose brand includes the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByBrandIncludes($brand)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByBrandIncludes($brand);
	}

	/**
	 * Find all companies
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * Find all companies limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findSet($limit = 15, $offset = 0)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findSet($limit, $offset);
	}

	/**
	 * Find a company by a given ID
	 * @param integer $id company ID
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * Find all posts/contacts for a company by a given id ordered by job_title
	 * @param integer $id company ID
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findPostsOrderByJobTitle($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostsOrderByJobTitle($id);
	}

	/**
	 * Find all posts/contacts for a company by a given id ordered by first_name
	 * @param integer $id company ID
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findPostsOrderByFirstName($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostsOrderByFirstName($id);
	}

	/**
	 * Find count of all posts/contacts for a company by a given id
	 * @param integer $id company id
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findPostCount($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findPostCount($id);
	}

	/**
	 * Find all posts/contacts for a company by a given id ordered by first_name
	 * @param integer $id company ID
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findCompanyPostInitiatives($id, $initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCompanyPostInitiatives($id, $initiative_id);
	}

	/**
	 * Find all client initiatives for a company ordered by client/initiative
	 * @param integer $id company ID
	 * @return associative array of raw mapper info
	 */
	public static function findCompanyClientInitiatives($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCompanyClientInitiatives($id);
	}

	/**
	 * Find all companies by a custom sql where clause
	 * @param string $where_clause custom sql where clause
	 * @return mapper company collection
	 */
	public static function findCompanyByCustomWhereClause($where_clause)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCompanyByCustomWhereClause($where_clause);
	}

	/**
	 * Find the notes for a given company.
	 * @param integer $company_id
	 * @return array
	 */
	public static function findNotes($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findNotes($company_id);
	}

	/**
	 * Set the company additional information.
	 * @param string $name the company additional information.
	 */
	public function setAdditionalInfo($additionalInformation)
	{
		$this->additional_info = $additionalInformation;
		$this->markDirty();
	}

	/**
	 * Return the company additional information.
	 * @return string the company additional information.
	 */
	public function getAdditionalInfo()
	{
		return $this->additional_info;
	}


}

?>