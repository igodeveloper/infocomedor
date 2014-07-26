<?php
//llamada al controller compra
//http://infocomedor/compras/compra?userSession=fede&sucursalSession=1&codUsuarioSession=1&monedaSession=1#
class Menus_MenuController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );    
    $parametrosNamespace->unsetAll();
    }

    public function indexAction()
    {
        
    }
    public function sucursaldataAction() {
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'inmobiliaria_wasmosy.sucursal'), array('distinct(C.cod_sucursal)', 'C.desc_sucursal'))
                 ->order(array('C.desc_sucursal'));
        $result = $db->fetchAll($select);
        $htmlResult = '<option style="size: 30px" value="-1">---Seleccione---</option>';
        foreach ($result as $arr) {
             $htmlResult .= '<option style="size: 30px" value="'.$arr["cod_sucursal"].'">' .trim(utf8_encode($arr["desc_sucursal"])).'</option>';	
        }
        echo  $htmlResult;
     }	
       
    public function usuariodataAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $json_rowData = $this->getRequest ()->getParam ("parametros");
        $json_rowData = str_replace("\\", '', $json_rowData);	
        //$json_rowData = '{"username":"ghghhg","password":"sddsd","SelecAgencia":"1"}';
        $rowData = json_decode($json_rowData);
        $where =null;
        $where.="UPPER(USER) = '".strtoupper(trim($rowData->username))."'";
        $where.="password = '".trim($rowData->password)."'";
        $password = trim($rowData->password);
        //$password = md5($password);
        $db = Zend_Db_Table::getDefaultAdapter();
        $htmlResult['resultado'] = '-1';
        $select = $db->select()
                 ->from(array('C' => 'usuario'), array('C.COD_USUARIO','C.NOMBRE_APELLIDO','C.PERMISO'))
                 ->where(" UPPER(ID_USUARIO) = '".strtoupper(trim($rowData->username))."'")
                 ->where("USUARIO_PASSWORD = '".$password."'");
                 //->where("A.cod_sucursal = ".trim($rowData->SelecAgencia));
//echo $select;die();        
        $result = $db->fetchAll($select);
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $parametrosNamespace->unlock ();        
        foreach ($result as $arr) {                         
            $htmlResult['resultado'] = trim(utf8_encode($arr["COD_USUARIO"]));   
          //  session_name("sesion");
            //session_start();
//echo 'usuario : '.$arr["USUARIO"].' sucursal : '.$arr["DESC_SUCURSAL"].'<br>';           
            $_SESSION['username'] = trim($rowData->username);
            $_SESSION['cod_usuario'] = trim(utf8_encode($arr["COD_USUARIO"]));
            $_SESSION['desc_usuario'] = trim(utf8_encode($arr["NOMBRE_APELLIDO"]));
            $_SESSION['PERMISO'] = trim(utf8_encode($arr["PERMISO"]));
//echo 'usuario secion: '.$_SESSION['desc_usuario'].' sucursal secion: '.$_SESSION['nomAgencia'].'<br>'; 
            $parametrosNamespace->username = trim($rowData->username);
            $parametrosNamespace->cod_usuario = trim(utf8_encode($arr["COD_USUARIO"]));
            $parametrosNamespace->desc_usuario = trim(utf8_encode($arr["NOMBRE_APELLIDO"]));            
            $parametrosNamespace->PERMISO = trim(utf8_encode($arr["PERMISO"]));            
        }
        $parametrosNamespace->lock ();                       
        echo $this->_helper->json ( $htmlResult);
    }  
}
