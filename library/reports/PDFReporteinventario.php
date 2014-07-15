<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
	error_reporting(0);
    class PDFReporteinventario extends FPDF
    {  
            var $Conn;
            var $parametros;
            var $B;
            var $I;
            var $U;
            var $HREF;
            var $fila = 1;

            function PDFReporteinventario($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
            {
                //Llama al constructor de la clase padre
                $this->FPDF($orientation,$unit,$format);
                $this->parametros = json_decode($parametrosPDF);      
               /* print_r($this->parametros->cod_producto_tipo); die();*/
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
            $this->Cell(80,10,utf8_decode("Listado Inventario"),0,0,'L');
            $this->Ln(10);//Salto de l�nea
            $this->SetX(10+$y);    
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Fecha "),0,0,'L');
            $this->SetX(35+$y);   
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '.date('d/m/Y H:i:s')),0,0,'L');                            
            $this->Ln(10);//Salto de l�nea   

            

            
        }

        function Body()
        {          

                 $sql2="select 
                    a.COD_INVENTARIO,
                    a.COD_PRODUCTO,
                    b.PRODUCTO_DESC,
                    b.COD_UNIDAD_MEDIDA,
                    c.ISO_UNIDAD_MEDIDA
                from inventario a
                    join producto b on 
                a.COD_PRODUCTO = b.COD_PRODUCTO
                    join unidad_medida c on
                b.COD_UNIDAD_MEDIDA = c.COD_UNIDAD_MEDIDA
                    where a.COD_INVENTARIO = (select max(x.COD_INVENTARIO) from inventario x)
                order by b.PRODUCTO_DESC desc";   

                //echo $sql."<br>";                              
                $dtDatos2 = $this->Conn->query($sql2);

                $sql = "select max(x.COD_INVENTARIO) from inventario x";
                $dtDatos = $this->Conn->query($sql);
                $fila1 = mysql_fetch_row($dtDatos);
               
               
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,'Cod Inventario: '.$fila1[0],0,0,'L');
                $this->Ln(5); 

               $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,"Item",0,0,'L');                
                $this->SetX(40);
                $this->Cell(17,10,"Producto",0,0,'L');
                $this->SetX(100);
                $this->Cell(18,10,"Inventario",0,0,'L');
                $this->SetX(130);
                $this->Cell(25,10,"Uni Med",0,0,'L');
                $this->Ln(10);
            
                
    
                $count = 1;
                $item=0;
                while($row2 = mysql_fetch_assoc($dtDatos2))
                {   
                    // print_r($row2);
                    // die();
                    $item++;
                    $COD_INVENTARIO = $row2["COD_INVENTARIO"];
                    $COD_PRODUCTO = $row2["COD_PRODUCTO"];
                    $PRODUCTO_DESC = $row2["PRODUCTO_DESC"];
                    $ISO_UNIDAD_MEDIDA = $row2["ISO_UNIDAD_MEDIDA"];


                $this->SetX(11+$x);
                $this->SetFont('Arial','',9);
                $this->SetX(10);
                $this->Cell(24,10,$item,0,0,'L');                
                $this->SetX(40);
                $this->Cell(17,10,$PRODUCTO_DESC,0,0,'L');
                $this->SetX(100);
                $this->Cell(18,10,'______',0,0,'L');
                $this->SetX(130);
                $this->Cell(25,10,$ISO_UNIDAD_MEDIDA,0,0,'L');
                 $this->Ln(5);      
                $this->fila++;
                $count++;
                                    
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