<?php
if (!defined('ROOT_PATH'))
{
	define('ROOT_PATH', './');
}

//require_once(ROOT_PATH.'config/config.php');
//require_once(ROOT_PATH.'config/session.php');

require_once(ROOT_PATH.'domain/Ajax_JSON.class.php');
require_once(ROOT_PATH.'domain/ajaxResponse.class.php');
//require_once(ROOT_PATH.'ajaxWarning.class.php');
//require_once(ROOT_PATH.'ajaxNotice.class.php');

//
//----- Start Session Manangement -----
//
//require_once(ROOT_PATH.'library/authenticate.php');
//
//------  End Session Manangement ------
//

// ** TO DO
//----check security
//----check request is valid
//----is requesting URL within the application?
//----need to revise this to handle post requests

// we will do our own error handling
//error_reporting (E_STRICT);
//error_reporting(E_ALL);
error_reporting (0);

set_error_handler("userErrorHandler");

if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST')
{
	$json = new Services_JSON();
	
	// Get page parameters

//	try
//	{	
//		$id = $_GET['id'];
	
		$ajaxRequest = null;
			
		
		$ajaxRequest = isset($_GET['ajaxRequest']) ? $_GET['ajaxRequest'] : (isset($_POST['ajaxRequest']) ? $_POST['ajaxRequest'] : null);
		$ajaxCommand = isset($_GET['ajaxCommand']) ? $_GET['ajaxCommand'] : (isset($_POST['ajaxCommand']) ? $_POST['ajaxCommand'] : null);
//	}
//	catch (Exception $e)
//	{
//		$responseObject = new ajaxResponse();
//		array_push($responseObject->warnings, 'Caught exception: ',  $e->getMessage());
//		echo $json->encode($responseObject);
//		exit();
//	}
	
	
	
	if (!is_null($ajaxRequest))
	{
		$ajaxRequest = $json->decode($ajaxRequest);
	}
	else
	{
		$responseObject = new ajaxResponse();
		array_push($responseObject->warnings, 'Blank request string submitted');
		echo $json->encode($responseObject);
		exit();
	}
	
	
	// extract the command string from the request string
	if (!is_null($ajaxCommand))
	{
		$ajaxCommand = 'ajax_' . $ajaxCommand;
	}
	else
	{
		$responseObject = new ajaxResponse();
		array_push($responseObject->warnings, 'Blank command string submitted');
		echo $json->encode($responseObject);
		exit();
	}


	
	
	// set up the command objects
	if (file_exists('./command/' . $ajaxCommand . '.class.php'))
	{
		require_once('./command/' . $ajaxCommand . '.class.php');
		
		if (class_exists($ajaxCommand))
		{
				$commandObject = new $ajaxCommand($ajaxRequest);
				$responseObject  = $commandObject->getResponse();
		}
		else
		{
			$responseObject = new ajaxResponse();
			array_push($responseObject->warnings, 'Class not available');
		}
	}
	else
	{
		$responseObject = new ajaxResponse();
		array_push($responseObject->warnings, 'Command object file not found');
	}
	
	// send back the response
	echo $json->encode($responseObject);
	
}
else
{
	die();
}


function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars = null)
{
   // timestamp for the error entry
   $dt = date("Y-m-d H:i:s");

   // define an assoc array of error string
   // in reality the only entries we should
   // consider are E_WARNING, E_NOTICE, E_USER_ERROR,
   // E_USER_WARNING and E_USER_NOTICE
   $errortype = array (
   				 E_ERROR              => 'Error',
               E_WARNING            => 'Warning',
               E_PARSE              => 'Parsing Error',
               E_NOTICE            => 'Notice',
               E_CORE_ERROR        => 'Core Error',
               E_CORE_WARNING      => 'Core Warning',
               E_COMPILE_ERROR      => 'Compile Error',
               E_COMPILE_WARNING    => 'Compile Warning',
               E_USER_ERROR        => 'User Error',
               E_USER_WARNING      => 'User Warning',
               E_USER_NOTICE        => 'User Notice',
               E_STRICT            => 'Runtime Notice',
               E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
               );
   // set of errors for which a var trace will be saved
   $errors_to_report = array(E_ERROR, 
   						E_WARNING,
   						E_PARSE,
						E_NOTICE,
   						E_CORE_ERROR,
   						E_USER_ERROR, 
   						E_CORE_WARNING,
   						E_COMPILE_ERROR,
   						E_COMPILE_WARNING,
   						E_USER_WARNING, 
   						E_USER_NOTICE);
  
   $err = "Date/Time:\t\t" . $dt . "\n";
   $err .= "Error Num:\t\t" . $errno . "\n";
   $err .= "Error Type:\t\t" . $errortype[$errno] . "\n";
   $err .= "Error Msg:\t\t" . $errmsg . "\n";
   $err .= "Script Name:\t\t" . $filename . "\n";
   $err .= "Script Line Num:\t\t" . $linenum . "\n";
	

   if (in_array($errno, $errors_to_report)) 
   {
		$responseObject = new ajaxResponse();
		array_push($responseObject->warnings, $err);
		$json = new Services_JSON();
		echo $json->encode($responseObject);
		exit();
   }	
   
      
	
}

?>
