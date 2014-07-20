<?php

class Caja_MovcajaController extends Zend_Controller_Action
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
		//$parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
		//$cod_usuario = $parametrosNamespace->cod_usuario;
		$cod_usuario = 1;
        $db = Zend_Db_Table::getDefaultAdapter();
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('C' => 'usuario'), 
						   array('C.cod_usuario',
								 'C.nombre_apellido',
								 'D.fecha_hora_apertura',
								 'M.cod_mov_caja',
								 'M.cod_caja',
								 'M.fecha_hora_mov',
								 'M.monto_mov',
								 'M.cod_tipo_mov',
								 'M.factura_mov',
								 'M.tipo_factura_mov',
								 'M.observacion_mov',
								 'T.desc_tipo_mov',
								 'T.tipo_mov'))
					->join(array('D' => 'caja'), 'D.cod_usuario_caja = C.cod_usuario')
					->join(array('M' => 'mov_caja'), 'M.cod_caja = D.cod_caja')
					->join(array('T' => 'tipo_movimiento'), 'T.cod_tipo_mov = M.cod_tipo_mov')
					->where("D.cod_usuario_caja = ".$cod_usuario)
                    ->order('M.cod_mov_caja desc');	
			$result = $db->fetchAll($select);
//die($select);	
        $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridMovCaja.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
	}

	private function obtenerPaginas($result,$cantidadFilas,$page){
		$this->_paginator = Zend_Paginator::factory($result);
                $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
			$desc_tipo_mov = '';
            if($item['tipo_mov']== 'R')
                $desc_tipo_mov = 'Egreso';
            else
                $desc_tipo_mov = 'Ingreso';
			$desc_factura_mov = '';
            if($item['factura_mov']== 'P')
                $desc_factura_mov = 'Proveedor';
            else if($item['factura_mov']== 'C')
                $desc_factura_mov = 'Compra en Local';				
            $arrayDatos ['cell'] = array(		
                null,				
                $item['cod_usuario'],
                $item['nombre_apellido'],
                $item['cod_caja'],
                $item['cod_mov_caja'],
                $item['fecha_hora_mov'],
                $item['monto_mov'],
				$desc_tipo_mov,
                $item['cod_tipo_mov'],
                $item['factura_mov'],
                $desc_factura_mov,
                $item['tipo_factura_mov'],
                $item['observacion_mov'],                
                $item['tipo_mov']
            );
            $arrayDatos ['columns'] = array(
                "modificar",
				'cod_usuario',
				'nombre_apellido',
				'cod_caja',
				'cod_mov_caja',
				'fecha_hora_mov',
				'monto_mov',
				'desc_tipo_mov',
				'cod_tipo_mov',
				'factura_mov',
				'desc_factura_mov',
				'tipo_factura_mov',
				'observacion_mov',				
				'tipo_mov'
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
            $applicationModel = new Application_Model_Movcaja();
            self::almacenardatos($applicationModel,$rowData);
	}

	public function modificarAction(){
            $this->_helper->viewRenderer->setNoRender ( true );
            $json_rowData = $this->getRequest ()->getParam ( "parametros" );
            $rowData = json_decode($json_rowData);
            $rowClass = new Application_Model_Movcaja();
		if($rowData->cod_mov_caja != null){
				$rowClass->setCod_mov_caja($rowData->cod_mov_caja);
			}
            self::almacenardatos($rowClass,$rowData);
	}

        public function almacenardatos($rowClass,$rowData){
     	try{
     		$service = new Application_Model_DataService('Application_Model_DbTable_Movcaja');
        	$db = Zend_Db_Table::getDefaultAdapter();			
        	$db->beginTransaction();			
                $rowClass->setCod_caja(trim(utf8_decode($rowData->cod_caja)));
				if(trim($rowData->cod_mov_caja) == '')
					$rowClass->setFecha_hora_mov(date('Y-m-d h:i:s'));				
				$rowClass->setMonto_mov(trim(utf8_decode($rowData->monto_mov)));
				$rowClass->setCod_tipo_mov(trim(utf8_decode($rowData->cod_tipo_mov)));
				if(isset($rowData->factura_mov))
					$rowClass->setFactura_mov(trim(utf8_decode($rowData->factura_mov)));
				else
					$rowClass->setFactura_mov(0);
				if(isset($rowData->tipo_factura_mov))
					$rowClass->setTipo_factura_mov(trim(utf8_decode($rowData->tipo_factura_mov)));
				else
					$rowClass->setTipo_factura_mov('');
				if(trim($rowData->observacion_mov) <> '')
					$rowClass->setObservacion_mov(trim(utf8_decode($rowData->observacion_mov)));
				else
					$rowClass->setObservacion_mov('');
				if(trim($rowData->firmante_mov) <> '')
					$rowClass->setFirmante_mov(trim(utf8_decode($rowData->firmante_mov)));                                
                                $rowClass->setTipo_mov('EFECTIVO');
                $result_pk = $service->saveRow($rowClass);
	    	$db->commit();
                
                $var_nombrearchivo = 'nro_movimiento_'.trim($result_pk);
                $path_tmp = './tmp/';
                $orientation='P';
                $unit='mm';
                $format='A4';

                if(!isset($pdf))
                  $pdf= new PDFReporteagresocaja($orientation,$unit,$format,$result_pk);
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->Body();

                $file = basename($var_nombrearchivo."_".date('Ymdhis'));
                $file .= '.pdf';
                //Guardar el PDF en un fichero
                $pdf->Output($path_tmp.$file, 'F');
                $pdf->close();
                unset($pdf);
                echo json_encode(array("result" => "EXITO","archivo" => $file));	    	
        }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
		$db->rollBack();
	}
    }
	public function cajaabiertadataAction()
	{
//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$result = '';
		$parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
		$cod_usuario = $parametrosNamespace->cod_usuario;
		$cod_usuario = 1;
		$jsonResultado = json_encode(array("resultado" => 'cerrado'));		
		try {
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('C' => 'usuario'), 
						   array('C.cod_usuario',
								 'C.nombre_apellido',
								 'D.fecha_hora_apertura',
								 'D.cod_caja'))
					->join(array('D' => 'caja'), 'D.cod_usuario_caja = C.cod_usuario')
					->where("fecha_hora_cierre is null and cod_usuario_caja = ".$cod_usuario);	
			$result = $db->fetchAll($select);
//die($select);			
			foreach ($result as $arr) {
				$jsonResultado = json_encode(array(
					"resultado" => 'abierto',
					"cod_usuario" => $arr["cod_usuario"],
					"nombre_apellido" => $arr["nombre_apellido"],
					"fecha_hora_apertura" => $arr["fecha_hora_apertura"],
					"cod_caja" => $arr["cod_caja"]));
			}
		} catch (Exception $e) {
				$jsonResultado = json_encode(array("resultado" => 'error'));
		}
		echo $jsonResultado;
	}
	public function tipomovimientodataAction()
	{
//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$result = '';
		try {
		     $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('T' => 'tipo_movimiento'), array('T.cod_tipo_mov', 'T.desc_tipo_mov'))
                ->distinct(true);
                
        	$result = $db->fetchAll($select);
			$htmlResultado = '<option value="-1">Seleccione</option>';
			foreach ($result as $arr) {
				$htmlResultado .= '<option value="' . $arr["cod_tipo_mov"] . '">' .
				trim(utf8_encode($arr["desc_tipo_mov"])) . '</option>';
			}
		} catch (Exception $e) {
			$htmlResultado = "error";
		}
		echo $htmlResultado;
	}
}

