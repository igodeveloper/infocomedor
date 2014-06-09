<?php
class Application_Model_Mesa extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Mesa";
	protected $_desc_mesa = null;
	protected $_cod_mesa = null;
	public $_data = null;
	public function getDesc_mesa(){
		return $this->_desc_mesa;
	}
	public function getCod_mesa(){
		return $this->_cod_mesa;
	}


	public function setDesc_mesa($_ds_mesa){
		$this->_desc_mesa = $_ds_mesa;
	}
	public function setCod_mesa($_cod_mesa){
		$this->_cod_mesa = $_cod_mesa;
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
			'COD_MESA' => $this->_cod_mesa,
			'DES_MESA' => $this->_desc_mesa);
		
}
    public function setFromArray(array $data) {
    	foreach (array('cod_mesa', 'desc_mesa') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>