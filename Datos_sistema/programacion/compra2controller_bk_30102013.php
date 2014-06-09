<?php

class Compras_Compra2Controller extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $parametrosNamespace->cantidadFilas = null;
        $parametrosNamespace->Application_Model_DbTable = "Application_Model_DbTable_Compra";
        $parametrosNamespace->lock();
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
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
                ->from(array('C' => 'COMPRA'), array('C.COD_PROVEEDOR',
                    'P.PROVEEDOR_NOMBRE',
                    'C.NRO_FACTURA_COMPRA',
                    'C.FECHA_EMISION_FACTURA',
                    'C.FECHA_VENCIMIENTO_FACTURA',
                    'C.MONTO_TOTAL_COMPRA',
                    'C.COD_MONEDA_COMPRA',
                    'M.DESC_MONEDA',
                    'C.COD_FORMA_PAGO',
                    'F.DES_FORMA_PAGO',
                    'C.CONTROL_FISCAL'))
                ->join(array('P' => 'PROVEEDOR'), 'C.COD_PROVEEDOR = P.COD_PROVEEDOR')
                ->join(array('M' => 'MONEDA'), 'C.COD_MONEDA_COMPRA = M.COD_MONEDA')
                ->join(array('F' => 'FORMA_PAGO'), 'C.COD_FORMA_PAGO = F.COD_FORMA_PAGO');

//        die($select);
        $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridCompra.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
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
                ->from(array('C' => 'COMPRA'), array('C.COD_PROVEEDOR',
                    'P.PROVEEDOR_NOMBRE',
                    'C.NRO_FACTURA_COMPRA',
                    'C.FECHA_EMISION_FACTURA',
                    'C.FECHA_VENCIMIENTO_FACTURA',
                    'C.MONTO_TOTAL_COMPRA',
                    'C.COD_MONEDA_COMPRA',
                    'M.DESC_MONEDA',
                    'C.COD_FORMA_PAGO',
                    'F.DES_FORMA_PAGO',
                    'C.CONTROL_FISCAL'))
                ->join(array('P' => 'PROVEEDOR'), 'C.COD_PROVEEDOR = P.COD_PROVEEDOR')
                ->join(array('M' => 'MONEDA'), 'C.COD_MONEDA_COMPRA = M.COD_MONEDA')
                ->join(array('F' => 'FORMA_PAGO'), 'C.COD_FORMA_PAGO = F.COD_FORMA_PAGO');

        if ($Obj != null) {
            //print_r($Obj);
            //die();

            if ($Obj->codproveedor != null) {
//                            die($Obj->codproveedor);
                $select->where("C.COD_PROVEEDOR = ?", $Obj->codproveedor);
            }
            if ($Obj->nameproveedor != null) {
                $select->where("P.PROVEEDOR_NOMBRE = ?", $Obj->nameproveedor);
            }
            if ($Obj->codigointerno != null) {
                $select->where("C.NRO_FACTURA_COMPRA = ?", $Obj->codigointerno);
            }
            if ($Obj->controlfiscal != null) {
                $select->where("C.CONTROL_FISCAL = ?", $Obj->controlfiscal);
            }
            if ($Obj->fechaemision != null) {
                $select->where("C.FECHA_EMISION_FACTURA >= ?", $Obj->fechaemision);
            }
            if ($Obj->fechavencimiento != null) {
                $select->where("C.FECHA_VENCIMIENTO_FACTURA >= ?", $Obj->fechavencimiento);
            }
            if ($Obj->formapago != - 1) {
                $select->where("C.COD_FORMA_PAGO = ?", $Obj->formapago);
            }
//                    print_r($select);
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

//        die($select);
//       $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridCompra.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }

    private function obtenerPaginas($result, $cantidadFilas, $page) {
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {

            $arrayDatos ['cell'] = array(
                null,
                null,
                $item['COD_PROVEEDOR'],
                $item['PROVEEDOR_NOMBRE'],
                $item['NRO_FACTURA_COMPRA'],
                $item['CONTROL_FISCAL'],
                $item['FECHA_EMISION_FACTURA'],
                $item['FECHA_VENCIMIENTO_FACTURA'],
                $item['MONTO_TOTAL_COMPRA'],
                $item['COD_MONEDA_COMPRA'],
                $item['DESC_MONEDA'],
                $item['COD_FORMA_PAGO'],
                $item['DES_FORMA_PAGO'],
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                "pago",
                "COD_PROVEEDOR",
                "PROVEEDOR_NOMBRE",
                "NRO_FACTURA_COMPRA",
                "CONTROL_FISCAL",
                "FECHA_EMISION_FACTURA",
                "FECHA_VENCIMIENTO_FACTURA",
                "MONTO_TOTAL_COMPRA",
                "COD_MONEDA_COMPRA",
                "DESC_MONEDA",
                "COD_FORMA_PAGO",
                "DES_FORMA_PAGO"
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

    public function proveedordataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PROVEEDOR'))
                ->order(array('P.PROVEEDOR_NOMBRE'));
        $result = $db->fetchAll($select);

        echo json_encode($result);
    }

    public function validaproveedordataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $datos = $this->getRequest()->getParam("parametro");
        $getvalue = $datos["value"];
        $getreference = $datos["reference"];
        switch ($getreference) {
            case 'cod':
                $where = "P.COD_PROVEEDOR=" . $getvalue;
                break;
            case 'ruc':
                $where = "P.PROVEEDOR_RUC=" . $getvalue;
                break;
            case 'name':
                $where = "P.PROVEEDOR_NOMBRE= '" . $getvalue . "'";
                break;
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PROVEEDOR'), array('P.COD_PROVEEDOR', 'P.PROVEEDOR_NOMBRE', 'P.PROVEEDOR_RUC'))
                ->where($where);
        $result = $db->fetchAll($select);
//        print_r($result);
        $option = array();
        foreach ($result as $value) {
            $razonsocial = utf8_encode(trim($value ['PROVEEDOR_NOMBRE']));
            $codproverdor = $value ['COD_PROVEEDOR'];
            $rucproveedor = $value ['PROVEEDOR_RUC'];
            $option = array("cod" => $codproverdor, "name" => $razonsocial, "ruc" => $rucproveedor);
        }
//        echo $option;
        echo json_encode($option);
    }

    public function productodataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PRODUCTO'))
                ->order(array('P.PRODUCTO_DESC'));
//        print_r($select);die();
        $result = $db->fetchAll($select);

        echo json_encode($result);
    }

    public function maxnrofacturaAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C' => 'COMPRA'), array(new Zend_Db_Expr('(max(C.NRO_FACTURA_COMPRA)+1) as maxId')));
        $result = $db->fetchAll($select);
        echo json_encode($result);
    }

    public function productvalidationdataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $datos = $this->getRequest()->getParam("parametro");
        $getvalue = trim($datos["value"]);
        $getreference = $datos["reference"];
        switch ($getreference) {
            case 'cod':
                $where = "P.COD_PRODUCTO=" . $getvalue;
                break;
            case 'descripcion':
                $where = "P.PRODUCTO_DESC= '" . $getvalue . "'";
                break;
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PRODUCTO'), array('P.COD_PRODUCTO', 'P.PRODUCTO_DESC', 'P.COD_UNIDAD_MEDIDA', 'U.ISO_UNIDAD_MEDIDA'))
                ->distinct(true)
                ->join(array('U' => 'UNIDAD_MEDIDA'), 'P.COD_UNIDAD_MEDIDA = U.COD_UNIDAD_MEDIDA', array())
                ->where($where);
        $result = $db->fetchAll($select);

        foreach ($result as $value) {
            $descripcionProducto = utf8_encode(trim($value ['PRODUCTO_DESC']));
            $codProducto = $value ['COD_PRODUCTO'];
            $uniMedidaCod = $value ['COD_UNIDAD_MEDIDA'];
            $uniMedidaDesc = utf8_encode(trim($value ['ISO_UNIDAD_MEDIDA']));
            $option = array("cod" => $codProducto, "descripcion" => $descripcionProducto, "unimedcod" => $uniMedidaCod, "unimeddesc" => $uniMedidaDesc);
        }
        echo json_encode($option);
    }

    public function guardarAction() {

//		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $dataCompra = $this->getRequest()->getParam("dataCompra");
        $dataCompraDetalle = $this->getRequest()->getParam("dataCompraDetalle");
        $dataCompraCantItems = $this->getRequest()->getParam("dataCompraCantItems");

        $compraModel = new Application_Model_Compra();
        self::almacenarDatos($compraModel, $dataCompra, $dataCompraDetalle, $dataCompraCantItems);
//        self::almacenarDatos($compraModel, $dataCompra, $dataCompraDetalle);
    }

    public function almacenarDatos($compraModel, $dataCompra, $dataCompraDetalle, $dataCompraCantItems) {
//    public function almacenarDatos($compraModel, $dataCompra, $dataCompraDetalle) {
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            $compraDetModel = new Application_Model_CompraDetalle();
            $compraImpModel = new Application_Model_CompraImpuesto();

            $servCompra = new Application_Model_DataService("Application_Model_DbTable_Compra");
            $servCompraDetalle = new Application_Model_DataService("Application_Model_DbTable_CompraDetalle");
            $servCompraImpuesto = new Application_Model_DataService("Application_Model_DbTable_CompraImpuesto");

            $compraModel->setCod_Proveedor($dataCompra["codproveedor"]);
            $compraModel->setNro_Factura_Compra(0);
            $compraModel->setFecha_Emision_Factura($dataCompra["fechaEmision"]);
            $compraModel->setFecha_Vencimiento_Factura($dataCompra["fechaVencimiento"]);
            $compraModel->setMonto_Total_Compra($dataCompra["montoTotalCompra"]);
            $compraModel->setCod_Moneda_Compra($dataCompra["codigoMoneda"]);
            $compraModel->setCod_Usuario($dataCompra["codigoUsuario"]);
            $compraModel->setCod_Forma_Pago($dataCompra["formaPago"]);
            $compraModel->setControl_Fiscal($dataCompra["controlFiscal"]);
//                    print_r($compraModel);
//                    die();
            $codFacturaCompra = $servCompra->saveRow($compraModel);
            $codFactura = $db->lastInsertId();
//                    print_r($codFactura);
//                    die();
            
            if ($codFactura != null) {
                 
                $i = 0;
                foreach ($dataCompraDetalle as $fila) {
                    
                    $i++;
                    $compraDetModel->setNro_Factura_Compra($codFactura);
                    $compraDetModel->setDet_Item_Compra($i);
                    $compraDetModel->setCod_Producto($fila["codproducto"]);
                    $compraDetModel->setCantidad_Compra(((int) $fila["cantidad"]));
                    $compraDetModel->setMonto_Compra(((int) $fila["totalparcial"]));
                    $compraDetModel->setCod_unidad_medida(($fila["codUnidadMedida"]));
//                            print_r($compraDetModel);
//                            die();
                    $codCompraDetalle = $servCompraDetalle->saveRow($compraDetModel);
                    
                    $compraImpModel->setNro_Factura_Compra($codFactura);
                    $compraImpModel->setDet_Item_Impuesto($i);
                    $compraImpModel->setCod_Impuesto($fila["codimpuesto"]);
                    if ($fila["iva5"] > 0)
                        $compraImpModel->setMonto_Impuesto((int) ($fila["iva5"]));
                    if ($fila["iva10"] > 0)
                        $compraImpModel->setMonto_Impuesto((int) ($fila["iva10"]));
                    if ($fila["codimpuesto"] == 1)
                        $compraImpModel->setMonto_Impuesto(0);
                    $codCompraImpuesto = $servCompraImpuesto->saveRow($compraImpModel);
//                    print_r($codCompraImpuesto);
//                    die();
                }
                $db->commit();
            }
            
            echo json_encode(array("result" => "EXITO", "codInsertado" => $codFactura));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "mensaje" => $e->getCode(), "errotname" => $e->getMessage(),
                "otro" => $e->getFile(), "linea" => $e->getLine()));
            $db->rollBack();
        }
    }

    public function modaleditarAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $NumeroInterno = $this->getRequest()->getParam("NumeroInterno");
        $getNumeroInterno = trim($NumeroInterno["NumeroInterno"]);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('CD' => 'COMPRA_DETALLE'), array('CD.COD_PRODUCTO_ITEM',
                    'P.PRODUCTO_DESC',
                    'CD.CANTIDAD_COMPRA',
                    'CD.COD_UNIDAD_MEDIDA',
                    'U.ISO_UNIDAD_MEDIDA',
                    'CD.MONTO_COMPRA',
                    'I.COD_IMPUESTO',
                    'I.MONTO_IMPUESTO'))
                ->join(array('U' => 'UNIDAD_MEDIDA'), 'CD.COD_UNIDAD_MEDIDA = U.COD_UNIDAD_MEDIDA')
                ->join(array('P' => 'PRODUCTO'), 'CD.COD_PRODUCTO_ITEM = P.COD_PRODUCTO')
                ->join(array('I' => 'COMPRA_IMPUESTO'), 'I.NRO_FACTURA_COMPRA = CD.NRO_FACTURA_COMPRA AND I.DET_ITEM_IMPUESTO = CD.DET_ITEM_COMPRA')
                ->where("CD.NRO_FACTURA_COMPRA = ?", $getNumeroInterno);
//        print_r($select);
//        die();
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {

            $codproducto = $value ['COD_PRODUCTO'];
            $descripcionproducto = $value ['PRODUCTO_DESC'];
            $cantidad = $value ['CANTIDAD_COMPRA'];
            $codUnidadMedida = $value['COD_UNIDAD_MEDIDA'];
            $unidadmedida = $value['ISO_UNIDAD_MEDIDA'];
            $preciounitario = ($value['MONTO_COMPRA'] / $cantidad);
            $totalparcial = $value['MONTO_COMPRA'];
            $codimpuesto = $value['COD_IMPUESTO'];
            $iva5 = ($codimpuesto == 5) ? $value['MONTO_IMPUESTO'] : 0;
            $iva10 = ($codimpuesto == 10) ? $value['MONTO_IMPUESTO'] : 0;
            if ($codimpuesto == 1) {
                $iva5 = 0;
                $iva10 = 0;
            }
            $option1 = array("codproducto" => $codproducto, "descripcionproducto" => $descripcionproducto, "cantidad" => $cantidad,
                "codUnidadMedida" => $codUnidadMedida, "unidadmedida" => $unidadmedida, "preciounitario" => $preciounitario,
                "totalparcial" => $totalparcial, "codimpuesto" => $codimpuesto, "iva5" => $iva5, "iva10" => $iva10);
            array_push($option, $option1);
        }

        echo json_encode($option);
    }

    public function calculasaldoAction() {
//                $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $nroFactura = $this->getRequest()->getParam("nroFactura");
        $where = "P.ESTADO_PAGO <> 'A'";
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PAGO_PROVEEDOR'), array('SUM(P.MONTO_PAGO) AS PAGOS'));
        $select->where("P.NRO_FACTURA_COMPRA = ?", $nroFactura);
        $select->where($where);
//         print_r($select);DIE();
        $result = $db->fetchAll($select);
//        print_r($result);die();

        echo json_encode($result[0]['PAGOS']);
    }

    public function modalpagosAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $NumeroInterno = $this->getRequest()->getParam("NumeroInterno");
//        die($NumeroInterno);
        
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('PP' => 'PAGO_PROVEEDOR'), 
                       array('PP.COD_PAGO_PROVEEDOR',
                             'PP.MONTO_PAGO',
                             'PP.DES_BANCO',
                             'PP.NRO_CHEQUE',
                             'PP.ESTADO_PAGO'))
                ->where("PP.NRO_FACTURA_COMPRA =".$NumeroInterno);
//        print_r($select);
//        die();
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
            $estado_pago = ($value['ESTADO_PAGO'] === 'A')?'ANULADO':'ACTIVO';
            $descripcion_banco = ($value['DES_BANCO'] === '0')?'-':$value ['DES_BANCO'];
            $numero_cheque = ($value['NRO_CHEQUE'] === '0')?'-':$value ['NRO_CHEQUE'];
            $option1 = array(
                "COD_PAGO_PROVEEDOR" => $value ['COD_PAGO_PROVEEDOR'], 
                "MONTO_PAGO" => $value ['MONTO_PAGO'], 
                "DES_BANCO" =>  $descripcion_banco, 
                "NRO_CHEQUE" => $numero_cheque,
                "ESTADO_PAGO" => $estado_pago
                );
            array_push($option, $option1);
        }

        echo json_encode($option);
    }

}