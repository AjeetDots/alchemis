<?php

require_once('app/domain/DomainObject.php');
//require_once('app/domain/Client.php');

/**
 * @package alchemis
 */
class app_domain_Rbac extends app_domain_DomainObject
{
	private $name;
	private $client;

	function __construct($id = null, $name = 'main')
	{
		parent::__construct($id);
		$this->name = $name;
	}

	/**
	 * Gets the campaign history.
	 * @param integer $id the campaign ID
	 * @return app_mapper_CampaignMapper
	 */
	public static function findCommands()
	{
		$dir = "app/command/";
		
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir))
		{
			if ($dh = opendir($dir))
			{
				while (($file = readdir($dh)) !== false)
				{
					$filepath = "app/command/$file";
					
					if (is_file($filepath))
					{
						require_once($filepath);
						
						$classroot = substr($file, 0, -4);
//						echo "<p>".$classroot;
						
						$classname = "app_command_$classroot";
	
						if (class_exists($classname))
						{
							$cmd_class = new ReflectionClass($classname);
							if ($cmd_class->isSubClassOf('app_command_Command'))
							{
								$cmds[] = $classname;
	//							return $cmd_class->newInstance();
							}
						}
//						echo "<br />filename: $file : filetype: " . filetype($dir . $file) . "\n";
					}
				}
				closedir($dh);
			}
		}
		
		return $cmds;
	}


	public static function getUsers()
	{
		return array(	'1' => array(	'username' => 'ian',
										'id'       => 1,
										'fullname' => 'Ian Munday',
										'email'    => 'ian.munday@illumen.co.uk'),
						'2' => array(	'username' => 'david',
										'id'       => 2,
										'fullname' => 'David Carter',
										'email'    => 'david.carter@illumen.co.uk'));
	}

	public static function getRoles()
	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->findAll();

		return array(	'1' => array(	'id'   => 1,
										'name' => 'anonymous'),
						'2' => array(	'id'   => 2,
										'name' => 'administrator'),
						'3' => array(	'id'   => 3,
										'name' => 'database_guardian'),
						'4' => array(	'id'   => 4,
										'name' => 'nmb'),
						'5' => array(	'id'   => 5,
										'name' => 'client'));
	}


//	/**
//	 * Set the campaign's name.
//	 * @param string $name the campaign name
//	 */
//	public function setName($name)
//	{
//		$this->name = $name;
//		$this->markDirty();
//	}
//
//	/**
//	 * Set the campaign's parent client.
//	 * @param app_domain_Client $client the parent client
//	 */
//	public function setClient(app_domain_Client $client)
//	{
//		$this->client = $client;
//		$this->markDirty();
//	}
//
//	/**
//	 * Set the campaign revision.
//	 * @param string $revision the campaign revision
//	 */
//	public function setRevision($revision)
//	{
//		$this->revision = $revision;
//		$this->markDirty();
//	}
//
//	/**
//	 * Set the campaign revision.
//	 * @param string $revision the campaign revision
//	 */
//	public function setCreated($created)
//	{
//		$this->created = $created;
//		$this->markDirty();
//	}
//
//	/**
//	 * Get the campaign's name.
//	 * @return string the campaign name
//	 */
//	public function getName()
//	{
//		return $this->name;
//	}
//
//	/**
//	 * Get the campaign's parent client.
//	 * @return app_domain_Client
//	 */
//	public function getClient()
//	{
//		return $this->client;
//	}
//
//	/**
//	 * Get the campaign's revision.
//	 * @return string the campaign revision
//	 */
//	public function getRevision()
//	{
//		return $this->revision;
//	}
//
//	/**
//	 * Get the campaign's created date.
//	 * @return string the campaign created date
//	 */
//	public function getCreated()
//	{
//		return $this->created;
//	}
//
//	/**
//	 * Find 
//	 * @return app_mapper_CampaignCollection
//	 */
//	public static function findAll()
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->findAll();
//	}
//
//	/**
//	 * 
//	 * @param integer $id
//	 * @return app_mapper_CampaignMapper
//	 */
//	public static function find($id)
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->find($id);
//	}
//
//	/**
//	 * Gets the campaign history.
//	 * @param integer $id the campaign ID
//	 * @return app_mapper_CampaignMapper
//	 */
//	public static function getHistory($id)
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->getHistory($id);
//	}

}

?>