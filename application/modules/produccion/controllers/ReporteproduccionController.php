<?php

class Produccion_ReporteproduccionController extends Zend_Controller_Action
{



    public function init()
    {
        /* Initialize action controller here */
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
   
    public function imprimirreporteAction() {
    //        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
       // die($json_rowData);
       // die();
        // $rowData = json_decode($json_rowData);
        //$curso = $rowData->curso;
        
        $var_nombrearchivo = 'productos';
        $path_tmp = './tmp/';
        $orientation='P';
        $unit='mm';
        $format='A4';
        
        if(!isset($pdf))
        $pdf= new PDFReporteproduccion($orientation,$unit,$format,$json_rowData);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Body($json_rowData);

        $file = basename($var_nombrearchivo."_".date('Ymdhis'));
        $file .= '.pdf';
        //Guardar el PDF en un fichero
        $pdf->Output($path_tmp.$file, 'F');
        $pdf->close();
        unset($pdf);
        echo json_encode(array("result" => "EXITO","archivo" => $file));
       // echo json_encode(array("result" => "EXITO","archivo" => $file));
        //echo "<script>  window.open('".$path_tmp.$file."');  </script>";                      
    }	

    public function cargartipoproductoAction()
    {
//      $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('R' => 'TIPO_PRODUCTO'), array('R.COD_TIPO_PRODUCTO', 'R.TIPO_PRODUCTO_DESCRIPCION'))
                ->distinct(true);
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="0">Seleccione</option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["COD_TIPO_PRODUCTO"] . '">' .
                trim(utf8_encode($arr["TIPO_PRODUCTO_DESCRIPCION"])) . '</option>';
            }
        } catch (Exception $e) {
            $htmlResultado = "error";
        }
        echo $htmlResultado;
    }
    
   
}

