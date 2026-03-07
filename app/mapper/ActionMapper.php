<?php

/**
 * Defines the app_mapper_ActionMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/Mapper.php');

/**
 * @package Alchemis
 */
class app_mapper_ActionMapper extends app_mapper_Mapper implements app_domain_ActionFinder
{
	protected static $DB;

	public function __construct() 
	{
		if (!self :: $DB) 
		{
			self :: $DB = app_controller_ApplicationHelper :: instance()->DB();
		}
	}
	
	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Action($array['id']);
		$obj->setSubject($array['subject']);
		$obj->setNotes($array['notes']);
		$obj->setDueDate($array['due_date']);
		$obj->setReminderDate($array['reminder_date']);
		$obj->setCompletedDate($array['completed_date']);
		$obj->setUserId($array['user_id']);
		$obj->setPostInitiativeId($array['post_initiative_id']);
		$obj->setMeetingId($array['meeting_id']);
		$obj->setInformationRequestId($array['information_request_id']);
		$obj->setCommunicationId($array['communication_id']);
		$obj->setTypeId($array['type_id']);
		$obj->setCommunicationTypeId($array['communication_type_id']);
		$obj->setCommunicationType($array['communication_type']);
		$obj->setActionedByClient($array['actioned_by_client']);
		
		// get object resources
		$obj->setResourceIds(self::findResourceIds($array['id']));
		$obj->setResources(self::findResources($array['id']));
		
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_actions');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_actions ' .
					'(id, subject, notes, due_date, reminder_date, completed_date, user_id, actioned_by_client, ' .
					'post_initiative_id, meeting_id, information_request_id, communication_id, type_id, communication_type_id, created_at) ' .
					'VALUES (' . 
					self::$DB->quote($object->getId(), 'integer') . ', ' .
					self::$DB->quote($object->getSubject(), 'text') . ', ' .
					self::$DB->quote($object->getNotes(), 'text') . ', ' .
					self::$DB->quote($object->getDueDate(), 'timestamp') . ', ' .
					self::$DB->quote($object->getReminderDate(), 'timestamp') . ', ' .
					self::$DB->quote($object->getCompletedDate(), 'timestamp') . ', ' .
					self::$DB->quote($object->getUserId(), 'integer') . ', ' .
					self::$DB->quote($object->getActionedByClient(), 'boolean') . ', ' .
					self::$DB->quote($object->getPostInitiativeId(), 'integer') . ', ' .
					self::$DB->quote($object->getMeetingId(), 'integer') . ', ' .
					self::$DB->quote($object->getInformationRequestId(), 'integer') . ', ' .
					self::$DB->quote($object->getCommunicationId(), 'integer') . ', ' .
					self::$DB->quote($object->getTypeId(), 'integer') . ', ' .
					self::$DB->quote($object->getCommunicationTypeId(), 'integer') . ', ' .
					self::$DB->quote(date('Y-m-d H:i:s'), 'timestamp'). ')';
		self::$DB->query($query);

		// save any associated resources
		$resources = $object->getResourceIds(); 
		if (count($resources) > 0)
		{
			// delete any existing records
			$query = 'DELETE FROM tbl_action_resources WHERE action_id = ' .self::$DB->quote($object->getId(), 'integer');
			self::$DB->query($query);

			// insert new entries
			foreach ($resources as $resource)	
			{
				$query = 'INSERT INTO tbl_action_resources ' .
					'(action_id, resource_id) ' .
					'VALUES (' . 
					self::$DB->quote($object->getId(), 'integer') . ', ' .
					self::$DB->quote($resource['resource_id'], 'integer') . ')';
				self::$DB->query($query);
			}
		}
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_actions SET ' .
					'subject = ' . self::$DB->quote($object->getSubject(), 'text') . ', ' .
					'notes = ' . self::$DB->quote($object->getNotes(), 'text') . ', ' .
					'due_date = ' . self::$DB->quote($object->getDueDate(), 'timestamp') . ', ' .
					'reminder_date = ' . self::$DB->quote($object->getReminderDate(), 'timestamp') . ', ' .
					'completed_date = ' . self::$DB->quote($object->getCompletedDate(), 'timestamp') . ', ' .
					'user_id = ' . self::$DB->quote($object->getUserId(), 'integer') . ', ' .
					'actioned_by_client = ' . self::$DB->quote($object->getActionedByClient(), 'boolean') . ', ' .
					'post_initiative_id = ' . self::$DB->quote($object->getPostInitiativeId(), 'integer') . ', ' .
					'meeting_id = ' . self::$DB->quote($object->getMeetingId(), 'integer') . ', ' .
					'information_request_id = ' . self::$DB->quote($object->getInformationRequestId(), 'integer') . ', ' .
					'communication_id = ' . self::$DB->quote($object->getCommunicationId(), 'integer') . ', ' .
					'type_id = ' . self::$DB->quote($object->getTypeId(), 'integer') . ', ' .
					'communication_type_id = ' . self::$DB->quote($object->getCommunicationTypeId(), 'integer') . ' ' .
					'WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);
		
		// save any associated resources
		$resources = $object->getResourceIds();
		if (count($resources) > 0)
		{
			// delete any existing records
			$query = 'DELETE FROM tbl_action_resources WHERE action_id = ' .self::$DB->quote($object->getId(), 'integer');
			self::$DB->query($query);
			
			// insert new entries
			foreach ($resources as $resource)	
			{
				$query = 'INSERT INTO tbl_action_resources ' .
					'(action_id, resource_id) ' .
					'VALUES (' . 
					self::$DB->quote($object->getId(), 'integer') . ', ' .
					self::$DB->quote($resource['resource_id'], 'integer') . ')';
				self::$DB->query($query);
			}
		}
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_actions WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);
	}

	/**
	 * Find the resource ids for a given action.
	 * @param integer $id contact ID
	 * @return array
	 */
	public function findResourceIds($id)
	{
		$query = 'SELECT id, resource_id FROM tbl_action_resources WHERE action_id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $result;
	}
	
	/**
	 * Find the resources for a given action.
	 * @param integer $id contact ID
	 * @return array
	 */
	public function findResources($id)
	{
		$query = 'SELECT lkp_art.description AS resource ' .
				'FROM tbl_action_resources ar ' .
				'JOIN tbl_lkp_action_resource_types lkp_art ON lkp_art.id = ar.resource_id ' .
				'WHERE ar.action_id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $result;
	}	
	/**
	 * Find the given action.
	 * @param integer $id contact ID
	 * @return app_domain_Contact
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT a.*, lkp_act.description as communication_type ' .
				'FROM tbl_actions a ' .
				'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
				'WHERE a.id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/**
	 * Find all actions.
	 * @return app_mapper_ActionCollection collection of app_domain_Event objects
	 */
	public function findAll()
	{
		$query = 'SELECT * FROM tbl_actions ORDER BY due_date ASC';
		$result = self::$DB->query($query);
		return new app_mapper_EventCollection($result, $this);
	}

	/**
	 * Finds count of objects with id.
	 * @return boolean
	 */
	public function lookupExistsInDb($id)
	{
		$query = 'SELECT count(id) ' .
				'FROM tbl_actions ' .
				'WHERE id = ' . self::$DB->quote($id, 'integer');
		return self::$DB->queryOne($query);
	}
	
	
	
 	/**
 	 * Find the actions owned by a given user.
	 * @param integer $user_id user ID
	 * @param integer $limit
	 * @return app_mapper_ActionCollection collection of app_domain_Event objects
	 */
	public function findByUserId($user_id, $limit = null)
	{
		$query = 'SELECT a.*, IF(due_date < NOW(), -1, 0) AS compare, lkp_act.description as communication_type ' .
				'FROM tbl_actions a ' .
				'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'WHERE a.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'ORDER BY a.due_date ASC';
		if (!is_null($limit))
		{
			$query .= ' LIMIT ' . self::$DB->quote($limit, 'integer');
		}
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}
		
 	/**
 	 * Find the current actions (ie not completed) owned by a given user.
	 * @param integer $user_id user ID
	 * @param integer $limit
	 * @return app_mapper_ActionCollection collection of app_domain_Event objects
	 */
	public function findCurrentByUserId($user_id, $limit = null)
	{
		$query = 'SELECT a.*, IF(due_date < NOW(), -1, 0) AS compare, lkp_act.description as communication_type ' .
				'FROM tbl_actions a ' .
				'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'WHERE a.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND a.completed_date is null ' .
					'ORDER BY due_date ASC';
		if (!is_null($limit))
		{
			$query .= ' LIMIT ' . self::$DB->quote($limit, 'integer');
		}
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}
	
	/**
 	 * Find the actions associated with a given client.
	 * @param integer $client_id client ID
	 * @param integer $limit
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findByClientId($user_id, $limit = null)
	{
		$query = 'SELECT a.*, IF(due_date < NOW(), -1, 0) AS compare, lkp_act.description as communication_type ' .
				'FROM tbl_actions a ' .
				'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
				'WHERE a.client_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'ORDER BY a.due_date ASC';
		if (!is_null($limit))
		{
			$query .= ' LIMIT ' . self::$DB->quote($limit, 'integer');
		}
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}

	/**
 	 * Find the actions associated with a post initiative id.
	 * @param integer $post_initiative_id meeting id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findByPostInitiativeId($post_initiative_id)
	{
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare, ' .
					'IF(completed_date is null, -1, 0) AS completed, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
					'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'ORDER BY completed, compare asc, a.due_date ASC';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}

	/**
 	 * Find the current (ie not completed) actions associated with a post initiative id.
	 * @param integer $post_initiative_id meeting id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findCurrentByPostInitiativeId($post_initiative_id)
	{
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare,  ' .
					'IF(completed_date is null, -1, 0) AS completed, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
					'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND completed_date is null ' .
					'ORDER BY completed, compare asc, a.due_date ASC';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}
	
	/**
 	 * Find the actions associated with a post initiative id.
	 * @param integer $post_initiative_id meeting id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare, ' .
					'IF(completed_date is null, -1, 0) AS completed, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
					'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND a.type_id = ' . self::$DB->quote($type_id, 'integer') . ' ' .
					'ORDER BY completed, compare asc, a.due_date ASC';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}

 	/** Find the current (ie not completed) actions associated with a post initiative id.
	 * @param integer $post_initiative_id meeting id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findCurrentByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare, ' .
					'IF(completed_date is null, -1, 0) AS completed, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
					'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND a.type_id = ' . self::$DB->quote($type_id, 'integer') . ' ' .
					'AND a.completed_date is null ' .
					'ORDER BY completed, compare asc, a.due_date ASC';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}
	
	/**
 	 * Find the actions associated with a given post initiative id for a particular set of type ids.
	 * @param integer $post_initiative_id meeting id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids)
	{
		$id_string = implode(",", $type_ids); 
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare, ' .
					'IF(completed_date is null, -1, 0) AS completed, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
					'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND a.type_id in (' . $id_string . ') ' .
					'ORDER BY completed, compare asc, a.due_date ASC';
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}

	/** Find the current actions associated with a given post initiative id for a particular set of type ids.
	 * @param integer $post_initiative_id meeting id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findCurrentByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids)
	{
		$id_string = implode(",", $type_ids); 
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare, ' .
					'IF(completed_date is null, -1, 0) AS completed, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
				'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' . 
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND a.type_id in (' . $id_string . ') ' .
					'AND completed_date is null ' .
					'ORDER BY completed, compare asc, a.due_date ASC';
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}

	/**
 	 * Find the actions associated with a given meeting.
	 * @param integer $meeting_id meeting ID
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findByMeetingId($meeting_id)
	{
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
				'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.meeting_id = ' . self::$DB->quote($meeting_id, 'integer') . ' ' .
					'ORDER BY compare desc, a.due_date ASC';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}
		
	/**
 	 * Find the actions associated with a given communication id and type id.
	 * @param integer $communication_id communication id
	 * @param integer $type_id type_id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findByCommunicationIdAndTypeId($communication_id, $type_id)
	{
		$query = 'SELECT a.*, lkp_at.description as action_type, ' .
					'IF(due_date < NOW(), -1, 0) AS compare, lkp_act.description as communication_type ' .
					'FROM tbl_actions a ' .
				'LEFT JOIN tbl_lkp_action_communication_types lkp_act ON a.communication_type_id = lkp_act.id ' .
					'LEFT JOIN tbl_lkp_action_types lkp_at ON lkp_at.id = a.type_id	' .
					'WHERE a.communication_id = ' . self::$DB->quote($communication_id, 'integer') . ' ' .
					'AND a.type_id = ' . self::$DB->quote($type_id, 'integer') . ' ' .
					'ORDER BY compare desc, a.due_date ASC';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_ActionCollection($result, $this);
	}
		

	/**
 	 * Find the current actions associated with a given post_initiitive_id.
	 * @param integer $post_initiative_id post initiative id
	 * @return single item
	 */
	public function findCurrentCountByPostInitiativeId($post_initiative_id)
	{
		$query = 'SELECT count(id) ' .
					'FROM tbl_actions ' .
					'WHERE post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
//					'AND due_date > NOW() ' .
					'AND completed_date is null';
//		echo $query;
		return self::$DB->queryOne($query);
	}

	/**
 	 * Find the overdue actions associated with a given post_initiitive_id.
	 * @param integer $post_initiative_id post initiative id
	 * @return single item
	 */
	public function findOverdueCountByPostInitiativeId($post_initiative_id)
	{
		$query = 'SELECT count(id) ' .
					'FROM tbl_actions ' .
					'WHERE post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND due_date < NOW() ' .
					'AND completed_date is null';
//		echo $query;
		return self::$DB->queryOne($query);
	}
		
	/** Find the count of actions associated with a given post_initiative_id for a particulat type id.
	 * @param integer $post_initiative_id post initiative id
	 * @param integer $type_id 
	 * @return single item
	 */
	public function findCurrentCountByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		$query = 'SELECT count(id) ' .
					'FROM tbl_actions ' .
					'WHERE post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND type_id = ' . self::$DB->quote($type_id, 'integer') . ' ' .
//					'AND due_date > NOW() ' .
					'AND completed_date is null';
//		echo $query;
		return self::$DB->queryOne($query);
	}
		
	/** Find the count of current actions associated with a given post initiative id for a particular set of type ids.
	 * @param integer $post_initiative_id meeting id
	 * @return app_mapper_ActionCollection collection of app_domain_Action objects
	 */
	public function findCurrentCountByPostInitiativeIdAndMultipleTypeIds($post_initiative_id, $type_ids)
	{
		$id_string = implode(",", $type_ids); 
		$query = 'SELECT count(id) ' .
					'FROM tbl_actions ' .
					'WHERE post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
					'AND type_id in (' . $id_string . ') ' .
					'AND completed_date is null';
		return self::$DB->queryOne($query);
	}
	
		
	/**
	 * Find action types lookup information
	 * @return array
	 */
	public function findActionTypesAll()
	{
		$query = 'select id, description from tbl_lkp_action_types order by sort_order'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find action types by id
	 * @return array
	 */
	public function findActionTypeById($id)
	{
		$query = 'select description from tbl_lkp_action_types ' .
				'WHERE id = ' . self::$DB->quote($id, 'integer'); 
		return self::$DB->queryOne($query);
	}
	
	
	/**
	 * Find action communication types lookup information
	 * @return array
	 */
	public function findActionCommunicationTypesAll()
	{
		$query = 'select id, description from tbl_lkp_action_communication_types order by sort_order'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
	/**
	 * Find action resource types lookup information
	 * @return array
	 */
	public function findActionResourceTypesAll()
	{
		$query = 'select id, description from tbl_lkp_action_resource_types order by sort_order'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
	
	
}

?>