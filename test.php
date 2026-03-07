<?php
ob_start();
require 'include/jpgraph-2.2/jpgraph.php';
$out = ob_get_clean();
echo 'Output length: ' . strlen($out) . "\n";
echo 'Content: ' . substr($out, 0, 100) . "\n";
