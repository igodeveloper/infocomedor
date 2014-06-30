<?php

class Parametricos_ProductoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
//			echo 'hola';
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
                ->from(array('C' => 'PRODUCTO'), 
                       array('C.COD_PRODUCTO',
                             'C.PRODUCTO_DESC',
                       		 'C.COD_PRODUCTO_TIPO',
                       		 'TP.TIPO_PRODUCTO_DESCRIPCION',
                       		 'C.COD_UNIDAD_MEDIDA',
                       		 'UM.DESC_UNIDAD_MEDIDA',
                       		 'C.COD_RECETA',
                       		 'R.RECETA_DESCRIPCION',
                       		 'C.COD_IMPUESTO',
                       		 'I.IMP_PORCENTAJE',
                       		 'C.PRECIO_VENTA'))
                   ->join(array('TP' => 'TIPO_PRODUCTO'), 'C.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                   ->join(array('UM' => 'UNIDAD_MEDIDA'), 'C.COD_UNIDAD_MEDIDA = UM.COD_UNIDAD_MEDIDA')
                   ->join(array('I' => 'IMPUESTO'), 'C.COD_IMPUESTO = I.COD_IMPUESTO')
                   ->joinLeft(array('R' => 'RECETA'), 'C.COD_RECETA = R.COD_RECETA');
                   
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
		$cod_receta = ($item['COD_RECETA'] == null)?0:$item['COD_RECETA'];
		$cod_receta_desc = ($item['RECETA_DESCRIPCION'] == null)?' - ':$item['RECETA_DESCRIPCION'];
            $arrayDatos ['cell'] = array(
                null,
                $item['COD_PRODUCTO'],
                $item['PRODUCTO_DESC'],
                $item['COD_PRODUCTO_TIPO'],
                $item['TIPO_PRODUCTO_DESCRIPCION'],
                $item['COD_UNIDAD_MEDIDA'],
                $item['DESC_UNIDAD_MEDIDA'],
//                $item['COD_RECETA'],
//                $item['RECETA_DESCRIPCION']
				$cod_receta,
				$cod_receta_desc,
				$item['COD_IMPUESTO'],
				 $item['IMP_PORCENTAJE'],
				$item['PRECIO_VENTA']
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                'COD_PRODUCTO',
				'PRODUCTO_DESC',
				'COD_PRODUCTO_TIPO',
				'TIPO_PRODUCTO_DESCRIPCION',
				'COD_UNIDAD_MEDIDA',
				'DESC_UNIDAD_MEDIDA',
             	'COD_RECETA',
                'RECETA_DESCRIPCION',
            'COD_IMPUESTO',
            'IMP_PORCENTAJE',
            'PRECIO_VENTA'
            
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
                ->from(array('C' => 'PRODUCTO'), 
                       array('C.COD_PRODUCTO',
                             'C.PRODUCTO_DESC',
                       		 'C.COD_PRODUCTO_TIPO',
                       		 'TP.TIPO_PRODUCTO_DESCRIPCION',
                       		 'C.COD_UNIDAD_MEDIDA',
                       		 'UM.DESC_UNIDAD_MEDIDA',
                       		 'C.COD_RECETA',
                       		 'R.RECETA_DESCRIPCION'))
                   ->join(array('TP' => 'TIPO_PRODUCTO'), 'C.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                   ->join(array('UM' => 'UNIDAD_MEDIDA'), 'C.COD_UNIDAD_MEDIDA = UM.COD_UNIDAD_MEDIDA')
                   ->joinLeft(array('R' => 'RECETA'), 'C.COD_RECETA = R.COD_RECETA');

        if ($Obj != null) {
            if ($Obj->descripcion != null) {
                $select->where("upper(C.PRODUCTO_DESC) like upper('%".$Obj->descripcion."%')");
            }
        	if ($Obj->descripciontipoproducto != null) {
                $select->where("upper(TP.TIPO_PRODUCTO_DESCRIPCION) like upper('%".$Obj->descripciontipoproducto."%')");
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
                $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
		try{
                    $db = Zend_Db_Table::getDefaultAdapter();
                    $db->beginTransaction();
                    $servCon = new Application_Model_DataService('Application_Model_DbTable_Producto');
                    $servCon->deleteRowById(array("COD_PRODUCTO"=>$id));
                    $db->commit();
		    echo json_encode(array("result" => "EXITO"));
	    }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
			$db->rollBack();
		}
	}

	public function guardarAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest()->getParam("parametros");
        $rowData = json_decode($json_rowData);
        try {

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $data = array(
                'COD_PRODUCTO' => 0,
                'PRODUCTO_DESC' => $rowData->PRODUCTO_DESC,
                'COD_PRODUCTO_TIPO' => (int) (trim($rowData->COD_PRODUCTO_TIPO)),
                'COD_UNIDAD_MEDIDA' => (int) (trim($rowData->COD_UNIDAD_MEDIDA)),
            	'COD_RECETA' => (int) (trim($rowData->COD_RECETA)),
            	'COD_IMPUESTO' => (int) (trim($rowData->COD_IMPUESTO)),
            	'PRECIO_VENTA' => (int) (trim($rowData->PRECIO_VENTA))
                
            );
//            print_r($data);
//            die();
            $upd = $db->insert('PRODUCTO', $data);

            $codigoInsertado = $db->lastInsertId();

                $select = $db->select()
                        ->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO','S.SALDO_STOCK'))
                        ->distinct(true)
                        ->where("S.COD_PRODUCTO = ?", $codigoInsertado);
                $resultado_select = $db->fetchAll($select);
                
                $existe = ($resultado_select[0]['COD_PRODUCTO'] <> null)?$resultado_select[0]['COD_PRODUCTO']:0;
                $saldo_producto = ($resultado_select[0]['SALDO_STOCK']>0)?$resultado_select[0]['SALDO_STOCK']:0;
                    $data = array(
                        'COD_PRODUCTO' => $codigoInsertado,
                        'SALDO_STOCK' => 0,
                        'STOCK_FECHA_ACTUALIZA' => ( date("Y-m-d H:i:s"))
                    );
                // SI EL PRODUCTO EXISTE INSERAMOS SI NO ACTUALIZAMOS

                if($existe == 0){
                            $upd = $db->insert('STOCK', $data);
                } 

            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }

    public function modificarAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest()->getParam("parametros");
        $rowData = json_decode($json_rowData);
        try {

            $db = Zend_Db_Table::getDefaultAdapter();
             $db->beginTransaction();
             $data = array(
                'COD_PRODUCTO' => $rowData->COD_PRODUCTO,
                'PRODUCTO_DESC' => $rowData->PRODUCTO_DESC,
                'COD_PRODUCTO_TIPO' => (int) (trim($rowData->COD_PRODUCTO_TIPO)),
                'COD_UNIDAD_MEDIDA' => (int) (trim($rowData->COD_UNIDAD_MEDIDA)),
                'COD_RECETA' => (int) (trim($rowData->COD_RECETA)),
             	'COD_IMPUESTO' => (int) (trim($rowData->COD_IMPUESTO)),
            	'PRECIO_VENTA' => (int) (trim($rowData->PRECIO_VENTA))
                
            );
            $where = "COD_PRODUCTO= " . $rowData->COD_PRODUCTO;

            $upd = $db->update('PRODUCTO', $data, $where);
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode()));
            $db->rollBack();
        }
    }
   
	public function tipoproductoAction()
	{
//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$result = '';
		try {
			$srvEmp = new Application_Model_DataService(
            'Application_Model_DbTable_Tipoproducto');
			$result = $srvEmp->getAllRows();
			$htmlResultado = '<option value="-1">Seleccione</option>';
			foreach ($result as $arr) {
				$htmlResultado .= '<option value="' . $arr["COD_TIPO_PRODUCTO"] . '">' .
				trim(utf8_encode($arr["TIPO_PRODUCTO_DESCRIPCION"])) . '</option>';
			}
		} catch (Exception $e) {
			$htmlResultado = "error";
		}
		echo $htmlResultado;
	}
	
	public function unidadmedidaAction()
	{
//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$result = '';
		try {
			$srvEmp = new Application_Model_DataService(
            'Application_Model_DbTable_Unidadmedida');
			$result = $srvEmp->getAllRows();
			$htmlResultado = '<option value="-1">Seleccione</option>';
			foreach ($result as $arr) {
				$htmlResultado .= '<option value="' . $arr["COD_UNIDAD_MEDIDA"] . '">' .
				trim(utf8_encode($arr["DESC_UNIDAD_MEDIDA"])) . '</option>';
			}
		} catch (Exception $e) {
			$htmlResultado = "error";
		}
		echo $htmlResultado;
	}
	
	public function recetaAction()
	{
//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$result = '';
		try {
		     $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('R' => 'RECETA'), array('R.COD_RECETA', 'R.RECETA_DESCRIPCION'))
                ->distinct(true);
                
        	$result = $db->fetchAll($select);
			$htmlResultado = '<option value="0">Sin receta</option>';
			foreach ($result as $arr) {
				$htmlResultado .= '<option value="' . $arr["COD_RECETA"] . '">' .
				trim(utf8_encode($arr["RECETA_DESCRIPCION"])) . '</option>';
			}
		} catch (Exception $e) {
			$htmlResultado = "error";
		}
		echo $htmlResultado;
	}
	
	public function impuestoAction()
	{
//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$result = '';
		try {
		     $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('R' => 'IMPUESTO'), array('R.COD_IMPUESTO', 'R.IMP_PORCENTAJE'))
                ->distinct(true);
                
        	$result = $db->fetchAll($select);
			$htmlResultado = '<option value="-1">Seleccione</option>';
			foreach ($result as $arr) {
				$htmlResultado .= '<option value="' . $arr["COD_IMPUESTO"] . '">' .
				trim(utf8_encode($arr["IMP_PORCENTAJE"])) . '</option>';
			}
		} catch (Exception $e) {
			$htmlResultado = "error";
		}
		echo $htmlResultado;
	}

}

