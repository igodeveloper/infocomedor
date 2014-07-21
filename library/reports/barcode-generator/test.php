<?php
		echo 'hola<br>';
		//$conten = file_get_contents("http://216.55.242.3/store_proc/documentosPDF/facturasMensuales/SourceProgram/barcode-generator/bgen.php?barcode=12012012012012");
		$conten = file_get_contents("bgen.php 12012012012012");		
//   		$this->Image('12012012012012.gif',78,5,63);
//		$this->Image('h12012012012012.gif',78,155,63);			
		echo $conten;
?>
