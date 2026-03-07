<?php
require 'include/jpgraph-2.2/jpgraph.php';

echo "PHP version: " . PHP_VERSION . "\n";
echo "GD loaded: " . (extension_loaded('gd') ? 'yes' : 'no') . "\n";

if (function_exists('_phpErrorHandler')) {
    $rf = new ReflectionFunction('_phpErrorHandler');
    echo "Handler file: " . $rf->getFileName() . "\n";
    echo "Params: total=" . $rf->getNumberOfParameters()
       . " required=" . $rf->getNumberOfRequiredParameters() . "\n";
} else {
    echo "_phpErrorHandler not defined\n";
}