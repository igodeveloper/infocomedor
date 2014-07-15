<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
  //  error_reporting(0);
    class PDFReportefacturacion extends FPDF
    {
  
        var $Conn;
        var $parametros;
        var $B;
        var $I;
        var $U;
        var $HREF;
        var $fila = 1;

        function PDFReportefacturacion($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
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
            $this->Cell(80,10,utf8_decode("Reporte de Facturacion"),0,0,'L');
            $this->printLine(35);
            $this->Ln(10);//Salto de l�nea       
        }

        function Body()
        {         
			//{"codcliente":"5","namecliente":"Ivan Gomez","codigointerno":"1","controlfiscal":null,"fechaemision":"2014-07-15","estado":"-1"}
            $where = 'where'; 
            if(trim($this->parametros->codcliente) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= ' a.COD_CLIENTE = '.$this->parametros->codcliente; 
            }
            if(trim($this->parametros->namecliente) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " d.cliente_des like '%".$this->parametros->namecliente."%'";
            }            
            if(trim($rowData->codigointerno) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= ' a.fac_nro = '.$this->parametros->codigointerno;
            }            
            if(trim($this->parametros->controlfiscal) <> null){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.CONTROL_FISCAL = '".$this->parametros->controlfiscal."'";
            }            
            if(trim($this->parametros->fechaemision) <> ''){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.fac_fecha_emi = '".$this->parametros->fechaemision."'";
            }                
            if(trim($this->parametros->estado) <> '-1'){
                if(trim($where) <> 'where') $where .= ' and ';
                $where .= " a.estado = '".$this->parametros->estado."'";
            }                
            $orderby = 'order by a.fac_fecha_emi,a.fac_nro';
            $sql="select d.cliente_des,
				a.fac_nro,
                a.fac_fecha_emi,
                a.fac_fech_vto,
				a.fac_monto_total,
				a.control_fiscal
                from factura a
               inner join cliente d on
                d.COD_CLIENTE = a.COD_CLIENTE";
            if(trim($where) <> 'where')
                $sql .= ' '.$where;
            $sql .= ' '.$orderby;
			
            $dtDatos = $this->Conn->query($sql); 
            $count = 1;
			
			$monto_total_cobrado = 0;
			$monto_total_iva_cobrado = 0;
            while($row = mysql_fetch_assoc($dtDatos))
            {
                $cliente_des     = $row['cliente_des'];
                $fac_nro          = $row['fac_nro'];
                $fac_fecha_emi     = $row['fac_fecha_emi'];
                $fac_fech_vto           = $row['fac_fech_vto'];
                $fac_monto_total        = $row['fac_monto_total'];
                $control_fiscal= $row['control_fiscal'];

                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,"Proveedor : ",0,0,'L');   
                $this->SetFont('Arial','',9);
                $this->SetX(28);
                $this->Cell(24,10,$cliente_des,0,0,'L'); 
                $this->SetFont('Arial','B',9);
                $this->SetX(70);
                $this->Cell(17,10,"Codigo Interno : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(97);
                $this->Cell(17,10,$fac_nro,0,0,'L'); 
                $this->SetFont('Arial','B',9);
                $this->SetX(103);
                $this->Cell(18,10,"Fecha Emision Fac. : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(135);
                $this->Cell(18,10,$fac_fecha_emi,0,0,'L');
                $this->SetFont('Arial','B',9);
                $this->SetX(155);
                $this->Cell(25,10,"Fecha Venc. Fac. : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(183);
                $this->Cell(25,10,$fac_fech_vto,0,0,'L');
                $this->Ln(5);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(19,10,"Monto Total Compra : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(45);
                $this->Cell(19,10,$fac_monto_total,0,0,'L');                                
                $this->Ln(5);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(18,10,"Control Fiscal : ",0,0,'L');
                $this->SetFont('Arial','',9);
                $this->SetX(35);
                $this->Cell(18,10,$control_fiscal,0,0,'L');                
                $this->Ln(5);
                
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',7);
                $this->SetX(20);
                $this->Cell(24,10,"Item",0,0,'L');                
                $this->SetX(35);
                $this->Cell(17,10,"Producto",0,0,'L');
                $this->SetX(65);
                $this->Cell(18,10,"Monto Detalle",0,0,'L');
                $this->SetX(100);
                $this->Cell(25,10,"Iva",0,0,'L');
                $this->SetX(130);
                $this->Cell(19,10,"Monto Iva",0,0,'L');                                   
                $this->Ln(5); 
                $sql_detalle = 'select b.fac_det_item,d.PRODUCTO_DESC,
                    b.fac_det_total,
                    f.IMP_SIGLA,c.fact_imp_monto
                    from factura a
                    left join factura_detalle b on
                    a.fac_nro = b.fac_nro
                    left join factura_impuesto c on
                    c.fac_nro = b.fac_nro and
                    c.fac_impuesto_item = b.fac_det_item
                    inner join producto d on
                    b.COD_PRODUCTO = d.COD_PRODUCTO
                    left join impuesto f on
                    f.COD_IMPUESTO = c.COD_IMPUESTO
                    where b.fac_nro = '.$fac_nro.'
                    order by b.fac_det_item';				
                $dtDatos_detalle = $this->Conn->query($sql_detalle); 
                while($row = mysql_fetch_assoc($dtDatos_detalle))
                {
                    $this->SetX(11+$x);
                    $this->SetFont('Arial','',7);
                    $this->SetX(20);
                    $this->Cell(24,10,$row['fac_det_item'],0,0,'L');                
                    $this->SetX(35);
                    $this->Cell(17,10,$row['PRODUCTO_DESC'],0,0,'L');
                    $this->SetX(65);
                    $this->Cell(18,10,$row['fac_det_total'],0,0,'L');
                    $this->SetX(100);
                    $this->Cell(25,10,$row['IMP_SIGLA'],0,0,'L');
                    $this->SetX(130);
                    $this->Cell(19,10,$row['fact_imp_monto'],0,0,'L');
					if(trim($row['fac_det_total']) <> '')
						$monto_total_cobrado = $monto_total_cobrado + $row['fac_det_total'];
					if(trim($row['fact_imp_monto']) <> '')
						$monto_total_iva_cobrado = $monto_total_iva_cobrado + $row['fact_imp_monto'];
                    $this->Ln(5);					
                } 
                $this->Ln(10);
            }  
            $this->Ln(10);
            $this->SetX(11+$x);
            $this->SetFont('Arial','',9);
            $this->SetX(10);
            $this->Cell(24,10,"Total Facturacion",0,0,'L');                
            $this->SetX(45);
            $this->Cell(17,10,': '.$monto_total_cobrado,0,0,'L');
            $this->Ln(5);
            $this->SetX(10);
            $this->Cell(18,10,"Total Iva",0,0,'L');
            $this->SetX(45);
            $this->Cell(25,10,': '.$monto_total_iva_cobrado,0,0,'L'); 			
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