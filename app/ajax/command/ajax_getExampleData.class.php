<?php
if (!defined('ROOT_PATH'))
{
	define('ROOT_PATH', '../../../');
}

require_once(ROOT_PATH.'config/config.php');
require_once(ROOT_PATH.'config/session.php');


require_once(ROOT_PATH.'library/Task.class.php');
require_once(ROOT_PATH.'scripts/ajax/ajaxResponse.class.php');
require_once(ROOT_PATH.'scripts/ajax/ajaxWarning.class.php');
require_once(ROOT_PATH.'scripts/ajax/ajaxNotice.class.php');

class ajax_getTasks
{
	
	private $response = array();
	
	function __construct($request)
	{
		// do processing
		
		// create the response object - this holds the data structure types to be passed back to the
		// calling client
		$response = new ajaxResponse();
		
		/*
	 	* ----- Add warnings -----
	 	*/
		// 
		// -- None required
				
		/*
	 	* ----- Add notices -----
	 	*/
		// -- None required
		
		/*
	 	* ----- Add Data -----
	 	*/
	    $projectId = $request->id;
	    array_push($response->data, $this->ajaxPopulateTasks($projectId));
				
		/*
		 * Set response object
		 */
		$this->setResponse($response);
	
	}
	
	    /*
	 * ----- Start of Accessors -----
	 */
    
    /**
	 * Gets the response object.
	 * @return array
	 * @access public
	 */
	public function getResponse()
	{
		return $this->response;
	}
	
	/*
	 * ----- Start of Mutators -----
	 */
    
    /**
	 * Sets the response object.
	 * @param array
	 * @access public
	 */
	public function setResponse($response)
	{
		$this->response = $response;
	}

	function ajaxPopulateTasks($projectId = null)
	{
		$result = array();
		
		if (!empty($projectId))
		{
			
			if ($tasks = Task::selectByProjectId($projectId))
			{
				
				foreach ($tasks as $task)
				{
					$result[] = array(	'value' => $task->ID ,
										'text' 	=> $task->Title);
				}
			}
		}
		
		return $result;;
	}
}

?>
