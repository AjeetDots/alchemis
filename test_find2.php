<?php
$lines = file('include/jpgraph-2.2/jpgraph.php');
foreach ($lines as $i => $line) {
    if (strpos($line, 'image.png') !== false) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}
