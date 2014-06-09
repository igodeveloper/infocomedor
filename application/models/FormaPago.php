<?php
class Application_Model_FormaPago extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_FormaPago";
	protected $_desc_FormaPago = null;
	protected $_cod_FormaPago = null;
	protected $_iso_FormaPago = null;
	public $_data = null;
	public function getDesc_FormaPago(){
		return $this->_desc_FormaPago;
	}
	public function getCod_FormaPago(){
		return $this->_cod_FormaPago;
	}
	public function getIso_FormaPago(){
		return $this->_iso_FormaPago;
	}
	public function setDesc_FormaPago($_ds_FormaPago){
		$this->_desc_FormaPago = $_ds_FormaPago;
	}
	public function setCod_FormaPago($_cod_FormaPago){
		$this->_cod_FormaPago = $_cod_FormaPago;
	}
	public function setIso_FormaPago($_dias_FormaPago){
		$this->_iso_FormaPago = $_dias_FormaPago;
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
			'COD_FORMA_PAGO' => $this->_cod_FormaPago,
			'DES_FORMA_PAGO' => $this->_desc_FormaPago,
			'FORMA_PAGO_SIGLA' => $this->_iso_FormaPago);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_FormaPago', 'desc_FormaPago', 'iso_FormaPago') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>