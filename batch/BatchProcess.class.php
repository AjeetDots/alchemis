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
		$parts = parse_url($dsn);
		
		return array(
			'username' => $parts['user'] ?? '',
			'password' => $parts['pass'] ?? '',
			'database' => isset($parts['path']) ? ltrim($parts['path'], '/') : '',
			'hostname' => $parts['host'] ?? '',
			'port'     => $parts['port'] ?? 3306,
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
