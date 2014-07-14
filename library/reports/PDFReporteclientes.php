<?php
  //define("PATH_FPDF","../includes/FPDF/fpdf16/");
  //require(PATH_FPDF.'fpdf.php');
	error_reporting(0);
    class PDFReporteclientes extends FPDF
    {  
            var $Conn;
            var $parametros;
            var $B;
            var $I;
            var $U;
            var $HREF;
            var $fila = 1;

            function PDFReporteclientes($orientation='P',$unit='mm',$format='A4',$parametrosPDF)
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
            $this->Cell(80,10,utf8_decode("Reporte de Clientes"),0,0,'L');
            $this->Ln(10);//Salto de l�nea
            $this->SetX(10+$y);    
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Fecha Reporte "),0,0,'L');
            $this->SetX(35+$y);   
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '.date('d/m/Y H:i:s')),0,0,'L');                            
            $this->Ln(10);//Salto de l�nea   

            $x=-2;
            $this->SetX(11+$x);
            $this->SetFont('Arial','B',9);
            $this->SetX(10);
            $this->Cell(24,10,"Cliente",0,0,'L');                
            $this->SetX(45);
            $this->Cell(17,10,"Ruc",0,0,'L');
            $this->SetX(65);
            $this->Cell(18,10,"Direccion",0,0,'L');
            $this->SetX(100);
            $this->Cell(25,10,"Telefono",0,0,'L');
            $this->SetX(130);
            $this->Cell(19,10,"Email",0,0,'L');
            $this->SetX(160);
            $this->Cell(19,10,"Empresa",0,0,'L');                        
            $this->SetX(180);
            $this->Cell(19,10,"Tel. Empresa",0,0,'L');            
            $this->printLine(55);
            $this->Ln(10);          
        }

        function Body()
        {          
            $sql="select a.CLIENTE_DES,a.CLIENTE_RUC,a.CLIENTE_DIRECCION,a.CLIENTE_TELEFONO,
                a.CLIENTE_EMAIL,b.DES_EMPRESA,b.EMP_DIRECCION,b.EMP_TELEFONO 
                from cliente a
                left join empresa b on
                a.COD_EMPRESA = b.COD_EMPRESA
                order by a.CLIENTE_DES desc";            
            //echo $sql."<br>";                              
            $dtDatos = $this->Conn->query($sql);
        
            $count = 1;
            
            while($row = mysql_fetch_assoc($dtDatos))
            {
                $CLIENTE_DES     = $row['CLIENTE_DES'];
                $CLIENTE_RUC          = $row['CLIENTE_RUC'];
                $CLIENTE_DIRECCION      = $row['CLIENTE_DIRECCION'];
                $CLIENTE_TELEFONO           = $row['CLIENTE_TELEFONO'];
                $CLIENTE_EMAIL        = $row['CLIENTE_EMAIL'];
                $DES_EMPRESA   = $row['DES_EMPRESA'];
                $EMP_DIRECCION               = $row['EMP_DIRECCION'];
                $EMP_TELEFONO= $row['EMP_TELEFONO'];
                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','',9);
                $this->SetX(10);
                $this->Cell(24,10,$CLIENTE_DES,0,0,'L');                
                $this->SetX(45);
                $this->Cell(17,10,$CLIENTE_RUC,0,0,'L');
                $this->SetX(65);
                $this->Cell(18,10,$CLIENTE_DIRECCION,0,0,'L');
                $this->SetX(100);
                $this->Cell(25,10,$CLIENTE_TELEFONO,0,0,'L');
                $this->SetX(130);
                $this->Cell(19,10,$CLIENTE_EMAIL,0,0,'L');
                $this->SetX(160);
                $this->Cell(19,10,$DES_EMPRESA,0,0,'L');
                $this->SetX(180);
                $this->Cell(19,10,$EMP_TELEFONO,0,0,'L');                
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