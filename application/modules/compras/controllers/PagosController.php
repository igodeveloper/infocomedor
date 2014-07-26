<?php

class Compras_PagosController extends Zend_Controller_Action {

    public function init() {
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
                    'PC.PROVEEDOR_NOMBRE',
                    'PP.NRO_FACTURA_COMPRA',
                    'CC.CONTROL_FISCAL',   
                    'PP.DES_BANCO',
                    'PP.NRO_CHEQUE',
                    'PP.MONTO_PAGO',
                    'PP.ESTADO_PAGO',
                    'PP.COD_CAJA',
                    'PP.COD_MOV_CAJA'))
                ->join(array('CC' => 'COMPRA'), 'CC.NRO_FACTURA_COMPRA = PP.NRO_FACTURA_COMPRA')
                ->join(array('PC' => 'PROVEEDOR'), 'CC.COD_PROVEEDOR = PC.COD_PROVEEDOR')
                 ->order(array('PP.NRO_FACTURA_COMPRA DESC'));

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
            if ($Obj->PROVEEDOR_NOMBRE != null) {
                $select->where("PC.PROVEEDOR_NOMBRE like '%".$Obj->PROVEEDOR_NOMBRE."%' ");
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
                $item['PROVEEDOR_NOMBRE'],
                $item['NRO_FACTURA_COMPRA'],
                $item['CONTROL_FISCAL'],
                
                $descripcion_banco,
                $numero_cheque,
                $item['MONTO_PAGO'],
                
                $estado_pago,
                $item['COD_CAJA'],
                $item['COD_MOV_CAJA']
                
            );
            $arrayDatos ['columns'] = array(
                "COD_PAGO_PROVEEDOR",
                "PROVEEDOR_NOMBRE",
                "NRO_FACTURA_COMPRA",
                "CONTROL_FISCAL",
                "DES_BANCO",
                "NRO_CHEQUE",
                "MONTO_PAGO",
                "ESTADO_PAGO",
                'COD_CAJA',
                'COD_MOV_CAJA'
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
        $data_Pagos = json_decode($this->getRequest()->getParam("parametrosPagos"));
        $caja_abierta=self::verificacaja($data_Pagos->COD_CAJA);
        
        try {

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            if($caja_abierta){
                if($data_Pagos->COD_MOV_CAJA == 0){
                     $delete_mov = $db->delete('MOV_CAJA', array(
                            'COD_TIPO_MOV = ?' =>1,
                            'TIPO_FACTURA_MOV = ?' =>'C',
                            'FACTURA_MOV = ?' => $data_Pagos->NRO_FACTURA_COMPRA

                        ));
                    $data_pago_proveedor = array(
                    
                        'ESTADO_PAGO'=>'A',
                 
                     );
                    $where ="COD_PAGO_PROVEEDOR = ".$data_Pagos->COD_PAGO_PROVEEDOR;
                                   
            
                     $update_pago = $db->update('PAGO_PROVEEDOR',$data_pago_proveedor,$where); 

                }else {
                    $egreso = array('FACTURA_MOV' => 0,
                                'TIPO_FACTURA_MOV' => ""  
                                );
                    $where = "COD_MOV_CAJA = " . $data_Pagos->COD_MOV_CAJA;
                    $upd = $db->update('MOV_CAJA', $egreso, $where);
                    $cod_vuelto = self::buscaVuelto($data_Pagos->NRO_FACTURA_COMPRA);
                    // echo $cod_vuelto."vuelto";
                    if($cod_vuelto != null){
                        $delete_vuelto = $db->delete('MOV_CAJA', array(
                            'COD_MOV_CAJA = ?' => $cod_vuelto,
                            'COD_TIPO_MOV = ?' =>3,
                            'TIPO_FACTURA_MOV = ?' =>'C'
                        ));
                    }
                    $data_pago_proveedor = array(
                    
                        'ESTADO_PAGO'=>'A',
                 
                     );
                    $where = "COD_PAGO_PROVEEDOR = " . $data_Pagos->COD_PAGO_PROVEEDOR;
                    // $where = "NRO_FACTURA_COMPRA = " . $data_Pagos->NRO_FACTURA_COMPRA;
            
                    $result_pagos = $db->update('PAGO_PROVEEDOR',$data_pago_proveedor,$where); 
                } 
                $db->commit();
                 echo json_encode(array("result" => "EXITO"));
            }else{
                echo json_encode(array("result" => "CERRADA"));
            }
            
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "errotname" => $e->getMessage()));
            $db->rollBack();
        }
    }

    public function verificacaja($cod_caja){

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'CAJA'), array('COUNT(C.COD_CAJA)'))
                 ->where("C.COD_CAJA = ?", $cod_caja)
                 ->where("C.FECHA_HORA_CIERRE IS NULL");
        // print_r($select);
        $result = $db->fetchAll($select);
        
        if($result[0]['COUNT(C.COD_CAJA)'] > 0){
           return true;
        }else {
           return false;
        }
        

    }

    public function buscaVuelto($nro_factura){

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'MOV_CAJA'), array('C.COD_MOV_CAJA'))
                 ->where("C.FACTURA_MOV = ?", $nro_factura)
                 ->where("C.TIPO_FACTURA_MOV = ?", 'C')
                 ->where("C.COD_TIPO_MOV = ?", 3);
                
        $result = $db->fetchAll($select);
        // print_r($result);
        if(count($result)>0)
            return $result[0]['COD_MOV_CAJA'];
        else
            return null;
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