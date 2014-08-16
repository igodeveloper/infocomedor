<?php

class Produccion_BajaStockController extends Zend_Controller_Action
{



    public function init()
    {
        /* Initialize action controller here */
         $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
            if(!$parametrosNamespace->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/menus/menu')->redirectAndExit();
            }else{
                 if($parametrosNamespace->PERMISO!=1){
                    $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                    $r->gotoUrl('/error')->redirectAndExit();
                }
            }
        $parametrosNamespace->lock();
    }

    public function indexAction()
    {

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
                ->from(array('B' => 'baja_stock'), 
                       array(
                            'P.cod_producto',
                            'P.producto_desc',
                            'B.cantidad_baja',
                            'B.fecha_hora_baja',
                            'U.desc_unidad_medida',
                            'B.observacion_mov',
                            'B.cod_baja_stock',
                            'B.estado'
                           ))
                ->join(array('P' => 'producto'), 'B.cod_producto = P.cod_producto')
                ->join(array('U' => 'unidad_medida'), 'U.cod_unidad_medida = P.cod_unidad_medida')
                ->order(array('B.fecha_hora_baja DESC'));   
//die($select);        
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
            $estado = 'Activo';
            if($item['estado'] == 'A')
                $estado = 'Anulado';
            $arrayDatos ['cell'] = array(
                null,
                $item['cod_producto'],
                $item['producto_desc'],
                $item['cantidad_baja'],
                $item['desc_unidad_medida'],
                $item['fecha_hora_baja'],                
                $item['observacion_mov'],
                $item['cod_baja_stock'],
                $estado
                    
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                "cod_producto",
                "producto_desc",
                "cantidad_baja",
                "desc_unidad_medida",
                "fecha_hora_baja",                
                "observacion_mov",
                "cod_baja_stock",
                'estado'
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

    public function anulacionAction(){        
        $this->_helper->viewRenderer->setNoRender ( true );
        $id = $this->getRequest ()->getParam ( "id" );
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        try{
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            //$servCon = new Application_Model_DataService('Application_Model_DbTable_Movcaja');
            //$servCon->deleteRowById(array("cod_mov_caja"=>$id));
            $data_update = array(                    
                'estado'=>'A',                 
            );
            $where ="cod_baja_stock = ".$id;                                               
            $update_pago = $db->update('baja_stock',$data_update,$where);                    
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
        $applicationModel = new Application_Model_Bajastock();
        self::almacenardatos($applicationModel,$rowData);
    }

    public function modificarAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
        $rowData = json_decode($json_rowData);
        $rowClass = new Application_Model_BajaStock();
        if($rowData->cod_baja_stock != null){
            $rowClass->setCod_baja_stock($rowData->cod_baja_stock);
        }
        self::almacenardatos($rowClass,$rowData);
    }

    public function almacenardatos($rowClass,$rowData){
        try{
            $service = new Application_Model_DataService('Application_Model_DbTable_BajaStock');
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
                $rowClass->setCod_producto(trim(utf8_decode($rowData->cod_producto)));
                $rowClass->setCod_unidad_medida(trim(utf8_decode($rowData->cod_unidad_medida)));
                $rowClass->setCantidad_baja(trim(utf8_decode($rowData->cantidad_baja)));                
                $rowClass->setFecha_hora_baja(date('Y-m-d h:i:s'));
                $rowClass->setObservacion_mov(trim(utf8_decode($rowData->observacion_mov)));
                $result = $service->saveRow($rowClass);                
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
            echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
            $db->rollBack();
        }
    }
    public function creartablasAction()
    {        
        $table = new Zend_ModelCreator('baja_stock','infocomedor');
    }    
    public function cargarunidadproductostockAction()
    {
//      $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
        $rowData = json_decode($json_rowData);        
        $cod_producto = $rowData->producto;
        $jsonResultado = json_encode(array("resultado" => 'EXITO',
            "cod_unidad_medida" => null,
            "desc_unidad_medida" => '',
            "desc_unidad_medida" => null));      
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $select = $db->select()
                    ->from(array('C' => 'unidad_medida'), 
                           array('C.cod_unidad_medida',
                                 'C.desc_unidad_medida',
                                 'S.saldo_stock'))
                    ->join(array('P'=>'producto'),'P.cod_unidad_medida = C.cod_unidad_medida')
                    ->join(array('S'=>'stock'),'S.cod_producto = P.cod_producto')
                    ->where("P.cod_producto = ".$cod_producto);                  
            $result = $db->fetchAll($select);
            foreach ($result as $arr) {
                $jsonResultado = json_encode(array("resultado" => 'EXITO',
                    "cod_unidad_medida" => $arr["cod_unidad_medida"],
                    "desc_unidad_medida" => $arr["desc_unidad_medida"],
                    "saldo_stock" => $arr["saldo_stock"]));                 
            }
        } catch (Exception $e) {
                $jsonResultado = json_encode(array("resultado" => 'error'));
        }
        echo $jsonResultado;
    }
    public function cargarproductostockAction() {
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('A' => 'stock'), array('distinct(A.cod_producto)', 'D.producto_desc'))
                ->join(array('D' => 'producto'), 'D.cod_producto = A.cod_producto')
                 ->order(array('D.producto_desc'));
        $result = $db->fetchAll($select);
        $htmlResult = '<option value="-1">---Seleccione---</option>';
        foreach ($result as $arr) {
             $htmlResult .= '<option value="'.$arr["cod_producto"].'">' .trim(utf8_decode($arr["producto_desc"])).'</option>';	
        }
        echo  $htmlResult;
     }     
}

