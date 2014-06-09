<?php 
class Application_Model_TipoProducto extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_TipoProducto";
	protected $_cod_tipo_producto = null;
	protected $_tipo_producto_descripcion = null;
	public $_data = null;
	public function getCod_tipo_producto(){
		return $this->_cod_tipo_producto;
	}
	public function getTipo_producto_descripcion(){
		return $this->_tipo_producto_descripcion;
	}
	public function setCod_tipo_producto($_cod_tipo_producto){
		$this->_cod_tipo_producto = $_cod_tipo_producto;
	}
	public function setTipo_producto_descripcion($_tipo_producto_descripcion){
		$this->_tipo_producto_descripcion = $_tipo_producto_descripcion;
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
			'COD_TIPO_PRODUCTO' => $this->_cod_tipo_producto, 
			'TIPO_PRODUCTO_DESCRIPCION' => $this->_tipo_producto_descripcion);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_tipo_producto', 'tipo_producto_descripcion') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>