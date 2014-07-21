<?php
/**
 *
 * Carlos Ruiz Diaz, carlos.ruizdiaz@conexiongroup.com
 * As_PY - 2010
 *
 */

define('IMG_FORMAT',	'gif');

// Including all required classes
require('barcodegen.1d-php5.v2.1.0/class/BCGFont.php');
require('barcodegen.1d-php5.v2.1.0/class/BCGColor.php');
require('barcodegen.1d-php5.v2.1.0/class/BCGDrawing.php'); 

// Including the barcode technology
include('barcodegen.1d-php5.v2.1.0/class/BCGcode128.barcode.php');

function GenerateBarcode($barcodeDir, $barcodeString)
{

	$font = new BCGFont('barcodegen.1d-php5.v2.1.0/class/font/Arial.ttf', 8);
	
	// The arguments are R, G, B for color.
	$color_black = new BCGColor(0, 0, 0);
	$color_white = new BCGColor(255, 255, 255); 
		
	$code = new BCGcode128();	
	
	$code->setScale(1); // Resolution
	$code->setThickness(55); // Thickness
	$code->setForegroundColor($color_black); // Color of bars
	$code->setBackgroundColor($color_white); // Color of spaces
	$code->setFont($font); // Font (or 0)

	$code->parse($barcodeString); // Text

	$drawing = new BCGDrawing('', $color_white);
	
	
	$drawing->setBarcode($code);
	$drawing->setFilename("$barcodeDir/$barcodeString.gif");
	$drawing->draw();

	//header('Content-Type: image/'.IMG_FORMAT);
	$drawing->finish(BCGDrawing::IMG_FORMAT_GIF);
}

$cwd			= getcwd();
$file			= "$cwd/{$_GET['barcode']}.".IMG_FORMAT;
//echo "---> $file";
//exit(0);
if (!file_exists($file))
	GenerateBarcode("$cwd/", $_GET['barcode']);

header('Content-Type: image/'.IMG_FORMAT);
echo file_get_contents($file);


?>
