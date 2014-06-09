<?php

class Parametricos_FormaPagoController extends Zend_Controller_Action
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
        $parametrosNamespace->jsGrip = '/js/grillasmodulos/parametricos/gridFormaPago.js';
        $parametrosNamespace->Application_Model_DbTable = "Application_Model_DbTable_FormaPago";
        $parametrosNamespace->busqueda = "DES_FORMA_PAGO";
        $parametrosNamespace->lock ();
    }

    public function listarAction() {
		$this->_helper->viewRenderer->setNoRender ( true );

		$cantidadFilas = $this->getRequest ()->getParam ( "rows" );
		if (! isset ( $cantidadFilas )) {
			$cantidadFilas = 10;
		}
		$parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
		$parametrosNamespace->unlock();
		$parametrosNamespace->cantidadFilas = $cantidadFilas;

		$page = $this->getRequest ()->getParam ( "page" );
		if (! isset ( $page )) {
			$page = 1 ;
		}

		$this->view->headScript ()->appendFile ( $this->view->baseUrl () . '/js/bootstrap.js' );
	 	$this->view->headScript ()->appendFile ( $this->view->baseUrl () . $parametrosNamespace->jsGrip );

	 	$where = $parametrosNamespace->parametrosBusqueda;
		$servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);

		if($where !=null) {
			$result = $servCon->getRowsByWhere($where);
		} else {
			$result = $servCon->getAllRowsOrdered(array($parametrosNamespace->busqueda));
		}
		$parametrosNamespace->lock();
                $pagina = self::obtenerPaginas($result,$cantidadFilas,$page);
		echo $this->_helper->json ( $pagina );
	}

	private function obtenerPaginas($result,$cantidadFilas,$page){
		$this->_paginator = Zend_Paginator::factory ($result);
	 	$this->_paginator->setItemCountPerPage ( $cantidadFilas );
	 	$this->_paginator->setCurrentPageNumber($page);
		$pagina ['rows'] = array ();
		foreach ( $this->_paginator as $item ) {
			$arrayDatos ['cell'] = array($item["COD_FORMA_PAGO"],null,trim(utf8_encode($item["DES_FORMA_PAGO"])),$item["FORMA_PAGO_SIGLA"]);
			$arrayDatos ['columns'] = array("id","modificar","nombre","sigla");
			array_push ( $pagina ['rows'],$arrayDatos);
		}
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

        $servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
        $where =null;

        if($rowData->descripcion != null){
                $where.="UPPER($parametrosNamespace->busqueda) like '".strtoupper(trim($rowData->descripcion))."%'";
        }

        $parametrosNamespace->parametrosBusqueda = $where;
        $parametrosNamespace->lock ();


        $result = $servCon->getRowsByWhere($where);
        $pagina = self::obtenerPaginas($result,$cantidadFilas,$page);
        $jsondata = $this->_helper->json ( $pagina );
        echo $jsondata;
    }

    public function eliminarAction(){
		$this->_helper->viewRenderer->setNoRender ( true );
		$id = $this->getRequest ()->getParam ( "id" );
                $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
		try{
                    $db = Zend_Db_Table::getDefaultAdapter();
                    $db->beginTransaction();
                    $servCon = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
                    $servCon->deleteRowById(array("COD_FORMA_PAGO"=>$id));
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
            $applicationModel = new Application_Model_FormaPago();
            self::almacenardatos($applicationModel,$rowData);
	}

	public function modificarAction(){
            $this->_helper->viewRenderer->setNoRender ( true );
            $json_rowData = $this->getRequest ()->getParam ( "parametros" );
            $rowData = json_decode($json_rowData);
            $rowClass = new Application_Model_FormaPago();
		if($rowData->idRegistro != null){
				$rowClass->setCod_FormaPago($rowData->idRegistro);
			}
            self::almacenardatos($rowClass,$rowData);
	}

        public function almacenardatos($rowClass,$rowData){
     	try{
                $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
     		$service = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
        	$db = Zend_Db_Table::getDefaultAdapter();
        	$db->beginTransaction();
                $rowClass->setDesc_FormaPago(trim(utf8_decode($rowData->descripcionFormaPago)));
                $rowClass->setIso_FormaPago($rowData->isoFormaPago);
                $result = $service->saveRow($rowClass);
	    	$db->commit();
	    	echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
		$db->rollBack();
	}
    }


}

