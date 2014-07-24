<?php
/*
*	Conexion S.A.
*	Autor : Federico Cano
*	Fecha : 01-03-2012
*	Modificado :
*/
        //define("PATH_FPDF","/var/www/html/store_proc/FPDF/fpdf16/");
	//require(PATH_FPDF.'fpdf.php');
	//require('/var/www/html/store_proc/documentosPDF/n2t.class.php');
    class PDFFacturaPY extends FPDF
    {
        var $var_Cliente;
        var $var_Direccion;
        var $var_BarrioCiudad;
        var $var_Cliid;
        var $var_Clisuc;
        var $var_PeriodoFacturadoDesde;
        var $var_PeriodoFacturadoHasta;
        var $var_FacNro = 1;
        var $var_MontoTotal;	
        var $Conn;	
        var $var_Empresa;
        var $var_Sucursal;
        var $B;
        var $I;
        var $U;
        var $HREF;
        var $var_FechaDesde;
        var $var_FechaHasta;
        var $trans;
        var $parametros;

        public $xIniTit1 = 15; // Posicion X del Titulo 1 del Encabezado
        public $xIniTit2 = 140; // Posicion X del Titulo 2 del Encabezado
        public $xIniDat1 = 45; // Posicion X del los Datos 1 del Encabezado
        public $xIniDat2 = 160; // Posicion X del los Datos 2 del Encabezado

        public $sepEncab = 3; //Espacio de separacion entre Lineas del Encabezado
        public $sepDet = 5; //Espacio de separacion entre Lineas del Detalle
        public $yBlo1 = 40;
        public $yBlo2 = 0;
        public $yBlo3 = 0;
        public $yBlo4 = 0;
        public $yBlo5 = 0;
        public $yBlo1_Dup = 0;
        public $yBlo2_Dup = 0;
        public $yBlo3_Dup = 0;
        public $yBlo4_Dup = 0;
        public $yBlo5_Dup = 0;
        public $reng1 = 1;
        public $reng2 = 6;
        public $reng3 = 11;


        function PDFFacturaPY($orientation='P',$unit='mm',$format='A4',$parametros)
        {
            $this->yBlo2 = $this->yBlo1 + 15; //Pos del Inicio del Cuadro de Los Detalles
            $this->yBlo3 = $this->yBlo2 + 10; // Alto del Area del titulo de los Detalles
            $this->yBlo4 = $this->yBlo3 + 35; // Alto del Area de Detalles
            $this->yBlo5 = $this->yBlo4 + 15; // Alto del Area de Totales de los Detalles
            $diferencia = 146; // diferencia de separacion entre Facturas
            $this->yBlo1_Dup = $this->yBlo1 + $diferencia;
            $this->yBlo2_Dup = $this->yBlo2 + $diferencia;
            $this->yBlo3_Dup = $this->yBlo3 + $diferencia;
            $this->yBlo4_Dup = $this->yBlo4 + $diferencia;
            $this->yBlo5_Dup = $this->yBlo5 + $diferencia;
            $this->parametros = $parametros;

            //Llama al constructor de la clase padre
            $this->FPDF($orientation,$unit,$format);
            //Iniciaci�n de variables
            $this->Conn = new Conexion();	
            //$this->tipo = $tipo;
            $this->B=0;
            $this->I=0;
            $this->U=0;
            $this->HREF='';   	
            $this->SetAutoPageBreak(false);
            $this->trans = new N2t();				 	    
        }		
        //Cabecera de p�gina
        function Header()
        {
            $this->Image("./css/images/infocomedor.jpg",4,10,50,30);
            $this->Image("./css/images/infocomedor.jpg",4,155,50,30);
            $this->SetY(0);
            $this->setDatosCabecera();
            $this->setEsqueleto();
            //$this->SetY(148);
            //$this->Image("./css/images/infocomedor.jpg",4,155,50,30);
            //$this->setDatosCabecera();   		
        }		
		
        function setEsqueleto()
        {

            $this->SetFont('Arial','B',7);		    	    
            $this->SetFillColor(200);

            $y1 = $this->yBlo2;
            $y2 = $this->yBlo2 + (($this->yBlo3 -$this->yBlo2) /2);
            $y3 = $this->yBlo3;
            $y4 = $this->yBlo4;
            $y5 = $this->yBlo5;
            $reng1 = $this->reng1;
            $reng2 = $this->reng2;
            $reng3 = $this->reng3;
            $y1_1 = $this->yBlo2 + $reng1;
            $y1_2 = $this->yBlo2 + $reng2 ;
            $y4_1 = $this->yBlo4 + $reng1;
            $y4_2 = $this->yBlo4 + $reng2 ;
            $y4_3 = $this->yBlo4 + $reng3;

            $l1 = $this->yBlo5 - $this->yBlo2; //Largo de la  1ra y ultima linea vertical
            $l2 = $this->yBlo4 - $this->yBlo3; // Largo de las lineas verticales Articulo y Cantidad
            $l3 = $this->yBlo4 - $this->yBlo2; //Largo de las lineas verticales Clase Serv y Precio
            $l4 = $this->yBlo4  - $y2;
            $l5 = ($this->yBlo3 -$this->yBlo2);// Alto del Encabezado Sombreado del Detalle
            $l6 = ($this->yBlo5 -$this->yBlo4);// Alto del Pie Sombreado del Detalle


            for($i = 1;$i <= 2;$i++){
                if($i== 2){		
                    $y1 = $this->yBlo2_Dup;
                    $y2 = $this->yBlo2_Dup +(($this->yBlo3_Dup -$this->yBlo2_Dup) /2);
                    $y3 = $this->yBlo3_Dup;
                    $y4 = $this->yBlo4_Dup;
                    $y5 = $this->yBlo5_Dup;
                    $y1_1 = $this->yBlo2_Dup + $reng1;
                    $y1_2 = $this->yBlo2_Dup + $reng2;
                    $y4_1 = $this->yBlo4_Dup + $reng1;
                    $y4_2 = $this->yBlo4_Dup + $reng2;
                    $y4_3 = $this->yBlo4_Dup + $reng3;
                }

                //Pintar el sombreado
                $this->SetXY(15.1,$y1);	
                $this->Cell(185,$l5,'',0,0,'L',true);	    
                $this->SetXY(15.1,$y4);	
                $this->Cell(185,$l6,'',0,0,'L',true);	   	
                                        // Imprimir Lineas
                $this->printLine(15,$y1,200,$y1);	//1ra linea horizontal
                $this->printLine(113,$y1,113,$y1+$l3);	// separador de clase vertical
                $this->printLine(130,$y1,130,$y1+$l3);	// vertical de exentas
                $this->printLine(15,$y1,15,$y1+$l1);  //1ra linea vertical izq
                $this->printLine(200,$y1,200,$y1+$l1);//2da linea vertical der			  

                $this->printLine(130,$y2,200,$y2);	// horizontal de valor venta
                $this->printLine(153,$y2,153,$y2+$l4);	// vertical de 5%
                $this->printLine(176,$y2,176,$y2+$l4);	// vertical de 10%

                $this->printLine(35,$y3,35,$y3+$l2);	// separador de articulo vertical
                $this->printLine(55,$y3,55,$y3+$l2);	// separador de cantidad vertical
                $this->printLine(15,$y3,200,$y3); //2da linea horizontal	    		    		    		    

                $this->printLine(15,$y4,200,$y4);//3ra linea horizontal
                $this->printLine(15,$y5,200,$y5);//6ta linea horizontal

                // Imprimir Titulos de Factura
                $this->SetXY(16,$y1_2); $this->Cell(30,3,'Producto',0,0,'L');
                $this->SetXY(39,$y1_2); $this->Cell(30,3,'Cantidad',0,0,'L');
                $this->SetXY(60,$y1_2); $this->Cell(30,3,utf8_decode('Descripción de Producto'),0,0,'L');						
                $this->SetXY(116,$y1_1); $this->Cell(30,3,'Precio',0,0,'L');			    		    	    			
                $this->SetXY(116,$y1_2); $this->Cell(30,3,'Unitario',0,0,'L');			    		    	    		
                $this->SetXY(153,$y1_1); $this->Cell(30,3,'Valor  de  Venta',0,0,'L');			    		    	    				    		    
                $this->SetXY(135,$y1_2); $this->Cell(30,3,'Exentas',0,0,'L');			    		    	    				    		    	    
                $this->SetXY(162,$y1_2); $this->Cell(30,3,'5%',0,0,'L');			    		    	    				    		    	    	    
                $this->SetXY(184,$y1_2); $this->Cell(30,3,'10%',0,0,'L');			    		    	    				    		    	    	    	    						
                $this->SetXY(16,$y4_1); $this->Cell(30,3,'Subtotales',0,0,'L');			    		    	    				    		    	    	    	    							    
                $this->SetXY(16,$y4_2); $this->Cell(30,3,'Total a pagar',0,0,'L');
                $this->SetXY(16,$y4_3); $this->Cell(30,3,utf8_decode('Liquidación del IVA'),0,0,'L');			    		    	    				    		    	    	    	    							    	    
                $this->SetXY(76,$y4_3); $this->Cell(30,3,'5%',0,0,'L');			    		    	    				    		    	    	    	    							    	    	    
                $this->SetXY(115,$y4_3); $this->Cell(30,3,'10%',0,0,'L');
                $this->SetXY(145,$y4_3); $this->Cell(30,3,'Total',0,0,'L');	  
            }
        }
        function setDatosCabecera()
        {
            $xTit1 = $this->xIniTit1;
            $xTit2 = $this->xIniTit2;
            $yTit = $this->yBlo1;	
            $this->SetFont('Arial','',7);

            for($i = 1;$i <= 2;$i++){
                if($i== 2){		
                    $yTit = $this->yBlo1_Dup;	                           
                }
                $this->SetXY($xTit1,$yTit);	$this->Cell(30,3,utf8_decode('Fecha de Emisión:') ,0,0,'L');
//	$this->SetXY($xTit2,$yTit);	$this->Cell(30,3,'Cond. de Venta:CONTADO/   /  CR�DITO/   /',0,0,'L');	
                $yTit = $yTit + ($this->sepEncab);
                $this->SetXY($xTit1,$yTit);	$this->Cell(30,3,utf8_decode('Nombre o Razón Social:'),0,0,'L');
                $this->SetXY($xTit2,$yTit);	$this->Cell(30,3,'R.U.C.  /  C.I.:',0,0,'L');	
                $yTit = $yTit + ($this->sepEncab);
                $this->SetXY($xTit1,$yTit);	$this->Cell(30,3,utf8_decode('Dirección:'),0,0,'L');
                $this->SetXY($xTit2,$yTit);	$this->Cell(30,3,'Vencimiento:',0,0,'L');				
                $yTit = $yTit + ($this->sepEncab);
                $this->SetXY($xTit1,$yTit);	$this->Cell(30,3,'Barrio / Ciudad:',0,0,'L');
                $this->SetXY($xTit2,$yTit);	$this->Cell(30,3,'Control Interno:',0,0,'L');			
                $yTit = $yTit + ($this->sepEncab);
                $this->SetXY($xTit1,$yTit); $this->Cell(30,3,utf8_decode('Telófono:'),0,0,'L');	
                $this->SetXY($xTit2,$yTit);	$this->Cell(30,3,'Cod. Cliente:',0,0,'L');					
            }				
        }	
        function Body()
        {												

            //$this->codigo_barra =	$this->obtiene_codigoBarra($var_empresa,$var_sucursal,$var_serie_doc,$var_codigo, $FacMonTotF, $var_facfvtoi); 				   

//echo dirname(__FILE__)."/barcode-generator/bgen.php?barcode=".$this->codigo_barra;
//die();
//                   $conten = file_get_contents(dirname(__FILE__)."/barcode-generator/bgen.php?barcode=".$this->codigo_barra);
//                   $this->Image(dirname(dirname(__FILE__)).'/barcode-generator/'.$this->codigo_barra.'.gif',78,5,63);
//                   $this->Image(dirname(dirname(__FILE__)).'/barcode-generator/'.$this->codigo_barra.'.gif',78,155,63);



            $this->datosCabecera();            
            $this->cargaDatos();
        }		

        function datosCabecera()
        {
            $sql="select a.FAC_FECHA_EMI,b.CLIENTE_DES,b.CLIENTE_DIRECCION,b.CLIENTE_TELEFONO,b.CLIENTE_RUC,a.FAC_FECH_VTO,a.FAC_NRO,a.COD_CLIENTE
                from factura a 
                inner join cliente b on
                a.COD_CLIENTE = b.COD_CLIENTE
                where a.FAC_NRO = ".$this->parametros;            
            //echo $sql."<br>";                              
            $dtDatos = $this->Conn->query($sql);
            
            while($row = mysql_fetch_assoc($dtDatos))
            {                
                $CLIENTE_DES        = $row['CLIENTE_DES'];
                $FAC_FECHA_EMI      = substr($row['FAC_FECHA_EMI'],8,2).'/'.substr($row['FAC_FECHA_EMI'],5,2).'/'.substr($row['FAC_FECHA_EMI'],0,4);
                $CLIENTE_DIRECCION  = $row['CLIENTE_DIRECCION'];
                $CLIENTE_TELEFONO   = $row['CLIENTE_TELEFONO'];
                $CLIENTE_RUC        = $row['CLIENTE_RUC'];
                $FAC_FECH_VTO       = substr($row['FAC_FECH_VTO'],8,2).'/'.substr($row['FAC_FECH_VTO'],5,2).'/'.substr($row['FAC_FECH_VTO'],0,4);
                $FAC_NRO            = $row['FAC_NRO'];
                $COD_CLIENTE        = $row['COD_CLIENTE'];

                $this->calFecha($row['FAC_FECHA_EMI']);
                
                $xTit1 = $this->xIniDat1;
                $xTit2 = $this->xIniDat2;
                $yTit = $this->yBlo1;	
                $yTit = $yTit + ($this->sepEncab);
                for($i =1; $i<=2; $i++){
                    if($i == 2){ 
                        $yTit = $this->yBlo1_Dup;
                        $yTit = $yTit + ($this->sepEncab);
                    }
                    $this->SetFont('Arial','',7);
                    //$this->SetXY($xTit1,$yTit);	
                    //$this->Cell(30,3,$FAC_FECHA_EMI,0,0,'L');                    
                    //$yTit = $yTit + ($this->sepEncab);
                    $this->SetXY($xTit1,$yTit);	
                    $this->Cell(30,3,$CLIENTE_DES,0,0,'L');			
                    $this->SetXY($xTit2,$yTit);				
                    $this->Cell(30,3,$CLIENTE_RUC,0,0,'L');
                    $yTit = $yTit + ($this->sepEncab);
                    $this->SetXY($xTit1,$yTit);	
                    $this->Cell(30,3,$CLIENTE_DIRECCION,0,0,'L');				
                    $this->SetXY($xTit2,$yTit);				
                    $this->Cell(30,3,$FAC_FECH_VTO,0,0,'L');
                    $yTit = $yTit + ($this->sepEncab);
                    $this->SetXY($xTit1,$yTit);	
                    $this->Cell(30,3,'',0,0,'L');								
                    $this->SetXY($xTit2,$yTit);				
                    $this->Cell(30,3,$FAC_NRO,0,0,'L');                    
                    $yTit = $yTit + ($this->sepEncab);
                    $this->SetXY($xTit1,$yTit);	
                    $this->Cell(30,3,$CLIENTE_TELEFONO,0,0,'L');				
                    $this->SetXY($xTit2,$yTit);				
                    $this->Cell(30,3,$COD_CLIENTE,0,0,'L');
                }                
            }             	 		
        }
        function cargaDatos()
        {
            $sql = "select b.COD_PRODUCTO,b.FAC_DET_TOTAL,d.PRODUCTO_DESC,d.PRECIO_VENTA,c.COD_IMPUESTO,c.FACT_IMP_MONTO,
                e.IMP_PORCENTAJE,b.FAC_DET_CANTIDAD
                from factura a 
                inner join factura_detalle b on
                a.FAC_NRO = b.FAC_NRO
                inner join factura_impuesto c on
                c.FAC_NRO = b.FAC_NRO and
                c.FAC_IMPUESTO_ITEM = b.FAC_DET_ITEM
                inner join producto d on
                d.COD_PRODUCTO = b.COD_PRODUCTO
                inner join impuesto e on
                e.COD_IMPUESTO = c.COD_IMPUESTO
                where a.FAC_NRO =".$this->parametros;
            //echo $sql."<br>";                              
            $dtDatos = $this->Conn->query($sql);

            $salto              = $this->yBlo3 + 1;
            $salto_doble        = $this->yBlo3_Dup + 1;
            $sub_total_exenta   = 0;
            $sub_total_5        = 0;
            $sub_total_10       = 0;
            $total_iva          = 0;
            $total_total        = 0;
            $ERUT               = 0;
            while($row = mysql_fetch_assoc($dtDatos))
            {		
                $COD_PRODUCTO    = $row['COD_PRODUCTO'];
                $FAC_DET_TOTAL    = $row['FAC_DET_TOTAL'];
                $PRODUCTO_DESC    = $row['PRODUCTO_DESC'];
                $PRECIO_VENTA    = $row['PRECIO_VENTA'];
                $COD_IMPUESTO    = $row['COD_IMPUESTO'];
                $FACT_IMP_MONTO    = $row['FACT_IMP_MONTO'];
                $IMP_PORCENTAJE    = $row['IMP_PORCENTAJE'];
                $FAC_DET_CANTIDAD    = $row['FAC_DET_CANTIDAD'];


                $var_FACITEMONF = $var_FACITEMONF + $var_FIMPIMPF;

                $this->SetFont('Arial','',7);		    	    
                $this->SetXY(15,$salto);	
                $this->Cell(30,3,$COD_PRODUCTO,0,0,'L');						    					
                $this->SetXY(15,$salto_doble);	
                $this->Cell(30,3,$COD_PRODUCTO,0,0,'L');				
                $this->SetXY(35,$salto);	
                $this->Cell(30,3,$FAC_DET_CANTIDAD,0,0,'L');
                $this->SetXY(35,$salto_doble);	
                $this->Cell(30,3,$FAC_DET_CANTIDAD,0,0,'L');	    	
                $this->SetXY(55,$salto);	
                $this->Cell(30,3,substr($PRODUCTO_DESC,0,40),0,0,'L');						    					
                $this->SetXY(55,$salto_doble);	
                $this->Cell(30,3,substr($PRODUCTO_DESC,0,40),0,0,'L');						    					
                $this->SetXY(100,$salto);	
                $this->Cell(30,3,number_format(CEIL($PRECIO_VENTA),0,',','.'),0,0,'R');
                $this->SetXY(100,$salto_doble);	
                $this->Cell(30,3,number_format(CEIL($PRECIO_VENTA),0,',','.'),0,0,'R');

                if($IMP_PORCENTAJE == 0)
                {					
                    $this->SetXY(123,$salto);	
                    $this->Cell(30,3,number_format(CEIL($FAC_DET_TOTAL),0,',','.'),0,0,'R');					
                    $this->SetXY(123,$salto_doble);	
                    $this->Cell(30,3,number_format(CEIL($FAC_DET_TOTAL),0,',','.'),0,0,'R');						    		
                    $sub_total_exenta   += $FAC_DET_TOTAL;
                    $total_total        += $FAC_DET_TOTAL;
                }
                if($IMP_PORCENTAJE == 5)
                {					
                    $this->SetXY(146,$salto);	
                    $this->Cell(30,3,number_format(CEIL($FAC_DET_TOTAL),0,',','.'),0,0,'R');					
                    $this->SetXY(146,$salto_doble);	
                    $this->Cell(30,3,number_format(CEIL($FAC_DET_TOTAL),0,',','.'),0,0,'R');						    		
                    $sub_total_5 += $FAC_DET_TOTAL;
                    $total_total += $FAC_DET_TOTAL;
                }				
                if($IMP_PORCENTAJE == 10)
                {					
                    $this->SetXY(170,$salto);	
                    $this->Cell(30,3,number_format(CEIL($FAC_DET_TOTAL),0,',','.'),0,0,'R');					
                    $this->SetXY(170,$salto_doble);	
                    $this->Cell(30,3,number_format(CEIL($FAC_DET_TOTAL),0,',','.'),0,0,'R');						    		
                    $sub_total_10   += $FAC_DET_TOTAL;
                    $total_total    += $FAC_DET_TOTAL;
                }		
                $salto 		+= + $this->sepDet;
                $salto_doble 	+= + $this->sepDet;	
            }	
            unset($sql);unset($dtDatos);unset($row);

            $posy = $this->yBlo5 + 1;
            $posyDup = $this->yBlo5_Dup + 1;

            $xTit1 = $this->xIniTit1;
            $xTit2 = $this->xIniTit2;
            $yTit = $this->yBlo1;	
            $yTitTot = $this->yBlo4;	
            $total_iva += ($sub_total_5 / 11) +  ($sub_total_10 / 11);
            for($i = 1;$i <= 2;$i++){
                if($i== 2){	
                    $yTit = $this->yBlo1_Dup;
                    $yTitTot = $this->yBlo4_Dup;
                }

                $this->SetXY($xTit2,$yTit);	
                $this->Cell(30,3,'Cond. de Venta:CONTADO/ X /  CREDITO/   /',0,0,'L');					    					

                if($sub_total_exenta <> 0)
                {
                //$this->SetXY(123,96);	
                    $this->SetXY(123,($yTitTot+$this->reng1));	$this->Cell(30,3,number_format(CEIL($sub_total_exenta),0,',','.'),0,0,'R');					

    //					$this->SetXY(123,242);	
    //					$this->Cell(30,3,number_format(CEIL($sub_total_exenta),0,',','.'),0,0,'R');					
                }
                if($sub_total_5 <> 0)
                {
                    $this->SetXY(146,($yTitTot+$this->reng1));	$this->Cell(30,3,number_format(CEIL($sub_total_5),0,',','.'),0,0,'R');					  		
                    $this->SetXY(80,($yTitTot+$this->reng3));	$this->Cell(30,3,number_format(CEIL($sub_total_5 / 11),0,',','.'),0,0,'R');
    /*					$this->SetXY(146,96);	
                $this->Cell(30,3,number_format(CEIL($sub_total_5),0,',','.'),0,0,'R');					  		
                $this->SetXY(80,106);	
                $this->Cell(30,3,number_format(CEIL($sub_total_5 / 11),0,',','.'),0,0,'R');	*/

    /*					$this->SetXY(146,242);	
                $this->Cell(30,3,number_format(CEIL($sub_total_5),0,',','.'),0,0,'R');					  		  			
                $this->SetXY(80,242);	
                $this->Cell(30,3,number_format(CEIL($sub_total_5 / 11),0,',','.'),0,0,'R');	*/
        //	$total_iva += $sub_total_5 / 11;   			
                }
                if($sub_total_10 <> 0)
                {
                    $this->SetXY(170,($yTitTot+$this->reng1));	$this->Cell(30,3,number_format(CEIL($sub_total_10),0,',','.'),0,0,'R');
                    $this->SetXY(115,($yTitTot+$this->reng3));	$this->Cell(30,3,number_format(CEIL($sub_total_10 / 11),0,',','.'),0,0,'R');					  		
    /*					$this->SetXY(170,96);	
                $this->Cell(30,3,number_format(CEIL($sub_total_10),0,',','.'),0,0,'R');					  		
                $this->SetXY(115,106);	
                $this->Cell(30,3,number_format(CEIL($sub_total_10 / 11),0,',','.'),0,0,'R');					  		

                $this->SetXY(170,242);	
                $this->Cell(30,3,number_format(CEIL($sub_total_10),0,',','.'),0,0,'R');					  		
                $this->SetXY(115,252);	
                $this->Cell(30,3,number_format(CEIL($sub_total_10 / 11),0,',','.'),0,0,'R');*/					  		  			
        //	$total_iva += $sub_total_10 / 11;
                }
                if($total_iva <> 0)
                {
                    $this->SetXY(170,($yTitTot+$this->reng3)); $this->Cell(30,3,number_format(CEIL($total_iva),0,',','.'),0,0,'R');
    /*					$this->SetXY(170,106);	
                        $this->Cell(30,3,number_format(CEIL($total_iva),0,',','.'),0,0,'R');	  		
                        $this->SetXY(170,252);	
                        $this->Cell(30,3,number_format(CEIL($total_iva),0,',','.'),0,0,'R');*/	  		  			
                }
                if($total_total <> 0)
                {
                    $this->SetXY(170,($yTitTot+$this->reng1)); $this->Cell(30,3,number_format(CEIL($total_total),0,',','.'),0,0,'R');	
                    $this->SetXY(170,($yTitTot+$this->reng2)); $this->Cell(30,3,number_format(CEIL($total_total),0,',','.'),0,0,'R');

                /*	$this->SetXY(170,101);	
                        $this->Cell(30,3,number_format(CEIL($total_total),0,',','.'),0,0,'R');	*/				  		
                    $monto = round($total_total);  			
                    $monto_enletras = $this->trans->Convert($monto, "");		   
                    $monto_enletras .= ' guaranies';
                    $monto_enletras = strtoupper($monto_enletras); 
                    $this->SetXY(35,($yTitTot+$this->reng2)); $this->Cell(30,3,$monto_enletras,0,0,'L');
                /*	$this->SetXY(35,101);	
                        $this->Cell(30,3,$monto_enletras,0,0,'L');					  		  			
                        $this->SetXY(35,247);	
                        $this->Cell(30,3,$monto_enletras,0,0,'L');*/		  			
                /*	$this->SetXY(170,247);		
                        $this->Cell(30,3,number_format(CEIL($total_total),0,',','.'),0,0,'R');	*/				  		  			  			  			
                }  		
            }
        }
      
        function calFecha($var_facfemi)
        {

            if(!empty($var_facfemi))
            {
                $ddia = substr($var_facfemi,8,2);
                $mmes = substr($var_facfemi,5,2);
                $anho = substr($var_facfemi,0,4);
            }						
            $nomb_mmes = "";
            if ($mmes == 1)
            {
              $nomb_mmes = "Enero";
            }
            elseif ($mmes == 2)
            {
              $nomb_mmes = "Febrero";
            }
            elseif ($mmes == 3)
            {
              $nomb_mmes = "Marzo";
            }
            elseif ($mmes == 4)
            {
              $nomb_mmes = "Abril";
            }
            elseif ($mmes == 5)
            {
              $nomb_mmes = "Mayo";
            }
            elseif ($mmes == 6)
            {
              $nomb_mmes = "Junio";
            }
            elseif ($mmes == 7)
            {
              $nomb_mmes = "Julio";
            }
            elseif ($mmes == 8)
            {
              $nomb_mmes = "Agosto";
            }
            elseif ($mmes == 9)
            {
              $nomb_mmes = "Setiembre";
            }
            elseif ($mmes == 10)
            {
              $nomb_mmes = "Octubre";
            }
            elseif ($mmes == 11)
            {
              $nomb_mmes = "Noviembre";
            }
            elseif ($mmes == 12)
            {
              $nomb_mmes = "Diciembre";
            }
            $fecha_documento = $ddia." de ".$nomb_mmes." de ".$anho;			

            $xTit1 = $this->xIniDat1;
            $xTit2 = $this->xIniDat2;
            $yTit = $this->yBlo1;
            for($i =1; $i<=2; $i++){
                if($i == 2){ 
                        $yTit = $this->yBlo1_Dup;
                }
                $this->SetFont('Arial','',7);
                $this->SetXY($xTit1,$yTit);			
                $this->Cell(30,3,$fecha_documento,0,0,'L');	
                $yTit = $yTit +($this->sepEncab *3);	
                $this->SetXY($xTit2,$yTit);	
                $this->Cell(30,3,$var_documento,0,0,'L');		    	    
            }
        }
        function printLine($x1,$y1,$x2,$y2)
        {
                $this->SetDrawColor(10);
            $this->SetLineWidth(.3);
            $this->Line($x1, $y1, $x2, $y2);		
        }	
        function WriteHTML($html)
        {
            //Int�rprete de HTML
            $html=str_replace("\n",' ',$html);
            $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
            foreach($a as $i=>$e)
            {
                if($i%2==0)
                {
                    //Text
                    if($this->HREF)
                        $this->PutLink($this->HREF,$e);
                    else
                        $this->Write(5,$e);
                }
                else
                {
                    //Etiqueta
                    if($e[0]=='/')
                        $this->CloseTag(strtoupper(substr($e,1)));
                    else
                    {
                        //Extraer atributos
                        $a2=explode(' ',$e);
                        $tag=strtoupper(array_shift($a2));
                        $attr=array();
                        foreach($a2 as $v)
                        {
                            if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                                $attr[strtoupper($a3[1])]=$a3[2];
                        }
                        $this->OpenTag($tag,$attr);
                    }
                }
            }
        }

        function OpenTag($tag,$attr)
        {
            //Etiqueta de apertura
            if($tag=='B' || $tag=='I' || $tag=='U')
                $this->SetStyle($tag,true);
            if($tag=='A')
                $this->HREF=$attr['HREF'];
            if($tag=='BR')
                $this->Ln(5);
        }

        function CloseTag($tag)
        {
            //Etiqueta de cierre
            if($tag=='B' || $tag=='I' || $tag=='U')
                $this->SetStyle($tag,false);
            if($tag=='A')
                $this->HREF='';
        }

        function SetStyle($tag,$enable)
        {
            //Modificar estilo y escoger la fuente correspondiente
            $this->$tag+=($enable ? 1 : -1);
            $style='';
            foreach(array('B','I','U') as $s)
            {
                if($this->$s>0)
                    $style.=$s;
            }
            $this->SetFont('',$style);
        }

        function PutLink($URL,$txt)
        {
            //Escribir un hiper-enlace
            $this->SetTextColor(0,0,255);
            $this->SetStyle('U',true);
            $this->Write(5,$txt,$URL);
            $this->SetStyle('U',false);
            $this->SetTextColor(0);
        }
        function obtiene_codigoBarra($empresa,$sucursal,$var_serie,$var_numero, $FacMonTotF, $facfvtoi)
        {

            $VENCI = '0';
            $COD = '0700';
            $NROFAC = str_repeat('0',(12 - strlen($var_numero)));
            $NROFAC = $NROFAC . $var_numero;
            $MONE   = '0';

            $AAAA = substr($facfvtoi,0,4);
            $MMMM = substr($facfvtoi,5,2);
            $DDDD = substr($facfvtoi,8,2);

            $monto = round($FacMonTotF);
            $impo = str_repeat('0',(10 - strlen($monto)));
            $impo = $impo . $monto. substr($FacMonTotF,(strlen($FacMonTotF)-2),2);

            //============================================================================================
            // CALCULO AL ULTIMO DIGITO DE CODIGO DE BARRA
            $BARRA = $VENCI . $COD . $NROFAC . $MONE . $AAAA . $MMMM  . $DDDD . $impo;
            $I = 1;
            while( $I <= 38)
            {
                $Resto = $I-(round($I/2)*2);
                IF ($Resto == 0)
                    $PESOS[$I] = 3;
                ELSE
                    $PESOS[$I] = 1;

                $I++;
            }

            $I = 1;
            while ($I <= 38)
            {
                $MATBARRA[$I] = SUBSTR($BARRA,$I,1);
                $I++;
            }

            $I = 1;
            while ($I <= 38)
            {
                $RESULTADO[$I]  = $PESOS[$I] * $MATBARRA[$I];
                $I++;
            }

            $SUMA = 0;
            $I    = 1;
            while ($I <= 38)
            {
                $SUMA  = $SUMA + $RESULTADO[$I];
                $I++;
            }

            $VALOR = SUBSTR($SUMA,(strlen($SUMA)-1),1);
            $VALOR = 10 - $VALOR;
            if ($VALOR == 10)
            {
               $VALOR = 0;
            }   

            $DIG = $VALOR;

            //============================================================================================

            $barrastring = $VENCI . $COD . $NROFAC . $MONE . $AAAA . $MMMM  . $DDDD . $impo . $DIG;			
            return $barrastring;
        }
    }

?>