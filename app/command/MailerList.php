<?php

require_once('app/command/ManipulationCommand.php');
require_once('app/mapper/MailerMapper.php');
require_once('app/domain/Mailer.php');

class app_command_MailerList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
//		require_once('Auth/Session.php');
//		$session = Auth_Session::singleton();
//		$request->setObject('user', $session->getSessionUser());

		$task = $request->getProperty('task');

		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{

		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}


	protected function init(app_controller_Request $request)
	{
		switch ($request->getProperty('display')) {
			case 'archived':
				$mailers = app_domain_Mailer::findArchived();
				break;
			case 'all':
                $mailers = app_domain_Mailer::findAll();
                break;
			case 'current':
			default:
				$mailers = app_domain_Mailer::findCurrent();
				break;
		}

//		echo '<pre>';
//		print_r($mailers);
//		echo '</pre>';

		$request->setObject('mailers', $mailers);
	}

}

?>