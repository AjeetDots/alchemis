<?php
require_once('include/Zend/Config.php');
require_once('include/Zend/Db.php');
require_once('include/Zend/Debug.php');

class batch_BatchProcess
{
 	protected $db;
	
	function __construct() {
		$connection = self::getDbConnection();
		$config = new Zend_Config(
			array(
				'database' => array(
					'adapter' => 'Mysqli',
			    	'params'  => array(
						'host'     => $connection['hostname'],
						'dbname'   => $connection['database'],
						'username' => $connection['username'],
						'password' => $connection['password'],
					)
				)
			)
		);
	
		$this->db = Zend_Db::factory($config->database);
		$this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
	
		$this->init();
	}
	
	/**
	 * Gets an open DB connection object.
	 * @return resource an open database connection ready for use.
	 * @access protected
	 * @static
	 */
	protected static function getDbConnection()
	{
		require_once('app/base/Registry.php');
		$dsn = app_base_ApplicationRegistry::getDSN();
		$username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
		$password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
		$database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
		$hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
		return array(
				'username' => $username, 
				'password' => $password,
				'database' => $database,
				'hostname' => $hostname,
		);
	}

	function getNextId($tableName) {
		$data = array('sequence' => null);
		$this->db->insert($tableName . '_seq', $data);
		$value = $this->db->lastInsertId();
		if (is_numeric($value)) {
			$n = $this->db->delete($tableName . '_seq', 'sequence < ' . $value );
			if ($n != 1 && $value > 1) {
				throw new Exception('nextID: could not delete previous sequence table values from '.$tableName . '_seq');
			}
		}
		return $value;
	}
	
	function swapEmptyStringsForNull($data) {
		foreach ($data as &$item) {
			if ($item === '') {
				$item = null;
			}
		}
		return $data;
	}

}
