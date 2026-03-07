<?php

class app_controller_Input {

  private $values;

  public function __construct($values)
  {
    $this->values = $values;
  }

  public function has($key)
  {
    return array_key_exists($key, $this->values) && !empty($this->values[$key]);
  }

  public function get($key)
  {
    return $this->has($key) ? $this->values[$key] : null;
  }

  public function all()
  {
    return $this->values;
  }
  
  public function only($keys)
  {
    $keys = is_array($keys) ? $keys : func_get_args();
    
    $results = [];
    
    foreach ($keys as $key) {
      if (isset($this->values[$key])) {
        $results[$key] = $this->values[$key];
      }
    }
    
    return $results;
  }

}