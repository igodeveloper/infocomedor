<?php 
class Application_Model_Tipomovimiento extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Tipomovimiento";
	protected $_cod_tipo_mov = null;
	protected $_desc_tipo_mov = null;
	protected $_tipo_mov = null;
	public $_data = null;
	public function getCod_tipo_mov(){
		return $this->_cod_tipo_mov;
	}
	public function getDesc_tipo_mov(){
		return $this->_desc_tipo_mov;
	}
	public function getTipo_mov(){
		return $this->_tipo_mov;
	}
	public function setCod_tipo_mov($_cod_tipo_mov){
		$this->_cod_tipo_mov = $_cod_tipo_mov;
	}
	public function setDesc_tipo_mov($_desc_tipo_mov){
		$this->_desc_tipo_mov = $_desc_tipo_mov;
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
			'cod_tipo_mov' => $this->_cod_tipo_mov, 
			'desc_tipo_mov' => $this->_desc_tipo_mov, 
			'tipo_mov' => $this->_tipo_mov);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_tipo_mov', 'desc_tipo_mov', 'tipo_mov') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>