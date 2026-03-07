<?php

/**
 * @author     Ian Munday
 * @copyright  2004, Illumen Ltd.
 */


if (!defined('ROOT_PATH'))
{
	define('ROOT_PATH', '../');
}

require_once(ROOT_PATH . 'config/config.php');
require_once(ROOT_PATH . 'includes/EasySql/EasySql.class.php');
require_once(ROOT_PATH . 'includes/SafeSQL/SafeSQL.class.php');



//
//----- Start of Session Configuration Defaults -----
//
// These give a default set of session configurations 
// however, it is expected that these be defined 
// elsewhere, e.g. in a project's 'config.php' file.  
//
if (!defined('SESSION_DB_SS_TABLE'))
{
	// Table name
	define('SESSION_DB_SS_TABLE', 'tbl_Sessions');
}

if (!defined('SESSION_DB_ID_FIELD'))
{
	// Field name of session ID
	define('SESSION_DB_ID_FIELD', 'ID');
}

if (!defined('SESSION_DB_EX_FIELD'))
{
	// Field name of expiration
	define('SESSION_DB_EX_FIELD', 'Expiration');
}

if (!defined('SESSION_DB_DT_FIELD'))
{
	// Field name of data
	define('SESSION_DB_DT_FIELD', 'Data');
}

if (!defined('SESSION_USE_COOKIES'))
{
	// Allow use of cookies
	define('SESSION_USE_COOKIES', true);
}

if (!defined('SESSION_ONLY_COOKIES'))
{
	// Use only cookies
	define('SESSION_ONLY_COOKIES', true);                
}

if (!defined('SESSION_TRANS_ID'))
{
	// Use transparent session id
	define('SESSION_TRANS_ID', false);                
}

if (!defined('SESSION_NAME'))
{
	// Cookie name
	define('SESSION_NAME', 'PHP_SESSION');     	  
}

if (!defined('SESSION_EXPIRE'))
{
	// Time in seconds
	define('SESSION_EXPIRE', 14400);
}

if (!defined('SESSION_PROBABILITY'))
{
	// Probability garbage collection will be called
	define('SESSION_PROBABILITY', 1);
}

if (!defined('SESSION_LENGTH'))
{
	// Bit length of session ID
	define('SESSION_LENGTH', 32);
}

//if (!defined('SESSION_SH_SID'))
//{
//	// Session ID
//	define('SESSION_SH_SID', false);
//}

if (!defined('COOKIE_LIFETIME'))
{
	// Time in seconds
	define('COOKIE_LIFETIME', 14400);
}
//
//------ End of Session Configuration Defaults ------
//


/**
 * Session Class<br />
 * Custom Session Handler Class
 * @package Illumen
 */
class Session
{
	/**
	 * The database resource object.
	 * @var resource
	 * @access private
	 */
	public $dbConn;

	/**
	 * Whether an error has been reported.
	 * @var boolean
	 * @access private
	 */
	public $error;


	/**
	 * Session constructor.<br />
	 * Instatiates a Session
	 * @access public
	 */
	function Session()
	{
		$this->error = false;
		
		// Call to set ini configuration settings
		$this->set_ini();

		// Set the cookie_lifetime.  Defaults to 0 in php.ini, so is only deleted 
		// once browser reloaded.  By setting a value here we override this default.
		session_set_cookie_params(COOKIE_LIFETIME);
		
		// Bind methods to save handler
		session_set_save_handler(array(&$this, 'open'), 
								 array(&$this, 'close'), 
								 array(&$this, 'read'), 
								 array(&$this, 'write'), 
								 array(&$this, 'destroy'), 
								 array(&$this, 'gc'));
		
		// Start the session
		if (!isset($_REQUEST[SESSION_NAME]))
		{
			session_id($this->new_sid());
			// Needed to get PDF reports working in IE
			//session_cache_limiter('private_no_expire');
 			session_start();
		}
		else
		{
			// Needed to get PDF reports working in IE
			//session_cache_limiter('private_no_expire');
			session_start();
		}
	}
	
	
	/**
	 * Set ini directives.
	 */
	function set_ini()
	{
		ini_set('session.save_handler',     'user');
		ini_set('session.gc_maxlifetime',   SESSION_EXPIRE);
		ini_set('session.gc_probability',   SESSION_PROBABILITY);
		ini_set('session.use_cookies',      SESSION_USE_COOKIES);
		ini_set('session.use_only_cookies', SESSION_ONLY_COOKIES);
		ini_set('session.use_trans_id',     SESSION_TRANS_ID);
		ini_set('session.name',             SESSION_NAME);		
	}
	
	
	/**
	 * Opens a database connection.
	 * @param mixed $a - not used
	 * @param mixed not used
	 * @return null
	 */
	function open($a, $b)
	{
		$this->dbConn = new EasySql(CORE_DB_USER, CORE_DB_PASSWORD, CORE_DB_NAME, CORE_DB_HOST);
		return null;
	}


	/**
	 * Close database connection (not used but needed as argument).
	 * @return boolean
	 */
	function close()
	{
		return true;
	}


	/**
	 * Grab session data for current user.
	 * NB - Read function must return string value always to make save handler work as expected.  
	 *      Return empty string if there is no data to read.  Return values from other handlers 
	 *      are converted to boolean expression.  True for success, false for failure. 
	 * @param integer ID of the session
	 * @return string the session data
	 */
	function read($id)
	{		
		$this->dbConn->select(CORE_DB_NAME);
		
		$query = "select * from ".SESSION_DB_SS_TABLE." where ".SESSION_DB_ID_FIELD." = '".$id."' "
					."and ".SESSION_DB_EX_FIELD." > '".time()."'";		
		
		if ($row = $this->dbConn->get_row($query))
		{
			return $row->Data;
		}
		else
		{
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
		$this->dbConn->select(CORE_DB_NAME);

		// Escapes special characters in the data string for use in an SQL statement
		$data = mysql_real_escape_string($data);	
		
		// Set expire time
		$seconds = SESSION_EXPIRE;
		$expires = time() + $seconds;
		
		// Check for active session
		$query = "select ".SESSION_DB_ID_FIELD." from ".SESSION_DB_SS_TABLE
					." where ".SESSION_DB_ID_FIELD." = '".session_id()."'";
		
		if ($row = $this->dbConn->get_row($query))
		{
			// Session is active, so update
			$query = "update ".SESSION_DB_SS_TABLE." set ".SESSION_DB_EX_FIELD." = '".$expires."', ".SESSION_DB_DT_FIELD." = '".$data."' "
						."where ".SESSION_DB_ID_FIELD." = '".$id."' and ".SESSION_DB_EX_FIELD." > ".time();
		}
		else
		{
			// So active session found, so insert new
			$query = "insert into ".SESSION_DB_SS_TABLE." values('".$id."', '".$expires."', '".$data."')";
		}
		
		$this->dbConn->query($query);
	}
	
	
	/**
	 * Destroys current session.
	 * @param string id of session to destroy
	 */
	function destroy($id)
	{
		$this->dbConn->select(CORE_DB_NAME);

		// Set query
		$query = "delete from ".SESSION_DB_SS_TABLE." where ".SESSION_DB_ID_FIELD." = '".$id."'";
		
		// Null the session cookie
		if (setcookie(SESSION_NAME, '', 0, '/'))
		{
			unset($_COOKIE[SESSION_NAME]);
		}
		
		// Remove cookie details from database.
		$this->dbConn->query($query);
	}
	

	/**
	 * Cleans up reduntant sessions
	 * @param integer [default: 0] number of seconds session can exist in database 
	 *                before being garbage collected
	 */
	function gc($lifetime = 0)
	{
		$this->dbConn->select(CORE_DB_NAME);
		$query = "delete from ".SESSION_DB_SS_TABLE." where ".SESSION_DB_EX_FIELD." < '".time()."'";
		$this->dbConn->query($query);
	}
	

	/**
	 * Returns DB connection error.
	 * @return string the connection error
	 */
	function connection_error()
	{
		return $this->error;
	}
	

	/**
	 * Creates new session ID.
	 * @return string the session ID if successful; boolean false otherwise
	 */
	function new_sid()
	{
		// Define session ID variable
		$_sid = false;

		// Character range to use for session ID
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

		// Get count of characters in character range
		$count = strlen($chars);

		// Create the session ID
		for($i = 0; $i < SESSION_LENGTH; $i++)
		{
			$_sid .= $chars[mt_rand(0, $count-1)];
		}
		
		// Return data for use
		return $_sid;
	}


}


?>