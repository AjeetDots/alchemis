<?php

class app_view_BaseView extends app_view_View
{

  private $template;
  private $params = array();

  public function __construct($template, $params)
  {
    parent::__construct();
    // allow laravel way of directories with .
    $template = str_replace('.', '/', $template);
    $this->template = $template . '.tpl';
    $this->params = $params;
  }

  public function setTemplate($template)
  {
    $this->template = $template;
  }

  public function getTemplate()
  {
    return $this->template;
  }

  public function setParam($key, $value)
  {
    $this->params[$key] = $value;
  }

  public function getParam($key)
  {
    return $this->params[$key];
  }

  public function setParams($params)
  {
    foreach($params as $key => $param){
      $this->setParam($key, $param);
    }
  }

  public function getParams()
  {
    return $this->params;
  }

  public function render()
  {
    $this->smarty->assign($this->params);
    return $this->smarty->fetch($this->template);
  }

  // for backwards compatibility?!
  public function doExecute()
  {
    echo $this->render();
  }

}