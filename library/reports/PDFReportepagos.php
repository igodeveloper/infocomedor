<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
  //  error_reporting(0);
    class PDFReportepagos extends FPDF
    {
  
        var $Conn;
        var $parametros;
        var $B;
        var $I;
        var $U;
        var $HREF;
        var $fila = 1;

        function PDFReportepagos($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
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
            //Logo
            $x = 0;
            $y = 0;
            //$this->Image("./images/central.jpg",10,10,25,25);
            $this->Image("./css/images/infocomedor.jpg",10,5,50,30);
            $this->Ln(15);
            //Arial bold 15
            $this->SetFont('Arial','B',12);
            $this->SetX(80+$x);
            $this->Cell(80,10,utf8_decode("Reporte de Pagos"),0,0,'L');
            $this->printLine(35);
            $this->Ln(10);//Salto de l�nea       
        }

        function Body()
        {         
            $where = 'where'; 
            if(trim($this->parametros->NRO_FACTURA_COMPRA) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= ' b.NRO_FACTURA_COMPRA = '.$this->parametros->NRO_FACTURA_COMPRA; 
            }
            if(trim($this->parametros->NRO_CHEQUE) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " b.NRO_CHEQUE = '".$this->parametros->NRO_CHEQUE."'";
            }            
            if(trim($this->parametros->ESTADO_PAGO) <> '-1'){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " b.ESTADO_PAGO = '".$this->parametros->ESTADO_PAGO."'";
            }   
            $group_principal = 'group by a.NRO_FACTURA_COMPRA';
            $sql_principal = "select a.NRO_FACTURA_COMPRA
                from compra a
                inner join pago_proveedor b on
                a.NRO_FACTURA_COMPRA = b.NRO_FACTURA_COMPRA
                ";          
            if(trim($where) <> 'where')
                $sql_principal .= ' '.$where;
            $sql_principal .= ' '.$group_principal;
            // echo $sql_principal; die();
            $dtDatos_principal = $this->Conn->query($sql_principal); 
       
            while($row_principal = mysql_fetch_assoc($dtDatos_principal))
            {            
                $orderby = 'order by a.NRO_FACTURA_COMPRA';
                $sql="select d.PROVEEDOR_NOMBRE,a.NRO_FACTURA_COMPRA,
                    a.FECHA_EMISION_FACTURA,
                    a.FECHA_VENCIMIENTO_FACTURA,a.MONTO_TOTAL_COMPRA
                    ,a.CONTROL_FISCAL
                    from compra a
                    inner join proveedor d on
                    d.COD_PROVEEDOR = a.COD_PROVEEDOR
                    where a.NRO_FACTURA_COMPRA = ".$row_principal['NRO_FACTURA_COMPRA'];               
                $sql .= ' '.$orderby;
                
                $dtDatos = $this->Conn->query($sql); 
                $count = 1;

                while($row = mysql_fetch_assoc($dtDatos))
                {
                    $PROVEEDOR_NOMBRE           = $row['PROVEEDOR_NOMBRE'];
                    $NRO_FACTURA_COMPRA         = $row['NRO_FACTURA_COMPRA'];
                    $FECHA_EMISION_FACTURA      = substr($row['FECHA_EMISION_FACTURA'],8,2).'/'.substr($row['FECHA_EMISION_FACTURA'],5,2).'/'.substr($row['FECHA_EMISION_FACTURA'],0,4).' '.substr($row['FECHA_EMISION_FACTURA'],11);                    
                    $FECHA_VENCIMIENTO_FACTURA  = substr($row['FECHA_VENCIMIENTO_FACTURA'],8,2).'/'.substr($row['FECHA_VENCIMIENTO_FACTURA'],5,2).'/'.substr($row['FECHA_VENCIMIENTO_FACTURA'],0,4).' '.substr($row['FECHA_VENCIMIENTO_FACTURA'],11);                    
                    $MONTO_TOTAL_COMPRA         = number_format(CEIL($row['MONTO_TOTAL_COMPRA']),0,',','.');                    
                    $CONTROL_FISCAL             = $row['CONTROL_FISCAL'];

                    $x=-2;
                    $this->SetX(11+$x);
                    $this->SetFont('Arial','B',9);
                    $this->SetX(10);
                    $this->Cell(24,10,"Proveedor : ",0,0,'L');   
                    $this->SetFont('Arial','',9);
                    $this->SetX(28);
                    $this->Cell(24,10,$PROVEEDOR_NOMBRE,0,0,'L'); 
                    $this->SetFont('Arial','B',9);
                    $this->SetX(70);
                    $this->Cell(17,10,"Codigo Interno : ",0,0,'L');
                    $this->SetFont('Arial','',9);
                    $this->SetX(97);
                    $this->Cell(17,10,$NRO_FACTURA_COMPRA,0,0,'L'); 
                    $this->SetFont('Arial','B',9);
                    $this->SetX(103);
                    $this->Cell(18,10,"Fecha Emision Fac. : ",0,0,'L');
                    $this->SetFont('Arial','',9);
                    $this->SetX(135);
                    $this->Cell(18,10,$FECHA_EMISION_FACTURA,0,0,'L');
                    $this->SetFont('Arial','B',9);
                    $this->SetX(155);
                    $this->Cell(25,10,"Fecha Venc. Fac. : ",0,0,'L');
                    $this->SetFont('Arial','',9);
                    $this->SetX(183);
                    $this->Cell(25,10,$FECHA_VENCIMIENTO_FACTURA,0,0,'L');
                    $this->Ln(5);
                    $this->SetFont('Arial','B',9);
                    $this->SetX(10);
                    $this->Cell(19,10,"Monto Total Compra : ",0,0,'L');
                    $this->SetFont('Arial','',9);
                    $this->SetX(45);
                    $this->Cell(19,10,$MONTO_TOTAL_COMPRA,0,0,'L');                                 
                    $this->Ln(5);
                    $this->SetFont('Arial','B',9);
                    $this->SetX(10);
                    $this->Cell(18,10,"Control Fiscal : ",0,0,'L');
                    $this->SetFont('Arial','',9);
                    $this->SetX(35);
                    $this->Cell(18,10,$CONTROL_FISCAL,0,0,'L');                
                    $this->Ln(5);
                    
                    $item = 1;
                    $this->SetX(11+$x);
                    $this->SetFont('Arial','B',7);
                    $this->SetX(20);
                    $this->Cell(24,10,"Item",0,0,'L');                
                    $this->SetX(35);
                    $this->Cell(17,10,"Monto",0,0,'L');
                    $this->SetX(65);
                    $this->Cell(18,10,"Moneda",0,0,'L');
                    $this->SetX(100);
                    $this->Cell(25,10,"Cheque",0,0,'L');
                    $this->SetX(130);
                    $this->Cell(19,10,"Banco",0,0,'L');
                    $this->SetX(160);
                    $this->Cell(19,10,"Estado",0,0,'L');                                   
                    $this->Ln(5); 
                    $sql_detalle = 'select b.MONTO_PAGO,
                            c.DESC_MONEDA,b.NRO_CHEQUE,
                            b.DES_BANCO,b.ESTADO_PAGO
                            from compra a
                            inner join pago_proveedor b on
                            a.NRO_FACTURA_COMPRA = b.NRO_FACTURA_COMPRA
                            inner join moneda c on
                            c.COD_MONEDA = b.COD_MONEDA_PAGO
                            where b.NRO_FACTURA_COMPRA = '.$NRO_FACTURA_COMPRA.'
                            order by b.COD_PAGO_PROVEEDOR';                   
                    $dtDatos_detalle = $this->Conn->query($sql_detalle); 
                    while($row = mysql_fetch_assoc($dtDatos_detalle))
                    {
                        $estado_pago = '';
                        if($row['ESTADO_PAGO'] == 'T')
                            $estado_pago = 'Activo';
                        if($row['ESTADO_PAGO'] == 'A')
                            $estado_pago = 'Anulado';                        
                        $this->SetX(11+$x);
                        $this->SetFont('Arial','',7);
                        $this->SetX(20);
                        $this->Cell(24,10,$item,0,0,'L');  
                        $item++;
                        $this->SetX(35);
                        $this->Cell(17,10,number_format(CEIL($row['MONTO_PAGO']),0,',','.'),0,0,'L');                                                
                        $this->SetX(65);
                        $this->Cell(18,10,$row['DESC_MONEDA'],0,0,'L');
                        $this->SetX(100);
                        $this->Cell(25,10,$row['NRO_CHEQUE'],0,0,'L');
                        $this->SetX(130);
                        $this->Cell(19,10,$row['DES_BANCO'],0,0,'L');
                        $this->SetX(160);
                        $this->Cell(24,10,$estado_pago,0,0,'L');
                        $this->Ln(5);
                    } 
                    $this->Ln(10);
                }
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