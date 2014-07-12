<?php

class Compras_PagosController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
         $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
            if(!$parametrosNamespace->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/menus/menu')->redirectAndExit();
            }
        $parametrosNamespace->lock();
      
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
    }

    public function buscarAction() {

        $this->_helper->viewRenderer->setNoRender(true);
        $datos = $this->getRequest()->getParam("dataJsonBusqueda");
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
                ->from(array('PP' => 'PAGO_PROVEEDOR'), array(
                    'PP.COD_PAGO_PROVEEDOR',
                    'PP.NRO_FACTURA_COMPRA',
                    'PP.DES_BANCO',
                    'PP.NRO_CHEQUE',
                    'PP.MONTO_PAGO',
                    'PP.ESTADO_PAGO')
                );

        if ($Obj != null) {
            //print_r($Obj);
            //die();

            if ($Obj->NRO_FACTURA_COMPRA != null) {
//                            die($Obj->codproveedor);
                $select->where("PP.NRO_FACTURA_COMPRA = ?", $Obj->NRO_FACTURA_COMPRA);
            }
            if ($Obj->NRO_CHEQUE != null) {
                $select->where("PP.NRO_CHEQUE = ?", $Obj->NRO_CHEQUE);
            }
            if ($Obj->ESTADO_PAGO != -1) {
                $select->where("PP.ESTADO_PAGO = ?", $Obj->ESTADO_PAGO);
            }
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

//        die($select);
//       $result = $db->fetchAll($select);
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }

    private function obtenerPaginas($result, $cantidadFilas, $page) {
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
            $estado_pago = ($item['ESTADO_PAGO'] == 'A') ? 'ANULADO' : 'ACTIVO';
            $descripcion_banco = ($item['DES_BANCO'] == '0') ? '-' : $item ['DES_BANCO'];
            $numero_cheque = ($item['NRO_CHEQUE'] == '0') ? '-' : $item  ['NRO_CHEQUE'];
            $arrayDatos ['cell'] = array(
                $item['COD_PAGO_PROVEEDOR'],
                $item['NRO_FACTURA_COMPRA'],
                
                $descripcion_banco,
                $numero_cheque,
                $item['MONTO_PAGO'],
                $estado_pago
                
            );
            $arrayDatos ['columns'] = array(
                "COD_PAGO_PROVEEDOR",
                "NRO_FACTURA_COMPRA",
                "DES_BANCO",
                "NRO_CHEQUE",
                "MONTO_PAGO",
                "ESTADO_PAGO"
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

        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $parametrosNamespace->listadoCompras = $pagina ['rows'];
        $parametrosNamespace->lock();

        return $pagina;
    }

  
    public function anulacionpagoAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros_pagos = json_decode($this->getRequest()->getParam("parametrosPagos"));
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $serv_pagos = new Application_Model_DataService("Application_Model_DbTable_Pagoproveedor");
            $pago_Model = new Application_Model_Pagoproveedor();
            $pago_Model->setCod_pago_proveedor((int) $parametros_pagos->COD_PAGO_PROVEEDOR);
            $pago_Model->setNro_factura_compra((int) $parametros_pagos->NRO_FACTURA_COMPRA);
            $pago_Model->setMonto_pago((int) $parametros_pagos->MONTO_PAGO);
            $pago_Model->setCod_moneda_pago((int) $parametros_pagos->COD_MONEDA_COMPRA);
            $pago_Model->setNro_cheque((int) $parametros_pagos->NRO_CHEQUE);
            $pago_Model->setDes_banco($parametros_pagos->DES_BANCO);
            $pago_Model->setEstado_pago("A");

            $result_pagos = $serv_pagos->saveRow($pago_Model);

            $db->commit();


            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "errotname" => $e->getMessage()));
            $db->rollBack();
        }
    }
    
    
    public function verifica_pagos($nro_factura) {
     	 $db = Zend_Db_Table::getDefaultAdapter();
    	 $select = $db->select()
                ->from(array('P' => 'PAGO_PROVEEDOR'), 
                       array('COUNT(P.NRO_FACTURA_COMPRA)'))
                ->where("P.NRO_FACTURA_COMPRA = ?", $nro_factura)
                ->where("P.ESTADO_PAGO = ?", 'T');  
        $result = $db->fetchAll($select);
 		return $result[0]['COUNT(P.NRO_FACTURA_COMPRA)'];
    }

    public function cargartipoegresoAction()
    {
//      $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('R' => 'TIPO_MOVIMIENTO'), array('R.COD_TIPO_MOV', 'R.DESC_TIPO_MOV'))
                ->distinct(true)
                ->where("R.TIPO_MOV = ?", 'R');
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="0">Seleccione</option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["COD_TIPO_MOV"] . '">' .
                trim(utf8_encode($arr["DESC_TIPO_MOV"])) . '</option>';
            }
        } catch (Exception $e) {
            $htmlResultado = "error";
        }
        echo $htmlResultado;
    }

    public function cargagrillaegresoAction()
    {
       $this->_helper->viewRenderer->setNoRender(true);

        $cod = $this->getRequest()->getParam("data");
//        die($NumeroInterno);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('M' => 'MOV_CAJA'), 
                    array(
                        
                        'M.COD_MOV_CAJA',
                        'M.FECHA_HORA_MOV',
                        'M.MONTO_MOV',
                        'T.DESC_TIPO_MOV'
                        ))
                ->join(array('T' => 'TIPO_MOVIMIENTO'), 'M.COD_TIPO_MOV = T.COD_TIPO_MOV')
                ->where("M.COD_TIPO_MOV = ?",$cod)
                ->where("M.FACTURA_MOV = ?", 0);
//        print_r($select);
//        die();
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
          
            $option1 = array(
                "COD_MOV_CAJA"=> $value ['COD_MOV_CAJA'],
                "FECHA_HORA_MOV" => $value ['FECHA_HORA_MOV'],
                "MONTO_MOV" => $value ['MONTO_MOV'],
                "DESC_TIPO_MOV" => $value ['DESC_TIPO_MOV']
            );
            array_push($option, $option1);
        }

        echo json_encode($option);
    }
}