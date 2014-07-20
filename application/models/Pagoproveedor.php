<?php 
class Application_Model_Pagoproveedor extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Pagoproveedor";
	protected $_cod_pago_proveedor = null;
	protected $_nro_factura_compra = null;
	protected $_monto_pago = null;
	protected $_cod_moneda_pago = null;
	protected $_nro_cheque = null;
	protected $_des_banco = null;
	protected $_estado_pago = null;
	protected $_cod_mov_caja = null;
	public $_data = null;
	public function getCod_pago_proveedor(){
		return $this->_cod_pago_proveedor;
	}
	public function getNro_factura_compra(){
		return $this->_nro_factura_compra;
	}
	public function getMonto_pago(){
		return $this->_monto_pago;
	}
	public function getCod_moneda_pago(){
		return $this->_cod_moneda_pago;
	}
	public function getNro_cheque(){
		return $this->_nro_cheque;
	}
	public function getDes_banco(){
		return $this->_des_banco;
	}
	public function getEstado_pago(){
		return $this->_estado_pago;
	}
	public function getCod_mov_caja(){
		return $this->_cod_mov_caja;
	}
	public function setCod_mov_caja($_cod_mov_caja){
		$this->_cod_mov_caja = $_cod_mov_caja;
	}
	public function setCod_pago_proveedor($_cod_pago_proveedor){
		$this->_cod_pago_proveedor = $_cod_pago_proveedor;
	}
	public function setNro_factura_compra($_nro_factura_compra){
		$this->_nro_factura_compra = $_nro_factura_compra;
	}
	public function setMonto_pago($_monto_pago){
		$this->_monto_pago = $_monto_pago;
	}
	public function setCod_moneda_pago($_cod_moneda_pago){
		$this->_cod_moneda_pago = $_cod_moneda_pago;
	}
	public function setNro_cheque($_nro_cheque){
		$this->_nro_cheque = $_nro_cheque;
	}
	public function setDes_banco($_des_banco){
		$this->_des_banco = $_des_banco;
	}
	public function setEstado_pago($_estado_pago){
		$this->_estado_pago = $_estado_pago;
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
			'COD_PAGO_PROVEEDOR' => $this->_cod_pago_proveedor, 
			'NRO_FACTURA_COMPRA' => $this->_nro_factura_compra, 
			'MONTO_PAGO' => $this->_monto_pago, 
			'COD_MONEDA_PAGO' => $this->_cod_moneda_pago, 
			'NRO_CHEQUE' => $this->_nro_cheque, 
			'DES_BANCO' => $this->_des_banco, 
			'ESTADO_PAGO' => $this->_estado_pago)
			'CODD_MOV_CAJA' => $this->_cod_mov_caja);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_pago_proveedor', 'nro_factura_compra', 'monto_pago', 'cod_moneda_pago', 'nro_cheque', 'des_banco', 'estado_pago', 'cod_mov_caja' ) as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>