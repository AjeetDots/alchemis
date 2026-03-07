<?php

/**
 * Defines the app_command_Login class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('Auth/Session.php');
require_once('app/ajax/domain/Ajax_JSON.class.php');

/**
 * @package Framework
 */
class app_command_Login extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		pr([], 'Login.php doExecute(app_controller_Request $request)');
		$submitted = $request->getProperty('submitted');
		$username  = $request->getProperty('username');
		$password  = $request->getProperty('password');



		if (!$submitted) {
			//echo 'a';
			return self::statuses('CMD_INSUFFICIENT_DATA');
		} elseif (!$username) {
			//echo 'b';
			$request->addFeedback('Insufficient information provided');
			return self::statuses('CMD_INSUFFICIENT_DATA');
		} else {
			//echo '<br />ccccc <br />';			
			$session = Auth_Session::singleton();
			// echo "<pre>";
			// var_dump($session);
			// die;

			//print_r($session);
			//echo '</pre>';
			//echo 	session_id();
			//echo 'Here1';
			//exit();
			
			if ($session->login($username, $password, session_id(), $request)) {

				// echo '<pre>';
				// pr($request);
				// die('teresss');

				// echo "<pre>";
				// var_dump($session);
				// die;
				$redirect = $request->getProperty('redirect');
				// echo "<pre>";
				// var_dump($request);
				// die;
				$json = new Services_JSON();
				$redirect = $json->decode($redirect);
				// echo "<pre>";
				// var_dump($json);
				// die;

				$session->setRedirect($redirect);
				// echo "<pre>";
				// var_dump($session);
				// die;

				header('Location: index.php?cmd=Home');
				exit;
			} else {
				$request->addFeedback('Invalid Username, Password or IP address not whitelisted');
				$request->setProperty('username', $username);
				$request->setProperty('redirect', $request->getProperty('redirect'));
				return self::statuses('CMD_INSUFFICIENT_DATA');
			}
		}
	}
}
