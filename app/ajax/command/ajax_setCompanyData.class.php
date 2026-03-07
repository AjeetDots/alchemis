<?php
//if (!defined('ROOT_PATH'))
//{
//	define('ROOT_PATH', '../../');
//}

//require_once(ROOT_PATH.'config/config.php');
//require_once(ROOT_PATH.'config/session.php');


//require_once(ROOT_PATH.'library/Task.class.php');
require_once('./domain/ajaxResponse.class.php');
require_once('./domain/ajaxWarning.class.php');
require_once('./domain/ajaxNotice.class.php');

class ajax_setCompanyData
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
//	    $projectId = $request->id;
//		echo "<pre>";
//		echo print_r($request);
//		echo "</pre>";
		
	    array_push($response->data, $this->ajaxSetCompanyValue($request));
				
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

	function ajaxSetCompanyValue($request)
	{
//		$result = array();
				
		$result[] = array(	'field' => $request->field,
							'value' => $request->value . $request->item_id);
		
		return $result;
	}
	
	
}

?>

