<?php

class app_command_ResourceCommand extends app_command_BaseCommand {

  public function prepare()
  {
    $request = $this->request;
    $input = $request->input;

    if(!$request->action){

      if($request->method == 'GET' && $input->has('id')){
        $request->action = 'show';
      }else if($request->method == 'GET'){
        $request->action = 'create';
      }else if($request->method == 'POST' && $input->has('id')){
        $request->action = 'update';
      }else if($request->method == 'POST'){
        $request->action = 'store';
      }else if($request->method == 'PUT'){
        $request->action = 'update';
      }else if($request->method == 'DELETE'){
        $request->action = 'destroy';
      }

    }
  }

}