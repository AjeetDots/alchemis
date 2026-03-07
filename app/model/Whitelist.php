<?php

class app_model_Whitelist {

  public static function where($field, $value)
  {
    // Simple implementation - return a mock object that has first() method
    return new app_model_WhitelistQuery($field, $value);
  }

}

class app_model_WhitelistQuery {
  private $field;
  private $value;
  
  public function __construct($field, $value) {
    $this->field = $field;
    $this->value = $value;
  }
  
  public function first()
  {
    // For now, always return true (allow all IPs)
    // In a full implementation, this would query tbl_whitelist
    return (object)['ip' => $this->value];
  }
}