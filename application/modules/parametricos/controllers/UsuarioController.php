<?php

class Parametricos_UsuarioController extends Zend_Controller_Action
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
//          echo 'hola';
    }



    private function obtenerPaginas($result,$cantidadFilas,$page){
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
            $arrayDatos ['cell'] = array(
                null,
                $item['COD_USUARIO'],
                $item['ID_USUARIO'],
                $item['NOMBRE_APELLIDO'],
                $item['USUARIO_PASSWORD']
                
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                'COD_USUARIO',
                'ID_USUARIO',
                'NOMBRE_APELLIDO',
                'USUARIO_PASSWORD'
               
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
                ->from(array('C' => 'USUARIO'), 
                       array('C.COD_USUARIO',
                             'C.ID_USUARIO',
                             'C.NOMBRE_APELLIDO',
                             'C.USUARIO_PASSWORD'));

        if ($Obj != null) {
            if ($Obj->ID_USUARIO != null) {
                $select->where("upper(C.ID_USUARIO) like upper('%".$Obj->ID_USUARIO."%')");
            }
            if ($Obj->NOMBRE_APELLIDO != null) {
                $select->where("upper(C.NOMBRE_APELLIDO) like upper('%".$Obj->NOMBRE_APELLIDO."%')");
            }
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }

    public function eliminarAction(){
    $this->_helper->viewRenderer->setNoRender(true);
       $cod_Registro = json_decode($this->getRequest()->getParam("id"));
       try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
                $where= array('COD_USUARIO = ?' => $cod_Registro);
                $n = $db->delete('USUARIO', $where);
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
                'COD_USUARIO' => 0,
                'ID_USUARIO' => $rowData->ID_USUARIO,
                'NOMBRE_APELLIDO' => (trim($rowData->NOMBRE_APELLIDO)),
                'USUARIO_PASSWORD' => (trim($rowData->USUARIO_PASSWORD))
                
                
            );
//            print_r($data);
//            die();
            $upd = $db->insert('USUARIO', $data);
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
                'COD_USUARIO' => $rowData->COD_USUARIO,
                'ID_USUARIO' => $rowData->ID_USUARIO,
                'NOMBRE_APELLIDO' => (trim($rowData->NOMBRE_APELLIDO)),
                'USUARIO_PASSWORD' => (trim($rowData->USUARIO_PASSWORD))
                
            );
            $where = "COD_USUARIO= " . $rowData->COD_USUARIO;

            $upd = $db->update('USUARIO', $data, $where);
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
        } catch (Exception $e) {
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode()));
            $db->rollBack();
        }
    }
   
    
    
    
    
    public function empresaclienteAction()
    {
//      $this->_helper->layout->disableLayout();
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

