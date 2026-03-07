<?php

use Illuminate\Translation\Translator as Translator;
use Illuminate\Translation\FileLoader as FileLoader;
use Illuminate\Filesystem\Filesystem as Filesystem;

class Validator extends Illuminate\Validation\Validator {

  public function __construct(array $data, array $rules, array $messages = array(), array $customAttributes = array())
  {
    $loader = new FileLoader(new Filesystem, APP_DIRECTORY . 'app/lang');
    $translator = new Translator($loader, 'en');

    parent::__construct($translator, $data, $rules, $messages, $customAttributes);
  }

}