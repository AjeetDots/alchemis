<?php
 /*
     Example24 : X versus Y chart
 */

 // Standard inclusions   
 include("pChart/pData.class");
 include("pChart/pChart.class");

 // Dataset definition 
 $DataSet = new pData;

 // Create a circle
 for($i=0;$i<=360;$i=$i+10)
  {
   $DataSet->AddPoint(cos($i*3.14/180)*20,"Serie1");
   $DataSet->AddPoint(sin($i*3.14/180)*20,"Serie2");
   if ($i%3 == 1 )
    {
     $DataSet->AddPoint(cos($i*3.14/180)*15,"Serie3");
     $DataSet->AddPoint(sin($i*3.14/180)*15,"Serie4");
    }
  }

 $DataSet->SetSerieName("20px Radius","Serie1");
 $DataSet->SetSerieName("15px Radius","Serie3");
 $DataSet->AddSerie("Serie1");
 $DataSet->AddSerie("Serie2");
 $DataSet->SetXAxisName("X Axis");
 $DataSet->SetYAxisName("Y Axis");

 // Initialise the graph
 $Test = new pChart(300,300);
 $Test->setFixedScale(-25,25,5,-25,25,5);

 // Prepare the graph area
 $Test->drawGraphAreaGradient(132,153,172,50,TARGET_BACKGROUND);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->setGraphArea(55,20,270,235);
 $Test->drawXYScale($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","Serie2",213,217,221,TRUE,45);
 $Test->drawGraphArea(213,217,221,FALSE);
 $Test->drawGraphAreaGradient(162,183,202,50);
 $Test->drawGrid(4,TRUE,230,230,230,20);

 // Draw the chart
 $Test->drawXYGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","Serie2",0);
// $Test->drawXYGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3","Serie4",1);
 $Test->drawXYPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3","Serie4",1);

 // Draw the title
 $Title = "Drawing X versus Y charts is now possible  ";
 $Test->drawTextBox(0,280,300,300,$Title,0,255,255,255,ALIGN_RIGHT,TRUE,0,0,0,30);

 // Draw the legend
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->drawLegend(200,10,$DataSet->GetDataDescription(),255,255,255);

 $Test->Render("example24.png");
?>