<?php

require_once('app/domain/DomainObject.php');

/**
 * @package framework
 */
class app_domain_RbacPermission extends app_domain_DomainObject
{
	private $name;
	private $command;
	
	/**
	 * @param integer $id
	 * @param string $name 
	 */
	public function __construct($id = null, $name = null)
	{
		$this->name = $name;
		$this->permissions = self::getCollection('app_domain_RbacPermission');
		parent::__construct($id);
		
		if ($this->id)
		{
//			$this->doIanLoad();
//			echo "<br />do load";
//			$finder = $this->finder();
//			$old = $finder->find($this->id);
//			$this->setName($old->getName());
//			$x = self::find($this->id);
//			echo "<pre>";
//			print_r($x);
//			echo "</pre>";
//			exit;
		}
		else
		{
			echo "<br />skip load";
		}
//		echo "<br />\$this->id = ".$this->id;
//		echo "<br />\$this->name = ".$this->name;
//		echo "</div>";
//		exit;
	}

	/**
	 * Set the venue name.
	 * @param string $name_s the venue name
	 */
	public function setName($name_s)
	{
		$this->name = $name_s;
		$this->markDirty();
	}
	
	/**
	 * Return the venue name.
	 * @return string the venue name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the command.
	 * @param app_domain_RbacCommand $command the command
	 */
	public function setCommand(app_domain_RbacCommand $command)
	{
		$this->command = $command;
		$this->markDirty();
	}
	
	/**
	 * Return the command.
	 * @return app_domain_RbacCommand
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * 
	 * @return app_mapper_RbacPermissionCollection
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * 
	 * @param integer $id
	 * @return app_domain_RbacPermission
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * Return a collection of permissions for a given role and command.
	 * @param integer $role_id the role ID
	 * @param integer $command_id the command ID
	 * @return app_domain_RbacPermissionCollection
	 */
	public static function findByRoleAndCommand($role_id, $command_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByRoleAndCommand($role_id, $command_id);
	}

	/**
	 * Return a collection of avaialble permissions for a given command.
	 * @param integer $command_id the command ID
	 * @return app_domain_RbacPermissionCollection
	 */
	public static function findByCommand($command_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCommand($command_id);
	}

}

?>