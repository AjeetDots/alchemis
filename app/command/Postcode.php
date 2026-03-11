<?php

use app_controller_Response as Response;

class app_command_Postcode extends app_command_ResourceCommand
{

  public function create()
  {
    $postcodes = app_model_Postcode::all();
    
    return Response::view('postcode.index', [
      'postcodes' => $postcodes,
      'errors' => [],
      'input' => []
    ]);
  }
  
  public function store()
  {
    $input = $this->request->input;
    $data = $input->only('postcode');
    
    // validation
    $validator = new Validator($data, [
      'postcode' => 'required'
    ]);
    
    if ($validator->fails()) {
      $messages = $validator->messages();
      return Response::view('postcode.index', [
        'postcodes' => app_model_Postcode::all(),
        'errors' => $messages->toArray(),
        'input' => $data
      ]);
    }
    
    app_model_Postcode::create($data);
    
    return $this->create();
  }

}