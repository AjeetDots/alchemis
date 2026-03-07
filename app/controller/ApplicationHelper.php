<?php

/**
 * Defines the app_controller_ApplicationHelper class.
 * @author    $Author$
 * @copyright 2006 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

// Laravel dependencies
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

require_once('MDB2.php');
require_once('app/base/Registry.php');
require_once('app/controller/AppController.php');

/**
 * Not essential to Front Controller. Most implementations must acquire basic configuration data, 
 * though, so this is a strategy to do this.
 * This class simply reads a configuration file and makes value available to clients in the system.
 * @package Framework
 */
class app_controller_ApplicationHelper
{
	/**
	 * Holds object instance used in singleton pattern.
	 * @var app_controller_ApplicationHelper
	 */
	private static $instance;
	
	/**
	 * Path to configuration file.
	 * @var string configuration
	 */
	private $config = CONFIG_FILE;

	/**
	 * MDB2 database connection instance.
	 * @var MDB2
	 */
	private $db;

    /**
     * Declared private to implement Singleton pattern.
     */
    private function __construct() {}

	/**
	 * @return app_controller_ApplicationHelper
	 */
	public static function instance()
	{
		if (!self::$instance )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Responsible for loading configuration data. Checks the ApplicationRegistry to see if the 
	 * data is already cached. If the Registry object is already populated, it does nothing at all.
	 */
	public function init()
	{	
		if (app_base_ApplicationRegistry::isEmpty())
		{
			// Need to load the options direct from the XML file
			$this->getOptions();
		}

		// Laravel database setup
		// setup Illuminate/Database connection
		$config = app_base_ApplicationRegistry::all();
		$db = $config['database'];
		$capsuleConfig = [
			'driver'    => 'mysql',
			'host'      => $db['host'],
			'database'  => $db['database'],
			'username'  => $db['username'],
			'password'  => $db['password'],
			'charset'   => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
		];
		if (isset($db['port'])) {
			$capsuleConfig['port'] = (int) $db['port'];
		}
		$capsule = new DB;
		$capsule->addConnection($capsuleConfig);

		$capsule->setEventDispatcher(new Dispatcher(new Container));

		$capsule->setAsGlobal();
		$capsule->bootEloquent();
	}
	
	/**
	 * 
	 */
	private function getOptions()
	{
		
		$this->ensure(file_exists($this->config), 'Could not find options file.');
		$options = @SimpleXml_load_file($this->config);
		$this->ensure($options instanceof SimpleXMLElement, 'Could not resolve options file.');
		
		// get options based on env
		$env = $_SERVER['ALCHEMIS_ENV'];
		$this->ensure($env, 'No ALCHEMIS_ENV found');
		app_base_ApplicationRegistry::setEnvironment($env);

		$env_options = $options->environment->$env;
		$this->ensure($env_options, 'No options found for environment '. $env);

		$env_array = $this::xmlElementToArray($env_options);
		app_base_ApplicationRegistry::setItems($env_array);

		$db = $env_array['database'];
		// When running on host (not in Docker) with development, use ALCHEMIS_DB_HOST/ALCHEMIS_DB_PORT from .env.
		$isInDocker = file_exists('/.dockerenv');
		if ($env === 'development' && !$isInDocker && !empty($_SERVER['ALCHEMIS_DB_HOST'])) {
			$db['host'] = trim((string) $_SERVER['ALCHEMIS_DB_HOST']);
			$db['port'] = isset($_SERVER['ALCHEMIS_DB_PORT']) ? (int) $_SERVER['ALCHEMIS_DB_PORT'] : 3307;
			$env_array['database'] = $db;
			app_base_ApplicationRegistry::setItems($env_array);
		}
		// Default port for MySQL (e.g. AWS RDS) when not in config
		if (!isset($db['port']) || $db['port'] === '') {
			$db['port'] = 3306;
			$env_array['database'] = $db;
			app_base_ApplicationRegistry::setItems($env_array);
		}
		$dsnHost = isset($db['port']) ? $db['host'] . ':' . $db['port'] : $db['host'];
		$dsn = 'mysqli://' . $db['username'] . ':' . $db['password'] . '@' . $dsnHost . '/' . $db['database'];
		app_base_ApplicationRegistry::setDSN($dsn);
		
		$client_ini_settings = (string)$options->client_ini_settings;
		$this->ensure($client_ini_settings, 'No settings found');
		app_base_ApplicationRegistry::setClientIniSettings($options->client_ini_settings);
		
		$map = new app_controller_ControllerMap();
		
		foreach ($options->control->view as $default_view)
		{
			$stat_str = trim($default_view['status']);
			$status = app_command_Command::statuses($stat_str);
			$map->addView('default', $status, (string)$default_view);
		}
		
		foreach ($options->control->command as $command_view)
		{
			$command = trim((string)$command_view['name']);
			
			if ($command_view->classalias)
			{
				$classroot = trim((string)$command_view->classroot['name']);
				$map->addClassroot($command, $classroot);
			}
			
			if ($command_view->view)
			{
				$view = trim((string)$command_view->view);
				$forward = trim((string)$command_view->forward);
				$map->addView($command, 0, $view);
				if ($forward)
				{
					$map->addForward($command, 0, $forward);
				}
				
				foreach ($command_view->status as $command_view_status)
				{
					$view = trim((string)$command_view_status->view);
					$forward = trim((string)$command_view_status->forward);
					$stat_str = trim($command_view_status['value']);
					$status = app_command_Command::statuses($stat_str);
					if ($view)
					{
						$map->addView($command, $status, $view);
					}
					if ($forward)
					{
						$map->addForward($command, $status, $forward);
					}
				}
			}
		}
		
		app_base_ApplicationRegistry::setControllerMap($map);
//		echo "</pre></div>";
	}
	
	/**
	 * Rather than pepper the code with conditionals and throw statements we centralise the test 
	 * expression and the throw statement here.
	 */
	private function ensure($expr, $message)
	{
		if (!$expr)
		{
			throw new app_base_AppException($message);
		}
	}

	
	/**
	 * Returns an instance of MDB2 database class.
	 * @return MDB2 object
	 */
	public function DB()
	{
		$dsn = app_base_ApplicationRegistry::getDSN();
		$this->ensure($dsn, 'No DSN');
		if (!isset($this->db))
		{
			// Create MDB2 using factory
			$this->db = MDB2::factory($dsn);
			$this->db->query('SET NAMES utf8mb4');
			// Only enable MDB2 debug in development; in production it adds overhead (query logging etc.)
			$isDev = (isset($_SERVER['ALCHEMIS_ENV']) && $_SERVER['ALCHEMIS_ENV'] === 'development');
			$this->db->setOption('debug', $isDev);
		}
		$this->ensure((!MDB2::isError($this->db)), 'Unable to connect to DB');
		return $this->db;
	}

	/**
	 * 
	 * @return app_controller_AppController
	 */
	public function appController()
	{
		$map = app_base_ApplicationRegistry::getControllerMap();
		$this->ensure(is_object($map), 'No ControllerMap');
		return new app_controller_AppController($map);
	}

	static function xmlElementToArray($xmlString) {
		$json = json_encode($xmlString);
		$array = json_decode($json,TRUE);
		return $array;
	}
}

?>