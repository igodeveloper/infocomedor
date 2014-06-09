<?php

class Parametricos_EmpleadoController extends Zend_Controller_Action
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
        $parametrosNamespace->jsGrip = '/js/grillasmodulos/parametricos/gridEmpleado.js';
        $parametrosNamespace->Application_Model_DbTable = "Application_Model_DbTable_Empleado";
        $parametrosNamespace->busqueda = "EMPLEADO_NOMBRE";
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
			$arrayDatos ['cell'] = array($item["COD_EMPLEADO"],null,trim(utf8_encode($item["EMPLEADO_NOMBRE"])),trim(utf8_encode($item["EMPLEADO_APELLIDO"])),$item["EMPLEADO_NRO_DOC"],trim(utf8_encode($item["EMPLEADO_DIRECCION"])),$item["EMPLEADO_TELEFONO"]);
			$arrayDatos ['columns'] = array("id","modificar","descripcion","apellido","documento","direccion","telefono");
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
                    $servCon->deleteRowById(array("COD_EMPLEADO"=>$id));
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
            $applicationModel = new Application_Model_Empleado();
            self::almacenardatos($applicationModel,$rowData);
	}

	public function modificarAction(){
            $this->_helper->viewRenderer->setNoRender ( true );
            $json_rowData = $this->getRequest ()->getParam ( "parametros" );
            $rowData = json_decode($json_rowData);
            $rowClass = new Application_Model_Empleado();
		if($rowData->idRegistro != null){
				$rowClass->setCod_Empleado($rowData->idRegistro);
			}
            self::almacenardatos($rowClass,$rowData);
	}

        public function almacenardatos($rowClass,$rowData){
     	try{
                $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
     		$service = new Application_Model_DataService($parametrosNamespace->Application_Model_DbTable);
        	$db = Zend_Db_Table::getDefaultAdapter();
        	$db->beginTransaction();
                $rowClass->setNombre_Empleado(trim(utf8_decode($rowData->nombreEmpleado)));
                $rowClass->setApellido_Empleado(trim(utf8_decode($rowData->apellidoEmpleado)));
                $rowClass->setDocumento_Empleado($rowData->documentoEmpleado);
                $rowClass->setDir_Empleado($rowData->direccionEmpleado);
                $rowClass->setTel_Empleado($rowData->telefonoEmpleado);
                
                $result = $service->saveRow($rowClass);
	    	$db->commit();
	    	echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
		$db->rollBack();
	}
    }


}

