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
                $this->Cell(80,10,  utf8_decode("Usuario "),0,0,'L');               
                $this->SetX(30+$y); 
                $this->SetFont('Arial','',9);
                $this->Cell(80,10,  utf8_decode(": "),0,0,'L');
                $this->Ln(7);//Salto de l�nea   

                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,"Nro.",0,0,'L');                
                $this->SetX(20);
                $this->Cell(17,10,"Fecha hora movimiento",0,0,'L');
                $this->SetX(60);
                $this->Cell(18,10,"Monto movimiento",0,0,'L');
                $this->SetX(120);
                $this->Cell(25,10,"Tipo movimiento",0,0,'L');
                $this->SetX(150);
                $this->Cell(19,10,"Nro. Factura",0,0,'L');
                //$this->printLine(50);
                $this->Ln(10);          
            }
            //Pie de p�gina
            function Footer()
            {
                $this->SetFont('Arial','',9);
                $this->SetY(-32);
                $this->Cell(80,10,  utf8_decode("Prof. Ing. Gabriel E. Fleitas Ferrari"),0,0,'L');
                $this->SetX(70);
                $this->Cell(80,10,  utf8_decode("_____________________"),0,0,'L');                            
                $this->Ln(5);//Salto de l�nea
                $this->SetX(10);
                $this->Cell(80,10,  utf8_decode("OBSERVACIÓN:"),0,0,'L');
                $this->SetX(35);
                $this->Cell(80,10,  utf8_decode("______________________________________________________________________________________________"),0,0,'L');                
                $this->Ln(3);//Salto de l�nea   
                $this->Cell(80,10,  utf8_decode("____________________________________________________________________________________________________________"),0,0,'L');                                
                $this->Ln(3);//Salto de l�nea   
                $this->Cell(80,10,  utf8_decode("____________________________________________________________________________________________________________"),0,0,'L');                                                                                        
                
                $this->SetY(-15);
                $this->Cell(-2);
                $this->SetFont('Arial','B',6);
                $this->Cell(25,10,utf8_decode("Estado del documento: Cerrado definitivamente y habilitado para impresión de acta"),0,0,'L');                
                $this->printLine(290);
                $this->Ln(5);
                $this->Cell(-2);
                $this->Cell(50,10,utf8_decode("Sistema de Gestión Docente"),0,0,'L');
                $this->SetX(150);
                $this->Cell(50,10,utf8_decode("Facultad de Ingeniería - UNA"),0,0,'L');
            }
            function Body($parametros)
            {
                
            }
        function cabecera($nroacta,$anho,$codcarsec,$codcurso,$codasign,
                $seccion,$nrodocumento,$convocatoria,$periodo,$descripcion_carrera,
                $desc_curso_semestre,$desc_asignatura,$tipoexam)                    
        {
             switch (trim($tipoexam)) {
                 case '1F':
                     $letra_tipoexam = $periodo.' (PRIMER FINAL)';
                     break;
                 case '2F':
                     $letra_tipoexam = $periodo.'( SEGUNDO FINAL)';
                     break;
                 case '3F':
                     $letra_tipoexam = $periodo.' (TERCER FINAL)';
                     break;                 
             }                           
            $this->SetFont('Arial','B',12);
            $this->SetY(23);
            $this->SetX(140);
            $this->Cell(150,15,utf8_decode($nroacta),100,100,'L');                     
            $this->SetFont('Arial','',9); 
            $this->Ln(5);
            $this->SetY(32);
            $this->SetX(36);
            $this->Cell(150,15,utf8_decode(' : '.$anho),0,0,'L');
            $this->SetX(165);
            $this->Cell(150,15,utf8_decode(' : '.$letra_tipoexam),0,0,'L');                   
            $this->SetY(38);
            $this->SetX(36);
            $this->Cell(150,15,utf8_decode(' : '.$descripcion_carrera.'( '.$codcarsec.' )'),0,0,'L');
            $this->SetX(165);
            $this->Cell(150,15,utf8_decode(' : '.$convocatoria),0,0,'L');            
            $letra_codcurso = '';
             switch ($codcurso) {
                 case '1':
                     $letra_codcurso = '(PRIMER SEMESTRE)';
                     break;
                 case '2':
                     $letra_codcurso = '(SEGUNDO SEMESTRE)';
                     break;
                 case '3':
                     $letra_codcurso = '(TERCER SEMESTRE)';
                     break;             
                 case '4':
                     $letra_codcurso = '(CUARTO SEMESTRE)';
                     break;               
                 case '5':
                     $letra_codcurso = '(QUINTO SEMESTRE)';
                     break;                        
                 case '6':
                     $letra_codcurso = '(SEXTO SEMESTRE)';
                     break;                        
                 case '7':
                     $letra_codcurso = '(SEPTIMO SEMESTRE)';
                     break;
                 case '8':
                     $letra_codcurso = '(OCTAVO SEMESTRE)';
                     break;                        
                 case '9':
                     $letra_codcurso = '(NOVENO SEMESTRE)';
                     break;                        
                 case '10':
                     $letra_codcurso = '(DECIMO SEMESTRE)';
                     break;                        
             }                   
            $this->SetY(43);
            $this->SetX(36);
            $this->Cell(150,15,utf8_decode(' : '.$codcurso.' ('.$desc_curso_semestre.')'),0,0,'L');                    
            $this->SetY(48);
            $this->SetX(36);
            $this->Cell(150,15,utf8_decode(' : '.$desc_asignatura." ( ".$codasign." )"),0,0,'L');                   
            $this->SetY(53);
            $this->SetX(36);
            $this->Cell(150,15,utf8_decode(' : '."UNICO ( ".$seccion." )"),0,0,'L');  
            $this->SetX(102);
            $this->Cell(150,15,utf8_decode(' : '.$nrodocumento),0,0,'L');                 
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