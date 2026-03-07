<?php
if (!defined('ROOT_PATH'))
{
	define('ROOT_PATH', '../../');
}

//require_once(ROOT_PATH.'config/config.php');
//require_once(ROOT_PATH.'config/session.php');
//require_once(ROOT_PATH.'library/MarketingItem.class.php');
require_once(ROOT_PATH.'includes/jpgraph/jpgraph.php');
require_once(ROOT_PATH.'includes/jpgraph/jpgraph_pie.php');
require_once(ROOT_PATH.'includes/jpgraph/jpgraph_pie3d.php');
//require_once(ROOT_PATH.'library/Pipeline_campaign.class.php');

//
//----- Start Session Manangement -----
//
require_once(ROOT_PATH.'library/authenticate.php');
//
//------  End Session Manangement ------
//

$id = isset($_GET['id']) ? $_GET['id'] : null;

$itemCount     = MarketingItem::getItemListCount($id);
$dispatchCount = MarketingItem::getItemDispatchCount($id);

if (empty($itemCount))
{
	$itemCount = 0;
}

if (empty($dispatchCount))
{
	$dispatchCount = 0;
}


$data = array();
$legend = array();
$colors = array();

if ($dispatchCount > 0)
{
	$data[]   = $dispatchCount;
	$legend[] = 'Dispatched';
	$colors[] = Pipeline_campaign::getColor(5);
}

if ($itemCount > 0)
{
	$data[]   = $itemCount - $dispatchCount;
	$legend[] = "Not dispatched";
	$colors[] = Pipeline_campaign::getColor(2);
}



//
// Create a new graph
// 
$gJpgBrandTiming = false;
$graph = new PieGraph(700, 180, 'auto');
$graph->SetColor(Pipeline_campaign::getColorPlotBackground());
$graph->SetFrame(true, '#FFFFFF', 1);

//
// Title
//
$graph->title->Set('Total items / dispatched');
$graph->title->SetFont(FF_FONT1);
$graph->SetTitleBackground(Pipeline_campaign::getColorTitleBackground(), TITLEBKG_STYLE1, TITLEBKG_FRAME_BOTTOM, '#FFFFFF', 1);

if (!empty($data))
{
	// Only create a pie if some data exists (else jpgraph generates an error)
	$p1 = new PiePlot3D($data);
	$p1->SetSize(75);
	$p1->SetLegends($legend);
	$p1->SetCenter(0.20, 0.575); //.325
	$p1->SetLabelType(PIE_VALUE_ABS);
	$p1->value->SetFormat('%d');
	$p1->value->Show(true);
	$p1->SetSliceColors($colors);
	$graph->Add($p1);
}
else
{
	// Inform user no data was found
	$txt = new Text('< No data found >');
	$txt->SetPos(0.5, 0.575, 'center', 'center');
	$graph->AddText($txt);
}

$graph->legend->Pos(0.50, 0.35, 'left', 'center');
$graph->Stroke();

?>
