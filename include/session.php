<?php

if (!defined('ROOT_PATH'))
{
	throw new Exception('Constant ROOT_PATH is not defined.');
}


// Start a new Session if one does not already exist
require_once(ROOT_PATH . 'library/Session.class.php');
if (!session_id())
{
	$session = new Session();
}

// If a user is logged in, which we can tell be checking whether the 'actionedBy' variable is in 
// the session, then we need to get the details of the database they should be accessing.
if (isset($_SESSION['actionedBy']))
{
	require_once(ROOT_PATH . 'includes/EasySql/EasySql.class.php');
	$mydb = new EasySql(CORE_DB_USER, CORE_DB_PASSWORD, CORE_DB_NAME, CORE_DB_HOST);
	
	$row = $mydb->get_row('SELECT D.ID, D.Name, D.Username, D.Password, C.ID AS ClientID, C.Name AS ClientName ' .
							'FROM tbl_Users U ' .
							'INNER JOIN tbl_Clients C ON U.ClientID = C.ID ' .
							'INNER JOIN tbl_Databases D ON C.DatabaseID = D.ID ' .
							'WHERE U.ID = ' . $_SESSION['actionedBy']);

	define('CLIENT_DB_HOST',     CORE_DB_HOST);
	define('CLIENT_DB_NAME',     $row->Name);
	define('CLIENT_DB_USER',     $row->Username);
	define('CLIENT_DB_PASSWORD', $row->Password);
	define('CLIENT_ID',          $row->ClientID);
	define('CLIENT_NAME',        $row->ClientName);
	$smarty->assign('CLIENT_NAME', CLIENT_NAME);
}
else
{
//	throw new Exception("\$_SESSION['actionedBy'] is not defined.");
}

// Instantiate and start a timer
require_once(ROOT_PATH . 'library/NavigationHistory.class.php');
require_once(ROOT_PATH . 'library/PageGenerationTimer.class.php');
$timer = new PageGenerationTimer();
$timer->start();

// Include client-specifc language terms.
require_once(ROOT_PATH . 'lang/lang.php');

?>