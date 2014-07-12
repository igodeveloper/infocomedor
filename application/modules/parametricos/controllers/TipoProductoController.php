<?php

class Parametricos_TipoproductoController extends Zend_Controller_Action
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
                ->from(array('C' => 'TIPO_PRODUCTO'), 
                       array('C.COD_TIPO_PRODUCTO',
                             'C.TIPO_PRODUCTO_DESCRIPCION'));
        $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridTipoproducto.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
	}

	private function obtenerPaginas($result,$cantidadFilas,$page){
		$this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {

            $arrayDatos ['cell'] = array(
                null,
                $item['COD_TIPO_PRODUCTO'],
                $item['TIPO_PRODUCTO_DESCRIPCION']
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                "COD_TIPO_PRODUCTO",
                "TIPO_PRODUCTO_DESCRIPCION"
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
                ->from(array('C' => 'TIPO_PRODUCTO'), 
                       array('C.COD_TIPO_PRODUCTO',
                             'C.TIPO_PRODUCTO_DESCRIPCION'));

        if ($Obj != null) {
            if ($Obj->descripcion != null) {
                $select->where("upper(C.TIPO_PRODUCTO_DESCRIPCION) like upper('%".$Obj->descripcion."%')");
            }
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridTipoproducto.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }

    public function eliminarAction(){
		$this->_helper->viewRenderer->setNoRender ( true );
		$id = $this->getRequest ()->getParam ( "id" );
        if($id == 1){
            echo json_encode(array("result" => "MateriaPrima"));
        } else{
    		try{
                        $db = Zend_Db_Table::getDefaultAdapter();
                        $db->beginTransaction();
                        $servCon = new Application_Model_DataService('Application_Model_DbTable_TipoProducto');
                        $servCon->deleteRowById(array("COD_TIPO_PRODUCTO"=>$id));
                        $db->commit();
    		    echo json_encode(array("result" => "EXITO"));
    	    }catch( Exception $e ) {
    	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
    			$db->rollBack();
    		}
	}

    }

	public function guardarAction(){
            $this->_helper->viewRenderer->setNoRender ( true );
            $json_rowData = $this->getRequest ()->getParam ( "parametros" );
            $rowData = json_decode($json_rowData);
            $applicationModel = new Application_Model_TipoProducto();
            self::almacenardatos($applicationModel,$rowData);
	}

	public function modificarAction(){
            $this->_helper->viewRenderer->setNoRender ( true );
            $json_rowData = $this->getRequest ()->getParam ( "parametros" );
            $rowData = json_decode($json_rowData);
            $rowClass = new Application_Model_TipoProducto();
		if($rowData->COD_TIPO_PRODUCTO != null){
				$rowClass->setCod_tipo_producto($rowData->COD_TIPO_PRODUCTO);
			}
            self::almacenardatos($rowClass,$rowData);
	}

        public function almacenardatos($rowClass,$rowData){
     	try{
     		$service = new Application_Model_DataService('Application_Model_DbTable_TipoProducto');
        	$db = Zend_Db_Table::getDefaultAdapter();
        	$db->beginTransaction();
                $rowClass->setTipo_producto_descripcion(trim(utf8_decode($rowData->TIPO_PRODUCTO_DESCRIPCION)));
                $result = $service->saveRow($rowClass);
	    	$db->commit();
	    	echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
		$db->rollBack();
	}
    }


}

