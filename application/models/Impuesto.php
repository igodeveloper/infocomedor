<?php 
class Application_Model_Impuesto extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Impuesto";
	protected $_cod_impuesto = null;
        protected $_des_impuesto = null;
	protected $_imp_sigla = null;
	protected $_imp_porcentaje = null;
	public $_data = null;
	public function getDesc_impuesto(){
		return $this->_des_impuesto;
	}
	public function getCod_impuesto(){
		return $this->_cod_impuesto;
	}
	public function getImp_sigla(){
		return $this->_imp_sigla;
	}
	public function getImp_porcentaje(){
		return $this->_imp_porcentaje;
	}        
	public function setDesc_impuesto($_ds_impuesto){
		$this->_des_impuesto = $_ds_impuesto;
	}
	public function setCod_impuesto($_cod_impuesto){
		$this->_cod_impuesto = $_cod_impuesto;
	}
	public function setImp_sigla($_imp_sigla){
		$this->_imp_sigla = $_imp_sigla;
	}
	public function setImp_porcentaje($_imp_porcentaje){
		$this->_imp_porcentaje = $_imp_porcentaje;
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
			'COD_IMPUESTO' => $this->_cod_impuesto, 
			'DES_IMPUESTO' => $this->_des_impuesto, 
			'IMP_SIGLA' => $this->_imp_sigla,
			'IMP_PORCENTAJE' => $this->_imp_porcentaje);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_impuesto', 'des_impuesto', 'imp_sigla','imp_porcentaje') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>