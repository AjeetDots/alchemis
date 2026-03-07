<?php

/**
 * Defines the app_domain_Contact class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Contact extends app_domain_DomainObject
{
	protected $title;
	protected $post_id;
	protected $first_name;
	protected $surname;
	protected $telephone_mobile;
	protected $email;
	protected $linked_in;
	protected $deleted;

	const CONTACT_ORDER_FORENAME_SURNAME = 0;
	const CONTACT_ORDER_SURNAME_FORENAME = 1;
	const CONTACT_ORDER_TITLE_FORENAME_SURNAME = 2;

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

	public static function getFieldSpec($field = null)
	{
		$spec = array();
		$spec['title']					= array(	'alias'      => 'Title',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 25);
		$spec['first_name']				= array(	'alias'      => 'First Name',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 50);
		$spec['surname']	 			= array(	'alias'      => 'Surname',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 50);
		$spec['telephone_mobile']		= array(	'alias'      => 'Mobile',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 50);
		$spec['email'] 					= array(	'alias'      => 'Email',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 100);
		$spec['linked_in']  			= array(	'alias'      => 'Linked In',
													'type'       => 'text',
													'mandatory'  => false,
													'max_length' => 255);

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
	 * Set the contact's post id.
	 * @param string $post_id the contact's post id
	 */
	public function setPostId($post_id)
	{
		$this->post_id = $post_id;
		$this->markDirty();
	}

	/**
	 * Return the contact's title.
	 * @return string the contact's title
	 */
	public function getPostId()
	{

		return $this->post_id;
	}

	/**
	 * Set the contact's title.
	 * @param string $title the contact's title
	 */
	public function setTitle($title)
	{
		$title = trim($title);
		$this->title = $title;
		$this->markDirty();
	}

	/**
	 * Return the contact's title.
	 * @return string the contact's title
	 */
	public function getTitle()
	{

		return $this->title;
	}

	/**
	 * Set the contact's forename.
	 * @param string $forename the contact's forename
	 */
	public function setFirstName($first_name)
	{
		$first_name = trim($first_name);
		$this->first_name = $first_name;
		$this->markDirty();
	}

	/**
	 * Return the contact's first name.
	 * @return string the contact's first name
	 */
	public function getFirstName()
	{
		return $this->first_name;
	}

	/**
	 * Set the contact's surname.
	 * @param string $surname the contact's surname
	 */
	public function setSurname($surname)
	{
		$surname = trim($surname);
		$this->surname = $surname;
		$this->markDirty();
	}

	/**
	 * Return the contact's surname.
	 * @return string the contact's surname
	 */
	public function getSurname()
	{
		return $this->surname;
	}

//
//	/**
//	 * Set the contact's telephone.
//	 * @param string $telephone the contact's telephone
//	 */
//	public function setTelephone($telephone)
//	{
//		$telephone = trim($telephone);
//		$this->telephone = $telephone;
//		$this->markDirty();
//	}
//
//	/**
//	 * Return the contact's telephone.
//	 * @return string the contact's telephone
//	 */
//	public function getTelephone()
//	{
//
//		return $this->telephone;
//	}
//
//	/**
//	 * Set the contact's telephone_1.
//	 * @param string $telephone_1 the contact's telephone_1
//	 */
//	public function setTelephone1($telephone_1)
//	{
//		$telephone_1 = trim($telephone_1);
//		$this->telephone_1 = $telephone_1;
//		$this->markDirty();
//	}
//
//	/**
//	 * Return the contact's telephone_1.
//	 * @return string the contact's telephone_1
//	 */
//	public function getTelephone1()
//	{
//
//		return $this->telephone_1;
//	}


	/**
	 * Set the contact's telephone_mobile.
	 * @param string $telephone_mobile the contact's telephone_mobile
	 */
	public function setTelephoneMobile($telephone_mobile)
	{
		$telephone_mobile = trim($telephone_mobile);
		$this->telephone_mobile = $telephone_mobile;
		$this->markDirty();
	}

	/**
	 * Return the contact's telephone_mobile.
	 * @return string the contact's telephone_mobile
	 */
	public function getTelephoneMobile()
	{

		return $this->telephone_mobile;
	}


	/**
	 * Set the contact's email.
	 * @param string $email the contact's email
	 */
	public function setEmail($email)
	{
		$email = trim($email);
		$this->email = $email;
		$this->markDirty();
	}

	/**
	 * Return the contact's email.
	 * @return string the contact's email
	 */
	public function getEmail()
	{

		return $this->email;
	}


	/**
	* Set the contact's linked in profile.
	* @param string $email the contact's linked in profile
	*/
	public function setLinkedIn($linked_in)
	{
	$linked_in = trim($linked_in);

// 	if (!is_numeric($linked_in) && $linked_in != '') {
// 		$pattern = '/id=[0-9]*/';
// 		preg_match_all($pattern, $linked_in, $idMatches, PREG_PATTERN_ORDER);

// 		// assume that the linkedIn id will always be the first id in the query string
// 		$linked_in = substr($idMatches[0][0], 3);

// 		if (!$linked_in) {
// 			$linked_in = 'Invalid format: copied LinkedIn URL or id numeric code required';
// 		}
// 	}
	$this->linked_in = $linked_in;
	$this->markDirty();
	}

	/**
	* Return the contact's linked in profile.
	* @return string the contact's linked in profile.
	*/
	public function getLinkedIn()
	{

		return $this->linked_in;
	}


	/**
	 * Set the contact's deleted flag.
	 * @param string $deleted the contact's deleted flag
	 */
	public function setDeleted($deleted)
	{
		$this->deleted = $deleted;
		$this->markDirty();
	}

	/**
	 * Return the contact's deleted flag..
	 * @return string the contact's deleted flag.
	 */
	public function getDeleted()
	{

		return $this->deleted;
	}

	/**
	 * Return the contact's full name.
	 * @param integer $order the ordering of name. Default is 'forename surname'.
	 * @return string the contact's full name
	 */
	public function getName($order = self::CONTACT_ORDER_FORENAME_SURNAME)
	{
		switch ($order)
		{
			case self::CONTACT_ORDER_SURNAME_FORENAME:
				$name = $this->surname . ', ' . $this->first_name;
				break;

			case self::CONTACT_ORDER_TITLE_FORENAME_SURNAME:
				$name = $this->title . ' ' . $this->first_name . ' ' . $this->surname;
				break;

			case self::CONTACT_ORDER_FORENAME_SURNAME:
			default:
				$name = $this->first_name . ' ' . $this->surname;
				break;
		}

		// Trim and remove double spaces
		return str_replace('  ', ' ', trim($name));
	}

	/**
	 *
	 * @return app_mapper_ContactMapper
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
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
	 * @param integer $post_id
	 * @return app_mapper_ContactMapper
	 */
	public static function findByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPost($post_id);
	}

	/**
	 * @param integer $post_id
	 * @return app_mapper_ContactMapper
	 */
	public static function findCurrentByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findCurrentByPostId($post_id);
	}

	/**
	 * Find contacts where surnames start with the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByContactSurnameStart($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByContactSurnameStart($name);
	}


	/**
	 * Find contacts where full names start with the query string
	 * @param string $name query string
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public static function findByContactFullNameStart($name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByContactFullNameStart($name);
	}

    /**
     * Find contact by email address
     *
     * @param string $email Email address
     *
     * @return app_domain_Contact
     */
    public static function findByContactEmail($email)
    {
		$finder = self::getFinder(__CLASS__);
		return $finder->findByContactEmail($email);
    }
}

?>