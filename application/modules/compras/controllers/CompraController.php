<?php
//llamada al controller compra
//http://infocomedor/compras/compra?userSession=fede&sucursalSession=1&codUsuarioSession=1&monedaSession=1#
class Compras_CompraController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $parametrosNamespace->unlock ();
        $parametrosNamespace->parametrosBusqueda = null;
        $parametrosNamespace->cantidadFilas = null;
        $parametrosNamespace->jsGrip = '/js/grillasmodulos/compras/gridCompra.js';
        $parametrosNamespace->Application_Model_DbTable = "Application_Model_DbTable_Compra";
        $parametrosNamespace->busqueda = "NRO_FACTURA_COMPRA";
        //esto dos parametros sucursalSession y userSession tienen que venir ya desde logueo
        $parametrosNamespace->userSession = $this->getRequest ()->getParam ( "userSession" );
        $parametrosNamespace->codUsuarioSession = $this->getRequest ()->getParam ( "codUsuarioSession" );
        $parametrosNamespace->sucursalSession = $this->getRequest ()->getParam ( "sucursalSession" );
        $parametrosNamespace->monedaSession = $this->getRequest ()->getParam ( "monedaSession" );
        $parametrosNamespace->lock ();        
    }

    public function listarAction() {
            $this->_helper->viewRenderer->setNoRender ( true );

            $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
            $parametrosNamespace->unlock ();

            $cantidadFilas = $this->getRequest ()->getParam ( "rows" );

            if (! isset ( $cantidadFilas )) {
                    $cantidadFilas = 10;
            }

            $parametrosNamespace->cantidadFilasPeriodo = $cantidadFilas;            

            $page = $this->getRequest ()->getParam ( "page" );
            if (! isset ( $page )) {
                    $page = 1 ;
            }

            $this->view->headScript ()->appendFile ( $this->view->baseUrl () . '/js/bootstrap.js' );
            $this->view->headScript ()->appendFile ( $this->view->baseUrl () . '/js/gridPlanes.js' );

            $ser = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable); 
            $fields = array(
                            'COMPRA.COD_SUCURSAL',
                            'SUCURSAL.DES_SUCURSAL',
                            'COMPRA.COD_PROVEEDOR',
                            'PROVEEDOR.PROVEEDOR_NOMBRE',
                            'COMPRA.NRO_FACTURA_COMPRA',    					
                            'COMPRA.FECHA_EMISION_FACTURA',
                            'COMPRA.FECHA_VENCIMIENTO_FACTURA',
                            'COMPRA.MONTO_TOTAL_COMPRA',
                            'COMPRA.COD_MONEDA_COMPRA',
                            'MONEDA.DESC_MONEDA',
                            'COMPRA.CREDITO');

            $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
            $where = $parametrosNamespace->parametrosBusqueda;		

            $order = array('COMPRA.FECHA_EMISION_FACTURA ASC');
            $result = $ser->getRowsJoin($fields, array('Application_Model_DbTable_Sucursal',
                'Application_Model_DbTable_Proveedor','Application_Model_DbTable_Moneda'), null,$where, $order,null);


            $pagina = self::obtenerPaginas($result,$cantidadFilas,$page);
            echo $this->_helper->json ( $pagina );
    }
    private function obtenerPaginas($result,$cantidadFilas,$page){
            $this->_paginator = Zend_Paginator::factory ($result);
            $this->_paginator->setItemCountPerPage ( $cantidadFilas );
            $this->_paginator->setCurrentPageNumber($page);
            $pagina ['rows'] = array ();
            foreach ( $this->_paginator as $item ) {
                    $id = $item["COD_SUCURSAL"].'-'.$item["COD_PROVEEDOR"].'-'.$item["NRO_FACTURA_COMPRA"];
                    $fechaEmision = substr($item["FECHA_EMISION_FACTURA"],8,2).'/'.substr($item["FECHA_EMISION_FACTURA"],5,2).'/'.
                            substr($item["FECHA_EMISION_FACTURA"],0,4);
                    $fechaVencimiento = substr($item["FECHA_VENCIMIENTO_FACTURA"],8,2).'/'.substr($item["FECHA_VENCIMIENTO_FACTURA"],5,2).'/'.
                            substr($item["FECHA_VENCIMIENTO_FACTURA"],0,4);                        
                    if(trim($item["CREDITO"]) == 'S')
                        $credito = 'Si'; else $credito = 'No';
                    $arrayDatos ['cell'] = array($id,null,trim(utf8_encode($item["DES_SUCURSAL"])),
                        trim(utf8_encode($item["PROVEEDOR_NOMBRE"])),$item["NRO_FACTURA_COMPRA"],
                        $fechaEmision,$fechaVencimiento,$item["MONTO_TOTAL_COMPRA"],
                        trim(utf8_encode($item["DESC_MONEDA"])),$credito);
                    $arrayDatos ['columns'] = array("id","modificar","sucursal","proveedor","nroFactura",
                        "fechaEmision","fechaVencimiento","montoCompra","moneda","credito");
                    array_push ( $pagina ['rows'],$arrayDatos);
            }

            if($cantidadFilas == 0) $cantidadFilas = 10;

            $pagina ['records'] = count ( $result );
            $pagina ['page'] = $page;
            $pagina ['total'] = ceil ( $pagina ['records'] / $cantidadFilas );

            if($pagina['records'] == 0){
                    $pagina ['mensajeSinFilas'] = true;
            }

            $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
            $parametrosNamespace->unlock ();
            $parametrosNamespace->listadoImpuestos = $pagina ['rows'];
            $parametrosNamespace->lock ();

            return $pagina;
    }

    public function buscarAction(){
        $this->_helper->viewRenderer->setNoRender ( true );

        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $parametrosNamespace->unlock ();

        $cantidadFilas = $this->getRequest ()->getParam ( "rows" );
        if (! isset ( $cantidadFilas )) {
                $cantidadFilas = $parametrosNamespace->cantidadFilas;
        }
        $page = $this->getRequest ()->getParam ( "page" );
        if (! isset ( $page )) {
                $page = 1 ;
        }

        $json_rowData = $this->getRequest ()->getParam ("data");
        $rowData = json_decode($json_rowData);
//        $servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
        $where =array();
        if($rowData->comboSucursal != null and $rowData->comboSucursal <> -1){
                array_push($where, " SUCURSAL.COD_SUCURSAL = ".$rowData->comboSucursal);
        }                
        if($rowData->comboProveedor != null and $rowData->comboProveedor <> -1){
                array_push($where, " PROVEEDOR.COD_PROVEEDOR = ".$rowData->comboProveedor);
        }        
        if($rowData->nroFacturaCompra != null){                
                array_push($where, " COMPRA.NRO_FACTURA_COMPRA = ".$rowData->nroFacturaCompra);
        }        

        $parametrosNamespace->parametrosBusqueda = $where;
        $parametrosNamespace->lock ();

        $ser = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable); 
        $fields = array(
                        'COMPRA.COD_SUCURSAL',
                        'SUCURSAL.DES_SUCURSAL',
                        'COMPRA.COD_PROVEEDOR',
                        'PROVEEDOR.PROVEEDOR_NOMBRE',
                        'COMPRA.NRO_FACTURA_COMPRA',    					
                        'COMPRA.FECHA_EMISION_FACTURA',
                        'COMPRA.FECHA_VENCIMIENTO_FACTURA',
                        'COMPRA.MONTO_TOTAL_COMPRA',
                        'COMPRA.COD_MONEDA_COMPRA',
                        'MONEDA.DESC_MONEDA',
                        'COMPRA.CREDITO');

        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $where = $parametrosNamespace->parametrosBusqueda;		

        $order = array('COMPRA.FECHA_EMISION_FACTURA ASC');
        $result = $ser->getRowsJoin($fields, array('Application_Model_DbTable_Sucursal',
            'Application_Model_DbTable_Proveedor','Application_Model_DbTable_Moneda'), null,$where, $order,null);


        $pagina = self::obtenerPaginas($result,$cantidadFilas,$page);
        echo $this->_helper->json ( $pagina );
    }

    public function eliminarAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $id = explode('-', $this->getRequest ()->getParam ( "id" ));
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );

        

        $ser = new Application_Model_DataService('Application_Model_DbTable_Compra'); 
        $fields = array('COUNT(*)');
        $where = array();
        array_push($where, ' COMPRA.COD_SUCURSAL = '.$id[0]);
        array_push($where, ' COMPRA.COD_PROVEEDOR = '.$id[1]);
        array_push($where, ' COMPRA.NRO_FACTURA_COMPRA = '.$id[2]);
        array_push($where, ' COMPRA_DETALLE.TRANSFERENCIA_COMPRA <> 0');	
        $result = $ser->getRowsJoin($fields, array('Application_Model_DbTable_CompraDetalle'), null,$where,null,null);
        
        
        
        try{
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
            $servCon->deleteRowById(array("COD_INSUMO"=>$id));
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
        //$applicationModel = new Application_Model_Compra;
        //self::almacenardatos($applicationModel,$rowData);
        self::almacenardatos($rowData);
    }

    public function modificarAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
        $rowData = json_decode($json_rowData);
        $rowClass = new Application_Model_Insumo();
        if($rowData->idRegistro != null){
            $rowClass->setCod_Insumo($rowData->idRegistro);
        }
        self::almacenardatos($rowClass,$rowData);
    }

    public function almacenardatos($rowData){
        try{    
            $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
            $rowClass = new Application_Model_Compra();
            $service = new Application_Model_DataService('Application_Model_DbTable_Compra');
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $rowClass->setCod_Sucursal($parametrosNamespace->sucursalSession);
            $rowClass->setCod_Proveedor($rowData->cabecera->proveedor);
            $rowClass->setNro_Factura_Compra($rowData->cabecera->nroFactura);
            $rowClass->setFecha_Emision_Factura($rowData->cabecera->fechaEmision);
            $rowClass->setFecha_Vencimiento_Factura($rowData->cabecera->fechaVencimiento);
            $rowClass->setCod_Usuario($parametrosNamespace->codUsuarioSession);            
            $rowClass->setMonto_Total_Compra(0);
            $rowClass->setCod_Moneda_Compra($parametrosNamespace->monedaSession);
            $rowClass->setCredito($rowData->cabecera->credito);
            $rowClass->setCod_Forma_Pago($rowData->cabecera->formaPago);
            $result = $service->saveRow($rowClass);
            unset($service);
            unset($rowClass);
            $rowClass = new Application_Model_CompraDetalle();
            $service = new Application_Model_DataService('Application_Model_DbTable_CompraDetalle');
            $det_item_compra = 1;
            $rowClassImpuesto = new Application_Model_CompraImpuesto();
            $serviceImpuesto = new Application_Model_DataService('Application_Model_DbTable_CompraImpuesto');
            $det_item_impuesto = 1;
       
            foreach ($rowData->detalles as $row){
                $rowClass->setCod_Sucursal($parametrosNamespace->sucursalSession);
                $rowClass->setCod_Proveedor($rowData->cabecera->proveedor);
                $rowClass->setNro_Factura_Compra($rowData->cabecera->nroFactura);
                $rowClass->setDet_Item_Compra($det_item_compra);
                $rowClass->setCod_Insumo($row->insumoCompra);
                $rowClass->setCantidad_Compra($row->cantidadCompra);
                $rowClass->setMonto_Compra($row->montoCompra);
                $rowClass->setTransferencia_Compra(0);
                $result = $service->saveRow($rowClass);

                foreach ($row->impuestos as $rowImpuesto){
                    $impuesto = $rowImpuesto->codImpuesto;
                    $montoImpuesto = $rowImpuesto->montoImpuesto;

                    if($rowImpuesto->codImpuesto > 0 and $rowImpuesto->montoImpuesto > 0){
                        $rowClassImpuesto->setCod_Sucursal($parametrosNamespace->sucursalSession);                        
                        $rowClassImpuesto->setCod_Proveedor($rowData->cabecera->proveedor);                        
                        $rowClassImpuesto->setNro_Factura_Compra($rowData->cabecera->nroFactura);
                        $rowClassImpuesto->setDet_Item_Compra($det_item_compra);
                        $rowClassImpuesto->setDet_Item_Impuesto($det_item_impuesto);                        
                        $rowClassImpuesto->setCod_Impuesto($rowImpuesto->codImpuesto);
                        $rowClassImpuesto->setMonto_Impuesto($rowImpuesto->montoImpuesto);
                        $result = $serviceImpuesto->saveRow($rowClassImpuesto);
                        $det_item_impuesto++;
                    }                                        
                }
                $det_item_compra++;
            }   
            $db->commit();
            unset($service);
            unset($rowClass);
            unset($serviceImpuesto);
            unset($rowClassImpuesto);            
            echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
            echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
            $db->rollBack();
        }
    }
    
    public function sucursaldataAction() {
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'SUCURSAL'), array('distinct(C.COD_SUCURSAL)', 'C.DES_SUCURSAL'))
                 ->order(array('C.DES_SUCURSAL'));
        $result = $db->fetchAll($select);
        $htmlResult = '<option value="-1">---Seleccione---</option>';
        foreach ($result as $arr) {
             $htmlResult .= '<option value="'.$arr["COD_SUCURSAL"].'">' .trim(utf8_encode($arr["DES_SUCURSAL"])).'</option>';	
        }
        echo  $htmlResult;
     }	    
    public function proveedordataAction() {
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'PROVEEDOR'), array('distinct(C.COD_PROVEEDOR)', 'C.PROVEEDOR_NOMBRE'))
                 ->order(array('C.PROVEEDOR_NOMBRE'));
        $result = $db->fetchAll($select);
        $htmlResult = '<option value="-1">---Seleccione---</option>';
        foreach ($result as $arr) {

                         $htmlResult .= '<option value="'.$arr["COD_PROVEEDOR"].'">' .trim(utf8_encode($arr["PROVEEDOR_NOMBRE"])).'</option>';	
        }
        echo  $htmlResult;
     }	         
    public function insumodataAction() {
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'INSUMO'), array('distinct(C.COD_INSUMO)', 'C.DESC_INSUMO'))
                 ->order(array('C.DESC_INSUMO'));
        $result = $db->fetchAll($select);
        $htmlResult = '<option value="-1">---Seleccione---</option>';
        foreach ($result as $arr) {

                         $htmlResult .= '<option value="'.$arr["COD_INSUMO"].'">' .trim(utf8_encode($arr["DESC_INSUMO"])).'</option>';	
        }
        echo  $htmlResult;
     }	         
    public function formapagodataAction() {
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'FORMA_PAGO'), array('distinct(C.COD_FORMA_PAGO)', 'C.DES_FORMA_PAGO'))
                 ->order(array('C.DES_FORMA_PAGO'));
        $result = $db->fetchAll($select);
        $htmlResult = '<option value="-1">---Seleccione---</option>';
        foreach ($result as $arr) {

                         $htmlResult .= '<option value="'.$arr["COD_FORMA_PAGO"].'">' .trim(utf8_encode($arr["DES_FORMA_PAGO"])).'</option>';	
        }
        echo  $htmlResult;
     }	     
    public function unidadmedidadataAction() {
        //$json_rowData = $this->getRequest ()->getParam ( "dataUnidadMedida" );
        //$rowData = json_decode($json_rowData);
        $idInsumo = $this->getRequest ()->getParam ( "dataUnidadMedida" );
        $where =array();        
        array_push($where, " C.COD_INSUMO = ".$idInsumo);
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'INSUMO'), array('A.DESC_UNIDAD_MEDIDA'))
                 ->join(array( 'A' =>  'UNIDAD_MEDIDA' ), 'C.COD_UNIDAD_MEDIDA  = A.COD_UNIDAD_MEDIDA')
                 ->where(" C.COD_INSUMO = ".$idInsumo);
//echo $select;die();        
        $result = $db->fetchAll($select);
        foreach ($result as $arr) {
                         $htmlResult = trim(utf8_encode($arr["DESC_UNIDAD_MEDIDA"]));
        }
        echo  $htmlResult;
     }	               
    public function insumoimpuestodataAction() {  
        $idInsumo = $this->getRequest ()->getParam ( "dataInsumo" );
        $where =array();        
        array_push($where, " C.COD_INSUMO = ".$idInsumo);
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'INSUMO'), array('COALESCE(B.IMP_PORCENTAJE)','B.COD_IMPUESTO'))
                 ->join(array( 'A' =>  'INSUMO_IMPUESTO' ), 'C.COD_INSUMO  = A.COD_INSUMO')
                 ->join(array( 'B' =>  'IMPUESTO' ), 'B.COD_IMPUESTO  = A.COD_IMPUESTO')
                 ->where(" C.COD_INSUMO = ".$idInsumo);
//echo $select;die();        
        $result = $db->fetchAll($select);
        foreach ($result as $arr) {
                         $htmlResult['IMP_PORCENTAJE'] = trim(utf8_encode($arr["IMP_PORCENTAJE"]));
                         $htmlResult['COD_IMPUESTO'] = $arr["COD_IMPUESTO"];
        }
        //echo  $htmlResult;
        echo $this->_helper->json ( $htmlResult);
     }	       
}
