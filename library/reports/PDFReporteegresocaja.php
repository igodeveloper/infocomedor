<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
	error_reporting(0);
    class PDFReporteagresocaja extends FPDF
    {  
            var $Conn;
            var $parametros;
            var $B;
            var $I;
            var $U;
            var $HREF;
            var $fila = 1;

        function PDFReporteagresocaja($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
        {
            //Llama al constructor de la clase padre
            $this->FPDF($orientation,$unit,$format);
            $this->parametros = $parametrosPDF;                
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
            //Logo
            $x = 0;
            $y = 0;
            //$this->Image("./images/central.jpg",10,10,25,25);
            $this->Image("./css/images/infocomedor.jpg",10,5,50,30);
                            $this->Ln(15);
            //Arial bold 15
            $this->SetFont('Arial','B',12);
            $this->SetX(80+$x);
            $this->Cell(80,10,utf8_decode("Egreso de Efectivo"),0,0,'L');
            $this->Ln(10);//Salto de l�nea
            $this->SetX(10+$y);    
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Fecha reporte "),0,0,'L');
            $this->SetX(35+$y);   
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '.date('d/m/Y H:i:s')),0,0,'L');                            
            $this->Ln(20);//Salto de l�nea          
        }

        function Body()
        {          
            $sql="select 
            c.NOMBRE_APELLIDO,a.cod_caja,a.fecha_hora_apertura,b.fecha_hora_mov,b.monto_mov,
            d.desc_tipo_mov,b.firmante_mov,
            b.observacion_mov
            from caja a
            inner join mov_caja b on 
            a.cod_caja = b.cod_caja
            inner join usuario c on
            c.COD_USUARIO = a.cod_usuario_caja
            inner join tipo_movimiento d on
            d.cod_tipo_mov = b.cod_tipo_mov
            where cod_mov_caja = ".$this->parametros;            
            //echo $sql."<br>";                              
            $dtDatos = $this->Conn->query($sql);
        
            $count = 1;
            
            while($row = mysql_fetch_assoc($dtDatos))
            {
                $NOMBRE_APELLIDO    = $row['NOMBRE_APELLIDO'];
                $cod_caja           = $row['cod_caja'];
                $fecha_hora_apertura= $row['fecha_hora_apertura'];
                $fecha_hora_mov     = $row['fecha_hora_mov'];
                $monto_mov          = $row['monto_mov'];
                $desc_tipo_mov      = $row['desc_tipo_mov'];
                $firmante_mov       = $row['firmante_mov'];
                $observacion_mov    = $row['observacion_mov'];
                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','',9);
                $this->SetX(30);
                $this->Cell(24,10,'Usuario Caja',0,0,'L');                
                $this->SetX(100);
                $this->Cell(24,10,': '.$NOMBRE_APELLIDO,0,0,'L');                
                $this->Ln(5);
                $this->SetX(30);
                $this->Cell(24,10,'Codigo de Caja',0,0,'L');                                
                $this->SetX(100);
                $this->Cell(17,10,': '.$cod_caja,0,0,'L');
                $this->Ln(5);
                $this->SetX(30);
                $this->Cell(24,10,'Apertura de Caja',0,0,'L');                                
                $this->SetX(100);
                $this->Cell(18,10,': '.$fecha_hora_apertura,0,0,'L');
                $this->Ln(5);
                $this->SetX(30);
                $this->Cell(24,10,'Fecha/hora mov. Caja',0,0,'L');                                
                $this->SetX(100);
                $this->Cell(25,10,': '.$fecha_hora_mov,0,0,'L');
                $this->Ln(5);
                $this->SetX(30);
                $this->Cell(24,10,'Monto movimiento',0,0,'L');                                
                $this->SetX(100);
                $this->Cell(19,10,': '.$monto_mov,0,0,'L');
                $this->Ln(5);
                $this->SetX(30);
                $this->Cell(24,10,'Concepto de movimiento',0,0,'L');                                
                $this->SetX(100);
                $this->Cell(19,10,': '.$desc_tipo_mov,0,0,'L');
                $this->Ln(5);
                $this->SetX(30);
                $this->Cell(24,10,'Receptor del efectivo',0,0,'L');                                
                $this->SetX(100);
                $this->Cell(19,10,': '.$firmante_mov,0,0,'L');
                $this->Ln(5);
                $this->SetX(30);
                $this->Cell(24,10,'Firma del receptor',0,0,'L');    
                $this->SetX(100);
                $this->Cell(19,10,': _______________________________',0,0,'L');                
                $this->fila++;
                $count++;
                $this->Ln(5);                  
            }            
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