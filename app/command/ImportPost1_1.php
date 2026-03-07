<?php

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/Site.php');
require_once('app/domain/ImportCompany.php');

require_once('include/EasySql/EasySql.class.php');

// Ensure the maximum execution time is at least 1200 seconds (20 minutes);
set_time_limit(0);
ini_set('memory_limit', '1280M');
/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportPost1_1 extends app_command_ManipulationCommand
{
    protected $records;
    protected $success;
    protected $failure;

    public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');

		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			$this->processForm($request);
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}


	/**
	 * @param app_controller_Request $request
	 */
	private function init(app_controller_Request $request)
	{

        $this->splitContactNames();

	}

	function splitContactNames()
	{

		 $data = app_domain_ImportPost::doFindRawPosts();
			
        foreach($data as $row)
        {
            $nameArray = explode(' ', $row[5]);
            app_domain_ImportPost::updateContactNames($row[0], $nameArray);
        }
        
        echo count($data) . ' contact names were split';
	}

}
?>
