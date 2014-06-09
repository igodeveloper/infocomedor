<?php

class Caja_TipomovimientoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
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
                ->from(array('C' => 'tipo_movimiento'), 
                       array('C.cod_tipo_mov',
                             'C.desc_tipo_mov',
							 'C.tipo_mov'));
        $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridTipomovimiento.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
	}

	private function obtenerPaginas($result,$cantidadFilas,$page){
		$this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
            if($item['tipo_mov']== 'R')
                $desc_tipo_operacion = 'Resta';
            else
                $desc_tipo_operacion = 'Suma';
            $arrayDatos ['cell'] = array(
                null,
                $item['cod_tipo_mov'],
                $item['desc_tipo_mov'],
                $desc_tipo_operacion,
                $item['tipo_mov']
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                "cod_tipo_mov",
                "desc_tipo_mov",
                "desc_tipo_operacion",
                "tipo_mov"
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
                ->from(array('C' => 'tipo_movimiento'), 
                       array('C.cod_tipo_mov',
                             'C.desc_tipo_mov',
                             'C.tipo_mov'));
        if ($Obj != null) {
            if ($Obj->descripcion != null) {
                $select->where("upper(C.desc_tipo_mov) like upper('%".$Obj->descripcion."%')");
            }
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridTipomovimiento.js');
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
                    $servCon = new Application_Model_DataService('Application_Model_DbTable_Tipomovimiento');
                    $servCon->deleteRowById(array("cod_tipo_mov"=>$id));
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
            $applicationModel = new Application_Model_Tipomovimiento();
            self::almacenardatos($applicationModel,$rowData);
	}

	public function modificarAction(){
            $this->_helper->viewRenderer->setNoRender ( true );
            $json_rowData = $this->getRequest ()->getParam ( "parametros" );
            $rowData = json_decode($json_rowData);
            $rowClass = new Application_Model_TipoMovimiento();
            if($rowData->cod_tipo_mov != null){
		$rowClass->setCod_tipo_mov($rowData->cod_tipo_mov);
            }
            self::almacenardatos($rowClass,$rowData);
	}

        public function almacenardatos($rowClass,$rowData){
     	try{
     		$service = new Application_Model_DataService('Application_Model_DbTable_Tipomovimiento');
        	$db = Zend_Db_Table::getDefaultAdapter();
        	$db->beginTransaction();
                $rowClass->setDesc_tipo_mov(trim(utf8_decode($rowData->desc_tipo_mov)));
		$rowClass->setTipo_mov(trim(utf8_decode($rowData->tipo_mov)));
                $result = $service->saveRow($rowClass);
	    	$db->commit();
	    	echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
		$db->rollBack();
	}
    }


}

