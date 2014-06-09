<?php
class Application_Model_Concepto extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Concepto";
	protected $_desc_Concepto  = null;
	protected $_cod_Concepto  = null;
	protected $_accion_Concepto   = null;
	public $_data = null;
	public function getDesc_Concepto(){
		return $this->_desc_Concepto;
	}
	public function getCod_Concepto(){
		return $this->_cod_Concepto;
	}
	public function getAccion_Concepto(){
		return $this->_accion_Concepto;
	}
	public function setDesc_Concepto($_ds_Concepto){
		$this->_desc_Concepto= $_ds_Concepto;
	}
	public function setCod_Concepto($_cod_Concepto){
		$this->_cod_Concepto = $_cod_Concepto;
	}
	public function setAccion_Concepto($_accion_Concepto){
		$this->_accion_Concepto= $_accion_Concepto;
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
			'COD_CONCEPTO' => $this->_cod_Concepto,
			'DS_CONCEPTO' => $this->_desc_Concepto,
			'CONCEPTO_ACCION' => $this->_accion_Concepto);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_Concepto', 'desc_Concepto', 'accion_Concepto') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>