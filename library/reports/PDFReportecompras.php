<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
  //  error_reporting(0);
    class PDFReportecompras extends FPDF
    {
  
        var $Conn;
        var $parametros;
        var $B;
        var $I;
        var $U;
        var $HREF;
        var $fila = 1;

        function PDFReportecompras($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
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
            $codproveedor = '';
            if(trim($this->parametros->codproveedor) <> '')
                $codproveedor = $this->parametros->codproveedor;
            $nameproveedor = '';
            if(trim($this->parametros->nameproveedor) <> '')
                $nameproveedor = $this->parametros->nameproveedor;
            $codigointerno = '';
            if(trim($rowData->codigointerno) <> '')
                $codigointerno = $this->parametros->codigointerno;        
            $controlfiscal = '';
            if(trim($this->parametros->controlfiscal) <> 'NULL')
                $controlfiscal = $this->parametros->controlfiscal;
            $fechaemision = '';
            if(trim($this->parametros->fechaemision) <> '')
                $fechaemision = $this->parametros->fechaemision;
            $fechavencimiento = '';
            if(trim($this->parametros->fechavencimiento) <> '')
                $fechavencimiento = $this->parametros->fechavencimiento;
            $formapago = '';
            if($this->parametros->formapago <> -1)
                $formapago = $this->parametros->formapago; 
                           
            //Logo
            $x = 0;
            $y = 0;
            //$this->Image("./images/central.jpg",10,10,25,25);
            $this->Image("./css/images/infocomedor.jpg",10,5,50,30);
            $this->Ln(15);
            //Arial bold 15
            $this->SetFont('Arial','B',12);
            $this->SetX(80+$x);
            $this->Cell(80,10,utf8_decode("Reporte de Compras"),0,0,'L');
            $this->printLine(35);
            $this->Ln(10);//Salto de l�nea       
        }

        function Body()
        {         
            $where = 'where'; 
            if(trim($this->parametros->codproveedor) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= ' a.cod_proveedor = '.$this->parametros->codproveedor; 
            }
            if(trim($this->parametros->nameproveedor) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " d.PROVEEDOR_NOMBRE like '%".$this->parametros->nameproveedor."%'";
            }            
            if(trim($rowData->codigointerno) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= ' a.NRO_FACTURA_COMPRA = '.$this->parametros->codigointerno;
            }            
            if(trim($this->parametros->controlfiscal) <> null){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.CONTROL_FISCAL = '".$this->parametros->controlfiscal."'";
            }            
            if(trim($this->parametros->fechaemision) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.FECHA_EMISION_FACTURA = '".$this->parametros->fechaemision."'";
            }                
            if(trim($this->parametros->fechavencimiento) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.FECHA_VENCIMIENTO_FACTURA = '".$this->parametros->fechavencimiento."'";
            }                
            if($this->parametros->formapago <> -1){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.COD_FORMA_PAGO = ".$this->parametros->formapago;
            }  
            $orderby = 'order by a.FECHA_EMISION_FACTURA';
            $sql="select d.PROVEEDOR_NOMBRE,a.NRO_FACTURA_COMPRA,
                a.FECHA_EMISION_FACTURA,
                a.FECHA_VENCIMIENTO_FACTURA,a.MONTO_TOTAL_COMPRA,
                e.DESC_MONEDA,f.DES_FORMA_PAGO,a.CONTROL_FISCAL
                from compra a
               inner join proveedor d on
                d.COD_PROVEEDOR = a.COD_PROVEEDOR
                inner join moneda e on
                e.COD_MONEDA = a.COD_MONEDA_COMPRA
                inner join forma_pago f on
                f.COD_FORMA_PAGO = a.COD_FORMA_PAGO
                inner join usuario g on
                g.COD_USUARIO = a.COD_USUARIO";
            if(trim($where) <> '')
                $sql .= ' '.$where;
            $sql .= ' '.$orderby;
          
            $dtDatos = $this->Conn->query($sql); 
            $count = 1;
          
            while($row = mysql_fetch_assoc($dtDatos))
            {
                $PROVEEDOR_NOMBRE     = $row['PROVEEDOR_NOMBRE'];
                $NRO_FACTURA_COMPRA          = $row['NRO_FACTURA_COMPRA'];
                $FECHA_EMISION_FACTURA     = $row['FECHA_EMISION_FACTURA'];
                $FECHA_VENCIMIENTO_FACTURA           = $row['FECHA_VENCIMIENTO_FACTURA'];
                $MONTO_TOTAL_COMPRA        = $row['MONTO_TOTAL_COMPRA'];
                $DESC_MONEDA   = $row['DESC_MONEDA'];
                $DES_FORMA_PAGO               = $row['DES_FORMA_PAGO'];
                $CONTROL_FISCAL= $row['CONTROL_FISCAL'];

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
                $this->SetFont('Arial','B',9);
                $this->SetX(70);
                $this->Cell(24,10,"Moneda Compra : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(97);
                $this->Cell(24,10,$DESC_MONEDA,0,0,'L');  
                $this->SetFont('Arial','B',9);
                $this->SetX(120);
                $this->Cell(17,10,"Forma de Pago : ",0,0,'L');                
                $this->SetFont('Arial','',9);
                $this->SetX(145);
                $this->Cell(17,10,$DES_FORMA_PAGO,0,0,'L');                                
                $this->Ln(5);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(18,10,"Control Fiscal : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(35);
                $this->Cell(18,10,$CONTROL_FISCAL,0,0,'L');                
                $this->Ln(5);
                
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',7);
                $this->SetX(20);
                $this->Cell(24,10,"Item",0,0,'L');                
                $this->SetX(35);
                $this->Cell(17,10,"Producto",0,0,'L');
                $this->SetX(65);
                $this->Cell(18,10,"Cantidad",0,0,'L');
                $this->SetX(100);
                $this->Cell(25,10,"Monto Compra",0,0,'L');
                $this->SetX(130);
                $this->Cell(19,10,"Iva",0,0,'L');
                $this->SetX(160);
                $this->Cell(19,10,"Monto Iva",0,0,'L');                                   
                $this->Ln(5); 
                $sql_detalle = 'select b.DET_ITEM_COMPRA,d.PRODUCTO_DESC,b.CANTIDAD_COMPRA,
                    b.MONTO_COMPRA,e.DESC_UNIDAD_MEDIDA,
                    f.IMP_SIGLA,c.MONTO_IMPUESTO
                    from compra a
                    left join compra_detalle b on
                    a.NRO_FACTURA_COMPRA = b.NRO_FACTURA_COMPRA
                    left join compra_impuesto c on
                    c.NRO_FACTURA_COMPRA = b.NRO_FACTURA_COMPRA and
                    c.DET_ITEM_IMPUESTO = b.DET_ITEM_COMPRA
                    inner join producto d on
                    b.COD_PRODUCTO_ITEM = d.COD_PRODUCTO
                    inner join unidad_medida e on
                    e.COD_UNIDAD_MEDIDA = d.COD_UNIDAD_MEDIDA
                    left join impuesto f on
                    f.COD_IMPUESTO = c.COD_IMPUESTO
                    where b.NRO_FACTURA_COMPRA = '.$NRO_FACTURA_COMPRA.'
                    order by b.DET_ITEM_COMPRA';
                $dtDatos_detalle = $this->Conn->query($sql_detalle); 
                while($row = mysql_fetch_assoc($dtDatos_detalle))
                {
                    $this->SetX(11+$x);
                    $this->SetFont('Arial','',7);
                    $this->SetX(20);
                    $this->Cell(24,10,$row['DET_ITEM_COMPRA'],0,0,'L');                
                    $this->SetX(35);
                    $this->Cell(17,10,$row['PRODUCTO_DESC'],0,0,'L');
                    $this->SetX(65);
                    $this->Cell(18,10,$row['CANTIDAD_COMPRA'].' '.$row['DESC_UNIDAD_MEDIDA'],0,0,'L');
                    $this->SetX(100);
                    $this->Cell(25,10,$row['MONTO_COMPRA'],0,0,'L');
                    $this->SetX(130);
                    $this->Cell(19,10,$row['IMP_SIGLA'],0,0,'L');
                    $this->SetX(160);
                    $this->Cell(24,10,$row['MONTO_IMPUESTO'],0,0,'L');
                    $this->Ln(5);
                } 
                $this->Ln(10);
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