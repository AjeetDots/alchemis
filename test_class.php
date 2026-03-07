<?php
require_once('include/jpgraph-2.2/jpgraph.php');
$ref = new ReflectionClass('Graph');
foreach($ref->getMethods() as $method) {
    if ($method->getName() == '__construct' || $method->getName() == 'Graph') {
        echo $method->getName() . " defined in " . $method->getFileName() . ":" . $method->getStartLine() . "\n";
    }
}
