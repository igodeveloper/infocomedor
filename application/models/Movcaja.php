<?php 
class Application_Model_Movcaja extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Movcaja";
	protected $_cod_mov_caja = null;
	protected $_cod_caja = null;
	protected $_fecha_hora_mov = null;
	protected $_monto_mov = null;
	protected $_cod_tipo_mov = null;
	protected $_factura_mov = null;
	protected $_tipo_factura_mov = null;
	protected $_observacion_mov = null;
	protected $_tipo_mov = null;
	public $_data = null;
	public function getCod_mov_caja(){
		return $this->_cod_mov_caja;
	}
	public function getCod_caja(){
		return $this->_cod_caja;
	}
	public function getFecha_hora_mov(){
		return $this->_fecha_hora_mov;
	}
	public function getMonto_mov(){
		return $this->_monto_mov;
	}
	public function getCod_tipo_mov(){
		return $this->_cod_tipo_mov;
	}
	public function getFactura_mov(){
		return $this->_factura_mov;
	}
	public function getTipo_factura_mov(){
		return $this->_tipo_factura_mov;
	}
	public function getObservacion_mov(){
		return $this->_observacion_mov;
	}
	public function getTipo_mov(){
		return $this->_tipo_mov;
	}
	public function setCod_mov_caja($_cod_mov_caja){
		$this->_cod_mov_caja = $_cod_mov_caja;
	}
	public function setCod_caja($_cod_caja){
		$this->_cod_caja = $_cod_caja;
	}
	public function setFecha_hora_mov($_fecha_hora_mov){
		$this->_fecha_hora_mov = $_fecha_hora_mov;
	}
	public function setMonto_mov($_monto_mov){
		$this->_monto_mov = $_monto_mov;
	}
	public function setCod_tipo_mov($_cod_tipo_mov){
		$this->_cod_tipo_mov = $_cod_tipo_mov;
	}
	public function setFactura_mov($_factura_mov){
		$this->_factura_mov = $_factura_mov;
	}
	public function setTipo_factura_mov($_tipo_factura_mov){
		$this->_tipo_factura_mov = $_tipo_factura_mov;
	}
	public function setObservacion_mov($_observacion_mov){
		$this->_observacion_mov = $_observacion_mov;
	}
	public function setTipo_mov($_tipo_mov){
		$this->_tipo_mov = $_tipo_mov;
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
			'cod_mov_caja' => $this->_cod_mov_caja, 
			'cod_caja' => $this->_cod_caja, 
			'fecha_hora_mov' => $this->_fecha_hora_mov, 
			'monto_mov' => $this->_monto_mov, 
			'cod_tipo_mov' => $this->_cod_tipo_mov, 
			'factura_mov' => $this->_factura_mov, 
			'tipo_factura_mov' => $this->_tipo_factura_mov, 
			'observacion_mov' => $this->_observacion_mov, 
			'tipo_mov' => $this->_tipo_mov);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_mov_caja', 'cod_caja', 'fecha_hora_mov', 'monto_mov', 'cod_tipo_mov', 'factura_mov', 'tipo_factura_mov', 'observacion_mov', 'tipo_mov') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>