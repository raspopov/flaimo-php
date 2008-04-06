<?php
include ("jpgraph.php");
include ("jpgraph_bar.php");

// We need some data
$datay=array(1.22,1.43,1.51,1.11,1.1,1.034,0.955);
$datay2=array(2.22,2.43,2.51,2.11,2.1,2.034,1.955);
$labels = array('A','S','XX','L','H','H','123');

// Setup the graph.
$graph = new Graph(600,550,"flaimotest.png",0.30);
$graph ->img->SetImgFormat( "png");
$graph->SetScale("textlin");
$graph->img->SetMargin(55,50,50,50);
$graph ->SetShadow();
$graph ->ygrid->Show(true,true);
$graph ->xgrid->Show(true,true);
$graph ->yaxis->scale->SetGrace('haus');


$graph->xaxis->SetTickLabels($labels);
$graph->yaxis->SetTickLabels($labels);


$graph->title->Set('Flaimo\'s Chart');
$graph->title->SetColor('black');


$graph->subtitle->Set('Flaimo\'s Chart');
$graph->subsubtitle->Set('Flaimo\'s Chart');

$graph->footer->left->Set('Flaimo\'s Chart');

// Setup font for axis
$graph->xaxis->SetFont(FF_FONT1);

$graph->xaxis->title->Set('X-Achse');


$graph->yaxis->SetFont(FF_FONT1);
$graph ->yaxis->SetColor("red","blue");


// Create the bar pot
$bplot = new BarPlot($datay);
$bplot->SetWidth(0.6);

// Setup color for gradient fill style
$bplot->SetFillGradient("red","lightsteelblue",GRAD_HOR);

// Set color for the frame of each bar
$bplot->SetColor("navy");


// Create the bar pot
$bplot2 = new BarPlot($datay2);
$bplot2->SetWidth(0.6);

// Setup color for gradient fill style
$bplot2->SetFillGradient("green","lightsteelblue",GRAD_HOR);

// Set color for the frame of each bar
$bplot2->SetColor("red");

$bplot2->value->Show();


$gbplot  = new GroupBarPlot (array($bplot,$bplot2));
$gbplot->SetWidth(0.5);

$graph->Add($gbplot);

// Finally send the graph to the browser
$graph->Stroke();
?>