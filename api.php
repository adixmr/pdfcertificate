<?php

require('fpdf.php'); //includes fpdf library

$image = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp.png'; //temp.png is our background image, change it with your file's name
$pdf = new FPDF();
$pdf->SetFont('Arial','B',16); //uses arial font to write text on the pdf with size 16
$pdf->AddPage('L'); //Orientaiton of the pdf will be landscape, remove L for portrait mode
$pdf->Image($image,2,2,293,206); //margin and size of the image to be added in pdf. Refer to fpdf documentation.

$pdf->SetXY(150, 89);
$pdf->Write(0, $_REQUEST['name']); //writes name of the participant at the given coordinates

$pdf->SetXY(150, 125);
$pdf->Write(0, $_REQUEST['event']); //writes name of the event at the given coordinates

$pdf->SetXY(65, 165);
$pdf->Write(0, $_REQUEST['date']); //writes date of the event at the given coordinates


$dir = realpath(dirname(__FILE__)) . "/$_REQUEST[event]/"; //path of the directory where the pdf will be stored

if (!file_exists($dir)) {
    mkdir($dir, 0777, true); // creates a directory if it doesn't exist yet. NOTE: for an event name that is previously used, pdf will be added in the same folder
}

$filename = $dir.$_REQUEST['name'].".pdf"; //path of the pdf
$pdf->Output($filename,'F'); //saves the pdf at the given location

?>