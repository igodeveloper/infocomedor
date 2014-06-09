<?php
class Application_Model_Moneda extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Moneda";
	protected $_cod_moneda = null;
        protected $_desc_moneda = null;
	protected $_iso_moneda = null;
	public $_data = null;

	public function getCod_Moneda(){
		return $this->_cod_moneda; // codigo empresa
	}
        public function getDesc_Moneda(){
		return $this->_desc_moneda; // descripcion empresa
	}
	public function getIso_Moneda(){
		return $this->_iso_moneda; // ruc empresa
	}
	public function setCod_Moneda($_cod_moneda){
		$this->_cod_moneda = $_cod_moneda;
	}
        public function setDesc_Moneda($_desc_moneda){
		$this->_desc_moneda = $_desc_moneda;
	}
	public function setIso_Moneda($_iso_moneda){
		$this->_iso_moneda = $_iso_moneda;
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
			'COD_MONEDA' => $this->_cod_moneda,
			'DESC_MONEDA' => $this->_desc_moneda,
			'ISO_MONEDA' => $this->_iso_moneda);
}
    public function setFromArray(array $data) {
    	foreach (array('COD_MONEDA', 'DESC_MONEDA', 'ISO_MONEDA') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
