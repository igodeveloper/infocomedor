<?php

class Compras_ReportepagosController extends Zend_Controller_Action
{



    public function init()
    {
        
        /* Initialize action controller here */
         $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
            if(!$parametrosNamespace->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/menus/menu')->redirectAndExit();
            }
        $parametrosNamespace->lock();
    }

    public function indexAction()
    {

    }
  	
   public function imprimirreporteAction() {
    //        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
        //die($json_rowData);        
        //die();
        //{"NRO_FACTURA_COMPRA":"","NRO_CHEQUE":"","ESTADO_PAGO":"-1"}
        $rowData = json_decode($json_rowData);
        
        //$curso = $rowData->curso;
        
        $var_nombrearchivo = 'pagos_';
        $path_tmp = './tmp/';
        $orientation='P';
        $unit='mm';
        $format='A4';
        
        if(!isset($pdf))
          $pdf= new PDFReportepagos($orientation,$unit,$format,$json_rowData);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Body($json_rowData);

        $file = basename($var_nombrearchivo."_".date('Ymdhis'));
        $file .= '.pdf';
        //Guardar el PDF en un fichero
        $pdf->Output($path_tmp.$file, 'F');
        $pdf->close();
        unset($pdf);
        echo json_encode(array("result" => "EXITO","archivo" => $file));
       // echo json_encode(array("result" => "EXITO","archivo" => $file));
        //echo "<script>  window.open('".$path_tmp.$file."');  </script>";                      
    }  	
    public function listarAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $cantidadFilas = $this->getRequest()->getParam("rows");
        if (!isset($cantidadFilas)) {
            $cantidadFilas = 10;
        }
        $parametrosNamespace->cantidadFilas = $cantidadFilas;
        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C' => 'caja'), 
                       array('C.cod_caja',
                            'C.cod_usuario_caja',
                            'C.fecha_hora_apertura',
                            'C.fecha_hora_cierre',
                            'C.monto_caja_apertura',
                            'C.monto_caja_cierre',
                            'C.monto_diferencia_arqueo',
                            'C.arqueo_caja',
                            'U.nombre_apellido'
                           ))
                ->join(array('U' => 'usuario'), 'U.cod_usuario = C.cod_usuario_caja');
        $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridCaja.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }

    private function obtenerPaginas($result,$cantidadFilas,$page){
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
            if(trim($item['arqueo_caja']) == '')
                $arqueo_caja = 'No';
            else
                $arqueo_caja = 'Si';
            $arrayDatos ['cell'] = array(
                null,
                $item['cod_caja'],
                $item['cod_usuario_caja'],
                $item['nombre_apellido'],
                $item['fecha_hora_apertura'],
                $item['fecha_hora_cierre'],
                $item['monto_caja_apertura'],
                $item['monto_caja_cierre'],
                $item['monto_diferencia_arqueo'],
                $arqueo_caja,
                $item['arqueo_caja']
                
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                "cod_caja",
                "cod_usuario_caja",
                "nombre_apellido",
                "fecha_hora_apertura",
                "fecha_hora_cierre",
                "monto_caja_apertura",
                "monto_caja_cierre",
                "monto_diferencia_arqueo",
                "desc_arqueo_caja",
                "arqueo_caja"
            );
            array_push($pagina ['rows'], $arrayDatos);
        }

        if ($cantidadFilas == 0)
            $cantidadFilas = 10;

        $pagina ['records'] = count($result);
        $pagina ['page'] = $page;
        $pagina ['total'] = ceil($pagina ['records'] / $cantidadFilas);

        if ($pagina['records'] == 0) {
            $pagina ['mensajeSinFilas'] = true;
        }

        return $pagina;
    }

    public function buscarAction(){
         $this->_helper->viewRenderer->setNoRender(true);
        $datos = $this->getRequest()->getParam("data");
        $Obj = json_decode($datos);
//        print_r($Obj);
//        die();
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();

        $cantidadFilas = $this->getRequest()->getParam("rows");

        if (!isset($cantidadFilas)) {
            $cantidadFilas = 30;
        }

        $parametrosNamespace->cantidadFilas = $cantidadFilas;

        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C' => 'caja'), 
                       array('C.cod_caja',
                             'C.cod_usuario_caja',
                             'C.fecha_hora_apertura',
                             'C.fecha_hora_cierre',
                             'C.monto_caja_apertura',
                             'C.monto_caja_cierre',
                             'C.monto_diferencia_arqueo',
                             'C.arqueo_caja'));
        if ($Obj != null) {
            if ($Obj->descripcion != null) {
                $select->where("upper(C.cod_usuario_caja) like upper('%".$Obj->descripcion."%')");
            }
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridCaja.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }

    public function eliminarAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $id = $this->getRequest ()->getParam ( "id" );
                $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        try{
                    $db = Zend_Db_Table::getDefaultAdapter();
                    $db->beginTransaction();
                    $servCon = new Application_Model_DataService('Application_Model_DbTable_Caja');
                    $servCon->deleteRowById(array("cod_caja"=>$id));
                    $db->commit();
            echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
            echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
            $db->rollBack();
        }
    }

    public function guardarAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
        $rowData = json_decode($json_rowData);
        $applicationModel = new Application_Model_Caja();
        self::almacenardatos($applicationModel,$rowData);
    }

    public function modificarAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
        $rowData = json_decode($json_rowData);
        $rowClass = new Application_Model_Caja();
        if($rowData->cod_caja != null){
            $rowClass->setCod_caja($rowData->cod_caja);
        }
        self::almacenardatos($rowClass,$rowData);
    }

    public function almacenardatos($rowClass,$rowData){
        try{
            $service = new Application_Model_DataService('Application_Model_DbTable_Caja');
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
                    if(isset($rowData->cod_usuario_caja) and isset($rowData->cod_caja))
                            $rowClass->setCod_usuario_caja(trim(utf8_decode($rowData->cod_usuario_caja)));
                    if(isset($rowData->fecha_hora_apertura))
                            $rowClass->setFecha_hora_apertura(trim(utf8_decode($rowData->fecha_hora_apertura)));
                    else
                            $rowClass->setFecha_hora_apertura(date('Y-m-d h:i:s'));
                    if(trim($rowData->cod_caja) <> '')
                            $rowClass->setFecha_hora_cierre(date('Y-m-d h:i:s'));

                    if(trim($rowData->cod_caja) <> ''){
                        $entrada_efectivo = 0;
                        $salida_efectivo  = 0;
                        $entrada_cheque = 0;
                        $salida_cheque  = 0;						
                        $entrada_efectivo = $rowData->monto_caja_apertura + $rowData->monto_entrante_efectivo;
                        $salida_efectivo = $rowData->monto_saliente_efectivo; 
                        $monto_diferencia_efectivo = $entrada_efectivo - $salida_efectivo;
                        $monto_diferencia_arqueo_efectivo = $monto_diferencia_efectivo - $rowData->monto_caja_cierre_efectivo;
                        $rowClass->setMonto_diferencia_arqueo($monto_diferencia_arqueo_efectivo);
						
                        $entrada_cheque = $rowData->monto_entrante_cheque;
                        $salida_cheque = $rowData->monto_saliente_cheque; 
                        $monto_diferencia_cheque = $entrada_cheque - $salida_cheque;
                        $monto_diferencia_arqueo_cheque = $monto_diferencia_cheque - $rowData->monto_caja_cierre_cheque;
                        $rowClass->setMonto_diferencia_arqueo_cheque($monto_diferencia_arqueo_cheque);
						
                        $rowClass->setArqueo_caja('S');
                    }
                    if(isset($rowData->monto_caja_apertura))
                            $rowClass->setMonto_caja_apertura(trim(utf8_decode($rowData->monto_caja_apertura)));
                    
                    if(isset($rowData->monto_caja_cierre_efectivo) and trim($rowData->cod_caja) <> '')
                            $rowClass->setMonto_caja_cierre(trim(utf8_decode($rowData->monto_caja_cierre_efectivo)));
                    
                   // if(isset($rowData->monto_diferencia_arqueo))
                   //         $rowClass->setMonto_diferencia_arqueo(trim(utf8_decode($rowData->monto_diferencia_arqueo)));
                    
                    if(isset($rowData->arqueo_caja))
                            $rowClass->setArqueo_caja(trim(utf8_decode($rowData->arqueo_caja)));
//print_r($rowClass);
//die();
                $result = $service->saveRow($rowClass);
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
            echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
            $db->rollBack();
        }
    }
 
    public function usuariocajadataAction()
    {
//      $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            $result = '';
            $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
            $cod_usuario = $parametrosNamespace->cod_usuario;
            try {
                $db = Zend_Db_Table::getDefaultAdapter();
                $select = $db->select()
                        ->from(array('C' => 'usuario'), 
                               array('C.cod_usuario',
                                     'C.nombre_apellido',
                                     'now() as fechaaperturacaja'))
                        ->where("cod_usuario = ".$cod_usuario);               
                $result = $db->fetchAll($select);
                foreach ($result as $arr) {
                    $htmlResultado = json_encode(array("cod_usuario" => $arr["cod_usuario"],"nombre_apellido" => $arr["nombre_apellido"],
                        "fechaaperturacaja" => $arr["fechaaperturacaja"]));
                }
            } catch (Exception $e) {
                    $htmlResultado = "error";
            }
            echo $htmlResultado;
    }
    public function creartablasAction()
    {        
        $table = new Zend_ModelCreator('caja','infocomedor');
    }    
    public function cajaabiertadataAction()
    {
//      $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $cod_usuario = $parametrosNamespace->cod_usuario;
        $jsonResultado = json_encode(array("resultado" => 'cerrado'));      
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $select = $db->select()
                    ->from(array('C' => 'usuario'), 
                           array('C.cod_usuario',
                                 'C.nombre_apellido',
                                 'D.fecha_hora_apertura'))
                    ->join(array('D' => 'caja'), 'D.cod_usuario_caja = C.cod_usuario')
                    ->where("fecha_hora_cierre is null and cod_usuario_caja = ".$cod_usuario);  
            $result = $db->fetchAll($select);
//die($select);         
            foreach ($result as $arr) {
                $jsonResultado = json_encode(array("resultado" => 'abierto',"cod_usuario" => $arr["cod_usuario"],"nombre_apellido" => $arr["nombre_apellido"],
                    "fecha_hora_apertura" => $arr["fecha_hora_apertura"]));
            }
        } catch (Exception $e) {
                $jsonResultado = json_encode(array("resultado" => 'error'));
        }
        echo $jsonResultado;
    }
    public function cierrecajadataAction()
    {
//      $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
                $json_rowData = $this->getRequest ()->getParam ( "parametros" );
                $rowData = json_decode($json_rowData);
                $cod_caja = $rowData->cod_caja;
        $jsonResultado = json_encode(array("resultado" => 'cerrado'));      
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $select = $db->select()
                    ->from(array('C' => 'usuario'), 
                           array(                                
                                 'C.nombre_apellido',
                                 'now() as fechahoracierre'
                                 ))
                    ->join(array('D' => 'caja'), 'D.cod_usuario_caja = C.cod_usuario',array('D.cod_caja',
                                                                'D.cod_usuario_caja',
                                 'D.fecha_hora_apertura',
                                 'D.monto_caja_apertura',))
                    ->joinLeft(array('M' => 'mov_caja'), 'M.cod_caja = D.cod_caja',array('total_monto_mov' =>'SUM(M.monto_mov)',
					'tipo_forma_pago' =>'M.tipo_mov'))
                    ->joinLeft(array('T' => 'tipo_movimiento'), 'T.cod_tipo_mov = M.cod_tipo_mov',array('T.tipo_mov'))
                    ->where("D.cod_caja = ".$cod_caja)
                                        ->group('C.nombre_apellido')
                                        ->group('D.cod_caja')
                                        ->group('D.cod_usuario_caja')
                                        ->group('D.fecha_hora_apertura')
                                        ->group('D.monto_caja_apertura')
                                       ->group('T.tipo_mov')
									   ->group('M.tipo_mov');   
//die($select);                   
            $result = $db->fetchAll($select);           
            $monto_entrante_efectivo = 0;
            $monto_saliente_efectivo = 0;
            $monto_entrante_cheque = 0;
            $monto_saliente_cheque = 0;			
            foreach ($result as $arr) {
                if($arr['tipo_mov'] == 'R' and $arr['tipo_forma_pago'] == 'EFECTIVO')
                    $monto_saliente_efectivo = $arr['total_monto_mov'];
                if($arr['tipo_mov'] == 'S' and $arr['tipo_forma_pago'] == 'EFECTIVO')
                    $monto_entrante_efectivo = $arr['total_monto_mov'];                
                if($arr['tipo_mov'] == 'R' and $arr['tipo_forma_pago'] == 'CHEQUE')
                    $monto_saliente_cheque = $arr['total_monto_mov'];
                if($arr['tipo_mov'] == 'S' and $arr['tipo_forma_pago'] == 'CHEQUE')
                    $monto_entrante_cheque = $arr['total_monto_mov'];                					
                $jsonResultado = json_encode(array("resultado" => 'abierto',
                    "cod_caja" => $arr["cod_caja"],
                    "cod_usuario_caja" => $arr["cod_usuario_caja"],
                    "fecha_hora_apertura" => $arr["fecha_hora_apertura"],
                    "fecha_hora_cierre" => $arr["fechahoracierre"],
                    "monto_caja_apertura" => $arr["monto_caja_apertura"],
                    "nombre_apellido" => $arr["nombre_apellido"],
                    "monto_entrante_efectivo" => $monto_entrante_efectivo,
                    "monto_saliente_efectivo" => $monto_saliente_efectivo,
                    "monto_entrante_cheque" => $monto_entrante_cheque,
                    "monto_saliente_cheque" => $monto_saliente_cheque));
            }
        } catch (Exception $e) {
                $jsonResultado = json_encode(array("resultado" => 'error'));
        }
        echo $jsonResultado;
    }   
}

