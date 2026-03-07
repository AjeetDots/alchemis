<?php

/**
 * Defines the app_domain_Event class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_Event extends app_domain_DomainObject
{
	protected $subject;
	protected $notes;
	protected $date;
	protected $reminder_date;
	protected $user_id;
	protected $type_id;
	protected $client_id;
	protected $day_part;

	/**
	 * @param integer $id
	 * @param string $name
	 */
	public function __construct($id = null, $name = null)
	{
		parent::__construct($id);
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
		$spec['subject']  = array('alias'      => 'Subject',
		                          'type'       => 'text',
		                          'mandatory'  => true,
		                          'max_length' => 100);

		$spec['notes']    = array('alias'      => 'Notes',
                                  'type'       => 'text',
                                  'mandatory'  => false);

		$spec['date']     = array('alias'      => 'Date',
                                  'type'       => 'date',
                                  'mandatory'  => true);

		$spec['reminder_date'] = array('alias'      => 'Reminder Date',
                                       'type'       => 'date',
                                       'mandatory'  => false);

		$spec['day_part'] = array('alias'      => 'Day Part',
                                       'type'       => 'number',
                                       'mandatory'  => true);

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
	 * Set the subject.
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = trim($subject);
		$this->markDirty();
	}

	/**
	 * Return the subject.
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Set the notes.
	 * @param string $notes
	 */
	public function setNotes($notes)
	{
		$this->notes = $notes;
		$this->markDirty();
	}

	/**
	 * Return the notes.
	 * @return string
	 */
	public function getNotes()
	{
		if (is_null($this->notes) || strlen(trim($this->notes)) == 0)
		{
			$this->notes = ' ';
		}
		return $this->notes;
	}

	/**
	 * Set the due date.
	 * @param string $date the contact's forename
	 */
	public function setDate($date)
	{
		$this->date = $date;
		$this->markDirty();
	}

	/**
	 * Return the due date.
	 * @return string
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * Return the due date.
	 * @return string
	 */
	public function isOverdue()
	{
		return ($this->date < date('Y-m-d H:i:s'));
	}

	/**
	 * Set the reminder date.
	 * @param string $reminder_date
	 */
	public function setReminderDate($reminder_date)
	{
		$this->reminder_date = $reminder_date;
		$this->markDirty();
	}

	/**
	 * Return the reminder date.
	 * @return string
	 */
	public function getReminderDate()
	{
		return $this->reminder_date;
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
	 * Return the owner user.
	 * @return app_domain_User
	 */
	public function getUser()
	{
		require_once('app/domain/RbacUser.php');
		return app_domain_RbacUser::find($this->user_id);
	}

	/**
	 * Set the ID of the event type.
	 * @param integer $user_id
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		$this->markDirty();
	}

	/**
	 * Return the ID of the event type.
	 * @return integer
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}

	/**
	 * Return the event type.
	 * @return string
	 */
	public function getType()
	{
		$type = self::lookupTypeById($this->type_id);
		return $type['name'];
	}

	/**
	 * Set the ID of the client.
	 * @param integer $client_id
	 */
	public function setClientId($client_id)
	{
		$this->client_id = $client_id;
		$this->markDirty();
	}

	/**
	 * Return the ID of the client.
	 * @return integer
	 */
	public function getClientId()
	{
		return $this->client_id;
	}

	/**
	 * Return the client.
	 * @return app_domain_Client
	 */
	public function getClient()
	{
		require_once('app/domain/Client.php');
		return app_domain_Client::find($this->client_id);
	}

    /**
     * Set the day part value (ie how much of a day is used by this event - a value > 0 and <= 1.0).
     * @param string $day_part the day part value
     */
    public function setDayPart($day_part)
    {
        $this->day_part = $day_part;
        $this->markDirty();
    }

    /**
     * Return the day_part
     * @return decimal
     */
    public function getDayPart()
    {
        return $this->day_part;
    }


	/**
	 *
	 * @return app_mapper_EventMapper
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
 	 * Find the events owned by a given user.
	 * @param integer $user_id user ID
	 * @param integer $limit
	 * @return app_mapper_EventCollection collection of app_domain_Event objects
	 */
	public static function findByUserId($user_id, $limit = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByUserId($user_id, $limit);
	}

	/**
 	 * Count each type of event for a given user in a month.
	 * @param integer $user_id user ID
	 * @param string $year_month in the format 'YYYYMM'
	 * @return array
	 * @see countByUserIdAndDates()
	 */
	public static function countByUserIdYearMonth($user_id, $year_month)
	{
		$year  = substr($year_month, 0, 4);
		$month = substr($year_month, 4, 2);
		$start = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
		$end   = date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));
		return self::countByUserIdAndDates($user_id, $start, $end);
	}

	/**
 	 * Count each type of event for a given user between two dates.
	 * @param integer $user_id user ID
	 * @param string $start in the format 'YYYY-MM-DD'
	 * @param string $end in the format 'YYYY-MM-DD'
	 * @return array
	 */
	public static function countByUserIdAndDates($user_id, $start, $end)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countByUserIdAndDates($user_id, $start, $end);
	}

	/**
 	 * Find the events associated with a given client.
	 * @param integer $client_id client ID
	 * @param integer $limit
	 * @return app_mapper_EventCollection collection of app_domain_Event objects
	 */
	public static function findByClientId($client_id, $limit = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByClientId($client_id, $limit);
	}

	/**
	 * Lookup possible event types.
	 * @return array
	 */
	public static function lookupTypes()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupTypes();
	}

	/**
	 * Lookup event type by ID.
	 * @param integer $type_id
	 * @return array
	 */
	public static function lookupTypeById($type_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupTypeById($type_id);
	}

	/**
	 * Lookup possible event types.
	 * @return array
	 */
	public static function lookupTypesForDropdown()
	{
		$types = self::lookupTypes();
		foreach ($types as $type)
		{
			$res[$type['id']] = $type['name'];
		}
		return $res;
	}

}

?>