#!/usr/bin/env php
<?php

require_once('vendor/autoload.php');

ini_set('display_errors', 0);
error_reporting(0);
set_time_limit(0);

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application();

$console->register('run')
  ->setDescription('Run a controller/action')
  ->addArgument('controlleraction', InputArgument::REQUIRED, 'The controller/action to run')
  ->addArgument('options', InputArgument::IS_ARRAY, 'Options e.g. filter:company file:somefile.csv')
  ->addOption('method', null, InputOption::VALUE_OPTIONAL, 'The method, defaults to GET.')
  ->setCode(function (InputInterface $input, OutputInterface $output) {
    $command = explode('/', $input->getArgument('controlleraction'));
    $_SERVER['REQUEST_METHOD'] = $input->getOption('method') ? $input->getOption('method') : 'GET';
    $_REQUEST['cmd'] = $command[0];
    $_REQUEST['action'] = $command[1];

    $options = $input->getArgument('options');
    if (!empty($options)) {
      foreach ($options as $option) {
        $o = explode(':', $option);
        $_REQUEST[$o[0]] = $o[1];
      }
    }
    // env
    $_ENV['ALCHEMIS_ENV'] = isset($_REQUEST['env']) ? $_REQUEST['env'] : 'development';
    
    require_once('index.php');
  });

$console->run();