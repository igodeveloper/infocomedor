<?php 
class Application_Model_UnidadMedida extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_UnidadMedida";
	protected $_desc_unidad_medida = null;
	protected $_cod_unidad_medida = null;
	protected $_iso_unidad_medida = null;
	public $_data = null;
	public function getDesc_unidad_medida(){
		return $this->_desc_unidad_medida;
	}
	public function getCod_unidad_medida(){
		return $this->_cod_unidad_medida;
	}
	public function getIso_unidad_medida(){
		return $this->_iso_unidad_medida;
	}
	public function setDesc_unidad_medida($_ds_periodo){
		$this->_desc_unidad_medida = $_ds_periodo;
	}
	public function setCod_unidad_medida($_cod_periodo){
		$this->_cod_unidad_medida = $_cod_periodo;
	}
	public function setIso_unidad_medida($_dias_periodo){
		$this->_iso_unidad_medida = $_dias_periodo;
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
			'COD_UNIDAD_MEDIDA' => $this->_cod_unidad_medida, 
			'DESC_UNIDAD_MEDIDA' => $this->_desc_unidad_medida, 
			'ISO_UNIDAD_MEDIDA' => $this->_iso_unidad_medida);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_unidad_medida', 'desc_unidad_medida', 'iso_unidad_medida') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>