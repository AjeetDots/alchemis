<?php

/**
 * Defines the Registry classes.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

if (!defined('APP_DIRECTORY'))
{
	$path_parts = pathinfo($_SERVER['PATH_TRANSLATED']);
	define('APP_DIRECTORY', $path_parts['dirname'] . DIRECTORY_SEPARATOR);
}

/**
 * Provides access to data via static methods (or via instance methods on a Singleton). Every
 * object in the a system therefore has access to these objects.
 * @package Framework
 */
abstract class app_base_Registry
{
	/**
	 * Private so cannot be accessed from outside the class.
	 */
	protected function __construct() {}

	/**
	 * Return the value relating to a registry key. Not available to client code because we want to
	 * enforce type for get operations.
	 * @param string $key
	 * @return the value matching the key
	 */
	abstract protected function get($key);

	/**
	 * Set a key-value pair. Not available to client code because we want to enforce type for set
	 * operations.
	 * @param string $key
	 * @param mixed $val
	 */
	abstract protected function set($key, $val);

//	function isEmpty()
//	function isPopulated()
//	function clear();
}


/**
 * Store and server Request objects.
 * @package Framework
 */
class app_base_RequestRegistry extends app_base_Registry
{
	private $values = array();

	/**
	 * Private and static so cannot be accessed from outside the class.
	 * @see instance()
	 */
	private static $instance;

	/**
	 * Has access to the private and static instance property, and can be accessed via the class
	 * anywhere in a script.
	 *
	 * A static method cannot access object properties because it is, by definition, invoked in a
	 * class and not an object context. It can, however access a static property. When called we
	 * check the Registry::instance property. If empty then we create an instance of the class and
	 * store it in the property and then return the instance to the calling code. Because the
	 * static instance method is part of the Registry class, we have no problem instantiating a
	 * Registry object even though the constuctor is private.
	 *
	 * @return app_base_Registry
	 */
	public static function instance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Return the value relating to a registry key.
	 * @param string $key
	 * @return the value matching the key
	 */
	protected function get($key)
	{
		if (!isset($this->values[$key]))
		{
			throw new Exception('key not found');
		}
		return $this->values[$key];
	}

	/**
	 * Set a key-value pair.
	 * @param string $key
	 * @param mixed $val
	 */
	protected function set($key, $val)
	{
		$this->values[$key] = $val;
	}

	/**
	 * Returns the Request object.
	 * @return app_controller_Request object
	 */
	public static function getRequest()
	{
		return self::instance()->get('request');
	}

	/**
	 * Sets the Request object.
	 * @param app_controller_Request $request
	 */
	public static function setRequest(app_controller_Request $request)
	{
		return self::instance()->set('request', $request);
	}
}


/**
 * Uses the $_SESSION superglobal to set and retrieve values. We kick of the session with the
 * session_start() method. As always with sessions, you must ensure that you have not yet sent any
 * text to the user before using this class.
 * @package Framework
 */
class app_base_SessionRegistry extends app_base_Registry
{
	private static $instance;

	protected function __construct()
	{
		session_start();
	}

	static function instance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function get($key)
	{
		return $_SESSION[__CLASS__][$key];
	}

	protected function set($key, $val)
	{
		$_SESSION[__CLASS__][$key] = $val;
	}

	function setComplex(Complex $complex)
	{
		return self::instance()->set('complex', $complex);
	}

	function getComplex()
	{
		return self::instance()->get('complex');
	}
}


/**
 * TODO - Not production-quality code.
 * Uses serialization to save and restore the $values property.
 * @package Framework
 */
class app_base_ApplicationRegistry extends app_base_Registry
{
	private static $instance;
	private static $freezefile = 'data/applicationRegistry.txt';

	/**
	 * Array of values.
	 * @var array
	 */
	private $values = array();

	/**
	 * @var array
	 */
	private $dirty = false;

	/**
	 * Declared private to implement Singleton pattern.
	 */
	protected function __construct()
	{
//		echo "<p>app_base_ApplicationRegistry::__construct()</p>";
		$env = $_SERVER['ALCHEMIS_ENV'];
		self::$freezefile = 'data/applicationRegistry_'.$env.'.txt';
		if (!file_exists(self::$freezefile))
		{
			self::makeFile();
		}
		$this->doReload();
	}

	/**
	 *
	 */
	public static function instance()
	{
//		echo "<p>app_base_ApplicationRegistry::instance()</p>";
		if (!self::$instance)
		{
			try
			{
				self::$instance = new self();
			}
			catch (Exception $e)
			{
			}
		}
		return self::$instance;
	}

	/**
	 * Automatically invoked when the ApplicationRegistry object is destroyed. If the $dirty
	 * property is set to true, then the save() method is called.
	 * @see save()
	 */
	public function __destruct()
	{
//		echo "<p>app_base_ApplicationRegistry::__destruct()</p>";
		if ($this->dirty)
		{
			$this->save();
		}
	}

	/**
	 *
	 */
	public static function reload()
	{
//		echo "<p>app_base_ApplicationRegistry::reload()</p>";
		self::instance()->doReload();
	}

	/**
	 * Handles data acquistion, first checking for the file's existence and then reading its
	 * contents. Contents acquired, the method uses unserialize() to generate an array.
	 * @return boolean
	 */
	private function doReload()
	{
//		echo "<p>app_base_ApplicationRegistry::doReload()</p>";
		if (!file_exists(self::$freezefile))
		{
			throw new Exception('File does not exist: ' . self::$freezefile);
			return false;
//			echo "<br>here 1";
//			echo "<br>make file";
//			self::instance()->makeFile();
		}
		else
		{
//			echo "<br>here 2";
		}

		$serialized = file_get_contents(self::$freezefile, true);
		$array = unserialize($serialized);

		if (is_array($array))
		{
//			echo "is array";
			if ($this->dirty)
			{
				$this->values = array_merge($this->values, $array);
			}
			else
			{
				$this->values = $array;
			}
			return true;
		}
		return false;
	}

	private static function makeFile()
	{
		$ourFileHandle = fopen(self::$freezefile, 'w') or die("can't open file");
		fclose($ourFileHandle);
	}

	/**
	 * Works like doReload() in reverse. The $values array is serialized and saved to the storage
	 * file.
	 * @see doReload
	 */
	private function save()
	{
		
		if (!file_exists(APP_DIRECTORY . self::$freezefile))
		{
			throw new Exception('The file ' . self::$freezefile . ' does not exist');
		}
		
		if (!is_writable(APP_DIRECTORY . self::$freezefile))
		{
			throw new Exception('Unwritable path');
		}
		
		$frozen = serialize($this->values);

		file_put_contents(APP_DIRECTORY . self::$freezefile, $frozen, FILE_USE_INCLUDE_PATH);

		$this->dirty = false;
	}

	/**
	 * @param
	 */
	protected function get($key)
	{
		return $this->values[$key];
	}

	/**
	 * Set a key-value pair.
	 * @param string $key
	 * @param mixed $val
	 */
	protected function set($key, $val)
	{
		$this->dirty = true;
		$this->values[$key] = $val;
  }

  protected function getAll()
	{
		return $this->values;
	}

	/**
	 * Returns whether the values array is empty.
	 * @return boolean
	 */
	static function isEmpty()
	{
		$res = empty(self::instance()->values);
		return $res;
	}

	/**
	 * Gets an item from the registry with a particular key
	 * @param string $key
	 * @return string
	 */
	static function getItem($key)
	{
		return self::instance()->get($key);
	}

	public static function all()
	{
		return self::instance()->getAll();
	}

	public static function setItems($array)
	{
		$instance = self::instance();
		foreach($array as $key => $value){
			$instance->set($key, $value);
		}
	}
	
	static function getEnvironment()
	{
		return self::instance()->get('environment');
	}
		
	public static function setEnvironment($env)
	{
		return self::instance()->set('environment', $env);
	}

	/**
	* Gets the DSN
	* @return string
	*/
	static function getDSN()
	{
		return self::instance()->get('dsn');
	}

	/**
	 * @param string $dsn
	 */
	public static function setDSN($dsn)
	{
		return self::instance()->set('dsn', $dsn);
	}

	/**
	 * Gets the url
	 * @return string
	 */
	public static function getUrl()
	{
		return self::instance()->get('url');
	}

	/**
	 * Sets the url
	 * @param string $url
	 */
	public static function setUrl($url)
	{
		return self::instance()->set('url', $url);
	}

	/**
	 * Gets the name
	 * @return string
	 */
	public static function getName()
	{
		return self::instance()->get('name');
	}

    public static function getSystemMonitorEmail()
	{
		return self::instance()->get('system_monitor_email');
	}

	/**
	 * Sets the name
	 * @param string $name
	 */
	public static function setName($name)
	{
		return self::instance()->set('name', $name);
	}

	/**
	 * Gets the description
	 * @return string
	 */
	public static function getDescription()
	{
		return self::instance()->get('description');
	}

	/**
	 * Sets the description
	 * @param string $description
	 */
	public static function setDescription($description)
	{
		return self::instance()->set('description', $description);
	}

	/**
	 * Gets the developer
	 * @return string
	 */
	public static function getDeveloper()
	{
		return self::instance()->get('developer');
	}

	/**
	 * Sets the developer
	 * @param string $developer
	 */
	public static function setDeveloper($developer)
	{
		return self::instance()->set('developer', $developer);
	}

	/**
	 * Gets the version
	 * @return string
	 */
	public static function getVersion()
	{
		return self::instance()->get('version');
	}

	/**
	 * Sets the version
	 * @param string $version
	 */
	public static function setVersion($version)
	{
		return self::instance()->set('version', $version);
	}

	/**
	 * Gets the locale
	 * @return string
	 */
	public static function getLocale()
	{
		return self::instance()->get('locale');
	}

	/**
	 * Sets the locale
	 * @param string $locale
	 */
	public static function setLocale($locale)
	{
		return self::instance()->set('locale', $locale);
	}

	/**
	 * Gets the log directory
	 * @return string
	 */
	public static function getLogDirectory()
	{
		return self::instance()->get('log_directory');
	}

	/**
	 * Sets the log directory
	 * @param string $log_directory
	 */
	public static function setLogDirectory($log_directory)
	{
		return self::instance()->set('log_directory', $log_directory);
	}

	static function getControllerMap()
	{
		return self::instance()->get('cmap');
	}

	public static function setControllerMap(app_controller_ControllerMap $map)
	{
		return self::instance()->set('cmap', $map);
	}

	/**
	* Gets the client ini settings
	* @return array
	*/
	public static function getClientIniSettings()
	{
		return self::get('client_ini_settings');
	}

	/**
	* Sets the client ini settings
	* @param array $client_ini_settings
	*/
	public static function setClientIniSettings($client_ini_settings)
	{
		// convert client settings to an array
		$settings = self::instance()->xmlElementToArray($client_ini_settings);

		foreach ($settings as $key => $setting) {
			self::instance()->set($key, $setting);
		}

	}

	/**
	 *
	 * Used to convert a SimpleXMLElement object to an array
	 * @param SimpleXMLElement object $xmlString
	 * @return array
	 */
	private static function xmlElementToArray($xmlString) {
		$json = json_encode($xmlString);
		$array = json_decode($json,TRUE);
		return $array;
	}


}

?>