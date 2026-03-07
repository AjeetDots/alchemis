<?php

require_once('app/command/ManipulationCommand.php');
require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');
require_once('app/domain/Client.php');
require_once('app/domain/Company.php');

/**
 * Extends the base Command object by adding function(s) to handle validation
 * and help with making field values sticky.
 * @package Alchemis
 */
class app_command_ImportCompany5 extends app_command_ManipulationCommand
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
			exit();
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

        $request->setObject('client_initiative_lkp_data', $this->getClientInitiativeLkpData());

	}

    private function getClientInitiativeLkpData()
    {
        // client_id
        if ($items = app_domain_Client::findAllClientInitiatives())
        {
            $options = array();
            $options[0] = '-- select --';
            foreach ($items as $item)
            {
                $options[$item['initiative_id']] = @C_String::htmlDisplay(ucfirst($item['client_initiative_display']));
            }

            return $options;
        }
    }


    /**
     * Handles the processing of the form, trying to save each object. Assumes
     * any validation has already been performed.
     * @param app_controller_Request $request
     */
    protected function processForm(app_controller_Request $request)
    {
      	$properties = $request->getProperties();

      	// now update the client initiative id field
       	app_domain_ImportCompany::updateClientInitiativeId($request->getProperty('client_id'));

    	echo 'Client Initiative ID updated';
	    return true;
    }
}
?>
