<?php

/**
 * Defines the Auth_Session class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Auth
 * @version   SVN: $Id$
 */

require_once('app/base/Observable.php');

/**
 * Session table constants
 */
define('SESSION_TABLE',            'tbl_rbac_sessions');
define('SESSION_FIELD_ID',         'id');
define('SESSION_FIELD_EXPIRATION', 'expiration');
define('SESSION_FIELD_DATA',       'data');

/**
 * User table constants
 */
define('USER_TABLE',                'tbl_rbac_users');
define('USER_FIELD_ID',             'id');
define('USER_FIELD_USERNAME',       'handle');
define('USER_FIELD_EMAIL',            'email');
define('USER_FIELD_PASSWORD',       'password');
define('USER_FIELD_ACTIVE',         'is_active');

/**
 * Auth_Session class
 * @package Auth
 */
class Auth_Session implements app_base_Observable
{
    /**
     * Singleton
     * @var Auth_Session
     */
    private static $instance;

    /**
     * Array of Observers
     * @var array
     */
    private $observers = array();

    public $db;

    /**
     * Constructor.
     */
    private function __construct()
    {
        pr([], 'Session.php  __construct()');
        //		echo "<p><b>Auth_Session::__construct()</b></p>";
        if (!session_id()) {
            new Auth_Session_EasySql();
        }
    }

    /**
     * This uses the singleton pattern, making sure you have one and only instance of the class.
     * 
     */
    public static function singleton()
    {
        pr([], 'Session.php  singleton()');
        if (empty(self::$instance)) {
            self::$instance = new Auth_Session();
        }
        return self::$instance;
    }

    /**
     * To authenticate the current user.  Uses the following broad steps:<br />
     * (a) Creates a new Session object (see Session.class.php for documentation).<br />
     * (b) If no session id exists then authentication fails and return false.<br />
     * (c) Else, check the 'username' and 'password' from the session's 'userDetails' against the 
     *     database.  If they don't match the database then authentication fails and return false, 
     *     else authentication successful so return true.
     * @return boolean true if user authenticated; false otherwise.
     * @access public
     * @static
     */
    public function authenticate()
    {
        pr([], 'Session.php  authenticate()');
        //		echo "<p><b>Auth_Session::authenticate()</b></p>";
        $db = self::getDbConnection();
        $db->debug_all = false;

        if (!session_id()) {
            $session = new Auth_Session_EasySql();
        }

        if (!session_id()) {
            // No session exists, so return false.
            session_unset();
            session_destroy();
            return false;
        } elseif (isset($_SESSION['auth_session']['user'])) {
            // Create query to fetch row for given username.
            $sql = "SELECT COUNT(*) AS count " .
                "FROM " . USER_TABLE . " " .
                "WHERE " . USER_FIELD_USERNAME . " = '" . $_SESSION['auth_session']['user']['handle'] . "' " .
                "AND " . USER_FIELD_PASSWORD . " = '" . $_SESSION['auth_session']['user']['md5_password'] . "' " .
                "AND " . USER_FIELD_ACTIVE . " = 1";
            return $db->getVariable($sql) == 1;
        } else {
            return false;
        }
    }

    /**
     * Authenticates a given username and password combination by checking that they appear in 
     * the database, and that a session that was opened using that username is not already in 
     * existence.<br /><br />
     * NOTE:  The variable $forceNew determines the action to take if a current session is already 
     *        using the username / password combination.<br />
     *        true  -> terminates the old session, and starts a new one.
     *        false -> prevents creation of a new session, ie the cannot login until old session 
     *                 expires/terminated.
     * @param string $username the username to test (case-insensitive)
     * @param string $password the password to test (case-sensitive)
     * @param string $session_id the session ID (case-sensitive)
     * @return mixed an array of the user's details of the user details; else boolean false.
     */
    public function login($username, $password, $session_id, $request)
    {

        static $count = 0;
        $count++;

        if ($count > 3) {
            error_log('LOGIN LOOP DETECTED');
            return false;
        }

        pr([], 'Session.php  login($username, $password, $session_id, $request)');
        // Create db conection
        $db = self::getDbConnection();

        // Handle database connection failure
        if (!$db || !isset($db->dbh) || !$db->dbh) {
            return false;
        }

        $db->debug_all = false;

        // Create query to fetch row for given username.
        $sql = "SELECT U.* " .
            "FROM " . USER_TABLE . " AS U " .
            "WHERE U." . USER_FIELD_USERNAME . " = '" . $username . "' " .
            "AND U." . USER_FIELD_ACTIVE . " = 1";

        $result = $db->dbh->query($sql);
        if ($row = $result->fetchObject()) {
            // A record was returned for the given username.
            if (md5($password) == $row->password) {

                // check the login against whitelist, or skip if user has the permission
                if (!$row->permission_bypass_whitelist) {
                    $whitelist = app_model_Whitelist::where('ip', $request->ip)->first();
                    if (!$whitelist) return false;
                }

                // log user in DB as successful login
                app_model_LoginLog::create([
                    'user_id' => $row->id,
                    'ip' => $request->ip
                ]);

                // log user in session
                $_SESSION['auth_session']['user'] = [
                    'id' => $row->id,
                    'handle' => $row->handle,
                    'name' => $row->name,
                    'email' => $row->email,
                    'md5_password' => $row->password,
                    'client_id' => $row->client_id,
                ];
                pr($_SESSION, '2 Session.php');
                return true;
            } else {
                // Passwords don't match.
                return false;
            }
        }
        return false;
    }

    /**
     * Unsets and destroys the session.
     */
    public function logout()
    {
        pr([], 'Session.php  logout()');
        session_unset();
        session_destroy();
    }

    /**
     * Gets an open DB connection object.
     * @return resource an open database connection ready for use.
     * @access protected
     * @static
     */
    protected static function getDbConnection()
    {
        pr([], 'Session.php  getDbConnection()');
        require_once('app/base/Registry.php');
        require_once('include/EasySql/EasySql.class.php');
        $dsn = app_base_ApplicationRegistry::getDSN();
        $username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
        $password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
        $database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
        $hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
        try {
            return new EasySql($username, $password, $database, $hostname);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Gets the user data from the session.
     * @return array containing user data from the session
     */
    public function getSessionUser()
    {
        pr([], 'Session.php  getSessionUser()');
        pr($_SESSION, '1 Session.php getSessionUser');
        if (isset($_SESSION['auth_session']['user'])) {
            return $_SESSION['auth_session']['user'];
        }
    }

    /**
     * Attach an observer.
     * @param app_base_Observer $observer
     */
    public function attach(app_base_Observer $observer)
    {
        pr([], 'Session.php  attach(app_base_Observer $observer)');
        $this->observers[] = $observer;
    }

    /**
     * Detach an observer.
     * @param app_base_Observer $observer
     */
    function detach(app_base_Observer $observer)
    {
        pr([], 'Session.php  detach(app_base_Observer $observer)');
        $this->observers = array_diff($this->observers, array($observer));
    }

    /**
     * Notify the observers
     */
    function notify()
    {
        pr([], 'Session.php  notify()');
        foreach ($this->observers as $obs) {
            $obs->update($this);
        }
    }

    public function setLastCallBackQueryTime($date)
    {
        pr([], 'Session.php  setLastCallBackQueryTime($date)');
        $_SESSION['auth_session']['lastCallBackQueryTime'] = $date;
    }

    public function getLastCallBackQueryTime()
    {
        pr([], 'Session.php  getLastCallBackQueryTime()');
        if (isset($_SESSION['auth_session']['lastCallBackQueryTime'])) {
            return $_SESSION['auth_session']['lastCallBackQueryTime'];
        } else {
            return null;
        }
    }

    public function setRedirect($redirect)
    {
        pr([], 'Session.php  setRedirect($redirect)');
        $_SESSION['auth_session']['redirect'] = $redirect;
    }

    public function getRedirect()
    {
        pr([], 'Session.php  getRedirect()');
        if (isset($_SESSION['auth_session']['redirect'])) {
            return $_SESSION['auth_session']['redirect'];
        } else {
            return null;
        }
    }

    public function hasRedirect()
    {
        pr([], 'Session.php  hasRedirect()');
        if (!isset($_SESSION['auth_session']['redirect'])) {
            return false;
        } else {
            if (is_null($_SESSION['auth_session']['redirect'])) {
                return false;
            } else {
                if ($_SESSION['auth_session']['redirect'] == '') {
                    return false;
                }
            }
        }
        return true;
    }

    public function clearRedirect()
    {
        pr([], 'Session.php  clearRedirect()');
        unset($_SESSION['auth_session']['redirect']);
    }
}
