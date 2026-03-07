<?php

class System
{

  public static function memory()
  {
    $sys = new System;
    return [
      'used' => $sys->prettyBytes(memory_get_usage()),
      'peak' => $sys->prettyBytes(memory_get_peak_usage())
    ];
  }

  public function prettyBytes($size){
    $unit = array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
  }

}
