<?php

class Application_Model_Producto extends ZendExt_Db_Table_Row_Abstract {

    protected $_tableClass = "Application_Model_DbTable_Producto";
    protected $_cod_Producto = null;
    protected $_producto_Desc = null;
    protected $_cod_Producto_Tipo = null;
    protected $_cod_Unidad_Medida = null;
    public $_data = null;

    public function getCod_Producto() {
        return $this->_cod_Producto;
    }

    public function getProducto_Desc() {
        return $this->_producto_Desc;
    }

    public function getCod_Producto_Tipo() {
        return $this->_cod_Producto_Tipo;
    }

    public function getCod_Unidad_Medida() {
        return $this->_cod_Unidad_Medida;
    }

    public function setCod_Producto($_cod_Producto) {
        $this->_cod_Producto = $_cod_Producto;
    }

    public function setProducto_Desc($_producto_Desc) {
        $this->_producto_Desc = $_producto_Desc;
    }

    public function setCod_Producto_Tipo($_cod_Producto_Tipo) {
        $this->_cod_Producto_Tipo = $_cod_Producto_Tipo;
    }

    public function setCod_Unidad_Medida($_cod_Unidad_Medida) {
        $this->_cod_Unidad_Medida = $_cod_Unidad_Medida;
    }

    public function __get($propertyName) {
        $getter = "get" . $propertyName;
        if (!method_exists($this, $getter)) {
            throw new RuntimeException("Property by name " . $propertyName . " not found as part of this object.");
        }
        return $this->{$getter}();
    }

    public function toArr() {
        return array(
            'COD_PRODUCTO' => $this->_cod_Producto,
            'PRODUCTO_DESC' => $this->_producto_Desc,
            'COD_PRODUCTO_TIPO' => $this->_cod_Producto_Tipo,
            'COD_UNIDAD_MEDIDA' => $this->_cod_Unidad_Medida
        );
    }

    public function setFromArray(array $data) {
        foreach (array('COD_PRODUCTO', 'PRODUCTO_DESC', 'COD_PRODUCTO_TIPO', 'COD_UNIDAD_MEDIDA') as $property) {
            if (isset($data[strtoupper($property)])) {
                $this->{'_' . $property} = $data[strtoupper($property)];
            }
        }
    }

}

?>
