<?php

class Parametricos_PeriodoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $parametrosNamespace->unlock ();
        $parametrosNamespace->parametrosBusquedaPeriodo = null;
        $parametrosNamespace->cantidadFilasPeriodo = null;
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
		$parametrosNamespace->cantidadFilasPeriodo = $cantidadFilas;
		
		$page = $this->getRequest ()->getParam ( "page" );
		if (! isset ( $page )) {
			$page = 1 ;
		}
		
		$this->view->headScript ()->appendFile ( $this->view->baseUrl () . '/js/bootstrap.js' );
	 	$this->view->headScript ()->appendFile ( $this->view->baseUrl () . '/js/gridPeriodos.js' );
	 	
	 	$where = $parametrosNamespace->parametrosBusquedaPeriodo;
		$parametrosNamespace->lock();
		$servCon = new Application_Model_DataService("Application_Model_DbTable_Periodo");
    	
		if($where !=null) {
			$result = $servCon->getRowsByWhere($where);
		} else {
			$result = $servCon->getAllRowsOrdered(array("DS_PERIODO"));
		} 
		
                $pagina = self::obtenerPaginas($result,$cantidadFilas,$page);
		echo $this->_helper->json ( $pagina );
	}
	
	private function obtenerPaginas($result,$cantidadFilas,$page){
		$this->_paginator = Zend_Paginator::factory ($result);
	 	$this->_paginator->setItemCountPerPage ( $cantidadFilas );
	 	$this->_paginator->setCurrentPageNumber($page);
		$pagina ['rows'] = array ();
		foreach ( $this->_paginator as $item ) {
			$period ['cell'] = array($item["COD_PERIODO"],null,trim(utf8_encode($item["DS_PERIODO"])),$item["DIAS_PERIODO"]);
			$period ['columns'] = array("id","modificar","nombre","dias","cantidaddias");
			array_push ( $pagina ['rows'],$period);
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
			$cantidadFilas = $parametrosNamespace->cantidadFilasPeriodo;
		}
		$page = $this->getRequest ()->getParam ( "page" );
		if (! isset ( $page )) {
			$page = 1 ;
		}
		
		$periodo = $this->getRequest ()->getParam ("data");
        $period = json_decode($periodo);
        
        $servCon = new Application_Model_DataService("Application_Model_DbTable_Periodo");
        $where =null;
        
        if($period->descripcion != null){
        	$where.="UPPER(DS_PERIODO) like '".strtoupper(trim($period->descripcion))."%'";
        }
        
        $parametrosNamespace->parametrosBusquedaPeriodo = $where;
		$parametrosNamespace->lock ();
    
        
        $result = $servCon->getRowsByWhere($where);
        $pagina = self::obtenerPaginas($result,$cantidadFilas,$page);
        $jsondata = $this->_helper->json ( $pagina );
		echo $jsondata;
    }
    
    public function eliminarAction(){
		$this->_helper->viewRenderer->setNoRender ( true );
		$id = $this->getRequest ()->getParam ( "id" );
		try{
			$db = Zend_Db_Table::getDefaultAdapter();
        	$db->beginTransaction();
			$servCon = new Application_Model_DataService("Application_Model_DbTable_Periodo");
			$servCon->deleteRowById(array("COD_PERIODO"=>$id));
		    $db->commit();
		    echo json_encode(array("result" => "EXITO"));
	    }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
			$db->rollBack();
		}
	}
	
	public function guardarAction(){
    	$this->_helper->viewRenderer->setNoRender ( true );
        $per = $this->getRequest ()->getParam ( "parametrosPeriodo" );
        $period = json_decode($per);     
        $periodo = new Application_Model_Periodo();
    	self::almacenardatos($periodo,$period);
	}
	
	public function modificarAction(){
    	$this->_helper->viewRenderer->setNoRender ( true );
        $per = $this->getRequest ()->getParam ( "parametrosPeriodo" );
        $period = json_decode($per);
        $periodo = new Application_Model_Periodo();
		if($period->idperiodo != null){
				$periodo->setCod_periodo($period->idperiodo);
			}
    	self::almacenardatos($periodo,$period);
	}
    
    public function almacenardatos($periodo,$period){
     	try{
     		$servPeriodo = new Application_Model_DataService("Application_Model_DbTable_Periodo");
        	$db = Zend_Db_Table::getDefaultAdapter();     
        	$db->beginTransaction();
			$periodo->setDs_periodo(trim(utf8_decode($period->descripcionperiodo)));
			$periodo->setDias_periodo($period->cantidaddias);	
			$codPeriodo = $servPeriodo->saveRow($periodo);
	    	$db->commit();
	    	echo json_encode(array("result" => "EXITO"));
        }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
			$db->rollBack();
		}
    }
 
    
}

