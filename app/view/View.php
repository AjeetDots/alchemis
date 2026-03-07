<?php

/**
 * Defines the app_view_View class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

/**
 * Base View class which includes instatiation of smarty and request objects.
 * @package Framework
 */
abstract class app_view_View
{
	protected $request;
	protected $smarty;
	
	/**
	 * By declaring final, make impossible for a child class to override. No Command class 
	 * therefore will ever require arguments to its constructor.
	 */
	function __construct()
	{
		require_once('app/view/ViewHelper.php');
		$this->request = ViewHelper::getRequest();
		$this->smarty = ViewHelper::getSmarty();
		
		// Get session user and create a mock user object if null
		$session_user = $this->request->getObject('session_user');
		if (!$session_user) {
			require_once('app/domain/MockSessionUser.php');
			$session_user = new MockSessionUser();
		}
		$this->smarty->assign_by_ref('session_user', $session_user);
	}

	/**
	 * Uses the return value of the abstract doExecute() method to set the status flag, and to 
	 * cache itself in the Request object.
	 * 
	 * $param app_controller_Request $request
	 */
	public function execute()
	{
		$this->doExecute();
	}
  
	/**
	 * TODO - declare public?
	 * @param app_controller_Request $request
	 */
	abstract protected function doExecute();
}

?>