<?php

use app_controller_Response as Response;

/**
 * @package Alchemis
 */
class app_command_User extends app_command_ResourceCommand
{
	
	public function create()
	{
		$users = app_model_User::with('client')->orderBy('is_active', 'desc')
			->orderBy('handle')
			->get();
			
		$clients = app_model_Clients::orderBy('name')
			->where('is_current', true)
			->get();

		return Response::view('user.index', [
			'users' => $users,
			'clients' => $clients
		]);
	}
	
}

?>