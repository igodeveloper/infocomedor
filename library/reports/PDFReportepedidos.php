<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
  //  error_reporting(0);
    class PDFReportepedidos extends FPDF
    {
  
        var $Conn;
        var $parametros;
        var $B;
        var $I;
        var $U;
        var $HREF;
        var $fila = 1;

        function PDFReportepedidos($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
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
			//{"codcliente":"5","namecliente":"Ivan Gomez","codigointerno":"1","controlfiscal":null,"fechaemision":"2014-07-15","estado":"-1"}
                            
            //Logo
            $x = 0;
            $y = 0;
            //$this->Image("./images/central.jpg",10,10,25,25);
            $this->Image("./css/images/infocomedor.jpg",10,5,50,30);
            $this->Ln(15);
            //Arial bold 15
            $this->SetFont('Arial','B',12);
            $this->SetX(80+$x);
            $this->Cell(80,10,utf8_decode("Reporte de Pedidos"),0,0,'L');
            $this->printLine(35);
            $this->Ln(10);//Salto de l�nea       
        }

        function Body()
        {         
            //{"codcliente":"58","namecliente":"ivan gomez","codmesa":"10","estado":"PA"}
            $where = 'where'; 
            if(trim($this->parametros->codcliente) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= ' a.COD_CLIENTE = '.$this->parametros->codcliente; 
            }
            if(trim($this->parametros->namecliente) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " b.cliente_des like '%".$this->parametros->namecliente."%'";
            }            
            if(trim($rowData->codmesa) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= ' a.cod_mesa = '.$this->parametros->codmesa;
            }            
            if(trim($this->parametros->estado) <> null){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.estado = '".$this->parametros->estado."'";
            }            
            
            
            $orderby = 'order by a.KAR_FECH_MOV desc,a.COD_CLIENTE asc';
            $groupby = 'group by a.KAR_FECH_MOV,a.COD_CLIENTE,a.COD_MESA,b.CLIENTE_DES';
            $sql="select a.KAR_FECH_MOV,a.COD_CLIENTE,a.COD_MESA,b.CLIENTE_DES
                from karrito a
                left join cliente b on
                a.COD_CLIENTE = b.COD_CLIENTE";
            if(trim($where) <> 'where')
                $sql .= ' '.$where;
            $sql .= ' '.$groupby;
            $sql .= ' '.$orderby;
			
            $dtDatos = $this->Conn->query($sql); 
            $count = 1;
            $monto_total_cobrado = 0;
            $monto_total_iva_cobrado = 0;
            while($row = mysql_fetch_assoc($dtDatos))
            {
                $KAR_FECH_MOV    = $row['KAR_FECH_MOV'];
                $COD_CLIENTE   = $row['COD_CLIENTE'];                
                $COD_MESA       = $row['COD_MESA'];
                $CLIENTE_DES    = $row['CLIENTE_DES'];
                
                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,"Cliente : ",0,0,'L');   
                $this->SetFont('Arial','',9);
                $this->SetX(28);
                $this->Cell(24,10,$CLIENTE_DES,0,0,'L'); 
                $this->SetFont('Arial','B',9);
                $this->SetX(70);
                $this->Cell(17,10,"Mesa : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(97);
                $this->Cell(17,10,$COD_MESA,0,0,'L'); 
                $this->SetFont('Arial','B',9);
                $this->SetX(103);
                $this->Cell(18,10,"Fecha Pedido : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(135);
                $this->Cell(18,10,substr($KAR_FECH_MOV,8,2).'/'.substr($KAR_FECH_MOV,5,2).'/'.substr($KAR_FECH_MOV,0,4).' '.substr($KAR_FECH_MOV,11),0,0,'L');
                
                $this->Ln(5); 
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',7);
                $this->SetX(20);
                $this->Cell(24,10,"Producto",0,0,'L');                
                $this->SetX(55);
                $this->Cell(17,10,"Cantidad",0,0,'L');
                $this->SetX(85);
                $this->Cell(18,10,"Precio Uni.",0,0,'L');
                $this->SetX(120);
                $this->Cell(25,10,"Costo",0,0,'L');
                $this->SetX(150);
                $this->Cell(19,10,"Estado",0,0,'L');                                   
                $this->Ln(5); 
                $sql_detalle = "select c.PRODUCTO_DESC,a.KAR_CANT_PRODUCTO,
                a.KAR_PRECIO_PRODUCTO,a.KAR_PRECIO_FACTURAR,a.ESTADO,
                a.MONTO_IMPUESTO,b.IMP_PORCENTAJE
                from karrito a
                inner join producto c on
                c.COD_PRODUCTO = a.COD_PRODUCTO
                inner join impuesto b on
                b.COD_IMPUESTO = a.COD_IMPUESTO
                where a.KAR_FECH_MOV = '".$KAR_FECH_MOV."' and 
                    a.COD_CLIENTE = ".$COD_CLIENTE." and 
                    a.COD_MESA = ".$COD_MESA;
                
                $dtDatos_detalle = $this->Conn->query($sql_detalle); 
                $monto_total_iva_cobrado0 = 0;
                $monto_total_iva_cobrado5 = 0;
                $monto_total_iva_cobrado10 = 0;
                while($row = mysql_fetch_assoc($dtDatos_detalle))
                {
                    $estado = '';
                    if($row['ESTADO'] == 'PA')
                        $estado = 'Pagado';
                    if($row['ESTADO'] == 'PE')
                        $estado = 'Pendiente';                    
                    $this->SetX(11+$x);
                    $this->SetFont('Arial','',7);
                    $this->SetX(20);
                    $this->Cell(24,10,$row['PRODUCTO_DESC'],0,0,'L');                
                    $this->SetX(55);
                    $this->Cell(17,10,number_format(CEIL($row['KAR_CANT_PRODUCTO']),0,',','.'),0,0,'L');
                    $this->SetX(85);
                    $this->Cell(18,10,number_format(CEIL($row['KAR_PRECIO_PRODUCTO']),0,',','.'),0,0,'L');                    
                    $this->SetX(120);
                    $this->Cell(25,10,number_format(CEIL($row['KAR_PRECIO_FACTURAR']),0,',','.'),0,0,'L');
                    $this->SetX(150);
                    $this->Cell(19,10,$estado,0,0,'L');                    
                    if(trim($row['KAR_PRECIO_FACTURAR']) <> '')
                            $monto_total_cobrado = $monto_total_cobrado + $row['KAR_PRECIO_FACTURAR'];
                    if(trim($row['MONTO_IMPUESTO']) <> '' and $row['IMP_PORCENTAJE'] == 0){
                        $monto_total_iva_cobrado0 = $monto_total_iva_cobrado + $row['MONTO_IMPUESTO'];
                    }
                    if(trim($row['MONTO_IMPUESTO']) <> '' and $row['IMP_PORCENTAJE'] == 5){
                        $monto_total_iva_cobrado5 = $monto_total_iva_cobrado + $row['MONTO_IMPUESTO'];
                    }                    
                    if(trim($row['MONTO_IMPUESTO']) <> '' and $row['IMP_PORCENTAJE'] == 10){
                        $monto_total_iva_cobrado10 = $monto_total_iva_cobrado + $row['MONTO_IMPUESTO'];
                    }                    
                    $this->Ln(5);					
                }
                //$this->Ln(5);
                $this->SetX(11+$x);
                $this->SetFont('Arial','',9);
                $this->SetX(10);
                $this->Cell(24,10,"Total Facturacion",0,0,'L');                
                $this->SetX(45);
                $this->Cell(17,10,': '.number_format(CEIL($monto_total_cobrado),0,',','.'),0,0,'L');
                //$this->Ln(5);
                $this->SetX(80);
                $this->Cell(18,10,"Total Iva exenta",0,0,'L');
                $this->SetX(105);
                $this->Cell(25,10,': '.number_format(CEIL($monto_total_iva_cobrado0),0,',','.'),0,0,'L'); 			
                $this->Ln(5);
                $this->SetX(10);
                $this->Cell(18,10,"Total Iva 5",0,0,'L');
                $this->SetX(45);
                $this->Cell(25,10,': '.number_format(CEIL($monto_total_iva_cobrado5),0,',','.'),0,0,'L'); 			
                //$this->Ln(5);
                $this->SetX(80);
                $this->Cell(18,10,"Total Iva 10",0,0,'L');
                $this->SetX(105);
                $this->Cell(25,10,': '.number_format(CEIL($monto_total_iva_cobrado10),0,',','.'),0,0,'L'); 			                            
                $this->Ln(8);
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