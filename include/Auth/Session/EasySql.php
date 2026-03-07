<?php

/**
 * Defines the Auth_Session_EasySql class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Auth_Session
 * @version   SVN: $Id$
 */

require_once('EasySql/EasySql.class.php');
require_once('SafeSQL/SafeSQL.class.php');

/**
 * These give a default set of session configurations however, it is expected that these be defined 
 * elsewhere, e.g. in a project's 'config.php' file.
 */

// Table name
define('SESSION_DB_SS_TABLE', 'tbl_rbac_sessions');

// Field name of session ID
define('SESSION_DB_ID_FIELD', 'id');

// Field name of expiration
define('SESSION_DB_EX_FIELD', 'expiration');

// Field name of data
define('SESSION_DB_DT_FIELD', 'data');

// Allow use of cookies
define('SESSION_USE_COOKIES', true);

// Use only cookies
define('SESSION_ONLY_COOKIES', true);

// Cookie name
define('SESSION_NAME', 'AUTH_RBAC_SESSION');

// Time in seconds: 60 * 60 * 12 == 12 hours
define('SESSION_EXPIRE', 43200);

// Probability garbage collection will be called
define('SESSION_PROBABILITY', 1);

// Bit length of session ID
define('SESSION_LENGTH', 32);

// Specifies the lifetime of the cookie in seconds which is sent to the browser. The value 0 means 
// "until the browser is closed." Defaults to 0. Time in seconds. 
define('COOKIE_LIFETIME', 43200);


//void session_set_cookie_params ( int lifetime [, string path [, string domain [, bool secure [, bool httponly]]]] )

//session.cookie_path string
//session.cookie_path specifies path to set in session_cookie. Defaults to /.
define('COOKIE_PATH', '/');

//session.cookie_domain string
//session.cookie_domain specifies the domain to set in session_cookie. Default is none at all 
//meaning the host name of the server which generated the cookie according to cookies specification. See also 
//session_get_cookie_params() and session_set_cookie_params().
define('COOKIE_DOMAIN', null);

//session.cookie_secure boolean
//session.cookie_secure specifies whether cookies should only be sent over secure connections. Defaults to off. 
// This setting was added in PHP 4.0.4. See also session_get_cookie_params() and session_set_cookie_params().
define('COOKIE_SECURE', false);

//session.cookie_httponly boolean
//Marks the cookie as accessible only through the HTTP protocol. This means that the cookie won't 
//be accessible by scripting languages, such as JavaScript. This setting can effectively help to 
//reduce identity theft through XSS attacks (although it is not supported by all browsers).
define('COOKIE_HTTPONLY', true);

/**
 * Auth_Session_EasySql class<br />
 * Custom Session Handler Class
 * @package Auth_Session
 */
class Auth_Session_EasySql
{
	/**
	 * The database resource object.
	 * @var resource
	 */
	public $dbConn;

	/**
	 * Whether an error has been reported.
	 * @var boolean
	 */
	public $error;

	/**
	 * Session constructor.<br />
	 * Instatiates a Session
	 * @access public
	 */
	function __construct()
	{
		pr([], 'EasySql.php __construct()');
		$this->error = false;

		// Call to set ini configuration settings
		$this->set_ini();

		// Set the cookie_lifetime.  Defaults to 0 in php.ini, so is only deleted 
		// once browser reloaded.  By setting a value here we override this default.
		//		session_set_cookie_params(COOKIE_LIFETIME);
		//		session_set_cookie_params(COOKIE_LIFETIME, COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTPONLY);
		ini_set('session.cookie_lifetime', COOKIE_LIFETIME);
		ini_set('session.cookie_path',     COOKIE_PATH);
		ini_set('session.cookie_domain',   COOKIE_DOMAIN);
		ini_set('session.cookie_secure',   COOKIE_SECURE);
		ini_set('session.cookie_httponly', COOKIE_HTTPONLY);


		// Bind methods to save handler
		session_set_save_handler(
			array(&$this, 'open'),
			array(&$this, 'close'),
			array(&$this, 'read'),
			array(&$this, 'write'),
			array(&$this, 'destroy'),
			array(&$this, 'gc')
		);

		// Start the session

		if (!isset($_REQUEST[SESSION_NAME])) {

			$t = $this->new_sid();
			session_id($t);
			session_name('AUTH_RBAC_SESSION');
			// Needed to get PDF reports working in IE
			//session_cache_limiter('private_no_expire');
			session_start();
			//echo '123<br />' ;
		} else {
			// Needed to get PDF reports working in IE
			//session_cache_limiter('private_no_expire');
			// 			echo '456';
			session_start();
			//print_r($_SESSION);
		}
	}


	/**
	 * Set ini directives.
	 */
	function set_ini()
	{
		pr([], 'EasySql.php set_ini()');
		ini_set('session.save_handler',     'user');
		ini_set('session.gc_maxlifetime',   SESSION_EXPIRE);
		ini_set('session.gc_probability',   SESSION_PROBABILITY);
		ini_set('session.use_cookies',      SESSION_USE_COOKIES);
		ini_set('session.use_only_cookies', SESSION_ONLY_COOKIES);
		ini_set('session.name',             SESSION_NAME);

		//		$cookieParams = session_get_cookie_params();
		//		session_set_cookie_params(SESSION_EXPIRE, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure']);
	}


	/**
	 * Opens a database connection.
	 * @param mixed $a - not used
	 * @param mixed not used
	 * @return null
	 */
	function open($a, $b)
	{
		pr([], 'EasySql.php open($a, $b)');
		require_once('app/base/Registry.php');
		$dsn = app_base_ApplicationRegistry::getDSN();
		$username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
		$password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
		$database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
		$hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
		$this->dbConn = new EasySql($username, $password, $database, $hostname);
		return null;
	}


	/**
	 * Close database connection (not used but needed as argument).
	 * @return boolean
	 */
	function close()
	{
		pr([], 'EasySql.php close()');
		return true;
	}


	/**
	 * Grab session data for current user.
	 * NB - Read it must return string value always to make save handler work as expected.  
	 *      Return empty string if there is no data to read.  Return values from other handlers 
	 *      are converted to boolean expression.  True for success, false for failure. 
	 * @param integer ID of the session
	 * @return string the session data
	 */
	function read($id)
	{
		pr([], 'EasySql.php read($id)');
		$query = "select * from " . SESSION_DB_SS_TABLE . " where " . SESSION_DB_ID_FIELD . " = '" . $id . "' "
			. "and " . SESSION_DB_EX_FIELD . " > '" . time() . "'";
		$result = $this->dbConn->dbh->query($query);
		if ($row = $result->fetch_object()) {
			return $row->{SESSION_DB_DT_FIELD};
		} else {
			return '';
		}
	}


	/**
	 * Writes data to current session.
	 * @param string ID of the session
	 * @param string $data - the data for the session, stored as a string.
	 */
	function write($id, $data)
	{
		pr([], 'EasySql.php write($id, $data)');
		//echo "write" ;
		pr($this->dbConn->dbh, "Sumit");
		// Escapes special characters in the data string for use in an SQL statement
		$data = mysqli_real_escape_string($this->dbConn->dbh, $data);

		// Set expire time
		$seconds = SESSION_EXPIRE;
		$expires = time() + $seconds;

		// Check for active session
		$query = "select " . SESSION_DB_ID_FIELD . " from " . SESSION_DB_SS_TABLE
			. " where " . SESSION_DB_ID_FIELD . " = '" . session_id() . "'";
		$result = $this->dbConn->dbh->query($query);
		if ($row = $result->fetch_object()) {
			// Session is active, so update
			$query = "update " . SESSION_DB_SS_TABLE . " set " . SESSION_DB_EX_FIELD . " = '" . $expires . "', " . SESSION_DB_DT_FIELD . " = '" . $data . "' "
				. "where " . SESSION_DB_ID_FIELD . " = '" . $id . "' and " . SESSION_DB_EX_FIELD . " > " . time();
		} else {
			// So active session found, so insert new
			$query = "insert into " . SESSION_DB_SS_TABLE . " values('" . $id . "', '" . $expires . "', '" . $data . "')";
			//$query = "insert into ".SESSION_DB_SS_TABLE." values('".$id."', '".$expires."', 'data')";
		}

		$this->dbConn->dbh->query($query);
	}


	/**
	 * Destroys current session.
	 * @param string id of session to destroy
	 */
	function destroy($id)
	{
		pr([], 'EasySql.php destroy($id)');
		// Set query
		$query = "delete from " . SESSION_DB_SS_TABLE . " where " . SESSION_DB_ID_FIELD . " = '" . $id . "'";

		// Null the session cookie
		if (setcookie(SESSION_NAME, '', 0, '/')) {
			unset($_COOKIE[SESSION_NAME]);
		}

		// Remove cookie details from database.
		$this->dbConn->dbh->query($query);
	}


	/**
	 * Cleans up reduntant sessions
	 * @param integer [default: 0] number of seconds session can exist in database 
	 *                before being garbage collected
	 */
	function gc($lifetime = 0)
	{
		pr([], 'EasySql.php gc($lifetime = 0)');
		$query = "delete from " . SESSION_DB_SS_TABLE . " where " . SESSION_DB_EX_FIELD . " < '" . time() . "'";
		$this->dbConn->dbh->query($query);
	}


	/**
	 * Returns DB connection error.
	 * @return string the connection error
	 */
	function connection_error()
	{
		pr([], 'EasySql.php connection_error()');
		return $this->error;
	}


	/**
	 * Creates new session ID.
	 * @return string the session ID if successful; boolean false otherwise
	 */
	function new_sid()
	{
		pr([], 'EasySql.php new_sid()');
		// Define session ID variable
		$_sid = false;

		// Character range to use for session ID
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

		// Get count of characters in character range
		$count = strlen($chars);

		// Create the session ID
		for ($i = 0; $i < SESSION_LENGTH; $i++) {
			$_sid .= $chars[mt_rand(0, $count - 1)];
		}

		// Return data for use
		return $_sid;
	}
}
