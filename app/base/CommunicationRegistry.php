<?php

/**
 * Defines the app_base_CommunicationRegistry classes. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');

/**
 * Uses the $_SESSION superglobal to set and retrieve values. We kick of the session with the 
 * session_start() method. As always with sessions, you must ensure that you have not yet sent any 
 * text to the user before using this class.
 * @package Alchemis
 */
class app_base_CommunicationRegistry extends app_base_SessionRegistry
{
	/**
	 * Sets the information request collection. An Exception is thrown if a 
	 * collection already exists.
	 * @param app_domain_InformationRequest $collection
	 * @throws Exception
	 */
	public function setInformationRequest(app_domain_InformationRequest $collection)
	{
		if ($this->countInformationRequests() > 0)
		{
			throw new Exception('Attempting to overwrite an existing Information Request Collection');
		}
		return self::instance()->set('app_mapper_InformationRequestCollection', $collection);
	}

	/**
	 * Adds an information request to the collection.
	 * @param app_domain_InformationRequest $info_req
	 */
	public function addInformationRequest(app_domain_InformationRequest $info_req)
	{
		if ($collection = self::instance()->get('app_mapper_InformationRequestCollection'))
		{
			return $collection->add($info_req);
		}
		else
		{
			$collection = new app_domain_InformationRequestCollection();
			$collection->add($info_req);
			return self::instance()->set('app_mapper_InformationRequestCollection', $collection);
		}
	}

	/**
	 * Returns the information request collection.
	 * @return app_domain_InformationRequestCollection
	 */
	public function getInformationRequests()
	{
		return self::instance()->get('app_mapper_InformationRequestCollection');
	}

	/**
	 * Clears the information request collection.
	 */
	public function clearInformationRequests()
	{
		unset(self::instance()->get('app_mapper_InformationRequestCollection'));
	}

	/**
	 * Gets the number of information request objects in the collection.
	 * @return integer
	 */
	public function countInformationRequests()
	{
		if ($collection = self::instance()->get('app_mapper_InformationRequestCollection'))
		{
			return $collection->count();
		}
		return 0;
	}

	/**
	 * Sets the information request collection. An Exception is thrown if a 
	 * collection already exists.
	 * @param app_domain_MeetingCollection $collection
	 * @throws Exception
	 */
	public function setMeeting(app_domain_MeetingCollection $collection)
	{
		if ($this->countMeetings() > 0)
		{
			throw new Exception('Attempting to overwrite an existing Meeting Collection');
		}
		return self::instance()->set('app_mapper_MeetingCollection', $collection);
	}

	/**
	 * Adds a meeting to the collection.
	 * @param app_domain_Meeting $meeting
	 * @throws Exception
	 */
	public function addMeeting(app_domain_Meeting $meeting)
	{
		if ($collection = self::instance()->get('app_mapper_MeetingCollection'))
		{
			return $collection->add($meeting);
		}
		else
		{
			$collection = new app_mapper_MeetingCollection();
			$collection->add($meeting);
			return self::instance()->set('app_mapper_MeetingCollection', $collection);
		}
	}

	/**
	 * Returns the meeting collection.
	 * @return app_mapper_MeetingCollection
	 */
	public function getMeetings()
	{
		return self::instance()->get('app_mapper_MeetingCollection');
	}

	/**
	 * Clears the meeting collection.
	 */
	public function clearMeetings()
	{
		unset(self::instance()->get('app_mapper_MeetingCollection'));
	}

	/**
	 * Gets the number of meeting objects in the collection.
	 * @return integer
	 */
	public function countMeetings()
	{
		if ($collection = self::instance()->get('app_mapper_MeetingCollection'))
		{
			return $collection->count();
		}
		return 0;
	}


}

?>