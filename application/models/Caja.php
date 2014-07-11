<?php 
class Application_Model_Caja extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Caja";
	protected $_cod_caja = null;
	protected $_cod_usuario_caja = null;
	protected $_fecha_hora_apertura = null;
	protected $_fecha_hora_cierre = null;
	protected $_monto_caja_apertura = null;
	protected $_monto_caja_cierre = null;
	protected $_monto_diferencia_arqueo = null;
	protected $_arqueo_caja = null;
	protected $_monto_caja_cierre_cheque = null;
	protected $_monto_diferencia_arqueo_cheque = null;
	public $_data = null;
	public function getCod_caja(){
		return $this->_cod_caja;
	}
	public function getCod_usuario_caja(){
		return $this->_cod_usuario_caja;
	}
	public function getFecha_hora_apertura(){
		return $this->_fecha_hora_apertura;
	}
	public function getFecha_hora_cierre(){
		return $this->_fecha_hora_cierre;
	}
	public function getMonto_caja_apertura(){
		return $this->_monto_caja_apertura;
	}
	public function getMonto_caja_cierre(){
		return $this->_monto_caja_cierre;
	}
	public function getMonto_diferencia_arqueo(){
		return $this->_monto_diferencia_arqueo;
	}
	public function getArqueo_caja(){
		return $this->_arqueo_caja;
	}
	public function getMonto_caja_cierre_cheque(){
		return $this->_monto_caja_cierre_cheque;
	}
	public function getMonto_diferencia_arqueo_cheque(){
		return $this->_monto_diferencia_arqueo_cheque;
	}
	public function setCod_caja($_cod_caja){
		$this->_cod_caja = $_cod_caja;
	}
	public function setCod_usuario_caja($_cod_usuario_caja){
		$this->_cod_usuario_caja = $_cod_usuario_caja;
	}
	public function setFecha_hora_apertura($_fecha_hora_apertura){
		$this->_fecha_hora_apertura = $_fecha_hora_apertura;
	}
	public function setFecha_hora_cierre($_fecha_hora_cierre){
		$this->_fecha_hora_cierre = $_fecha_hora_cierre;
	}
	public function setMonto_caja_apertura($_monto_caja_apertura){
		$this->_monto_caja_apertura = $_monto_caja_apertura;
	}
	public function setMonto_caja_cierre($_monto_caja_cierre){
		$this->_monto_caja_cierre = $_monto_caja_cierre;
	}
	public function setMonto_diferencia_arqueo($_monto_diferencia_arqueo){
		$this->_monto_diferencia_arqueo = $_monto_diferencia_arqueo;
	}
	public function setArqueo_caja($_arqueo_caja){
		$this->_arqueo_caja = $_arqueo_caja;
	}
	public function setMonto_caja_cierre_cheque($_monto_caja_cierre_cheque){
		$this->_monto_caja_cierre_cheque = $_monto_caja_cierre_cheque;
	}
	public function setMonto_diferencia_arqueo_cheque($_monto_diferencia_arqueo_cheque){
		$this->_monto_diferencia_arqueo_cheque = $_monto_diferencia_arqueo_cheque;
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
			'cod_caja' => $this->_cod_caja, 
			'cod_usuario_caja' => $this->_cod_usuario_caja, 
			'fecha_hora_apertura' => $this->_fecha_hora_apertura, 
			'fecha_hora_cierre' => $this->_fecha_hora_cierre, 
			'monto_caja_apertura' => $this->_monto_caja_apertura, 
			'monto_caja_cierre' => $this->_monto_caja_cierre, 
			'monto_diferencia_arqueo' => $this->_monto_diferencia_arqueo, 
			'arqueo_caja' => $this->_arqueo_caja, 
			'monto_caja_cierre_cheque' => $this->_monto_caja_cierre_cheque, 
			'monto_diferencia_arqueo_cheque' => $this->_monto_diferencia_arqueo_cheque);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_caja', 'cod_usuario_caja', 'fecha_hora_apertura', 'fecha_hora_cierre', 'monto_caja_apertura', 'monto_caja_cierre', 'monto_diferencia_arqueo', 'arqueo_caja', 'monto_caja_cierre_cheque', 'monto_diferencia_arqueo_cheque') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>