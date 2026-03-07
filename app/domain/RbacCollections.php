<?php

interface app_domain_RbacCommandCollection extends Iterator
{
	public function add(app_domain_RbacCommand $command);
}

interface app_domain_RbacPermissionCollection extends Iterator
{
	public function add(app_domain_RbacPermission $permission);
}

interface app_domain_RbacRoleCollection extends Iterator
{
	public function add(app_domain_RbacRole $role);
}

interface app_domain_RbacUserCollection extends Iterator
{
	public function add(app_domain_RbacUser $user);
}

?>