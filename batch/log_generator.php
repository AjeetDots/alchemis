<?php


// echo date("d_M_Y__H_i_s");die;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



function generate_log($path, $file, $text){
    $logPath = 'log/' . $path;

    try{
        if (!file_exists($logPath)) {
            mkdir($logPath, 0777, true);
        }
    }catch(Exception $e){

    }

    $logFile = fopen($logPath .'/'.$file, "a") or die("Unable to open log file!");
    fwrite($logFile, $text . "\n");
    fclose($logFile);
    return true;
}