<?php

require_once('app/mapper.php'); 
require_once('app/domain/Collections.php'); 
require_once('app/mapper/Collection.php'); 

//require_once('app/domain/Venue.php');

require_once('app/domain/RbacCommand.php');
require_once('app/domain/RbacCollections.php');
require_once('app/domain/RbacPermission.php');
require_once('app/domain/RbacRole.php');
require_once('app/domain/RbacUser.php');


/**
 * @package rbac
 */
class app_mapper_RbacCommandCollection extends app_mapper_Collection implements app_domain_RbacCommandCollection
{
	public function add(app_domain_RbacCommand $command)
	{
		$this->doAdd($command);
	}
}


/**
 * @package rbac
 */
class app_mapper_RbacPermissionCollection extends app_mapper_Collection implements app_domain_RbacPermissionCollection
{
	public function add(app_domain_RbacPermission $permission)
	{
		$this->doAdd($permission);
	}
}


/**
 * @package rbac
 */
class app_mapper_RbacRoleCollection extends app_mapper_Collection implements app_domain_RbacRoleCollection
{
	public function add(app_domain_RbacRole $role)
	{
		$this->doAdd($role);
	}
}


/**
 * @package rbac
 */
class app_mapper_RbacUserCollection extends app_mapper_Collection implements app_domain_RbacUserCollection
{
	public function add(app_domain_RbacUser $user)
	{
		$this->doAdd($user);
	}
}

?>