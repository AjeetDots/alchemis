<?php

use app_controller_Request as Request;

class app_command_BaseCommand extends app_command_Command {

  public $request;

  public function doExecute(Request $request)
  {
    $this->request = $request;
    $this->prepare();
    return $this->run();
  }

  public function prepare()
  {
    // extendable function
  }

  public function run()
  {
    $request = $this->request;
    if(method_exists($this, $request->action)){
      return call_user_func([$this, $request->action]);
    }else{
      $classname = get_class($this);
      throw new Exception("Method {$request->action} on {$classname} does not exist");
    }
  }

}