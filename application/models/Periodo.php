<?php 
class Application_Model_Periodo extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Periodo";
	protected $_ds_periodo = null;
	protected $_cod_periodo = null;
	protected $_dias_periodo = null;
	public $_data = null;
	public function getDs_periodo(){
		return $this->_ds_periodo;
	}
	public function getCod_periodo(){
		return $this->_cod_periodo;
	}
	public function getDias_periodo(){
		return $this->_dias_periodo;
	}
	public function setDs_periodo($_ds_periodo){
		$this->_ds_periodo = $_ds_periodo;
	}
	public function setCod_periodo($_cod_periodo){
		$this->_cod_periodo = $_cod_periodo;
	}
	public function setDias_periodo($_dias_periodo){
		$this->_dias_periodo = $_dias_periodo;
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
			'DS_PERIODO' => $this->_ds_periodo, 
			'COD_PERIODO' => $this->_cod_periodo, 
			'DIAS_PERIODO' => $this->_dias_periodo);
}
    public function setFromArray(array $data) {
    	foreach (array('ds_periodo', 'cod_periodo', 'dias_periodo') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>