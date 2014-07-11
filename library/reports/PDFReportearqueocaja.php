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
                $this->Image("./images/membrete-actualizado.jpg",10,5,190,30);
				$this->Ln(15);
                //Arial bold 15
                $this->SetFont('Arial','B',12);
                $this->SetX(80+$x);
                $this->Cell(80,10,utf8_decode("ACTA DE EXAMEN FINAL Nº"),0,0,'L');
                $this->Ln(10);//Salto de l�nea
                $this->SetFont('Arial','B',9);
                $this->Cell(80,10,  utf8_decode("Año Académico "),0,0,'L');
                $this->SetX(140+$y);
                $this->Cell(80,10,  utf8_decode("Periodo "),0,0,'L');
                $this->Ln(5);//Salto de l�nea       
                $this->Cell(80,10,  utf8_decode("Carrera "),0,0,'L');
                $this->SetX(140+$y);
                $this->Cell(80,10,  utf8_decode("Convocatoria "),0,0,'L');                 
                $this->Ln(5);//Salto de l�nea                    
                $this->Cell(80,10,  utf8_decode("Curso/Semestre"),0,0,'L');               
                $this->Ln(5);//Salto de l�nea                         
                $this->Cell(80,10,  utf8_decode("Asignatura "),0,0,'L');
                $this->Ln(5);//Salto de l�nea                                   
                $this->Cell(80,10,  utf8_decode("Turno/Sección "),0,0,'L');
                $this->SetX(80+$y);
                $this->Cell(80,10,  utf8_decode("Nro. Interno "),0,0,'L');                 
                $this->Ln(7);//Salto de l�nea   
                $this->SetFont('Arial','',9);
                $this->Cell(80,10,  utf8_decode("Se hace constar que se ha tomado examen de la asignatura arriba indicada, a los alumnos cuya nómina se expresa y que los mismos han"),0,0,'L');
                $this->Ln(5);//Salto de l�nea   
                $this->Cell(80,10,  utf8_decode("merecido las respectivas calificaciones anotadas a continuación de sus respectivos nombres, todos de conformidad con las Leyes y"),0,0,'L');
                $this->Ln(5);//Salto de l�nea   
                $this->Cell(80,10,  utf8_decode("Reglamentos vigentes."),0,0,'L');
                
                $this->SetFont('Arial','B',9);                
                $this->Ln(2);                
                $this->SetX(160);
                $this->Cell(19,10,utf8_decode("CALIFICACIÓN"),0,0,'L');                
                $this->Ln(5);
                //$this->printLine(40);
                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,"Nro.",0,0,'L');                
                $this->SetX(30);
                $this->Cell(17,10,"DOCUMENTO",0,0,'L');
                $this->SetX(60);
                $this->Cell(18,10,"APELLIDOS Y NOMBRES",0,0,'L');
                $this->SetX(170);
                $this->Cell(25,10,"Nro.",0,0,'L');
                $this->SetX(180);
                $this->Cell(19,10,"LETRA",0,0,'L');
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
                $conexion = new Conexion();                
                
                $connection_pg = '';
                $this->parametros = json_decode($parametros);              
               //$result = pg_query($connection_pg, $sql);               
               //QUERY PARA LA CABECERA
               $sql_cabecera ="SELECT a.nroacta,a.anho,a.codcarsec,a.codcurso,
                    a.codasign,a.seccion,a.nrodocumento,a.convocatoria,
                    a.periodo,b.descripcion,c.descripcion as desc_curso_semestre,
                    d.descrip as desc_asignatura
                    FROM academico.notas_finales_cab a
                    INNER JOIN academico.codcarsec b on
                    b.codcarsec = a.codcarsec
                    INNER JOIN academico.codcurso c on
                    c.codcarsec = a.codcarsec and 
                    c.codcurso = a.codcurso                    
                    INNER JOIN academico.codasign d on
                    d.codcarsec = a.codcarsec and 
                    d.codcurso = a.codcurso and 
                    d.codasign = a.codasign
                    where a.codfacul = '".$this->parametros->codfacul."'
                    and a.codcarsec = '".$this->parametros->codcarsec."'
                    and a.codcurso = '".$this->parametros->codcurso."'
                    and a.codasign = '".$this->parametros->codasign."'
                    and a.anho = '".$this->parametros->anho."'
                    and a.turno = '".$this->parametros->turno."'
                    and a.seccion = '".$this->parametros->seccion."'
                    and a.convocatoria = ".$this->parametros->convocatoria."
                    and a.periodo = ".$this->parametros->periodo."
                    and a.tipoexam = '".$this->parametros->tipoexam."'";
				$sql_cabecera ="SELECT a.nroacta,a.anho,a.codcarsec,a.codcurso,
                    a.codasign,a.seccion,a.nrodocumento,a.convocatoria,
                    a.periodo,b.descripcion,c.descripcion as desc_curso_semestre,
                    a.descasign as desc_asignatura
                    FROM academico.notas_finales_cab a
                    INNER JOIN academico.codcarsec b on
                    b.codcarsec = a.codcarsec
                    INNER JOIN academico.codcurso c on
                    c.codcarsec = a.codcarsec and 
                    c.codcurso = a.codcurso                    
                    where a.codfacul = '".$this->parametros->codfacul."'
                    and a.codcarsec = '".$this->parametros->codcarsec."'
                    and a.codcurso = '".$this->parametros->codcurso."'
                    and a.codasign = '".$this->parametros->codasign."'
                    and a.anho = '".$this->parametros->anho."'
                    and a.turno = '".$this->parametros->turno."'
                    and a.seccion = '".$this->parametros->seccion."'
                    and a.convocatoria = ".$this->parametros->convocatoria."
                    and a.periodo = ".$this->parametros->periodo."
                    and a.tipoexam = '".$this->parametros->tipoexam."'";	
//die($sql_cabecera);               
               $dtDatos_cabecera = $conexion->query($sql_cabecera);               
               while($row = mysql_fetch_assoc($dtDatos_cabecera))
               {
                   $nroacta             = $row['nroacta'];
                   $anho                = $row['anho'];  
                   $codcarsec           = $row['codcarsec']; 
                   $codcurso            = $row['codcurso'];                    
                   $codasign            = $row['codasign'];  
                   $seccion             = $row['seccion'];
                   $nrodocumento        = $row['nrodocumento'];
                   $convocatoria        = $row['convocatoria'];
                   $periodo             = $row['periodo'];                   
                   $descripcion_carrera = $row['descripcion'];                  
                   $desc_curso_semestre = $row['desc_curso_semestre'];                                     
                   $desc_asignatura     = $row['desc_asignatura'];
                   $tipoexam            = $this->parametros->tipoexam;
                   $this->cabecera($nroacta,$anho,$codcarsec,$codcurso,$codasign,
                           $seccion,$nrodocumento,$convocatoria,$periodo,
                           $descripcion_carrera,$desc_curso_semestre,
                           $desc_asignatura,$tipoexam);
               }               
               $sql="SELECT a.cedula,b.apellido,b.nombre,a.codnota,c.fechaexam,c.nroacta
                    FROM academico.notas_finales_cab c
                    INNER JOIN academico.notas_finales_det a on
                    a.codfacul = c.codfacul and 
                    a.codcarsec = c.codcarsec and 
                    a.codcurso = c.codcurso and 
                    a.codasign = c.codasign and 
                    a.anho = c.anho and 
                    a.turno = c.turno and 
                    a.seccion = c.seccion and 
                    a.convocatoria = c.convocatoria and 
                    a.periodo = c.periodo and 
                    a.tipoexam = c.tipoexam
                    INNER JOIN academico.alumno b on
                    a.cedula = b.cedula
                    where a.codfacul = '".$this->parametros->codfacul."'
                    and a.codcarsec = '".$this->parametros->codcarsec."'
                    and a.codcurso = '".$this->parametros->codcurso."'
                    and a.codasign = '".$this->parametros->codasign."'
                    and a.anho = '".$this->parametros->anho."'
                    and a.turno = '".$this->parametros->turno."'
                    and a.seccion = '".$this->parametros->seccion."'
                    and a.convocatoria = ".$this->parametros->convocatoria."
                    and a.periodo = ".$this->parametros->periodo."
                    and a.tipoexam = '".$this->parametros->tipoexam."'
                    order by b.apellido asc";
//die($sql);               
//echo $sql."<br>";                              
               $dtDatos = $conexion->query($sql);
//               $this->esqueleto();
               $this->SetY(88);
               $filas = 1;
               $x = 0;
               $y = 0;
               $y1 = 8;
               while($row = mysql_fetch_assoc($dtDatos))
               {
                    $cedula             = $row['cedula'];
                    $apellido_nombre    = $row['apellido'].' '.$row['nombre'];
                    $codnota            = $row['codnota'];
                    $fechaexam          = $row['fechaexam'];
                    $nroacta            = $row['nroacta'];
                    switch ($codnota) {
                        case '1':
                            $letra_nota = 'UNO';
                            break;
                        case '2':
                            $letra_nota = 'DOS';
                            break;
                        case '3':
                            $letra_nota = 'TRES';
                            break;             
                        case '4':
                            $letra_nota = 'CUATRO';
                            break;               
                        case '5':
                            $letra_nota = 'CINCO';
                            break;                        
                        case '':
                            $letra_nota = 'AUSENTE';
                            $codnota = 'A';
                            break;
                    }
                    $this->SetFont('Arial','',9);                    
                    $this->SetX(10);                    
                    $this->Cell(8,10,utf8_decode($this->fila),0,0,'R');                    
                    $this->SetX(30);                    
                    $this->Cell(20,10,utf8_decode($cedula),0,0,'C');
                    $this->SetX(56);
                    $this->Cell(19,10,utf8_decode($apellido_nombre),0,0,'L');
                    $this->SetX(168);
                    $this->Cell(8,10,utf8_decode($codnota),0,0,'R');
                    $this->SetX(182);
                    $this->Cell(18,10,utf8_decode($letra_nota),0,0,'L');

//******************************************************************************
                    //LINEAS VERTICALES
                    //          x   y       x     y 
                    $this->Line(10, 88+$y, 10, 88+$y1);
                    $this->Line(25, 88+$y, 25, 88+$y1);
                    $this->Line(55, 88+$y, 55, 88+$y1);
                    $this->Line(169, 88+$y, 169, 88+$y1);
                    $this->Line(180, 88+$y, 180, 88+$y1);
                    $this->Line(205, 88+$y, 205, 88+$y1);
                    
                    //LINEAS HORIZONTALES                    
                    //          x   y       x     y 
                    $this->Line(10, 88+$x, 205, 88+$y);            
                    $x = $x + 8;
                    $y = $y + 8;
                    $y1= $y1 + 8;
//******************************************************************************
                    
                    $this->Ln(8);                  
                    $this->fila =$this->fila + 1;
                    $filas++;
                    if($filas > 12){                        
                        $this->SetY(184);
                        $this->SetX(10);
                        $this->SetFont('Arial','',9);
                        $this->Cell(80,10,  utf8_decode("En prueba de ello, firman esta acta los señores integrantes del Tribunal Examinador Profesores:"),0,0,'L');
                        $this->Ln(8);//Salto de l�nea   
                        $this->Cell(80,10,  utf8_decode("Presidente:"),0,0,'L');
                        $this->SetX(150);
                        $this->Cell(80,10,  utf8_decode("_____________________"),0,0,'L');                
                        $this->Ln(8);//Salto de l�nea   
                        $this->Cell(80,10,  utf8_decode("Miembro(s):"),0,0,'L');                         
                        //IMPRIME FIRMANTES
                        $sql_firmante = "SELECT apellido, nombre ,tipoint,
                            nroorden,tratamiento
                           FROM interven a
                           INNER JOIN profesor b on
                           a.codprofe = b.codprofe
                           WHERE 
                            a.codfacul = '".$this->parametros->codfacul."'
                            and a.codcarsec = '".$this->parametros->codcarsec."'
                            and a.codcurso = '".$this->parametros->codcurso."'
                            and a.codasign = '".$this->parametros->codasign."'
                            and a.anho = '".$this->parametros->anho."'
                            and a.turno = '".$this->parametros->turno."'
                            and a.seccion = '".$this->parametros->seccion."'
                            and a.convocatoria = ".$this->parametros->convocatoria."
                            and a.periodo = ".$this->parametros->periodo."
                            and a.tipoexam = '".$this->parametros->tipoexam."'
                            order by nroorden asc";
        //die($sql_firmante);                
                        $result = pg_query($connection_pg, $sql_firmante);
                        if (!$result) {
                          echo "Un error al recuperar los firmantes.\n";
                          exit;
                        }          
                        $yFirmante = 0;
                        while ($value = pg_fetch_row($result)) {  
                            $apellido   = $value [0];
                            $nombre     = $value [1];
                            $tipoint    = $value [2];
                            $nroorden   = $value [3];
                            $tratamiento= $value [4];
                            if($tipoint == 'P'){
                                $profesor = $tratamiento.' '.$nombre.' '.$apellido;
                                $this->SetY(192);
                                $this->SetX(30);
                                $this->Cell(80,10,  utf8_decode($profesor),0,0,'L');                                
                            }else{
                                $this->SetY(200+$yFirmante);
                                $profesor = $tratamiento.' '.$nombre.' '.$apellido;
                                $this->SetX(30);
                                $this->Cell(80,10,  utf8_decode($profesor),0,0,'L');                                                                
                                $this->SetX(150);
                                $this->Cell(80,10,  utf8_decode("_____________________"),0,0,'L');
                                $yFirmante = $yFirmante + 9;
                            }
                        }
                        $this->SetY(260);
                        $fecha = substr($fechaexam, 8, 2).'/'.substr($fechaexam, 5, 2).'/'.substr($fechaexam, 0, 4);
                        $fecha_letras=fechaALetras($fecha);
                        $this->Cell(80,10,  utf8_decode("Dada en San Lorenzo, $fecha_letras por ante mi, Secretario de que certifico:"),0,0,'L');                
                        $this->Line(10, 88+$x, 205, 88+$y);//LA ULTIMA LINEA DE LA GRILLA                        
                        $x = 0;
                        $y = 0;
                        $y1 = 8;                        
                        $this->AddPage();
 //                       $this->esqueleto();                                               
                        $this->cabecera($nroacta,$anho,$codcarsec,$codcurso,$codasign,
                           $seccion,$nrodocumento,$convocatoria,$periodo,
                           $descripcion_carrera,$desc_curso_semestre,
                           $desc_asignatura,$tipoexam);
                        $this->SetY(88);//REUBICA PARA LA IMPRESION DE LA GRILLA DE NOTAS
                        $filas = 1;
                    }
               }
               $this->Line(10, 88+$x, 205, 88+$y);//LA ULTIMA LINEA DE LA GRILLA  
                $this->SetY(184);
                $this->SetX(10);
                $this->SetFont('Arial','',9);
                $this->Cell(80,10,  utf8_decode("En prueba de ello, firman esta acta los señores integrantes del Tribunal Examinador Profesores:"),0,0,'L');
                $this->Ln(8);//Salto de l�nea   
                $this->Cell(80,10,  utf8_decode("Presidente:"),0,0,'L');
                $this->SetX(150);
                $this->Cell(80,10,  utf8_decode("_____________________"),0,0,'L');                
                $this->Ln(8);//Salto de l�nea   
                $this->Cell(80,10,  utf8_decode("Miembro(s):"),0,0,'L');
                //IMPRIME FIRMANTES
                $sql_firmante = "SELECT apellido, nombre ,tipoint,
                    nroorden,tratamiento
                   FROM interven a
                   INNER JOIN profesor b on
                   a.codprofe = b.codprofe
                   WHERE 
                    a.codfacul = '".$this->parametros->codfacul."'
                    and a.codcarsec = '".$this->parametros->codcarsec."'
                    and a.codcurso = '".$this->parametros->codcurso."'
                    and a.codasign = '".$this->parametros->codasign."'
                    and a.anho = '".$this->parametros->anho."'
                    and a.turno = '".$this->parametros->turno."'
                    and a.seccion = '".$this->parametros->seccion."'
                    and a.convocatoria = ".$this->parametros->convocatoria."
                    and a.periodo = ".$this->parametros->periodo."
                    and a.tipoexam = '".$this->parametros->tipoexam."'
                    order by nroorden asc";
//die($sql_firmante);                
                $result = pg_query($connection_pg, $sql_firmante);
                if (!$result) {
                  echo "Un error al recuperar los firmantes.\n";
                  exit;
                }          
                $yFirmante = 0;
                while ($value = pg_fetch_row($result)) {  
                    $apellido   = $value [0];
                    $nombre     = $value [1];
                    $tipoint    = $value [2];
                    $nroorden   = $value [3];
                    $tratamiento= $value [4];
                    if($tipoint == 'P'){
                        $profesor = $tratamiento.' '.$nombre.' '.$apellido;
                        $this->SetY(192);
                        $this->SetX(30);
                        $this->Cell(80,10,  utf8_decode($profesor),0,0,'L');                                
                    }else{
                        $this->SetY(200+$yFirmante);
                        $profesor = $tratamiento.' '.$nombre.' '.$apellido;
                        $this->SetX(30);
                        $this->Cell(80,10,  utf8_decode($profesor),0,0,'L');                                                                
                        $this->SetX(150);
                        $this->Cell(80,10,  utf8_decode("_____________________"),0,0,'L');
                        $yFirmante = $yFirmante + 9;
                    }
                }
                $this->SetY(260);
                $fecha = substr($fechaexam, 8, 2).'/'.substr($fechaexam, 5, 2).'/'.substr($fechaexam, 0, 4);
                $fecha_letras=fechaALetras($fecha);
                $this->Cell(80,10,  utf8_decode("Dada en San Lorenzo, $fecha_letras por ante mi, Secretario de que certifico:"),0,0,'L');                 
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