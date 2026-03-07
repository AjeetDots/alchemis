<?php

use app_controller_Response as Response;
use Carbon\Carbon;

class app_command_Whitelist extends app_command_ResourceCommand
{

  public function create()
  {
    $whitelist = app_model_Whitelist::all();

    foreach ($whitelist as $item) {
      $log = $item->logins()->orderBy('created_at', 'desc')->first();
      if ($log) {
        $created = new Carbon($log->created_at);
        $item->last_login = $created->toDayDateTimeString() . ' by ' . $log->user->name;
      }
    }

    return Response::view('whitelist.index', [
      'whitelist' => $whitelist
    ]);
  }

  public function store()
  {
    $input = $this->request->input;
    $data = $input->only('ip', 'description');

    // validation
    $validator = new Validator($data, [
      'ip' => 'required'
    ]);

    if ($validator->fails()) {
      $messages = $validator->messages();
      return Response::view('whitelist.index', [
        'errors' => $messages->toArray()
      ]);
    }

    app_model_Whitelist::create($data);

    return $this->create();
  }

  public function destroy()
  {
    $id = $this->request->input->get('id');
    app_model_Whitelist::destroy($id);
    return Response::redirect(['url' => $this->request->referrer]);
  }

}