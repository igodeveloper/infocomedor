<?php
//llamada al controller compra
//http://infocomedor/compras/compra?userSession=fede&sucursalSession=1&codUsuarioSession=1&monedaSession=1#
class Menus_AccesoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
    }

    public function compraspagosusuarioAction() {
        
        $this->_helper->viewRenderer->setNoRender ( true );
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $parametrosNamespace->unlock ();      
        
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'CAJA'), array('distinct(C.COD_USUARIO_CAJA)','COD_CAJA'))
                 ->where("C.COD_USUARIO_CAJA = ?", $parametrosNamespace->cod_usuario)
                 ->where("C.FECHA_HORA_CIERRE IS NULL");
        $result = $db->fetchAll($select);
       
        $arrResult=array("COD_USUARIO_CAJA" => $result[0] ['COD_USUARIO_CAJA'], 
                        "USERNAME" => $parametrosNamespace->username, 
                        "NOMBRE_APELLIDO" => $parametrosNamespace->desc_usuario,
                        "COD_CAJA" => $result[0] ['COD_CAJA']
                        );
        $parametrosNamespace->lock ();
        echo json_encode($arrResult);
        
     }	
       

}
