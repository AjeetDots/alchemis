<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('include/jpgraph-2.2/jpgraph.php');
require_once('include/jpgraph-2.2/jpgraph_bar.php');
require_once('include/Illumen/Graph.php');

$graph = new Graph(390, 250, 'auto');
$graph->SetMarginColor('#F9F9F9');
$graph->SetScale('textint');
$graph->SetFrame(true, '#F9F9F9', 1);

$labels = array('Team A', 'Team B');
$attended = array(10, 20);
$graph->xaxis->SetTickLabels($labels);

$b1plot = new BarPlot($attended);
$b1plot->SetFillColor(Illumen_Graph::getColor(2));

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5, 0.95, 'center', 'bottom');

$graph->Add($b1plot);

ob_start();
$graph->Stroke();
$out = ob_get_clean();

if (strpos($out, 'Deprecated') !== false || strpos($out, 'Warning') !== false || strpos($out, 'Fatal') !== false) {
    echo "WARNINGS FOUND:\n";
    echo substr($out, 0, 1000);
} else {
    echo "SUCCESS: " . strlen($out) . " bytes of PNG\n";
}
