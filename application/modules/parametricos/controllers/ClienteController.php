<?php

class Parametricos_ClienteController extends Zend_Controller_Action
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
                ->from(array('C' => 'CLIENTE'), 
                       array('C.COD_CLIENTE',
                             'C.CLIENTE_DES',
                       		 'C.CLIENTE_RUC',
                       		 'C.CLIENTE_DIRECCION',
                       		 'C.CLIENTE_TELEFONO',
                       		 'C.CLIENTE_EMAIL',
                       		 'C.COD_EMPRESA',
                       		 'E.DES_EMPRESA'))
                   ->joinLeft(array('E' => 'EMPRESA'), 'C.COD_EMPRESA = E.COD_EMPRESA')
                    ->order(array('C.COD_CLIENTE DESC'));

                   
        $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
	}

	private function obtenerPaginas($result,$cantidadFilas,$page){
		$this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
		$cod_empresa = ($item['COD_EMPRESA'] == 0)?0:$item['COD_EMPRESA'];
		$cod_empresa_desc = ($item['DES_EMPRESA'] == null)?' - ':$item['DES_EMPRESA'];
		$direccion = ($item['CLIENTE_DIRECCION'] == '0')?' - ':$item['CLIENTE_DIRECCION'];
		$telefono = ($item['CLIENTE_TELEFONO'] == '0')?' - ':$item['CLIENTE_TELEFONO'];
		$email = ($item['CLIENTE_EMAIL'] == '0')?' - ':$item['CLIENTE_EMAIL'];
            $arrayDatos ['cell'] = array(
                null,
                $item['COD_CLIENTE'],
                $item['CLIENTE_DES'],
                $item['CLIENTE_RUC'],
                $direccion,
                $telefono,
                $email,
                $cod_empresa,
				$cod_empresa_desc
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                'COD_CLIENTE',
				'CLIENTE_DES',
				'CLIENTE_RUC',
				'CLIENTE_DIRECCION',
				'CLIENTE_TELEFONO',
				'CLIENTE_EMAIL',
             	'COD_EMPRESA',
                'DES_EMPRESA'
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
                ->from(array('C' => 'CLIENTE'), 
                       array('C.COD_CLIENTE',
                             'C.CLIENTE_DES',
                       		 'C.CLIENTE_RUC',
                       		 'C.CLIENTE_DIRECCION',
                       		 'C.CLIENTE_TELEFONO',
                       		 'C.CLIENTE_EMAIL',
                       		 'C.COD_EMPRESA',
                       		 'E.DES_EMPRESA'))
                   ->joinLeft(array('E' => 'EMPRESA'), 'C.COD_EMPRESA = E.COD_EMPRESA');

        if ($Obj != null) {
            if ($Obj->descripcion != null) {
                $select->where("upper(C.CLIENTE_DES) like upper('%".$Obj->descripcion."%')");
            }
        	if ($Obj->ruc != null) {
                $select->where("upper(C.CLIENTE_RUC) like upper('%".$Obj->ruc."%')");
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
    $this->_helper->viewRenderer->setNoRender(true);
       $cod_Registro = json_decode($this->getRequest()->getParam("id"));
       try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
               	$where= array('COD_CLIENTE = ?' => $cod_Registro);
				$n = $db->delete('CLIENTE', $where);
         	 $db->commit();
             echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
       		$db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
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
                'COD_CLIENTE' => 0,
                'CLIENTE_DES' => $rowData->CLIENTE_DES,
                'CLIENTE_RUC' => (trim($rowData->CLIENTE_RUC)),
            	'CLIENTE_DIRECCION' => (trim($rowData->CLIENTE_DIRECCION)),
            	'CLIENTE_TELEFONO' => (trim($rowData->CLIENTE_TELEFONO)),
                'CLIENTE_EMAIL' => (trim($rowData->CLIENTE_EMAIL)),
            	'COD_EMPRESA' => (int) (trim($rowData->COD_EMPRESA))
                
            );
//            print_r($data);
//            die();
            $upd = $db->insert('CLIENTE', $data);
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
                'COD_CLIENTE' => trim($rowData->COD_CLIENTE),
                'CLIENTE_DES' => $rowData->CLIENTE_DES,
                'CLIENTE_RUC' => (trim($rowData->CLIENTE_RUC)),
            	'CLIENTE_DIRECCION' => (trim($rowData->CLIENTE_DIRECCION)),
            	'CLIENTE_TELEFONO' => (trim($rowData->CLIENTE_TELEFONO)),
                'CLIENTE_EMAIL' => (trim($rowData->CLIENTE_EMAIL)),
            	'COD_EMPRESA' => (int) (trim($rowData->COD_EMPRESA))
                
            );
            $where = "COD_CLIENTE= " . $rowData->COD_CLIENTE;

            $upd = $db->update('CLIENTE', $data, $where);
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode()));
            $db->rollBack();
        }
    }
   
	
	
	
	
	public function empresaclienteAction()
	{
//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$result = '';
		try {
		     $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('E' => 'EMPRESA'), array('E.COD_EMPRESA', 'E.DES_EMPRESA'))
                ->distinct(true);
        	$result = $db->fetchAll($select);
			$htmlResultado = '<option value="0">Sin empresa</option>';
			foreach ($result as $arr) {
				$htmlResultado .= '<option value="' . $arr["COD_EMPRESA"] . '">' .
				trim(utf8_encode($arr["DES_EMPRESA"])) . '</option>';
			}
		} catch (Exception $e) {
			$htmlResultado = "error";
		}
		echo $htmlResultado;
	}

}

