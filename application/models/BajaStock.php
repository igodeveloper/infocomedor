<?php 
class Application_Model_BajaStock extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_BajaStock";
	protected $_cod_baja_stock = null;
	protected $_cod_producto = null;
	protected $_fecha_hora_baja = null;
	protected $_observacion_mov = null;
	public $_data = null;
	public function getCod_baja_stock(){
		return $this->_cod_baja_stock;
	}
	public function getCod_producto(){
		return $this->_cod_producto;
	}
	public function getFecha_hora_baja(){
		return $this->_fecha_hora_baja;
	}
	public function getObservacion_mov(){
		return $this->_observacion_mov;
	}
	public function setCod_baja_stock($_cod_baja_stock){
		$this->_cod_baja_stock = $_cod_baja_stock;
	}
	public function setCod_producto($_cod_producto){
		$this->_cod_producto = $_cod_producto;
	}
	public function setFecha_hora_baja($_fecha_hora_baja){
		$this->_fecha_hora_baja = $_fecha_hora_baja;
	}
	public function setObservacion_mov($_observacion_mov){
		$this->_observacion_mov = $_observacion_mov;
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
			'cod_baja_stock' => $this->_cod_baja_stock, 
			'cod_producto' => $this->_cod_producto, 
			'fecha_hora_baja' => $this->_fecha_hora_baja, 
			'observacion_mov' => $this->_observacion_mov);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_baja_stock', 'cod_producto', 'fecha_hora_baja', 'observacion_mov') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>