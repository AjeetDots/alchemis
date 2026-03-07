<?php
if (!defined('ROOT_PATH'))
{
	define('ROOT_PATH', '../../../');
}

require_once(ROOT_PATH.'config/config.php');
require_once(ROOT_PATH.'config/session.php');


require_once(ROOT_PATH.'library/Project.class.php');
require_once(ROOT_PATH.'library/Client.class.php');
require_once(ROOT_PATH.'scripts/ajax/ajaxResponse.class.php');
require_once(ROOT_PATH.'scripts/ajax/ajaxWarning.class.php');
require_once(ROOT_PATH.'scripts/ajax/ajaxNotice.class.php');

class ajax_getProjects
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
		$clientId = $request->id;
	    array_push($response->data, $this->ajaxPopulateProjects($clientId));
	    
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

	
	function ajaxPopulateProjects($clientId = null)
	{
		$result = array();
		 
		if (!empty($clientId))
		{
			
//			$responseObject = new ProjectResponse();
				
			if ($projects = Project::selectByClientId($clientId))
			{
				foreach ($projects as $project)
				{
					
					$result[] = array(	'value' => $project->ID ,
										'text' 	=> $project->Title);
					
//					$resultObject = new ProjectResult();
//					$resultObject -> value = $project->ID;
//					$resultObject -> text = $project->Title;
//					
//					array_push($responseObject->results, $resultObject);
				
				}
			}
		}
		
//		return $responseObject->results;
		return $result;
	}

}

?>
