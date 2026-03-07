<?php

require_once('app/mapper/MailerMapper.php');
require_once('app/domain/Mailer.php');
require_once('app/mapper/MailerItemMapper.php');
require_once('app/domain/MailerItem.php');
require_once('include/jpgraph-2.2/jpgraph.php');
require_once('include/jpgraph-2.2/jpgraph_pie.php');
require_once('include/jpgraph-2.2/jpgraph_pie3d.php');
require_once('include/Utils/Graph.class.php');

class app_command_MailerStatisticsGraph2 extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
		$task = $request->getProperty('task');
		
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}
	
	
	protected function init(app_controller_Request $request)
	{
		$mailer_id = $request->getProperty('id');
		
		$despatch_count = app_domain_MailerItem::countDespatchedDateByMailerId($mailer_id);
		$response_count = app_domain_MailerItem::countResponseDateByMailerId($mailer_id);
		
		if (empty($despatch_count))
		{
			$despatch_count = 0;
		}
		
		if (empty($response_count))
		{
			$response_count = 0;
		}
		
		$data = array();
		$legend = array();
		$colors = array();
		
		if ($response_count > 0)
		{
			$data[]   = $response_count;
			$legend[] = 'Responses';
			$colors[] = illumen_Graph::getColor(2);
		}
		
		if ($despatch_count > 0)
		{
			$data[]   = $despatch_count - $response_count;
			$legend[] = 'No responses';
			$colors[] = illumen_Graph::getColor(5);
		}
		
		//
		// Create a new graph
		// 
		$gJpgBrandTiming = false;
		$graph = new PieGraph(700, 180, 'auto');
		$graph->SetColor(illumen_Graph::getColorPlotBackground());
		$graph->SetFrame(true, '#FFFFFF', 1);
		
		//
		// Title
		//
		$graph->title->Set('Responses / No responses');
		$graph->title->SetFont(FF_FONT1);
		$graph->SetTitleBackground(illumen_Graph::getColorTitleBackground(), TITLEBKG_STYLE1, TITLEBKG_FRAME_BOTTOM, '#FFFFFF', 1);
		
		if (!empty($data))
		{
			// Only create a pie if some data exists (else jpgraph generates an error)
			$p1 = new PiePlot3D($data);
			$p1->SetSize(75);
			$p1->SetLegends($legend);
			$p1->SetCenter(0.20, 0.575);
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
			
	}
}

?>