<?php

/**
 * Defines the app_domain_Message class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Message extends app_domain_DomainObject
{
	protected $timestamp;
	protected $user_id;
	protected $message;
	protected $published;

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
		$spec['message'] = array('alias'      => 'Message',
		                         'type'       => 'text',
		                         'mandatory'  => true,
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
	 * Set the reminder date.
	 * @param string $reminder_date
	 */
	public function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
		$this->markDirty();
	}

	/**
	 * Return the reminder date.
	 * @return string
	 */
	public function getTimestamp()
	{
		if (empty($this->timestamp))
		{
			$this->timestamp = date('Y-m-d H:i:s');
		}
		return $this->timestamp;
	}

	/**
	 * Set the ID of the owner user.
	 * @param integer $user_id
	 */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;
		$this->markDirty();
	}
	
	/**
	 * Return the ID of the owner user.
	 * @return integer
	 */
	public function getUserId()
	{
		return $this->user_id;
	}
	
	/**
	 * Set the ID of the owner user.
	 * @param integer $user_id
	 */
	public function setMessage($message)
	{
		$this->message = $message;
		$this->markDirty();
	}
	
	/**
	 * Return the ID of the owner user.
	 * @return integer
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Set whether the message is been published.
	 * @param boolean $published
	 */
	public function setPublished($published)
	{
		$this->published = $published;
		$this->markDirty();
	}

	/**
	 * Mark the messaged completed.
	 */
	public function publish()
	{
		$this->setPublished(true);
	}
	
	/**
	 * Mark the messaged completed.
	 */
	public function unpublish()
	{
		$this->setPublished(false);
	}

	/**
	 * Returns whether the message is marked as published.
	 * @return boolean
	 */
	public function isPublished()
	{
		return $this->published;
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
	 * Find all messages limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_MessageCollection collection of app_domain_Message objects
	 */
	public static function findSet($limit = 3, $offset = 0)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findSet($limit, $offset);
	}

}

?>