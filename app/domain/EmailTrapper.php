<?php

/**
 * Defines the app_domain_EmailTrapper class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
class app_domain_EmailTrapper extends app_domain_DomainObject
{
	
	// protected $updated_at;
		
	function __construct($id = null)
	{
		parent::__construct($id);
	}
	
// 	/**
// 	 * Set the client_initiative_id.
// 	 * @param string $client_initiative_id of the mailer
// 	 */
// 	public function setClientInitiativeId($client_initiative_id)
// 	{
// 		$this->client_initiative_id = $client_initiative_id;
// 		$this->markDirty();
// 	}
	
	
// 	/**
// 	 * Get the client_initiative_id.
// 	 * @return string $client_initiative_id of the mailer
// 	 */
// 	public function getClientInitiativeId()
// 	{
// 		return $this->client_initiative_id;
// 	}
	
	
	
// 	/** Find available filters which can be used to add mailer recipients
// 	 * @return raw array
// 	 */
// 	public static function findAvailableFiltersByUserId($user_id)
// 	{
// 		$finder = self::getFinder(__CLASS__);
// 		return $finder->findAvailableFiltersByUserId($user_id);
// 	}

	
}

?>
