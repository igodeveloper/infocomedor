<?php

class Compras_AutocompleteController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    /**
     * Retorna las coincidencias de $term con los campos
     * 
     * @param $term patron a buscar
     * @return resultado de coincidencias
     */
    public function proveedordataAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PROVEEDOR'), array('distinct(P.COD_PROVEEDOR)', 'P.PROVEEDOR_NOMBRE'))
                ->order(array('P.PROVEEDOR_NOMBRE'));
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
            $label = utf8_encode(trim($value ['PROVEEDOR_NOMBRE']));
            $id = $value ['COD_PROVEEDOR'];
            $option [] = array("id" => $id, "label" => $label);
        }
        echo json_encode($option);
    }

  

}

