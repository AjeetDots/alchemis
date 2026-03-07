<?php
require 'include/jpgraph-2.2/jpgraph.php';
$files = get_included_files();
foreach ($files as $f) {
    if (strpos(file_get_contents($f), 'image.png') !== false) {
        echo "FOUND 'image.png' in $f\n";
    }
}
