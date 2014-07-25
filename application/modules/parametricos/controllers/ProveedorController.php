<?php

class Parametricos_ProveedorController extends Zend_Controller_Action {

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
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $parametrosNamespace->parametrosBusqueda = null;
        $parametrosNamespace->cantidadFilas = null;
        $parametrosNamespace->jsGrip = '/js/grillasmodulos/parametricos/gridProveedor.js';
        $parametrosNamespace->Application_Model_DbTable = "Application_Model_DbTable_Proveedor";
        $parametrosNamespace->busqueda = "PROVEEDOR_NOMBRE";
        $parametrosNamespace->lock();
    }

    public function listarAction() {
        $this->_helper->viewRenderer->setNoRender(true);

        $cantidadFilas = $this->getRequest()->getParam("rows");
        if (!isset($cantidadFilas)) {
            $cantidadFilas = 10;
        }
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $parametrosNamespace->cantidadFilas = $cantidadFilas;

        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }

        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . $parametrosNamespace->jsGrip);

        $where = $parametrosNamespace->parametrosBusqueda;
        $servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);

        if ($where != null) {
            $result = $servCon->getRowsByWhere($where);
        } else {
            $result = $servCon->getAllRowsOrdered(array($parametrosNamespace->busqueda));
        }
        $parametrosNamespace->lock();
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
                $item["COD_PROVEEDOR"],
                null,
                trim(utf8_encode($item["PROVEEDOR_NOMBRE"])),
                trim(utf8_encode($item["PROVEEDOR_RUC"])),
                trim(utf8_encode($item["PROVEEDOR_DIRECCION"])),
                trim(utf8_encode($item["PROVEEDOR_TELEFONO"])),
                trim(utf8_encode($item["PROVEEDOR_CONTACTO"])),
                trim(utf8_encode($item["PROVEEDOR_EMAIL"])),
                trim(utf8_encode($item["PROVEEDOR_LIMITE_CREDITO"]))
            );

            $arrayDatos ['columns'] = array(
                "id",
                "modificar",
                "descripcion",
                "ruc",
                "direccion",
                "telefono",
                "nombrecontacto",
                "email",
                "limitecredito"
            );

            array_push($pagina ['rows'], $arrayDatos);
        }
        $pagina ['records'] = count($result);
        $pagina ['page'] = $page;
        $pagina ['total'] = ceil($pagina ['records'] / $cantidadFilas);

        if ($pagina['records'] == 0) {
            $pagina ['mensajeSinFilas'] = true;
        }

        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $parametrosNamespace->listadoImpuestos = $pagina ['rows'];
        $parametrosNamespace->lock();

        return $pagina;
    }

    public function buscarAction() {
        $this->_helper->viewRenderer->setNoRender(true);

        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();

        $cantidadFilas = $this->getRequest()->getParam("rows");
        if (!isset($cantidadFilas)) {
            $cantidadFilas = $parametrosNamespace->cantidadFilas;
        }
        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }

        $json_rowData = $this->getRequest()->getParam("data");
        $rowData = json_decode($json_rowData);

        $servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
        $where = null;

        if ($rowData->descripcion != null) {
            $where.="UPPER($parametrosNamespace->busqueda) like '" . strtoupper(trim($rowData->descripcion)) . "%'";
        }

        $parametrosNamespace->parametrosBusqueda = $where;
        $parametrosNamespace->lock();


        $result = $servCon->getRowsByWhere($where);
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        $jsondata = $this->_helper->json($pagina);
        echo $jsondata;
    }

    public function eliminarAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $id = $this->getRequest()->getParam("id");
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
            $servCon->deleteRowById(array("COD_PROVEEDOR" => $id));
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "mensaje" => $e->getCode()));
            $db->rollBack();
        }
    }

    public function guardarAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest()->getParam("parametros");
        $rowData = json_decode($json_rowData);
        $applicationModel = new Application_Model_Proveedor();
        self::almacenardatos($applicationModel, $rowData);
    }

    public function modificarAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest()->getParam("parametros");
        $rowData = json_decode($json_rowData);
        try {

            $db = Zend_Db_Table::getDefaultAdapter();
             $db->beginTransaction();
            $data = array(
                'PROVEEDOR_NOMBRE' => $rowData->descripcionProveedor,
                'PROVEEDOR_RUC' => $rowData->rucProveedor,
                'PROVEEDOR_DIRECCION' => $rowData->direccionProveedor,
                'PROVEEDOR_TELEFONO' => $rowData->telefonoProveedor,
                'PROVEEDOR_CONTACTO' => $rowData->nombrecontactoProveedor,
                'PROVEEDOR_EMAIL' => $rowData->emailProveedor,
                'PROVEEDOR_LIMITE_CREDITO' => (int)0
            );
            $where = "COD_PROVEEDOR= " . $rowData->idRegistro;

            $upd = $db->update('PROVEEDOR', $data, $where);
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode()));
            $db->rollBack();
        }
    }

    public function almacenardatos($rowClass, $rowData) {
        try {
            $parametrosNamespace = new Zend_Session_Namespace('parametros');
            $service = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $rowClass->setCod_Proveedor((int) (trim($rowData->idRegistro)));
            $rowClass->setDesc_Proveedor(trim(utf8_decode($rowData->descripcionProveedor)));
            $rowClass->setRuc_Proveedor(trim(utf8_decode($rowData->rucProveedor)));
            $rowClass->setDir_Proveedor($rowData->direccionProveedor);
            $rowClass->setTel_Proveedor($rowData->telefonoProveedor);
            $rowClass->setCont_nom_Proveedor(trim(utf8_decode($rowData->nombrecontactoProveedor)));
            $rowClass->setEmail_Proveedor(trim(utf8_decode($rowData->emailProveedor)));
            $rowClass->setLimite_credito_Proveedor(0);

//                print_r($rowClass); die();
            $result = $service->saveRow($rowClass);
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(), "line" => $e->getLine()
                , "mensaje" => $e->getMessage(), "file" => $e->getTraceAsString()));
            $db->rollBack();
        }
    }

}

