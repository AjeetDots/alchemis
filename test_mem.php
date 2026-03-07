<?php
ini_set('memory_limit', '1G');
require_once('include/jpgraph-2.2/jpgraph.php');
require_once('include/jpgraph-2.2/jpgraph_bar.php');
$graph = new Graph(390, 250, 'auto');
echo "Graph width: " . $graph->img->width . "\n";
