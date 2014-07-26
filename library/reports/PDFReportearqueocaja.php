<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
	error_reporting(0);
    class PDFReportearqueocaja extends FPDF
    {
  
			var $Conn;
            var $parametros;
            var $B;
            var $I;
            var $U;
            var $HREF;
            var $fila = 1;

            function PDFReportearqueocaja($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
            {
                //Llama al constructor de la clase padre
                $this->FPDF($orientation,$unit,$format);
                $this->parametros = json_decode($parametrosPDF);                
                //Iniciaci�n de variables
                $this->Conn = new Conexion();
                $this->B=0;
                $this->I=0;
                $this->U=0;
                $this->HREF='';
                
            }
        //Cabecera de p�gina
        function Header()
        {
            $sql="select c.NOMBRE_APELLIDO,a.fecha_hora_apertura,a.fecha_hora_cierre,
                a.monto_caja_apertura,a.monto_caja_cierre,a.monto_diferencia_arqueo,a.monto_diferencia_arqueo_cheque,
                a.arqueo_caja
                from caja a
                inner join usuario c on
                c.COD_USUARIO = a.cod_usuario_caja
                where a.cod_caja = ".$this->parametros->nro_caja;
            //die($sql);               
            //echo $sql."<br>";                              
            $dtDatos = $this->Conn->query($sql);
            while($row = mysql_fetch_assoc($dtDatos))
            {
                 $nombre_apellido                   = $row['NOMBRE_APELLIDO'];
                 $fecha_hora_apertura               = $row['fecha_hora_apertura'];
                 $fecha_hora_apertura               = substr($fecha_hora_apertura,8,2).'/'.substr($fecha_hora_apertura,5,2).'/'.substr($fecha_hora_apertura,0,4).' '.substr($fecha_hora_apertura,11);
                 $fecha_hora_cierre                 = $row['fecha_hora_cierre'];
                 $monto_caja_apertura               = number_format(CEIL($row['monto_caja_apertura']),0,',','.');
                 $monto_caja_cierre                 = number_format(CEIL($row['monto_caja_cierre']),0,',','.');
                 $monto_diferencia_arqueo           = number_format(CEIL($row['monto_diferencia_arqueo']),0,',','.');
                 $monto_diferencia_arqueo_cheque    = number_format(CEIL($row['monto_diferencia_arqueo_cheque']),0,',','.');
                 $arqueo_caja                       = $row['arqueo_caja'];
                 if(trim($arqueo_caja) <> '')
                     $arqueo_caja = 'Si';
                 else $arqueo_caja = 'No';   
                 if(trim($fecha_hora_cierre) == '')
                     $fecha_hora_cierre = 'Abierto';
            }                
            //Logo
            $x = 0;
            $y = 0;
            //$this->Image("./images/central.jpg",10,10,25,25);
            $this->Image("./css/images/infocomedor.jpg",10,5,50,30);
                            $this->Ln(15);
            //Arial bold 15
            $this->SetFont('Arial','B',12);
            $this->SetX(80+$x);
            $this->Cell(80,10,utf8_decode("Reporte de Arqueo de Caja"),0,0,'L');
            $this->Ln(10);//Salto de l�nea
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Nro. de Caja "),0,0,'L');
            $this->SetX(30+$y);
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '.$this->parametros->nro_caja),0,0,'L');
            $this->SetX(120+$y);    
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Fecha Reporte "),0,0,'L');
            $this->SetX(145+$y);   
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '.date('d/m/Y H:i:s')),0,0,'L');                
            $this->Ln(5);//Salto de l�nea
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Usuario Caja "),0,0,'L');               
            $this->SetX(30+$y); 
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(": ".$nombre_apellido),0,0,'L');
            $this->SetX(120+$y);    
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Apertura caja "),0,0,'L');
            $this->SetX(145+$y);   
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '.$fecha_hora_apertura),0,0,'L');  

            $this->Ln(5);//Salto de l�nea
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Monto Pertura "),0,0,'L');               
            $this->SetX(32+$y); 
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(": ".$monto_caja_apertura),0,0,'L');
            $this->SetX(120+$y);    
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Cierre caja "),0,0,'L');
            $this->SetX(145+$y);   
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '),0,0,'L');      
            $this->SetX(147+$y); 
            if(trim($fecha_hora_cierre) == 'Abierto')
                $this->SetTextColor(255,0,0);
            else
                $fecha_hora_cierre = substr($fecha_hora_cierre,8,2).'/'.substr($fecha_hora_cierre,5,2).'/'.substr($fecha_hora_cierre,0,4);
            $this->Cell(80,10,  utf8_decode($fecha_hora_cierre),0,0,'L');      
            $this->SetTextColor(0,0,0);
            $this->Ln(10);//Salto de l�nea   

            $x=-2;
            $this->SetX(11+$x);
            $this->SetFont('Arial','B',9);
            $this->SetX(10);
            $this->Cell(24,10,"Nro.",0,0,'L');                
            $this->SetX(20);
            $this->Cell(17,10,"Fecha hora movimiento",0,0,'L');
            $this->SetX(60);
            $this->Cell(18,10,"Monto movimiento",0,0,'L');
            $this->SetX(90);
            $this->Cell(25,10,"Tipo movimiento",0,0,'L');
            $this->SetX(150);
            $this->Cell(19,10,"Nro. Factura",0,0,'L');
            $this->printLine(63);
            $this->Ln(10);          
        }

        function Body()
        {          
            $sql="select 
             b.fecha_hora_mov,b.monto_mov,d.desc_tipo_mov,d.tipo_mov,b.factura_mov,b.tipo_factura_mov,b.tipo_mov as tipo,
             a.monto_caja_apertura,a.monto_caja_cierre,a.monto_caja_cierre_cheque
             ,a.monto_diferencia_arqueo,a.monto_diferencia_arqueo_cheque
             from caja a
             inner join mov_caja b on
             a.cod_caja = b.cod_caja
             inner join usuario c on
             c.COD_USUARIO = a.cod_usuario_caja
             inner join tipo_movimiento d on
             d.cod_tipo_mov = b.cod_tipo_mov
             where a.cod_caja = ".$this->parametros->nro_caja."
             order by b.fecha_hora_mov desc";               
            //echo $sql."<br>";                              
            $dtDatos = $this->Conn->query($sql); 
            $count = 1;
            while($row = mysql_fetch_assoc($dtDatos))
            {
                $fecha_hora_mov     = substr($row['fecha_hora_mov'],8,2).'/'.substr($row['fecha_hora_mov'],5,2).'/'.substr($row['fecha_hora_mov'],0,4).' '.substr($row['fecha_hora_mov'],11);;                
                $monto_mov          = number_format(CEIL($row['monto_mov']),0,',','.');
                $desc_tipo_mov      = $row['desc_tipo_mov'];
                $tipo_mov           = $row['tipo_mov'];
                $factura_mov        = $row['factura_mov'];
                $tipo_factura_mov   = $row['tipo_factura_mov'];
                $tipo               = $row['tipo'];
                $monto_caja_apertura= number_format(CEIL($row['monto_caja_apertura']),0,',','.');
                $monto_caja_cierre  = number_format(CEIL($row['monto_caja_cierre']),0,',','.');
                $monto_caja_cierre_cheque  = number_format(CEIL($row['monto_caja_cierre_cheque']),0,',','.');
                $monto_diferencia_arqueo    = number_format(CEIL($row['monto_diferencia_arqueo']),0,',','.');
                $monto_diferencia_arqueo_cheque = number_format(CEIL($row['monto_diferencia_arqueo_cheque']),0,',','.');
                if(trim($factura_mov) <> '' and $factura_mov > 0){                    
                    if($tipo_factura_mov == 'C')
                        $tipo_factura_mov = 'Compra';
                    if($tipo_factura_mov == 'V')
                        $tipo_factura_mov = 'Venta';              
                    $factura = $factura_mov.'('.$tipo_factura_mov.')';
                }
                else $factura = '';
                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','',9);
                $this->SetX(10);
                $this->Cell(24,10,$this->fila,0,0,'L');                
                $this->SetX(20);
                $this->Cell(17,10,$fecha_hora_mov,0,0,'L');
                $this->SetX(60);
                $this->Cell(18,10,$monto_mov,0,0,'L');
                $this->SetX(90);
                $this->Cell(25,10,$desc_tipo_mov.'('.$tipo_mov.')',0,0,'L');
                $this->SetX(150);
                $this->Cell(19,10,$factura,0,0,'L');
                $this->fila++;
                $count++;
                $this->Ln(5);                  
            }
           
            $this->Ln(10);
            $this->SetX(11+$x);
            $this->SetFont('Arial','',9);
            $this->SetX(10);
            $this->Cell(24,10,"Apertura Caja",0,0,'L');                
            $this->SetX(45);
            $this->Cell(17,10,': '.$monto_caja_apertura,0,0,'L');
            $this->Ln(5);
            $this->SetX(10);
            $this->Cell(18,10,"Cierre Caja efectivo",0,0,'L');
            $this->SetX(45);
            $this->Cell(25,10,': '.$monto_caja_cierre,0,0,'L');
            $this->SetX(100);
            $this->Cell(18,10,"Cierre Caja cheque",0,0,'L');
            $this->SetX(135);
            $this->Cell(25,10,': '.$monto_caja_cierre_cheque,0,0,'L');    
            $this->Ln(5);
            $this->SetX(10);
            $this->Cell(18,10,"Diferencia Caja efectivo",0,0,'L');
            $this->SetX(45);
            $this->Cell(25,10,': '.$monto_diferencia_arqueo,0,0,'L'); 
            $this->SetX(100);
            $this->Cell(18,10,"Diferencia Caja cheque",0,0,'L');
            $this->SetX(135);
            $this->Cell(25,10,': '.$monto_diferencia_arqueo_cheque,0,0,'L'); 
        }
        //Pie de p�gina
        function Footer()
        {
            $this->SetY(-15);
            $this->Cell(-2);
            $this->SetFont('Arial','B',6); 
            $this->SetX(9);
            $this->printLine(290);
            $this->Ln(5);
            $this->Cell(-2);
            $this->SetX(9);
            $this->Cell(50,10,utf8_decode("Infocomedor - Sistema de Gestión de Comedor"),0,0,'L');
            $this->SetX(177);
            $this->Cell(50,10,utf8_decode("Facultad Politecnica - UNA"),0,0,'L');
        }                
        function esqueleto(){
            $x = 0;
            $y = 0;
            $this->SetDrawColor(10);
            $this->SetLineWidth(0,5);
            //          x   y      x   y 
            $this->Line(10, 100, 10, 196);
            $this->Line(25, 100, 25, 196);
            $this->Line(55, 100, 55, 196);
            $this->Line(169, 100, 169, 196);
            $this->Line(180, 100, 180, 196);
            $this->Line(205, 100, 205, 196);
            
            $this->Line(10, 100, 205, 100);            
            $this->Line(10, 108, 205, 108);
            $this->Line(10, 116, 205, 116);
            $this->Line(10, 124, 205, 124);
            $this->Line(10, 132, 205, 132);
            $this->Line(10, 140, 205, 140);
            $this->Line(10, 148, 205, 148);
            $this->Line(10, 156, 205, 156);
            $this->Line(10, 164, 205, 164);
            $this->Line(10, 172, 205, 172);
            $this->Line(10, 180, 205, 180);
            $this->Line(10, 188, 205, 188);
            $this->Line(10, 196, 205, 196);
        }
        function printLine($y1)
        {
            $this->SetDrawColor(10);
            $this->SetLineWidth(0,5);
            $this->Line(10, $y1, 205, $y1);
        }

    }
?>