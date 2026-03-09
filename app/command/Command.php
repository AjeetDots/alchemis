<?php

/**
 * The Command class defines an array of status strings. It provides the statuses() method for 
 * converting a string ('CMD_OK') to its equivalent number, and getStatus() for revealing the 
 * current Command object's status flag.
 * @package framework
 */
abstract class app_command_Command
{
	protected $session_user;

	/**
	 * TODO - severely cut for this example 
	 */
	private static $STATUS_STRINGS = array(
		'CMD_DEFAULT'           => 0,
		'CMD_OK'                => 1,
		'CMD_ERROR'             => 2,
		'CMD_INSUFFICIENT_DATA' => 3,
		'CMD_SAVED'             => 4,
		'CMD_VALIDATION_ERROR'  => 5
	);

	private $status = 0;

	/**
	 * By declaring final, make impossible for a child class to override. No Command class 
	 * therefore will ever require arguments to its constructor.
	 */
	final function __construct()
	{
		pr([], "Command.php __construct()");
	}

	/**
	 * Uses the return value of the abstract doExecute() method to set the status flag, and to 
	 * cache itself in the Request object.
	 * 
	 * $param app_controller_Request $request
	 */
	function execute(app_controller_Request $request)
	{
		pr([], "Command.php execute(app_controller_Request)");
		$this->initSessionUser();
		$request->setObject('session_user', $this->session_user);
		if ($this->hasPermission($request)) {
			$response = $this->doExecute($request);
			if ($response instanceof app_controller_Response) {
				return $response;
			} else {
				$this->status = $response;
				$request->setCommand($this);
			}
		} else {
			throw new app_base_PermissionException('You do not have the correct permission');
		}
	}

	/**
	 * @return 
	 */
	function getStatus()
	{
		pr([], "Command.php getStatus()");
		return $this->status;
	}

	/**
	 * $param string $str
	 * @return 
	 */
	static function statuses($str = 'CMD_DEFAULT')
	{
		pr([], "Command.php statuses($str = 'CMD_DEFAULT')");
		if (empty($str)) {
			$str = 'CMD_DEFAULT';
		}
		//		echo "<p>Handling status: [" . self::$STATUS_STRINGS[$str] . "] $str</p>";
		return self::$STATUS_STRINGS[$str];
	}

	/**
	 * Grabs the session user and adds it to a instance variable in the command object.
	 */
	private function initSessionUser()
	{
		pr([], "Command.php initSessionUser()");
		// Get user information from the session
		$session = Auth_Session::singleton();
		$session_user = $session->getSessionUser();

		if (!is_array($session_user) || !isset($session_user['id'])) {
			// No logged-in user in the session; leave session_user unset for
			// commands (e.g. Login) that do not require an authenticated user.
			$this->session_user = null;
			return;
		}

		$user = app_domain_RbacUser::find($session_user['id']);
		$this->session_user = $user;
	}

	/**
	 * TODO - declare public?
	 * @param app_controller_Request $request
	 */
	abstract function doExecute(app_controller_Request $request);
	/**
	 * @param app_controller_Request $request
	 * @return boolean
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		pr([], "Command.php hasPermission(app_controller_Request)");
		return true;
	}
}
