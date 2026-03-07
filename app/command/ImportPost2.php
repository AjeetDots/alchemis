<?php

require_once('app/command/ManipulationCommand.php');
// require_once('include/Utils/Utils.class.php');
// require_once('include/Utils/String.class.php');
// require_once('app/domain/Site.php');
// require_once('app/domain/ImportCompany.php');
// require_once('app/domain/PostInitiative.php');
// require_once('app/domain/PostInitiativeNote.php');
// require_once('app/domain/TieredCharacteristic.php');

require_once('batch/import/StandardImport.class.php');
require_once('include/Zend/Debug.php');

/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportPost2 extends app_command_ManipulationCommand
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
        $request->setProperty('action', 'init');
    }


    private function processForm(app_controller_Request $request)
    {
    	
    	$withRollBack = $request->getProperty('with_rollback');
    	$withRollBack == 'on' ? $withRollBack = true : $withRollBack = false;
    	$logFile = 'var/import-logs/import_' . date('Ymd') . '_' . date('His') . '.txt';
    	
//    	Zend_Debug::dump(APP_DIRECTORY . $logFile);
//    	die();
    	$import = new batch_import_StandardImport(APP_DIRECTORY . $logFile, $withRollBack, true, true);
    	
    	$result = $import->getResult();
    	
    	$request->setProperty('action', 'completed');
    	$request->setProperty('company_processed_count',$result['processed']['companies'] );
    	$request->setProperty('contact_processed_count',$result['processed']['posts'] );
    	
    	$request->setProperty('company_added_count',$result['additions']['companies'] );
    	$request->setProperty('contact_added_count', $result['additions']['posts']);
    	
    	$request->setProperty('company_existing_count',$result['existing']['companies'] );
    	$request->setProperty('contact_existing_count', $result['existing']['posts']);
    	
    	$request->setProperty('company_duplicates_count',$result['duplicates']['companies'] );
    	$request->setProperty('contact_duplicates_count', $result['duplicates']['posts']);
    	
    	$request->setProperty('company_added_count',$result['additions']['companies'] );
    	$request->setProperty('contact_added_count', $result['additions']['posts']);
    	
    	$request->setProperty('company_failure_count',$result['failures']['companies'] );
    	$request->setProperty('contact_failure_count', $result['failures']['posts']);
    	
    	$request->setProperty('log_file_path',$logFile);
    }
}
?>
