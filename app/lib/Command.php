<?php

class Command {


  public function run($cmd)
  {
    exec('./command run ' . $cmd . ' &> /dev/null &');
  }

  public function runSync($cmd)
  {
    set_time_limit(0);
    exec('./command run ' . $cmd, $output);
    return $output;
  }

}